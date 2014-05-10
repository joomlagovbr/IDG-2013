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

if (!function_exists('akstringlen'))
{
	function akstringlen($string)
	{
		return function_exists('mb_strlen') ? mb_strlen($string, '8bit') : strlen($string);
	}
}

/**
 * JoomlaPack Archive creation class
 *
 * JPA Format 1.0 implemented, minus BZip2 compression support
 */
class AEArchiverJpa extends AEAbstractArchiver
{
	/** @var integer How many files are contained in the archive */
	private $_fileCount = 0;

	/** @var integer The total size of files contained in the archive as they are stored */
	private $_compressedSize = 0;

	/** @var integer The total size of files contained in the archive when they are extracted to disk. */
	private $_uncompressedSize = 0;

	/** @var string The name of the file holding the ZIP's data, which becomes the final archive */
	private $_dataFileName;

	/** @var string Standard Header signature */
	private $_archive_signature = "\x4A\x50\x41";

	/** @var string Entity Block signature */
	private $_fileHeader = "\x4A\x50\x46";

	/** @var string Marks the split archive's extra header */
	private $_extraHeaderSplit = "\x4A\x50\x01\x01"; //

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
	 * Extend the bootstrap code to add some define's used by the JPA format engine
	 * @see backend/akeeba/abstract/AEAbstractArchiver#__bootstrap_code()
	 */
	protected function __bootstrap_code()
	{
		if (!defined('_AKEEBA_COMPRESSION_THRESHOLD'))
		{
			$config = AEFactory::getConfiguration();
			define("_AKEEBA_COMPRESSION_THRESHOLD", $config->get('engine.archiver.common.big_file_threshold')); // Don't compress files over this size

			/**
			 * Akeeba Backup and JPA Format version change chart:
			 * Akeeba Backup 3.0: JPA Format 1.1 is used
			 * Akeeba Backup 3.1: JPA Format 1.2 with file modification timestamp is used
			 */
			define('_JPA_MAJOR', 1); // JPA Format major version number
			define('_JPA_MINOR', 2); // JPA Format minor version number

		}
		parent::__bootstrap_code();
	}

	/**
	 * Initialises the archiver class, creating the archive from an existent
	 * installer's JPA archive.
	 *
	 * @param string $sourceJPAPath     Absolute path to an installer's JPA archive
	 * @param string $targetArchivePath Absolute path to the generated archive
	 * @param array  $options           A named key array of options (optional)
	 *
	 * @access public
	 */
	public function initialize($targetArchivePath, $options = array())
	{
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "AEArchiverJpa :: new instance - archive $targetArchivePath");
		$this->_dataFileName = $targetArchivePath;

		// NEW 2.3: Should we enable Split ZIP feature?
		$registry = AEFactory::getConfiguration();
		$fragmentsize = $registry->get('engine.archiver.common.part_size', 0);
		if ($fragmentsize >= 65536)
		{
			// If the fragment size is AT LEAST 64Kb, enable Split ZIP
			$this->_useSplitZIP = true;
			$this->_fragmentSize = $fragmentsize;

			// Indicate that we have at least 1 part
			$statistics = AEFactory::getStatistics();
			$statistics->updateMultipart(1);
			$this->_totalFragments = 1;

			AEUtilLogger::WriteLog(_AE_LOG_INFO, "AEArchiverJpa :: Spanned JPA creation enabled");
			$this->_dataFileNameBase = dirname($targetArchivePath) . '/' . basename($targetArchivePath, '.jpa');
			$this->_dataFileName = $this->_dataFileNameBase . '.j01';
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

		// Try to kill the archive if it exists
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "AEArchiverJpa :: Killing old archive");
		$fp = @fopen($this->_dataFileName, "wb");
		if (!($fp === false))
		{
			@ftruncate($fp, 0);
			@fclose($fp);
		}
		else
		{
			if (file_exists($this->_dataFileName))
			{
				@unlink($this->_dataFileName);
			}
			@touch($this->_dataFileName);
			if (function_exists('chmod'))
			{
				chmod($this->_dataFileName, 0666);
			}
		}

