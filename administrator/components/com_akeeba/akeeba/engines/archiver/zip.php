<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebaengine
 *
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

class AEArchiverZip extends AEAbstractArchiver
{

	/** @var string Beginning of central directory record. */
	private $_ctrlDirHeader = "\x50\x4b\x01\x02";

	/** @var string End of central directory record. */
	private $_ctrlDirEnd = "\x50\x4b\x05\x06";

	/** @var string Beginning of file contents. */
	private $_fileHeader = "\x50\x4b\x03\x04";

	/** @var string The name of the temporary file holding the ZIP's Central Directory */
	private $_ctrlDirFileName;

	/** @var string The name of the file holding the ZIP's data, which becomes the final archive */
	private $_dataFileName;

	/** @var integer The total number of files and directories stored in the ZIP archive */
	private $_totalFileEntries;

	/** @var integer The total size of data in the archive. Note: On 32-bit versions of PHP, this will overflow for archives over 2Gb! */
	private $_totalDataSize = 0;

	/** @var integer The chunk size for CRC32 calculations */
	private $AkeebaPackerZIP_CHUNK_SIZE;

	/** @var bool Should I use Split ZIP? */
	private $_useSplitZIP = false;

	/** @var int Maximum fragment size, in bytes */
	private $_fragmentSize = 0;

	/** @var int Current fragment number */
	private $_currentFragment = 1;

	/** @var int Total number of fragments */
	private $_totalFragments = 1;

	/** @var string Archive full path without extension */
	private $_dataFileNameBase = '';

	/** @var bool Should I store symlinks as such (no dereferencing?) */
	private $_symlink_store_target = false;

	/**
	 * Extend the bootstrap code to add some define's used by the ZIP format engine
	 * @see backend/akeeba/abstract/AEAbstractArchiver#__bootstrap_code()
	 */
	protected function __bootstrap_code()
	{
		if (!defined('_AKEEBA_COMPRESSION_THRESHOLD'))
		{
			$config = AEFactory::getConfiguration();
			define("_AKEEBA_COMPRESSION_THRESHOLD", $config->get('engine.archiver.common.big_file_threshold')); // Don't compress files over this size
			define("_AKEEBA_DIRECTORY_READ_CHUNK", $config->get('engine.archiver.zip.cd_glue_chunk_size')); // How much data to read at once when finalizing ZIP archives
		}
		parent::__bootstrap_code();
	}

	/**
	 * Class constructor - initializes internal operating parameters
	 */
	public function __construct()
	{
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "AEArchiverZip :: New instance");

		// Get chunk override
		$registry = AEFactory::getConfiguration();
		if ($registry->get('engine.archiver.common.chunk_size', 0) > 0)
		{
			$this->AkeebaPackerZIP_CHUNK_SIZE = AKEEBA_CHUNK;
		}
		else
		{
			// Try to use as much memory as it's possible for CRC32 calculation
			$memLimit = ini_get("memory_limit");

			if (strstr($memLimit, 'M'))
			{
				$memLimit = (int)$memLimit * 1048576;
			}
			elseif (strstr($memLimit, 'K'))
			{
				$memLimit = (int)$memLimit * 1024;
			}
			elseif (strstr($memLimit, 'G'))
			{
				$memLimit = (int)$memLimit * 1073741824;
			}
			else
			{
				$memLimit = (int)$memLimit;
			}

			if (is_numeric($memLimit) && ($memLimit < 0))
			{
				$memLimit = "";
			} // 1.2a3 -- Rare case with memory_limit < 0, e.g. -1Mb!
			if (($memLimit == ""))
			{
				// No memory limit, use 2Mb chunks (fairly large, right?)
				$this->AkeebaPackerZIP_CHUNK_SIZE = 2097152;
			}
			elseif (function_exists("memory_get_usage"))
			{
				// PHP can report memory usage, see if there's enough available memory; Joomla! alone eats about 5-6Mb! This code is called on files <= 1Mb
				$memLimit = $this->_return_bytes($memLimit);
				$availableRAM = $memLimit - memory_get_usage();

				if ($availableRAM <= 0)
				{
					// Some PHP implemenations also return the size of the httpd footprint!
					if (($memLimit - 6291456) > 0)
					{
						$this->AkeebaPackerZIP_CHUNK_SIZE = $memLimit - 6291456;
					}
					else
					{
						$this->AkeebaPackerZIP_CHUNK_SIZE = 2097152;
					}
				}
				else
				{
					$this->AkeebaPackerZIP_CHUNK_SIZE = $availableRAM * 0.5;
				}
			}
			else
			{
				// PHP can't report memory usage, use a conservative 512Kb
				$this->AkeebaPackerZIP_CHUNK_SIZE = 524288;
			}
		}

		// NEW 2.3: Should we enable Split ZIP feature?
		$fragmentsize = $registry->get('engine.archiver.common.part_size', 0);
		if ($fragmentsize >= 65536)
		{
			// If the fragment size is AT LEAST 64Kb, enable Split ZIP
			$this->_useSplitZIP = true;
			$this->_fragmentSize = $fragmentsize;
			// Indicate that we have at least 1 part
			$statistics = AEFactory::getStatistics();
			$statistics->updateMultipart(1);
		}

		// NEW 2.3: Should I use Symlink Target Storage?
		$dereferencesymlinks = $registry->get('engine.archiver.common.dereference_symlinks', true);
		if (!$dereferencesymlinks)
		{
			// We are told not to dereference symlinks. Are we on Windows?
			if (function_exists('php_uname'))
			{
				$isWindows = stristr(php_uname(), 'windows');
			}
			else
			{
				$isWindows = (DIRECTORY_SEPARATOR == '\\');
			}
			// If we are not on Windows, enable symlink target storage
			$this->_symlink_store_target = !$isWindows;
		}


		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Chunk size for CRC is now " . $this->AkeebaPackerZIP_CHUNK_SIZE . " bytes");