		// Write the initial instance of the archive header
		$this->_writeArchiveHeader();
		if ($this->getError())
		{
			return;
		}
	}

	/**
	 * Updates the Standard Header with current information
	 */
	public function finalize()
	{
		// If Spanned JPA and there is no .jpa file, rename the last fragment to .jpa
		if ($this->_useSplitZIP)
		{
			$extension = substr($this->_dataFileName, -3);
			if ($extension != '.jpa')
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'Renaming last JPA part to .JPA extension');
				$newName = $this->_dataFileNameBase . '.jpa';
				if (!@rename($this->_dataFileName, $newName))
				{
					$this->setError('Could not rename last JPA part to .JPA extension.');

					return false;
				}
				$this->_dataFileName = $newName;
			}

			// Finally, point to the first part so that we can re-write the correct header information
			if ($this->_totalFragments > 1)
			{
				$this->_dataFileName = $this->_dataFileNameBase . '.j01';
			}
		}

		// Re-write the archive header
		$this->_writeArchiveHeader();

		if ($this->getError())
		{
			return;
		}
	}

	/**
	 * Returns a string with the extension (including the dot) of the files produced
	 * by this class.
	 * @return string
	 */
	public function getExtension()
	{
		return '.jpa';
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
	 * @since  1.2.1
	 * @access protected
	 * @abstract
	 */
	protected function _addFile($isVirtual, &$sourceNameOrData, $targetName)
	{
		static $configuration;

		static $memLimit = null;

		if (is_null($memLimit))
		{
			$memLimit = ini_get("memory_limit");

			if ((is_numeric($memLimit) && ($memLimit < 0)) || !is_numeric($memLimit))
			{
				$memLimit = 0; // 1.2a3 -- Rare case with memory_limit < 0, e.g. -1Mb!
			}

			$memLimit = $this->_return_bytes($memLimit);
		}

		$isDir = false;
		$isSymlink = false;
		if (is_null($isVirtual))
		{
			$isVirtual = false;
		}
		$compressionMethod = 0;

		if ($isVirtual)
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "-- Adding $targetName to archive (virtual data)");
		}
		else
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "-- Adding $targetName to archive (source: $sourceNameOrData)");
		}

		if (!$configuration)
		{
			$configuration = AEFactory::getConfiguration();
		}
		$timer = AEFactory::getTimer();

		// Initialize archive file pointer
		$fp = null;

		// Initialize inode change timestamp
		$filectime = 0;

		if (!$configuration->get('volatile.engine.archiver.processingfile', false))
		{
			// Uncache data -- WHY DO THAT?!
			/**
			 * $configuration->set('volatile.engine.archiver.sourceNameOrData', null);
			 * $configuration->set('volatile.engine.archiver.unc_len', null);
			 * $configuration->set('volatile.engine.archiver.resume', null);
			 * $configuration->set('volatile.engine.archiver.processingfile',false);
			 * /**/

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
				$fileSize = akstringlen($sourceNameOrData);
				$filectime = time();
			}
			else
			{
				if ($isSymlink)
				{
					$fileSize = akstringlen(@readlink($sourceNameOrData));
				}
				else
				{
					// Is the file readable?
					if (!is_readable($sourceNameOrData) && !$isDir)
					{
						// Unreadable files won't be recorded in the archive file
						$this->setWarning('Unreadable file ' . $sourceNameOrData . '. Check permissions');

						return false;
					}

					// Get the filesize
					$fileSize = $isDir ? 0 : @filesize($sourceNameOrData);
					$filectime = $isDir ? 0 : @filemtime($sourceNameOrData);
				}
			}

			// Decide if we will compress
			if ($isDir || $isSymlink)
			{
				$compressionMethod = 0; // don't compress directories...
			}
			else
			{
				if (!$memLimit || ($fileSize >= _AKEEBA_COMPRESSION_THRESHOLD))
				{
					// No memory limit, or over 1Mb files => always compress up to 1Mb files (otherwise it times out)
					$compressionMethod = ($fileSize <= _AKEEBA_COMPRESSION_THRESHOLD) ? 1 : 0;
				}
				elseif (function_exists("memory_get_usage"))
				{
					// PHP can report memory usage, see if there's enough available memory; Joomla! alone eats about 5-6Mb! This code is called on files <= 1Mb
					$availableRAM = $memLimit - memory_get_usage();
					$compressionMethod = (($availableRAM / 2.5) >= $fileSize) ? 1 : 0;
				}
				else
				{
					// PHP can't report memory usage, compress only files up to 512Kb (conservative approach) and hope it doesn't break
					$compressionMethod = ($fileSize <= 524288) ? 1 : 0;
				}
			}

			$compressionMethod = function_exists("gzcompress") ? $compressionMethod : 0;

			$storedName = $targetName;

			/* "Entity Description BLock" segment. */
			$unc_len = & $fileSize; // File size
			$storedName .= ($isDir) ? "/" : "";

			if ($compressionMethod == 1)
			{
				if ($isVirtual)
				{
					$udata =& $sourceNameOrData;
				}
				else
				{
					// Get uncompressed data
					$udata = @file_get_contents($sourceNameOrData); // PHP > 4.3.0 saves us the trouble
				}

				if ($udata === false)
				{
					// Unreadable file, skip it.
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
						$c_len = & $unc_len;
						$compressionMethod = 0;
					}
					else
					{
						unset($udata);
						$zdata = substr(substr($zdata, 0, -4), 2);
						$c_len = akstringlen($zdata);
					}
				}
			}
			else
			{
				$c_len = $unc_len;
				// Test for unreadable files
				if (!$isVirtual && !$isSymlink && !$isDir)
				{
					$myfp = @fopen($sourceNameOrData, 'rb');
					if ($myfp === false)
					{
						// Unreadable file, skip it.
						$this->setWarning('Unreadable file ' . $sourceNameOrData . '. Check permissions');

						return false;
					}
					@fclose($myfp);
				}
			}

			$this->_compressedSize += $c_len; // Update global data
			$this->_uncompressedSize += $fileSize; // Update global data
			$this->_fileCount++;

			// Get file permissions
			$perms = 0755;
			if (!$isVirtual)
			{
				if (@file_exists($sourceNameOrData))
				{
					if (@is_file($sourceNameOrData) || @is_link($sourceNameOrData))
					{
						if (@is_readable($sourceNameOrData))
						{
							$perms = @fileperms($sourceNameOrData);
						}
					}
				}
			}

			// Calculate Entity Description Block length
			$blockLength = 21 + akstringlen($storedName);
			if ($filectime > 0)
			{
				$blockLength += 8;
			} // If we need to store the file mod date

			// Get file type
			if ((!$isDir) && (!$isSymlink))
			{
				$fileType = 1;
			}
			elseif ($isSymlink)
			{
				$fileType = 2;
			}
			elseif ($isDir)
			{
				$fileType = 0;
			}

			// If it's a split ZIP file, we've got to make sure that the header can fit in the part
			if ($this->_useSplitZIP)
			{
				// Compare to free part space
				clearstatcache();
				$current_part_size = @filesize($this->_dataFileName);
				$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);
				if ($free_space <= $blockLength)
				{
					// Not enough space on current part, create new part
					if (!$this->_createNewPart())
					{
						$this->setError('Could not create new JPA part file ' . basename($this->_dataFileName));

						return false;
					}
				}
			}

			// Open data file for output
			$fp = @fopen($this->_dataFileName, "ab");
			if ($fp === false)
			{
				$this->setError("Could not open archive file '{$this->_dataFileName}' for append!");

				return false;
			}
			$this->_fwrite($fp, $this->_fileHeader); // Entity Description Block header
			if ($this->getError())
			{
				return false;
			}
			$this->_fwrite($fp, pack('v', $blockLength)); // Entity Description Block header length
			$this->_fwrite($fp, pack('v', akstringlen($storedName))); // Length of entity path
			$this->_fwrite($fp, $storedName); // Entity path
			$this->_fwrite($fp, pack('C', $fileType)); // Entity type
			$this->_fwrite($fp, pack('C', $compressionMethod)); // Compression method
			$this->_fwrite($fp, pack('V', $c_len)); // Compressed size
			$this->_fwrite($fp, pack('V', $unc_len)); // Uncompressed size
			$this->_fwrite($fp, pack('V', $perms)); // Entity permissions

			// Timestamp Extra Field, only for files
			if ($filectime > 0)
			{
				$this->_fwrite($fp, "\x00\x01"); // Extra Field Identifier
				$this->_fwrite($fp, pack('v', 8)); // Extra Field Length
				$this->_fwrite($fp, pack('V', $filectime)); // Timestamp
			}

			// Cache useful information about the file
			if (!$isDir && !$isSymlink && !$isVirtual)
			{
				$configuration->set('volatile.engine.archiver.unc_len', $unc_len);
				$configuration->set('volatile.engine.archiver.sourceNameOrData', $sourceNameOrData);
			}
		}
		else
		{
			// If we are continuing file packing we have an uncompressed, non-virtual file.
			// We need to set up these variables so as not to throw any PHP notices.
			$isDir = false;
			$isSymlink = false;
			$isVirtual = false;
			$compressionMethod = 0;

			// Create a file pointer to the archive file
			$fp = @fopen($this->_dataFileName, "ab");
			if ($fp === false)
			{
				$this->setError("Could not open archive file '{$this->_dataFileName}' for append!");

				return false;
			}
		}

		/* "File data" segment. */
		if ($compressionMethod == 1)
		{
			if (!$this->_useSplitZIP)
			{
				// Just dump the compressed data
				$this->_fwrite($fp, $zdata);
				if ($this->getError())
				{
					@fclose($fp);

					return false;
				}
			}
			else
			{
				// Split ZIP. Check if we need to split the part in the middle of the data.
				clearstatcache();
				$current_part_size = @filesize($this->_dataFileName);
				$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);
				if ($free_space >= akstringlen($zdata))
				{
					// Write in one part
					$this->_fwrite($fp, $zdata);
					if ($this->getError())
					{
						@fclose($fp);

						return false;
					}
				}
				else
				{
					$bytes_left = akstringlen($zdata);

					while ($bytes_left > 0)
					{
						clearstatcache();
						$current_part_size = @filesize($this->_dataFileName);
						$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);

						// Split between parts - Write first part
						$this->_fwrite($fp, $zdata, min(akstringlen($zdata), $free_space));
						if ($this->getError())
						{
							@fclose($fp);

							return false;
						}

						// Get the rest of the data
						$bytes_left = akstringlen($zdata) - $free_space;

						if ($bytes_left > 0)
						{
							// Create new part
							@fclose($fp);
							if (!$this->_createNewPart())
							{
								// Die if we couldn't create the new part
								$this->setError('Could not create new JPA part file ' . basename($this->_dataFileName));

								return false;
							}
							else
							{
								// Close the old data file
								@fclose($fp);
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
		elseif ((!$isDir) && (!$isSymlink))
		{
			if ($isVirtual)
			{
				if (!$this->_useSplitZIP)
				{
					// Just dump the data
					$this->_fwrite($fp, $sourceNameOrData);
					if ($this->getError())
					{
						@fclose($fp);

						return false;
					}
				}
				else
				{
					// Split JPA. Check if we need to split the part in the middle of the data.
					clearstatcache();
					$current_part_size = @filesize($this->_dataFileName);
					$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);
					if ($free_space >= akstringlen($sourceNameOrData))
					{
						// Write in one part
						$this->_fwrite($fp, $sourceNameOrData);
						if ($this->getError())
						{
							return false;
						}
					}
					else
					{
						$bytes_left = akstringlen($sourceNameOrData);

						while ($bytes_left > 0)
						{
							clearstatcache();
							$current_part_size = @filesize($this->_dataFileName);
							$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);

							// Split between parts - Write first part
							$this->_fwrite($fp, $sourceNameOrData, min(akstringlen($sourceNameOrData), $free_space));
							if ($this->getError())
							{
								@fclose($fp);

								return false;
							}

							// Get the rest of the data
							$rest_size = akstringlen($sourceNameOrData) - $free_space;
							if ($rest_size > 0)
							{
								// Create new part
								if (!$this->_createNewPart())
								{
									// Die if we couldn't create the new part
									$this->setError('Could not create new JPA part file ' . basename($this->_dataFileName));
									@fclose($fp);

									return false;
								}
								else
								{
									// Close the old data file
									@fclose($fp);
									// Open data file for output
									$fp = @fopen($this->_dataFileName, "ab");
									if ($fp === false)
									{
										$this->setError("Could not open archive file {$this->_dataFileName} for append!");

										return false;
									}
								}
								$zdata = substr($sourceNameOrData, -$rest_size);
							}
							$bytes_left = $rest_size;
						} // end while
					}
				}
			}
			else
			{
				// IMPORTANT! Only this case can be spanned across steps: uncompressed, non-virtual data
				// Load cached data if we're resumming file packing
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
					@fclose($fp);

					return false;
				}
				else
				{
					// Seek to the resume point if required
					if ($configuration->get('volatile.engine.archiver.processingfile', false))
					{
						// Seek to new offset
						$seek_result = @fseek($zdatafp, $resume);
						if ($seek_result === -1)
						{
							// What?! We can't resume!
							$this->setError(sprintf('Could not resume packing of file %s. Your archive is damaged!', $sourceNameOrData));
							@fclose($zdatafp);
							@fclose($fp);

							return false;
						}

						// Doctor the uncompressed size to match the remainder of the data
						$unc_len = $unc_len - $resume;
					}

					if (!$this->_useSplitZIP)
					{
						while (!feof($zdatafp) && ($timer->getTimeLeft() > 0) && ($unc_len > 0))
						{
							$zdata = fread($zdatafp, AKEEBA_CHUNK);
							$this->_fwrite($fp, $zdata, min(akstringlen($zdata), AKEEBA_CHUNK));
							$unc_len -= min(akstringlen($zdata), AKEEBA_CHUNK);
							if ($this->getError())
							{
								@fclose($zdatafp);
								@fclose($fp);

								return false;
							}
						}
						// WARNING!!! The extra $unc_len != 0 check is necessary as PHP won't reach EOF for 0-byte files.
						if (!feof($zdatafp) && ($unc_len != 0))
						{
							// We have to break, or we'll time out!
							$resume = @ftell($zdatafp);
							$configuration->set('volatile.engine.archiver.resume', $resume);
							$configuration->set('volatile.engine.archiver.processingfile', true);
							@fclose($zdatafp);
							@fclose($fp);

							return true;
						}
					}
					else
					{
						// Split JPA - Do we have enough space to host the whole file?
						clearstatcache();
						$current_part_size = @filesize($this->_dataFileName);
						$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);
						if ($free_space >= $unc_len)
						{
							// Yes, it will fit inside this part, do quick copy
							while (!feof($zdatafp) && ($timer->getTimeLeft() > 0) && ($unc_len > 0))
							{
								$zdata = fread($zdatafp, AKEEBA_CHUNK);
								$this->_fwrite($fp, $zdata, min(akstringlen($zdata), AKEEBA_CHUNK));
								//$unc_len -= min(akstringlen($zdata), AKEEBA_CHUNK);
								$unc_len -= AKEEBA_CHUNK;
								if ($this->getError())
								{
									@fclose($zdatafp);
									@fclose($fp);

									return false;
								}
							}
							//if(!feof($zdatafp) && ($unc_len != 0))
							if (!feof($zdatafp) && ($unc_len > 0))
							{
								// We have to break, or we'll time out!
								$resume = @ftell($zdatafp);
								$configuration->set('volatile.engine.archiver.resume', $resume);
								$configuration->set('volatile.engine.archiver.processingfile', true);
								@fclose($zdatafp);
								@fclose($fp);

								return true;
							}
						}
						else
						{
							// No, we'll have to split between parts. We'll loop until we run
							// out of space.

							while (!feof($zdatafp) && ($timer->getTimeLeft() > 0))
							{
								clearstatcache();
								$current_part_size = @filesize($this->_dataFileName);
								$free_space = $this->_fragmentSize - ($current_part_size === false ? 0 : $current_part_size);

								// Find optimal chunk size
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
									$this->_fwrite($fp, $zdata, min(akstringlen($zdata), $chunk_size_primary));
									//$unc_len -= min(akstringlen($zdata), $chunk_size_primary);
									$unc_len -= $chunk_size_primary;
									if ($this->getError())
									{
										@fclose($zdatafp);
										@fclose($fp);

										return false;
									}

									// Do we have enough time to proceed?
									//if( (!feof($zdatafp)) && ($unc_len != 0) && ($timer->getTimeLeft() <= 0) ) {
									if ((!feof($zdatafp)) && ($unc_len >= 0) && ($timer->getTimeLeft() <= 0))
									{
										// No, we have to break, or we'll time out!
										$resume = @ftell($zdatafp);
										$configuration->set('volatile.engine.archiver.resume', $resume);
										$configuration->set('volatile.engine.archiver.processingfile', true);
										@fclose($zdatafp);
										@fclose($fp);

										return true;
									}

								}

								// Read and write w/ secondary chunk size, if non-zero
								if ($chunk_size_secondary > 0)
								{
									$zdata = fread($zdatafp, $chunk_size_secondary);
									$this->_fwrite($fp, $zdata, min(akstringlen($zdata), $chunk_size_secondary));
									//$unc_len -= min(akstringlen($zdata), $chunk_size_secondary);
									$unc_len -= $chunk_size_secondary;
									if ($this->getError())
									{
										@fclose($zdatafp);
										@fclose($fp);

										return false;
									}
								}

								// Do we have enough time to proceed?
								//if( (!feof($zdatafp)) && ($unc_len != 0) && ($timer->getTimeLeft() <= 0) ) {
								if ((!feof($zdatafp)) && ($unc_len >= 0) && ($timer->getTimeLeft() <= 0))
								{
									// No, we have to break, or we'll time out!
									$resume = @ftell($zdatafp);
									$configuration->set('volatile.engine.archiver.resume', $resume);
									$configuration->set('volatile.engine.archiver.processingfile', true);

									// ...and create a new part as well
									if (!$this->_createNewPart())
									{
										// Die if we couldn't create the new part
										$this->setError('Could not create new JPA part file ' . basename($this->_dataFileName));
										@fclose($zdatafp);
										@fclose($fp);

										return false;
									}

									// ...then, return
									@fclose($zdatafp);
									@fclose($fp);

									return true;
								}

								// Create new JPA part, but only if we'll have more data to write
								//if(!feof($zdatafp) && ($unc_len != 0) && ($unc_len > 0) )
								if (!feof($zdatafp) && ($unc_len > 0))
								{
									if (!$this->_createNewPart())
									{
										// Die if we couldn't create the new part
										$this->setError('Could not create new JPA part file ' . basename($this->_dataFileName));
										@fclose($zdatafp);
										@fclose($fp);

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
											@fclose($zdatafp);

											return false;
										}
									}
								}
							} // end while
						}
					}
					@fclose($zdatafp);
				}
			}
		}
		elseif ($isSymlink)
		{
			$this->_fwrite($fp, @readlink($sourceNameOrData));
		}

		@fclose($fp);

		//AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "DEBUG -- Added $targetName to archive");

		// Uncache data
		$configuration->set('volatile.engine.archiver.sourceNameOrData', null);
		$configuration->set('volatile.engine.archiver.unc_len', null);
		$configuration->set('volatile.engine.archiver.resume', null);
		$configuration->set('volatile.engine.archiver.processingfile', false);

		// ... and return TRUE = success
		return true;
	}


	// ------------------------------------------------------------------------
	// Archiver-specific utility functions
	// ------------------------------------------------------------------------
	/**
	 * Outputs a Standard Header at the top of the file
	 *
	 */
	private function _writeArchiveHeader()
	{
		$fp = @fopen($this->_dataFileName, 'r+');
		if ($fp === false)
		{
			$this->setError('Could not open ' . $this->_dataFileName . ' for writing. Check permissions and open_basedir restrictions.');

			return false;
		}

		// Calculate total header size
		$headerSize = 19; // Standard Header
		if ($this->_useSplitZIP)
		{
			$headerSize += 8;
		} // Spanned JPA header

		$this->_fwrite($fp, $this->_archive_signature); // ID string (JPA)
		if ($this->getError())
		{
			return false;
		}
		$this->_fwrite($fp, pack('v', $headerSize)); // Header length; fixed to 19 bytes
		$this->_fwrite($fp, pack('C', _JPA_MAJOR)); // Major version
		$this->_fwrite($fp, pack('C', _JPA_MINOR)); // Minor version
		$this->_fwrite($fp, pack('V', $this->_fileCount)); // File count
		$this->_fwrite($fp, pack('V', $this->_uncompressedSize)); // Size of files when extracted
		$this->_fwrite($fp, pack('V', $this->_compressedSize)); // Size of files when stored

		// Do I need to add a split archive's header too?
		if ($this->_useSplitZIP)
		{
			$this->_fwrite($fp, $this->_extraHeaderSplit); // Signature
			$this->_fwrite($fp, pack('v', 4)); // Extra field length
			$this->_fwrite($fp, pack('v', $this->_totalFragments)); // Number of parts
		}

		@fclose($fp);
		if (function_exists('chmod'))
		{
			@chmod($this->_dataFileName, 0755);
		}
	}

	private function _createNewPart($finalPart = false)
	{
		// Push the previous part if we have to post-process it immediately
		$configuration = AEFactory::getConfiguration();
		if ($configuration->get('engine.postproc.common.after_part', 0))
		{
			// The first part needs its header overwritten during archive
			// finalization. Skip it from immediate processing.
			if ($this->_currentFragment != 1)
			{
				$this->finishedPart[] = $this->_dataFileName;
			}
		}

		$this->_totalFragments++;
		$this->_currentFragment = $this->_totalFragments;
		if ($finalPart)
		{
			$this->_dataFileName = $this->_dataFileNameBase . '.jpa';
		}
		else
		{
			$this->_dataFileName = $this->_dataFileNameBase . '.j' . sprintf('%02d', $this->_currentFragment);
		}
		AEUtilLogger::WriteLog(_AE_LOG_INFO, 'Creating new JPA part #' . $this->_currentFragment . ', file ' . $this->_dataFileName);
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
		// Try to write 6 bytes to it
		if ($result)
		{
			$result = @file_put_contents($this->_dataFileName, 'AKEEBA') == 6;
		}
		if ($result)
		{
			@unlink($this->_dataFileName);
			$result = @touch($this->_dataFileName);
			if (function_exists('chmod'))
			{
				chmod($this->_dataFileName, 0666);
			}
		}

		return $result;
	}

}