		parent::__construct();
	}

	/**
	 * Initialises the archiver class, creating the archive from an existent
	 * installer's JPA archive.
	 *
	 * @param string $sourceJPAPath     Absolute path to an installer's JPA archive
	 * @param string $targetArchivePath Absolute path to the generated archive
	 * @param array  $options           A named key array of options (optional). This is currently not supported
	 */
	public function initialize($targetArchivePath, $options = array())
	{
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "AEArchiverZip :: initialize - archive $targetArchivePath");

		// Get names of temporary files
		$configuration = AEFactory::getConfiguration();
		$this->_ctrlDirFileName = tempnam($configuration->get('akeeba.basic.output_directory'), 'akzcd');
		$this->_dataFileName = $targetArchivePath;

		// If we use splitting, initialize
		if ($this->_useSplitZIP)
		{
			AEUtilLogger::WriteLog(_AE_LOG_INFO, "AEArchiverZip :: Split ZIP creation enabled");
			$this->_dataFileNameBase = dirname($targetArchivePath) . '/' . basename($targetArchivePath, '.zip');
			$this->_dataFileName = $this->_dataFileNameBase . '.z01';
		}

		$this->_ctrlDirFileName = basename($this->_ctrlDirFileName);
		$pos = strrpos($this->_ctrlDirFileName, '/');
		if ($pos !== false)
		{
			$this->_ctrlDirFileName = substr($this->_ctrlDirFileName, $pos + 1);
		}
		$pos = strrpos($this->_ctrlDirFileName, '\\');
		if ($pos !== false)
		{
			$this->_ctrlDirFileName = substr($this->_ctrlDirFileName, $pos + 1);
		}
		$this->_ctrlDirFileName = AEUtilTempfiles::registerTempFile($this->_ctrlDirFileName);

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "AEArchiverZip :: CntDir Tempfile = " . $this->_ctrlDirFileName);

		// Create temporary file
		if (!@touch($this->_ctrlDirFileName))
		{
			$this->setError("Could not open temporary file for ZIP archiver. Please check your temporary directory's permissions!");

			return false;
		}
		if (function_exists('chmod'))
		{
			chmod($this->_ctrlDirFileName, 0666);
		}

		// Try to kill the archive if it exists
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "AEArchiverZip :: Killing old archive");
		$fp = fopen($this->_dataFileName, "wb");
		if (!($fp === false))
		{
			ftruncate($fp, 0);
			fclose($fp);
		}
		else
		{
			@unlink($this->_dataFileName);
		}
		if (!@touch($this->_dataFileName))
		{
			$this->setError("Could not open archive file for ZIP archiver. Please check your output directory's permissions!");

			return false;
		}
		if (function_exists('chmod'))
		{
			chmod($this->_dataFileName, 0666);
		}

		// On split archives, include the "Split ZIP" header, for PKZIP 2.50+ compatibility
		if ($this->_useSplitZIP)
		{
			file_put_contents($this->_dataFileName, "\x50\x4b\x07\x08");
			// Also update the statistics table that we are a multipart archive...
			$statistics = AEFactory::getStatistics();
			$statistics->updateMultipart(1);
		}
	}

	/**
	 * Creates the ZIP file out of its pieces.
	 * Official ZIP file format: http://www.pkware.com/appnote.txt
	 *
	 * @return boolean TRUE on success, FALSE on failure
	 */
	public function finalize()
	{
		// 1. Get size of central directory
		clearstatcache();
		$cdOffset = @filesize($this->_dataFileName);
		$this->_totalDataSize += $cdOffset;
		$cdSize = @filesize($this->_ctrlDirFileName);

		// 2. Append Central Directory to data file and remove the CD temp file afterwards
		$dataFP = fopen($this->_dataFileName, "ab");
		$cdFP = fopen($this->_ctrlDirFileName, "rb");

		if ($dataFP === false)
		{
			$this->setError('Could not open ZIP data file ' . $this->_dataFileName . ' for reading');

			return false;
		}

		if ($cdFP === false)
		{
			// Already glued, return
			fclose($dataFP);

			return false;
		}

		if (!$this->_useSplitZIP)
		{
			while (!feof($cdFP))
			{
				$chunk = fread($cdFP, _AKEEBA_DIRECTORY_READ_CHUNK);
				$this->_fwrite($dataFP, $chunk);
				if ($this->getError())
				{
					return;
				}
			}
			unset($chunk);
			fclose($cdFP);
		}
		else
			// Special considerations for Split ZIP
		{
			// Calcuate size of Central Directory + EOCD records
			$comment_length = function_exists('mb_strlen') ? mb_strlen($this->_comment, '8bit') : strlen($this->_comment);
			$total_cd_eocd_size = $cdSize + 22 + $comment_length;
			// Free space on the part
			clearstatcache();
			$current_part_size = @filesize($this->_dataFileName);
			$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);
			if (($free_space < $total_cd_eocd_size) && ($total_cd_eocd_size > 65536))
			{
				// Not enough space on archive for CD + EOCD, will go on separate part
				// Create new final part
				if (!$this->_createNewPart(true))
				{
					// Die if we couldn't create the new part
					$this->setError('Could not create new ZIP part file ' . basename($this->_dataFileName));

					return false;
				}
				else
				{
					// Close the old data file
					fclose($dataFP);
					// Open data file for output
					$dataFP = @fopen($this->_dataFileName, "ab");
					if ($dataFP === false)
					{
						$this->setError("Could not open archive file {$this->_dataFileName} for append!");

						return false;
					}
					// Write the CD record
					while (!feof($cdFP))
					{
						$chunk = fread($cdFP, _AKEEBA_DIRECTORY_READ_CHUNK);
						$this->_fwrite($dataFP, $chunk);
						if ($this->getError())
						{
							return;
						}
					}
					unset($chunk);
					fclose($cdFP);
				}
			}
			else
			{
				// Glue the CD + EOCD on the same part if they fit, or anyway if they are less than 64Kb.
				// NOTE: WE *MUST NOT* CREATE FRAGMENTS SMALLER THAN 64Kb!!!!
				while (!feof($cdFP))
				{
					$chunk = fread($cdFP, _AKEEBA_DIRECTORY_READ_CHUNK);
					$this->_fwrite($dataFP, $chunk);
					if ($this->getError())
					{
						return;
					}
				}
				unset($chunk);
				fclose($cdFP);
			}
		}

		AEUtilTempfiles::unregisterAndDeleteTempFile($this->_ctrlDirFileName);

		// 3. Write the rest of headers to the end of the ZIP file
		fclose($dataFP);
		clearstatcache();
		$dataFP = fopen($this->_dataFileName, "ab");
		if ($dataFP === false)
		{
			$this->setError('Could not open ' . $this->_dataFileName . ' for append');

			return false;
		}
		$this->_fwrite($dataFP, $this->_ctrlDirEnd);
		if ($this->getError())
		{
			return;
		}
		if ($this->_useSplitZIP)
		{
			// Split ZIP files, enter relevant disk number information
			$this->_fwrite($dataFP, pack('v', $this->_totalFragments - 1)); /* Number of this disk. */
			$this->_fwrite($dataFP, pack('v', $this->_totalFragments - 1)); /* Disk with central directory start. */
		}
		else
		{
			// Non-split ZIP files, the disk numbers MUST be 0
			$this->_fwrite($dataFP, pack('V', 0));
		}
		$this->_fwrite($dataFP, pack('v', $this->_totalFileEntries)); /* Total # of entries "on this disk". */
		$this->_fwrite($dataFP, pack('v', $this->_totalFileEntries)); /* Total # of entries overall. */
		$this->_fwrite($dataFP, pack('V', $cdSize)); /* Size of central directory. */
		$this->_fwrite($dataFP, pack('V', $cdOffset)); /* Offset to start of central dir. */
		$sizeOfComment = $comment_length = function_exists('mb_strlen') ? mb_strlen($this->_comment, '8bit') : strlen($this->_comment);
		// 2.0.b2 -- Write a ZIP file comment
		$this->_fwrite($dataFP, pack('v', $sizeOfComment)); /* ZIP file comment length. */
		$this->_fwrite($dataFP, $this->_comment);
		fclose($dataFP);
		//sleep(2);

		// If Split ZIP and there is no .zip file, rename the last fragment to .ZIP
		if ($this->_useSplitZIP)
		{
			$extension = substr($this->_dataFileName, -3);
			if ($extension != '.zip')
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'Renaming last ZIP part to .ZIP extension');
				$newName = $this->_dataFileNameBase . '.zip';
				if (!@rename($this->_dataFileName, $newName))
				{
					$this->setError('Could not rename last ZIP part to .ZIP extension.');

					return false;
				}
				$this->_dataFileName = $newName;
			}
		}
		// If Split ZIP and only one fragment, change the signature
		if ($this->_useSplitZIP && ($this->_totalFragments == 1))
		{
			$fp = fopen($this->_dataFileName, 'r+b');
			$this->_fwrite($fp, "\x50\x4b\x30\x30");
		}

		if (function_exists('chmod'))
		{
			@chmod($this->_dataFileName, 0755);
		}

		return true;
	}


	/**
	 * Returns a string with the extension (including the dot) of the files produced
	 * by this class.
	 * @return string
	 */
	public function getExtension()
	{
		return '.zip';
	}


	/**
	 * The most basic file transaction: add a single entry (file or directory) to
	 * the archive.
	 *
	 * @param bool   $isVirtual        If true, the next parameter contains file data instead of a file name
	 * @param string $sourceNameOrData Absolute file name to read data from or the file data itself is $isVirtual is true
	 * @param string $targetName       The (relative) file name under which to store the file in the archive
	 *
	 * @return True on success, false otherwise
	 */
	protected function _addFile($isVirtual, &$sourceNameOrData, $targetName)
	{
		static $configuration;

		// Note down the starting disk number for Split ZIP archives
		if ($this->_useSplitZIP)
		{
			$starting_disk_number_for_this_file = $this->_currentFragment - 1;
		}
		else
		{
			$starting_disk_number_for_this_file = 0;
		}

		if (!$configuration)
		{
			$configuration = AEFactory::getConfiguration();
		}

		if (!$configuration->get('volatile.engine.archiver.processingfile', false))
		{
			// See if it's a directory
			$isDir = $isVirtual ? false : is_dir($sourceNameOrData);
			// See if it's a symlink (w/out dereference)
			$isSymlink = false;
			if ($this->_symlink_store_target && !$isVirtual)
			{
				$isSymlink = is_link($sourceNameOrData);
			}

			// Get real size before compression
			if ($isVirtual)
			{
				$fileSize = function_exists('mb_strlen') ? mb_strlen($sourceNameOrData, '8bit') : strlen($sourceNameOrData);
			}
			else
			{
				if ($isSymlink)
				{
					$fileSize = function_exists('mb_strlen') ? mb_strlen(@readlink($sourceNameOrData), '8bit') : strlen(@readlink($sourceNameOrData));
				}
				else
				{
					$fileSize = $isDir ? 0 : @filesize($sourceNameOrData);
				}
			}

			// Get last modification time to store in archive
			$ftime = $isVirtual ? time() : @filemtime($sourceNameOrData);

			// Decide if we will compress
			if ($isDir || $isSymlink)
			{
				$compressionMethod = 0; // don't compress directories...
			}
			else
			{
				// Do we have plenty of memory left?
				$memLimit = ini_get("memory_limit");
				if (strstr($memLimit, 'M'))
				{
					$memLimit = (int)$memLimit * 1048576;
				}
				elseif (strstr($memLimit, 'K'))
				{
					$memLimit = (int)$memLimit * 1024;
				}
				elseif (strstr($memLimit, 'G'))
				{
					$memLimit = (int)$memLimit * 1073741824;
				}
				else
				{
					$memLimit = (int)$memLimit;
				}
				if (($memLimit == "") || ($fileSize >= _AKEEBA_COMPRESSION_THRESHOLD))
				{
					// No memory limit, or over 1Mb files => always compress up to 1Mb files (otherwise it times out)
					$compressionMethod = ($fileSize <= _AKEEBA_COMPRESSION_THRESHOLD) ? 8 : 0;
				}
				elseif (function_exists("memory_get_usage"))
				{
					// PHP can report memory usage, see if there's enough available memory; Joomla! alone eats about 5-6Mb! This code is called on files <= 1Mb
					$memLimit = $this->_return_bytes($memLimit);
					$availableRAM = $memLimit - memory_get_usage();
					$compressionMethod = (($availableRAM / 2.5) >= $fileSize) ? 8 : 0;
				}
				else
				{
					// PHP can't report memory usage, compress only files up to 512Kb (conservative approach) and hope it doesn't break
					$compressionMethod = ($fileSize <= 524288) ? 8 : 0;;
				}
			}

			$compressionMethod = function_exists("gzcompress") ? $compressionMethod : 0;

			$storedName = $targetName;

			if ($isVirtual)
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, '  Virtual add:' . $storedName . ' (' . $fileSize . ') - ' . $compressionMethod);
			}

			/* "Local file header" segment. */
			$unc_len = $fileSize; // File size

			if (!$isDir)
			{
				// Get CRC for regular files, not dirs
				if ($isVirtual)
				{
					$crc = crc32($sourceNameOrData);
				}
				else
				{
					$crcCalculator = new AECRC32CalcClass;
					$crc = $crcCalculator->crc32_file($sourceNameOrData, $this->AkeebaPackerZIP_CHUNK_SIZE); // This is supposed to be the fast way to calculate CRC32 of a (large) file.
					unset($crcCalculator);

					// If the file was unreadable, $crc will be false, so we skip the file
					if ($crc === false)
					{
						$this->setWarning('Could not calculate CRC32 for ' . $sourceNameOrData);

						return false;
					}
				}
			}
			else if ($isSymlink)
			{
				$crc = crc32(@readlink($sourceNameOrData));
			}
			else
			{
				// Dummy CRC for dirs
				$crc = 0;
				$storedName .= "/";
				$unc_len = 0;
			}

			// If we have to compress, read the data in memory and compress it
			if ($compressionMethod == 8)
			{
				// Get uncompressed data
				if ($isVirtual)
				{
					$udata =& $sourceNameOrData;
				}
				else
				{
					$udata = @file_get_contents($sourceNameOrData); // PHP > 4.3.0 saves us the trouble
				}

				if ($udata === false)
				{
					// Unreadable file, skip it. Normally, we should have exited on CRC code above
					$this->setWarning('Unreadable file ' . $sourceNameOrData . '. Check permissions');

					return false;
				}
				else
				{
					// Proceed with compression
					$zdata = @gzcompress($udata);
					if ($zdata === false)
					{
						// If compression fails, let it behave like no compression was available
						$c_len = $unc_len;
						$compressionMethod = 0;
					}
					else
					{
						unset($udata);

						$zdata = substr(substr($zdata, 0, -4), 2);
						$c_len = (function_exists('mb_strlen') ? mb_strlen($zdata, '8bit') : strlen($zdata));
					}
				}
			}
			else
			{
				$c_len = $unc_len;
			}

			/* Get the hex time. */
			$dtime = dechex($this->_unix2DosTime($ftime));

			if ((function_exists('mb_strlen') ? mb_strlen($dtime, '8bit') : strlen($dtime)) < 8)
			{
				$dtime = "00000000";
			}
			$hexdtime = chr(hexdec($dtime[6] . $dtime[7])) .
				chr(hexdec($dtime[4] . $dtime[5])) .
				chr(hexdec($dtime[2] . $dtime[3])) .
				chr(hexdec($dtime[0] . $dtime[1]));

			// Get current data file size
			//clearstatcache();
			//$old_offset = @filesize( $this->_dataFileName );

			// If it's a split ZIP file, we've got to make sure that the header can fit in the part
			if ($this->_useSplitZIP)
			{
				// Get header size, taking into account any extra header necessary
				$header_size = 30 + (function_exists('mb_strlen') ? mb_strlen($storedName, '8bit') : strlen($storedName));
				// Compare to free part space
				clearstatcache();
				$current_part_size = @filesize($this->_dataFileName);
				$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);
				if ($free_space <= $header_size)
				{
					// Not enough space on current part, create new part
					if (!$this->_createNewPart())
					{
						$this->setError('Could not create new ZIP part file ' . basename($this->_dataFileName));

						return false;
					}
				}
			}
			// Open data file for output
			$fp = @fopen($this->_dataFileName, "ab");
			if ($fp === false)
			{
				$this->setError("Could not open archive file {$this->_dataFileName} for append!");

				return false;
			}

			$seek_result = @fseek($fp, 0, SEEK_END);
			$old_offset = ($seek_result == -1) ? false : @ftell($fp);
			if ($old_offset === false)
			{
				@clearstatcache();
				$old_offset = @filesize($this->_dataFileName);
			}

			// Get the file name length in bytes
			if (function_exists('mb_strlen'))
			{
				$fn_length = mb_strlen($storedName, '8bit');
			}
			else
			{
				$fn_length = strlen($storedName);
			}

			$this->_fwrite($fp, $this->_fileHeader); /* Begin creating the ZIP data. */
			if (!$isSymlink)
			{
				$this->_fwrite($fp, "\x14\x00"); /* Version needed to extract. */
			}
			else
			{
				$this->_fwrite($fp, "\x0a\x03"); /* Version needed to extract. */
			}
			$this->_fwrite($fp, pack('v', 2048)); /* General purpose bit flag. Bit 11 set = use UTF-8 encoding for filenames & comments */
			$this->_fwrite($fp, ($compressionMethod == 8) ? "\x08\x00" : "\x00\x00"); /* Compression method. */
			$this->_fwrite($fp, $hexdtime); /* Last modification time/date. */
			$this->_fwrite($fp, pack('V', $crc)); /* CRC 32 information. */
			if (!isset($c_len))
			{
				$c_len = $unc_len;
			}
			$this->_fwrite($fp, pack('V', $c_len)); /* Compressed filesize. */
			$this->_fwrite($fp, pack('V', $unc_len)); /* Uncompressed filesize. */
			$this->_fwrite($fp, pack('v', $fn_length)); /* Length of filename. */
			$this->_fwrite($fp, pack('v', 0)); /* Extra field length. */
			$this->_fwrite($fp, $storedName); /* File name. */

			// Cache useful information about the file
			if (!$isDir && !$isSymlink && !$isVirtual)
			{
				$configuration->set('volatile.engine.archiver.unc_len', $unc_len);
				$configuration->set('volatile.engine.archiver.hexdtime', $hexdtime);
				$configuration->set('volatile.engine.archiver.crc', $crc);
				$configuration->set('volatile.engine.archiver.c_len', $c_len);
				$configuration->set('volatile.engine.archiver.fn_length', $fn_length);
				$configuration->set('volatile.engine.archiver.old_offset', $old_offset);
				$configuration->set('volatile.engine.archiver.storedName', $storedName);
				$configuration->set('volatile.engine.archiver.sourceNameOrData', $sourceNameOrData);
			}
		}
		else
		{
			// Since we are continuing archiving, it's an uncompressed regular file. Set up the variables.
			$compressionMethod = 1;
			$isDir = false;
			$isSymlink = false;
			$unc_len = $configuration->get('volatile.engine.archiver.unc_len');
			$hexdtime = $configuration->get('volatile.engine.archiver.hexdtime');
			$crc = $configuration->get('volatile.engine.archiver.crc');
			$c_len = $configuration->get('volatile.engine.archiver.c_len');
			$fn_length = $configuration->get('volatile.engine.archiver.fn_length');
			$old_offset = $configuration->get('volatile.engine.archiver.old_offset');
			$storedName = $configuration->get('volatile.engine.archiver.storedName');


			// Open data file for output
			$fp = @fopen($this->_dataFileName, "ab");
			if ($fp === false)
			{
				$this->setError("Could not open archive file {$this->_dataFileName} for append!");

				return false;
			}
		}


		/* "File data" segment. */
		if ($compressionMethod == 8)
		{
			// Just dump the compressed data
			if (!$this->_useSplitZIP)
			{
				$this->_fwrite($fp, $zdata);
				if ($this->getError())
				{
					return;
				}
			}
			else
			{
				// Split ZIP. Check if we need to split the part in the middle of the data.
				clearstatcache();
				$current_part_size = @filesize($this->_dataFileName);
				$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);
				if ($free_space >= (function_exists('mb_strlen') ? mb_strlen($zdata, '8bit') : strlen($zdata)))
				{
					// Write in one part
					$this->_fwrite($fp, $zdata);
					if ($this->getError())
					{
						return;
					}
				}
				else
				{
					$bytes_left = (function_exists('mb_strlen') ? mb_strlen($zdata, '8bit') : strlen($zdata));

					while ($bytes_left > 0)
					{
						clearstatcache();
						$current_part_size = @filesize($this->_dataFileName);
						$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);

						// Split between parts - Write a part
						$this->_fwrite($fp, $zdata, min((function_exists('mb_strlen') ? mb_strlen($zdata, '8bit') : strlen($zdata)), $free_space));
						if ($this->getError())
						{
							return;
						}

						// Get the rest of the data
						$bytes_left = (function_exists('mb_strlen') ? mb_strlen($zdata, '8bit') : strlen($zdata)) - $free_space;

						if ($bytes_left > 0)
						{
							// Create new part
							if (!$this->_createNewPart())
							{
								// Die if we couldn't create the new part
								$this->setError('Could not create new ZIP part file ' . basename($this->_dataFileName));

								return false;
							}
							else
							{
								// Close the old data file
								fclose($fp);
								// Open data file for output
								$fp = @fopen($this->_dataFileName, "ab");
								if ($fp === false)
								{
									$this->setError("Could not open archive file {$this->_dataFileName} for append!");

									return false;
								}
							}
							$zdata = substr($zdata, -$bytes_left);
						}
					}
				}
			}
			unset($zdata);
		}
		elseif (!($isDir || $isSymlink))
		{
			// Virtual file, just write the data!
			if ($isVirtual)
			{
				// Just dump the data
				if (!$this->_useSplitZIP)
				{
					$this->_fwrite($fp, $sourceNameOrData);
					if ($this->getError())
					{
						return;
					}
				}
				else
				{
					// Split ZIP. Check if we need to split the part in the middle of the data.
					clearstatcache();
					$current_part_size = @filesize($this->_dataFileName);
					$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);
					if ($free_space >= (function_exists('mb_strlen') ? mb_strlen($sourceNameOrData, '8bit') : strlen($sourceNameOrData)))
					{
						// Write in one part
						$this->_fwrite($fp, $sourceNameOrData);
						if ($this->getError())
						{
							return;
						}
					}
					else
					{
						$bytes_left = (function_exists('mb_strlen') ? mb_strlen($sourceNameOrData, '8bit') : strlen($sourceNameOrData));

						while ($bytes_left > 0)
						{
							clearstatcache();
							$current_part_size = @filesize($this->_dataFileName);
							$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);
							// Split between parts - Write first part
							$this->_fwrite($fp, $sourceNameOrData, min((function_exists('mb_strlen') ? mb_strlen($zdata, '8bit') : strlen($zdata)), $free_space));
							if ($this->getError())
							{
								return;
							}
							// Get the rest of the data
							$rest_size = (function_exists('mb_strlen') ? mb_strlen($sourceNameOrData, '8bit') : strlen($sourceNameOrData)) - $free_space;
							if ($rest_size > 0)
							{
								// Create new part if required
								if (!$this->_createNewPart())
								{
									// Die if we couldn't create the new part
									$this->setError('Could not create new ZIP part file ' . basename($this->_dataFileName));

									return false;
								}
								else
								{
									// Close the old data file
									fclose($fp);
									// Open data file for output
									$fp = @fopen($this->_dataFileName, "ab");
									if ($fp === false)
									{
										$this->setError("Could not open archive file {$this->_dataFileName} for append!");

										return false;
									}
								}
								// Get the rest of the compressed data
								$zdata = substr($sourceNameOrData, -$rest_size);
							}
							$bytes_left = $rest_size;
						}
					}
				}
			}
			else
			{
				// IMPORTANT! Only this case can be spanned across steps: uncompressed, non-virtual data
				if ($configuration->get('volatile.engine.archiver.processingfile', false))
				{
					$sourceNameOrData = $configuration->get('volatile.engine.archiver.sourceNameOrData', '');
					$unc_len = $configuration->get('volatile.engine.archiver.unc_len', 0);
					$resume = $configuration->get('volatile.engine.archiver.resume', 0);
				}

				// Copy the file contents, ignore directories
				$zdatafp = @fopen($sourceNameOrData, "rb");
				if ($zdatafp === false)
				{
					$this->setWarning('Unreadable file ' . $sourceNameOrData . '. Check permissions');

					return false;
				}
				else
				{
					$timer = AEFactory::getTimer();
					// Seek to the resume point if required
					if ($configuration->get('volatile.engine.archiver.processingfile', false))
					{
						// Seek to new offset
						$seek_result = @fseek($zdatafp, $resume);
						if ($seek_result === -1)
						{
							// What?! We can't resume!
							$this->setError(sprintf('Could not resume packing of file %s. Your archive is damaged!', $sourceNameOrData));

							return false;
						}

						// Doctor the uncompressed size to match the remainder of the data
						$unc_len = $unc_len - $resume;
					}

					if (!$this->_useSplitZIP)
					{
						// For non Split ZIP, just dump the file very fast
						while (!feof($zdatafp) && ($timer->getTimeLeft() > 0) && ($unc_len > 0))
						{
							$zdata = fread($zdatafp, AKEEBA_CHUNK);
							$this->_fwrite($fp, $zdata, min((function_exists('mb_strlen') ? mb_strlen($zdata, '8bit') : strlen($zdata)), AKEEBA_CHUNK));
							$unc_len -= AKEEBA_CHUNK;
							if ($this->getError())
							{
								return;
							}
						}
						if (!feof($zdatafp) && ($unc_len != 0))
						{
							// We have to break, or we'll time out!
							$resume = @ftell($zdatafp);
							$configuration->set('volatile.engine.archiver.resume', $resume);
							$configuration->set('volatile.engine.archiver.processingfile', true);

							return true;
						}
					}
					else
					{
						// Split ZIP - Do we have enough space to host the whole file?
						clearstatcache();
						$current_part_size = @filesize($this->_dataFileName);
						$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);
						if ($free_space >= $unc_len)
						{
							// Yes, it will fit inside this part, do quick copy
							while (!feof($zdatafp) && ($timer->getTimeLeft() > 0) && ($unc_len > 0))
							{
								$zdata = fread($zdatafp, AKEEBA_CHUNK);
								$this->_fwrite($fp, $zdata, min((function_exists('mb_strlen') ? mb_strlen($zdata, '8bit') : strlen($zdata)), AKEEBA_CHUNK));
								$unc_len -= AKEEBA_CHUNK;
								if ($this->getError())
								{
									return;
								}

							}
							if (!feof($zdatafp) && ($unc_len != 0))
							{
								// We have to break, or we'll time out!
								$resume = @ftell($zdatafp);
								$configuration->set('volatile.engine.archiver.resume', $resume);
								$configuration->set('volatile.engine.archiver.processingfile', true);

								return true;
							}
						}
						else
						{
							// No, we'll have to split between parts. We'll loop until we run
							// out of space.
							while (!feof($zdatafp) && ($timer->getTimeLeft() > 0))
							{
								// No, we'll have to split between parts. Write the first part
								// Find optimal chunk size
								clearstatcache();
								$current_part_size = @filesize($this->_dataFileName);
								$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);

								$chunk_size_primary = min(AKEEBA_CHUNK, $free_space);
								if ($chunk_size_primary <= 0)
								{
									$chunk_size_primary = max(AKEEBA_CHUNK, $free_space);
								}
								// Calculate if we have to read some more data (smaller chunk size)
								// and how many times we must read w/ the primary chunk size
								$chunk_size_secondary = $free_space % $chunk_size_primary;
								$loop_times = ($free_space - $chunk_size_secondary) / $chunk_size_primary;
								// Read and write with the primary chunk size
								for ($i = 1; $i <= $loop_times; $i++)
								{
									$zdata = fread($zdatafp, $chunk_size_primary);
									$this->_fwrite($fp, $zdata, min((function_exists('mb_strlen') ? mb_strlen($zdata, '8bit') : strlen($zdata)), $chunk_size_primary));
									$unc_len -= $chunk_size_primary;
									if ($this->getError())
									{
										return;
									}

									// Do we have enough time to proceed?
									if ((!feof($zdatafp)) && ($unc_len != 0) && ($timer->getTimeLeft() <= 0))
									{
										// No, we have to break, or we'll time out!
										$resume = @ftell($zdatafp);
										$configuration->set('volatile.engine.archiver.resume', $resume);
										$configuration->set('volatile.engine.archiver.processingfile', true);

										return true;
									}
								}
								// Read and write w/ secondary chunk size, if non-zero
								if ($chunk_size_secondary > 0)
								{
									$zdata = fread($zdatafp, $chunk_size_secondary);
									$this->_fwrite($fp, $zdata, min((function_exists('mb_strlen') ? mb_strlen($zdata, '8bit') : strlen($zdata)), $chunk_size_secondary));
									$unc_len -= $chunk_size_secondary;
									if ($this->getError())
									{
										return;
									}
								}

								// Do we have enough time to proceed?
								if ((!feof($zdatafp)) && ($unc_len != 0) && ($timer->getTimeLeft() <= 0))
								{
									// No, we have to break, or we'll time out!
									$resume = @ftell($zdatafp);
									$configuration->set('volatile.engine.archiver.resume', $resume);
									$configuration->set('volatile.engine.archiver.processingfile', true);

									// ...and create a new part as well
									if (!$this->_createNewPart())
									{
										// Die if we couldn't create the new part
										$this->setError('Could not create new ZIP part file ' . basename($this->_dataFileName));

										return false;
									}

									// ...then, return
									return true;
								}

								// Create new ZIP part, but only if we'll have more data to write
								if (!feof($zdatafp) && ($unc_len > 0))
								{
									// Create new ZIP part
									if (!$this->_createNewPart())
									{
										// Die if we couldn't create the new part
										$this->setError('Could not create new ZIP part file ' . basename($this->_dataFileName));

										return false;
									}
									else
									{
										// Close the old data file
										fclose($fp);

										// We have created the part. If the user asked for immediate post-proc, break step now.
										if ($configuration->get('engine.postproc.common.after_part', 0))
										{
											$resume = @ftell($zdatafp);
											$configuration->set('volatile.engine.archiver.resume', $resume);
											$configuration->set('volatile.engine.archiver.processingfile', true);

											$configuration->set('volatile.breakflag', true);
											@fclose($zdatafp);
											@fclose($fp);

											return true;
										}

										// Open data file for output
										$fp = @fopen($this->_dataFileName, "ab");
										if ($fp === false)
										{
											$this->setError("Could not open archive file {$this->_dataFileName} for append!");

											return false;
										}
									}
								}

							} // end while

						}
					}
					fclose($zdatafp);
				}
			}
		}
		elseif ($isSymlink)
		{
			$this->_fwrite($fp, @readlink($sourceNameOrData));
		}

		// Done with data file.
		fclose($fp);

		// Open the central directory file for append
		$fp = @fopen($this->_ctrlDirFileName, "ab");
		if ($fp === false)
		{
			$this->setError("Could not open Central Directory temporary file for append!");

			return false;
		}
		$this->_fwrite($fp, $this->_ctrlDirHeader);
		if (!$isSymlink)
		{
			$this->_fwrite($fp, "\x14\x00"); /* Version made by (always set to 2.0). */
			$this->_fwrite($fp, "\x14\x00"); /* Version needed to extract */
			$this->_fwrite($fp, pack('v', 2048)); /* General purpose bit flag */
			$this->_fwrite($fp, ($compressionMethod == 8) ? "\x08\x00" : "\x00\x00"); /* Compression method. */
		}
		else
		{
			// Symlinks get special treatment
			$this->_fwrite($fp, "\x14\x03"); /* Version made by (version 2.0 with UNIX extensions). */
			$this->_fwrite($fp, "\x0a\x03"); /* Version needed to extract */
			$this->_fwrite($fp, pack('v', 2048)); /* General purpose bit flag */
			$this->_fwrite($fp, "\x00\x00"); /* Compression method. */
		}

		$this->_fwrite($fp, $hexdtime); /* Last mod time/date. */
		$this->_fwrite($fp, pack('V', $crc)); /* CRC 32 information. */
		$this->_fwrite($fp, pack('V', $c_len)); /* Compressed filesize. */
		if ($compressionMethod == 0)
		{
			// When we are not compressing, $unc_len is being reduced to 0 while backing up.
			// With this trick, we always store the correct length, as in this case the compressed
			// and uncompressed length is always the same.
			$this->_fwrite($fp, pack('V', $c_len)); /* Uncompressed filesize. */
		}
		else
		{
			// When compressing, the uncompressed length differs from compressed length
			// and this line writes the correct value.
			$this->_fwrite($fp, pack('V', $unc_len)); /* Uncompressed filesize. */
		}
		$this->_fwrite($fp, pack('v', $fn_length)); /* Length of filename. */
		$this->_fwrite($fp, pack('v', 0)); /* Extra field length. */
		$this->_fwrite($fp, pack('v', 0)); /* File comment length. */
		$this->_fwrite($fp, pack('v', $starting_disk_number_for_this_file)); /* Disk number start. */
		$this->_fwrite($fp, pack('v', 0)); /* Internal file attributes. */
		if (!$isSymlink)
		{
			$this->_fwrite($fp, pack('V', $isDir ? 0x41FF0010 : 0xFE49FFE0)); /* External file attributes -   'archive' bit set. */
		}
		else
		{
			// For SymLinks we store UNIX file attributes
			$this->_fwrite($fp, "\x20\x80\xFF\xA1"); /* External file attributes for Symlink. */
		}
		$this->_fwrite($fp, pack('V', $old_offset)); /* Relative offset of local header. */
		$this->_fwrite($fp, $storedName); /* File name. */
		/* Optional extra field, file comment goes here. */

		// Finished with Central Directory
		fclose($fp);

		// Finaly, increase the file counter by one
		$this->_totalFileEntries++;

		// Uncache data
		$configuration->set('volatile.engine.archiver.sourceNameOrData', null);
		$configuration->set('volatile.engine.archiver.unc_len', null);
		$configuration->set('volatile.engine.archiver.resume', null);
		$configuration->set('volatile.engine.archiver.hexdtime', null);
		$configuration->set('volatile.engine.archiver.crc', null);
		$configuration->set('volatile.engine.archiver.c_len', null);
		$configuration->set('volatile.engine.archiver.fn_length', null);
		$configuration->set('volatile.engine.archiver.old_offset', null);
		$configuration->set('volatile.engine.archiver.storedName', null);
		$configuration->set('volatile.engine.archiver.sourceNameOrData', null);

		$configuration->set('volatile.engine.archiver.processingfile', false);

		// ... and return TRUE = success
		return true;
	}

	// ------------------------------------------------------------------------
	// Archiver-specific utility functions
	// ------------------------------------------------------------------------

	/**
	 * Converts a UNIX timestamp to a 4-byte DOS date and time format
	 * (date in high 2-bytes, time in low 2-bytes allowing magnitude
	 * comparison).
	 *
	 * @param integer $unixtime The current UNIX timestamp.
	 *
	 * @return integer  The current date in a 4-byte DOS format.
	 */
	private function _unix2DOSTime($unixtime = null)
	{
		$timearray = (is_null($unixtime)) ? getdate() : getdate($unixtime);

		if ($timearray['year'] < 1980)
		{
			$timearray['year'] = 1980;
			$timearray['mon'] = 1;
			$timearray['mday'] = 1;
			$timearray['hours'] = 0;
			$timearray['minutes'] = 0;
			$timearray['seconds'] = 0;
		}

		return (($timearray['year'] - 1980) << 25) |
		($timearray['mon'] << 21) |
		($timearray['mday'] << 16) |
		($timearray['hours'] << 11) |
		($timearray['minutes'] << 5) |
		($timearray['seconds'] >> 1);
	}

	private function _createNewPart($finalPart = false)
	{
		// Push the previous part if we have to post-process it immediately
		$configuration = AEFactory::getConfiguration();
		if ($configuration->get('engine.postproc.common.after_part', 0))
		{
			$this->finishedPart[] = $this->_dataFileName;
		}

		// Add the part's size to our rolling sum
		clearstatcache();
		$this->_totalDataSize += filesize($this->_dataFileName);

		$this->_totalFragments++;
		$this->_currentFragment = $this->_totalFragments;
		if ($finalPart)
		{
			$this->_dataFileName = $this->_dataFileNameBase . '.zip';
		}
		else
		{
			$this->_dataFileName = $this->_dataFileNameBase . '.z' . sprintf('%02d', $this->_currentFragment);
		}
		AEUtilLogger::WriteLog(_AE_LOG_INFO, 'Creating new ZIP part #' . $this->_currentFragment . ', file ' . $this->_dataFileName);
		// Inform CUBE that we have changed the multipart number
		$statistics = AEFactory::getStatistics();
		$statistics->updateMultipart($this->_totalFragments);
		// Try to remove any existing file
		@unlink($this->_dataFileName);
		// Touch the new file
		$result = @touch($this->_dataFileName);
		if (function_exists('chmod'))
		{
			chmod($this->_dataFileName, 0666);
		}

		return $result;
	}
}

// ===================================================================================================

/**
 * A handy class to abstract the calculation of CRC32 of files under various
 * server conditions and versions of PHP.
 */
class AECRC32CalcClass
{
	/**
	 * Returns the CRC32 of a file, selecting the more appropriate algorithm.
	 *
	 * @param string  $filename                   Absolute path to the file being processed
	 * @param integer $AkeebaPackerZIP_CHUNK_SIZE Obsoleted
	 *
	 * @return integer The CRC32 in numerical form
	 */
	public function crc32_file($filename, $AkeebaPackerZIP_CHUNK_SIZE)
	{
		static $configuration;

		if (!$configuration)
		{
			$configuration = AEFactory::getConfiguration();
		}

		if (function_exists("hash_file"))
		{
			$res = $this->crc32_file_php512($filename);
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "File $filename - CRC32 = " . dechex($res) . " [PHP512]");
		}
		else if (function_exists("file_get_contents") && (@filesize($filename) <= $AkeebaPackerZIP_CHUNK_SIZE))
		{
			$res = $this->crc32_file_getcontents($filename);
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "File $filename - CRC32 = " . dechex($res) . " [GETCONTENTS]");
		}
		else
		{
			$res = $this->crc32_file_php4($filename, $AkeebaPackerZIP_CHUNK_SIZE);
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "File $filename - CRC32 = " . dechex($res) . " [PHP4]");
		}

		if ($res === false)
		{
			$this->setWarning("File $filename - NOT READABLE: CRC32 IS WRONG!");
		}

		return $res;
	}

	/**
	 * Very efficient CRC32 calculation for PHP 5.1.2 and greater, requiring
	 * the 'hash' PECL extension
	 *
	 * @param string $filename Absolute filepath
	 *
	 * @return integer The CRC32
	 */
	private function crc32_file_php512($filename)
	{
		// Detection of buggy PHP hosts
		static $mustInvert = null;
		if (is_null($mustInvert))
		{
			$test_crc = @hash('crc32b', 'test', false);
			$mustInvert = (strtolower($test_crc) == '0c7e7fd8'); // Normally, it's D87F7E0C :)
			if ($mustInvert)
			{
				AEUtilLogger::WriteLog(_AE_LOG_WARNING, 'Your server has a buggy PHP version which produces inverted CRC32 values. Attempting a workaround. ZIP files may appear as corrupt.');
			}
		}

		$res = @hash_file('crc32b', $filename, false);
		if ($mustInvert)
		{
			// Workaround for buggy PHP versions (I think before 5.1.8) which produce inverted CRC32 sums
			$res2 = substr($res, 6, 2) . substr($res, 4, 2) . substr($res, 2, 2) . substr($res, 0, 2);
			$res = $res2;
		}
		$res = hexdec($res);

		return $res;
	}

	/**
	 * A compatible CRC32 calculation using file_get_contents, utilizing immense
	 * ammounts of RAM
	 *
	 * @param string $filename
	 *
	 * @return integer
	 */
	private function crc32_file_getcontents($filename)
	{
		return crc32(@file_get_contents($filename));
	}

	/**
	 * There used to be a workaround for large files under PHP4. However, it never
	 * really worked, so it is removed and a warning is posted instead.
	 *
	 * @param string  $filename
	 * @param integer $AkeebaPackerZIP_CHUNK_SIZE
	 *
	 * @return integer
	 */
	private function crc32_file_php4($filename, $AkeebaPackerZIP_CHUNK_SIZE)
	{
		$this->setWarning("Function hash_file not detected processing the 'large'' file $filename; it will appear as seemingly corrupt in the archive. Only the CRC32 is invalid, though. Please read our forum announcement for explanation of this message.");

		return 0;
	}
}