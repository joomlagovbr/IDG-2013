<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 *
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebaengine
 *
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

if (!defined('AKEEBA_CHUNK'))
{
	$configuration = AEFactory::getConfiguration();
	$chunksize = $configuration->get('engine.archiver.common.chunk_size', 1048756);
	define('AKEEBA_CHUNK', $chunksize);
}

// Set the umask so that files are created with (hopefully) 0666 permissions.
if (function_exists('umask'))
{
	umask(0111);
}

/**
 * Abstract parent class of all archiver engines
 */
abstract class AEAbstractArchiver extends AEAbstractObject
{
	/** @var   resource  JPA transformation source handle */
	private $_xform_fp;

	/** @var   string  The archive's comment. It's currently used ONLY in the ZIP file format */
	protected $_comment;

	/** @var   array  The last part which has been finalized and waits to be post-processed */
	public $finishedPart = array();

	/** @var   array  An array of open file pointers */
	private $filePointers = array();

	/**
	 * Common code which gets called on instance creation or wake-up (unserialization)
	 *
	 * @return  void
	 */
	protected function __bootstrap_code()
	{
		if (!defined('AKEEBA_CHUNK'))
		{
			// Cache chunk override as a constant
			$registry = AEFactory::getConfiguration();
			$chunk_override = $registry->get('engine.archiver.common.chunk_size', 0);
			define('AKEEBA_CHUNK', $chunk_override > 0 ? $chunk_override : 524288);
		}
	}

	/**
	 * Public constructor
	 *
	 * @return  AEAbstractArchiver
	 */
	public function __construct()
	{
		$this->__bootstrap_code();
	}

	/**
	 * Wakeup (unserialization) function
	 *
	 * @return  void
	 */
	final public function __wakeup()
	{
		$this->__bootstrap_code();
	}

	/**
	 * Release file pointers when the object is being serialized
	 *
	 * @return  void
	 */
	public function _onSerialize()
	{
		$this->_closeAllFiles();

		parent::_onSerialize();
	}

	/**
	 * Release file pointers when the object is being destroyed
	 *
	 * @return  void
	 */
	public function __destruct()
	{
		$this->_closeAllFiles();
	}

	/**
	 * Overrides setError() in order to also write the error message to the log file
	 *
	 * @param   string  $error  The error message
	 *
	 * @return  void
	 *
	 * @see  AEAbstractObject#setError($error)
	 */
	public function setError($error)
	{
		parent::setError($error);
		AEUtilLogger::WriteLog(_AE_LOG_ERROR, $error);
	}

	/**
	 * Overrides setWarning() in order to also write the warning  message to the log file
	 *
	 * @param   string  $warning  The warning message
	 *
	 * @return  void
	 *
	 * @see  AEAbstractObject#setWarning($warning)
	 */
	public function setWarning($warning)
	{
		parent::setWarning($warning);
		AEUtilLogger::WriteLog(_AE_LOG_WARNING, $warning);
	}

	/**
	 * Notifies the engine on the backup comment and converts it to plain text for
	 * inclusion in the archive file, if applicable.
	 *
	 * @param   string  $aComment The archive's comment
	 *
	 * @return  void
	 */
	final public function setComment($aComment)
	{
		// First, sanitize the comment in a text-only format
		$aComment = str_replace("\n", " ", $aComment); // Replace newlines with spaces
		$aComment = str_replace("<br>", "\n", $aComment); // Replace HTML4 <br> with single newlines
		$aComment = str_replace("<br/>", "\n", $aComment); // Replace HTML4 <br> with single newlines
		$aComment = str_replace("</p>", "\n\n", $aComment); // Replace paragraph endings with double newlines
		$aComment = str_replace("<b>", "*", $aComment); // Replace bold with star notation
		$aComment = str_replace("</b>", "*", $aComment); // Replace bold with star notation
		$aComment = str_replace("<i>", "_", $aComment); // Replace italics with underline notation
		$aComment = str_replace("</i>", "_", $aComment); // Replace italics with underline notation
		$this->_comment = strip_tags($aComment, '');
	}

	/**
	 * Adds a list of files into the archive, removing $removePath from the
	 * file names and adding $addPath to them.
	 *
	 * @param   array   $fileList    A simple string array of filepaths to include
	 * @param   string  $removePath  Paths to remove from the filepaths
	 * @param   string  $addPath     Paths to add in front of the filepaths
	 *
	 * @return  boolean  True on success
	 */
	final public function addFileList(&$fileList, $removePath = '', $addPath = '')
	{
		if (!is_array($fileList))
		{
			$this->setWarning('addFileList called without a file list array');

			return false;
		}

		if (function_exists('mb_internal_encoding'))
		{
			$mb_encoding = mb_internal_encoding();
			mb_internal_encoding('ISO-8859-1');
		}

		foreach ($fileList as $file)
		{
			$storedName = $this->_addRemovePaths($file, $removePath, $addPath);
			$ret = $this->_addFile(false, $file, $storedName);
			/**
			 * if( $ret === false ) {
			 * $this->setWarning(sprintf('Unreadable file %s. Check permissions.',$file));
			 * }
			 * /**/
		}

		if (function_exists('mb_internal_encoding'))
		{
			mb_internal_encoding($mb_encoding);
		}

		return true;
	}

	/**
	 * Adds a single file in the archive
	 *
	 * @param   string  $file        The absolute path to the file to add
	 * @param   string  $removePath  Path to remove from $file
	 * @param   string  $addPath     Path to prepend to $file
	 *
	 * @return  boolean
	 */
	final public function addFile($file, $removePath = '', $addPath = '')
	{
		if (function_exists('mb_internal_encoding'))
		{
			$mb_encoding = mb_internal_encoding();
			mb_internal_encoding('ISO-8859-1');
		}

		$storedName = $this->_addRemovePaths($file, $removePath, $addPath);
		$ret = $this->_addFile(false, $file, $storedName);

		if (function_exists('mb_internal_encoding'))
		{
			mb_internal_encoding($mb_encoding);
		}

		return $ret;
	}

	/**
	 * Adds a file to the archive, with a name that's different from the source
	 * filename
	 *
	 * @param   string  $sourceFile  Absolute path to the source file
	 * @param   string  $targetFile  Relative filename to store in archive
	 *
	 * @return  boolean
	 */
	final public function addFileRenamed($sourceFile, $targetFile)
	{
		if (function_exists('mb_internal_encoding'))
		{
			$mb_encoding = mb_internal_encoding();
			mb_internal_encoding('ISO-8859-1');
		}

		$ret = $this->_addFile(false, $sourceFile, $targetFile);

		if (function_exists('mb_internal_encoding'))
		{
			mb_internal_encoding($mb_encoding);
		}

		return $ret;
	}

	/**
	 * Adds a file to the archive, given the stored name and its contents
	 *
	 * @param   string  $fileName        The base file name
	 * @param   string  $addPath         The relative path to prepend to file name
	 * @param   string  $virtualContent  The contents of the file to be archived
	 *
	 * @return  boolean
	 */
	final public function addVirtualFile($fileName, $addPath = '', &$virtualContent)
	{
		$storedName = $this->_addRemovePaths($fileName, '', $addPath);

		if (function_exists('mb_internal_encoding'))
		{
			$mb_encoding = mb_internal_encoding();
			mb_internal_encoding('ISO-8859-1');
		}

		$ret = $this->_addFile(true, $virtualContent, $storedName);

		if (function_exists('mb_internal_encoding'))
		{
			mb_internal_encoding($mb_encoding);
		}

		return $ret;
	}

	/**
	 * Initialises the archiver class, creating the archive from an existent
	 * installer's JPA archive. MUST BE OVERRIDEN BY CHILDREN CLASSES.
	 *
	 * @param    string  $targetArchivePath  Absolute path to the generated archive
	 * @param    array   $options            A named key array of options (optional)
	 *
	 */
	abstract public function initialize($targetArchivePath, $options = array());

	/**
	 * Makes whatever finalization is needed for the archive to be considered
	 * complete and usefull (or, generally, clean up)
	 */
	abstract public function finalize();

	/**
	 * Returns a string with the extension (including the dot) of the files produced
	 * by this class.
	 *
	 * @return  string
	 */
	abstract public function getExtension();

	/**
	 * The most basic file transaction: add a single entry (file or directory) to
	 * the archive.
	 *
	 * @param   boolean  $isVirtual         If true, the next parameter contains file data instead of a file name
	 * @param   string   $sourceNameOrData  Absolute file name to read data from or the file data itself is $isVirtual is true
	 * @param   string   $targetName        The (relative) file name under which to store the file in the archive
	 *
	 * @return  boolean  True on success, false otherwise
	 */
	abstract protected function _addFile($isVirtual, &$sourceNameOrData, $targetName);

	// ------------------------- Helper methods -------------------------

	/**
	 * Opens a file, if it's not already open, or returns its cached file pointer if it's already open
	 *
	 * @param   string  $file  The filename to open
	 * @param   string  $mode  File open mode, defaults to binary write
	 *
	 * @return  resource
	 */
	protected final function _fopen($file, $mode = 'wb')
	{
		if (!array_key_exists($file, $this->filePointers))
		{
			$this->filePointers[$file] = @fopen($file, $mode);
		}

		return $this->filePointers[$file];
	}

	/**
	 * Closes an already open file
	 *
	 * @param   string  $file  The name of the file
	 *
	 * @return  boolean
	 */
	protected final function _fclose($file)
	{
		if (array_key_exists($file, $this->filePointers))
		{
			$fp = $this->filePointers[$file];
			$result = @fclose($fp);

			unset($this->filePointers[$file]);
		}
		else
		{
			$result = false;
		}

		return $result;
	}

	/**
	 * Closes all open files known to this archiver object
	 *
	 * @return  void
	 */
	protected final function _closeAllFiles()
	{
		if (!empty($this->filePointers))
		{
			foreach ($this->filePointers as $file => $fp)
			{
				@fclose($fp);

				unset($this->filePointers[$file]);
			}
		}
	}

	/**
	 * Write to file, defeating magic_quotes_runtime settings (pure binary write)
	 *
	 * @param   resource  $fp     Handle to a file
	 * @param   string    $data   The data to write to the file
	 * @param   integer   $p_len  Maximum length of data to write
	 *
	 * @return  boolean
	 */
	protected final function _fwrite($fp, $data, $p_len = null)
	{
		$len = is_null($p_len) ? (function_exists('mb_strlen') ? mb_strlen($data, '8bit') : strlen($data)) : $p_len;
		$ret = fwrite($fp, $data, $len);

		if (($ret === false) || (abs(($ret - $len)) >= 1))
		{
			$this->setError('Couldn\'t write to the archive file; check the output directory permissions and make sure you have enough disk space available.' . "[len=$ret / $len]");

			return false;
		}

		return true;
	}

	/**
	 * Converts a human formatted size to integer representation of bytes,
	 * e.g. 1M to 1024768
	 *
	 * @param   string  $val  The value in human readable format, e.g. "1M"
	 *
	 * @return  integer  The value in bytes
	 */
	protected final function _return_bytes($val)
	{
		$val = trim($val);
		$last = strtolower($val{strlen($val) - 1});

		switch ($last)
		{
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $val;
	}

	/**
	 * Removes the $p_remove_dir from $p_filename, while prepending it with $p_add_dir.
	 * Largely based on code from the pclZip library.
	 *
	 * @param   string  $p_filename    The absolute file name to treat
	 * @param   string  $p_remove_dir  The path to remove
	 * @param   string  $p_add_dir     The path to prefix the treated file name with
	 *
	 * @return  string  The treated file name
	 */
	private final function _addRemovePaths($p_filename, $p_remove_dir, $p_add_dir)
	{
		$p_filename = AEUtilFilesystem::TranslateWinPath($p_filename);
		$p_remove_dir = ($p_remove_dir == '') ? '' : AEUtilFilesystem::TranslateWinPath($p_remove_dir); //should fix corrupt backups, fix by nicholas

		if (!($p_remove_dir == ""))
		{
			if (substr($p_remove_dir, -1) != '/')
			{
				$p_remove_dir .= "/";
			}

			if ((substr($p_filename, 0, 2) == "./") || (substr($p_remove_dir, 0, 2) == "./"))
			{
				if ((substr($p_filename, 0, 2) == "./") && (substr($p_remove_dir, 0, 2) != "./"))
				{
					$p_remove_dir = "./" . $p_remove_dir;
				}

				if ((substr($p_filename, 0, 2) != "./") && (substr($p_remove_dir, 0, 2) == "./"))
				{
					$p_remove_dir = substr($p_remove_dir, 2);
				}
			}

			$v_compare = $this->_PathInclusion($p_remove_dir, $p_filename);

			if ($v_compare > 0)
			{
				if ($v_compare == 2)
				{
					$v_stored_filename = "";
				}
				else
				{
					$v_stored_filename = substr($p_filename, (function_exists('mb_strlen') ? mb_strlen($p_remove_dir, '8bit') : strlen($p_remove_dir)));
				}
			}
		}
		else
		{
			$v_stored_filename = $p_filename;
		}

		if (!($p_add_dir == ""))
		{
			if (substr($p_add_dir, -1) == "/")
			{
				$v_stored_filename = $p_add_dir . $v_stored_filename;
			}
			else
			{
				$v_stored_filename = $p_add_dir . "/" . $v_stored_filename;
			}
		}

		return $v_stored_filename;
	}

	/**
	 * This function indicates if the path $p_path is under the $p_dir tree. Or,
	 * said in an other way, if the file or sub-dir $p_path is inside the dir
	 * $p_dir.
	 * The function indicates also if the path is exactly the same as the dir.
	 * This function supports path with duplicated '/' like '//', but does not
	 * support '.' or '..' statements.
	 *
	 * Copied verbatim from pclZip library
	 *
	 * @param   string  $p_dir   Source tree
	 * @param   string  $p_path  Check if this is part of $p_dir
	 *
	 * @return  integer   0 if $p_path is not inside directory $p_dir,
	 *                    1 if $p_path is inside directory $p_dir
	 *                    2 if $p_path is exactly the same as $p_dir
	 */
	private final function _PathInclusion($p_dir, $p_path)
	{
		$v_result = 1;

		// ----- Explode dir and path by directory separator
		$v_list_dir = explode("/", $p_dir);
		$v_list_dir_size = sizeof($v_list_dir);
		$v_list_path = explode("/", $p_path);
		$v_list_path_size = sizeof($v_list_path);

		// ----- Study directories paths
		$i = 0;
		$j = 0;

		while (($i < $v_list_dir_size) && ($j < $v_list_path_size) && ($v_result))
		{
			// ----- Look for empty dir (path reduction)
			if ($v_list_dir[$i] == '')
			{
				$i++;

				continue;
			}

			if ($v_list_path[$j] == '')
			{
				$j++;

				continue;
			}

			// ----- Compare the items
			if (($v_list_dir[$i] != $v_list_path[$j]) && ($v_list_dir[$i] != '') && ($v_list_path[$j] != ''))
			{
				$v_result = 0;
			}

			// ----- Next items
			$i++;
			$j++;
		}

		// ----- Look if everything seems to be the same
		if ($v_result)
		{
			// ----- Skip all the empty items
			while (($j < $v_list_path_size) && ($v_list_path[$j] == ''))
			{
				$j++;
			}

			while (($i < $v_list_dir_size) && ($v_list_dir[$i] == ''))
			{
				$i++;
			}

			if (($i >= $v_list_dir_size) && ($j >= $v_list_path_size))
			{
				// ----- There are exactly the same
				$v_result = 2;
			}
			else if ($i < $v_list_dir_size)
			{
				// ----- The path is shorter than the dir
				$v_result = 0;
			}
		}

		// ----- Return
		return $v_result;
	}

	/**
	 * Transforms a JPA archive (containing an installer) to the native archive format
	 * of the class. It actually extracts the source JPA in memory and instructs the
	 * class to include each extracted file.
	 *
	 * @param   integer  $index  The index in the source JPA archive's list currently in use
	 * @param   integer  $offset The source JPA archive's offset to use
	 *
	 * @return  boolean  False if an error occurred, true otherwise
	 */
	public final function transformJPA($index, $offset)
	{
		static $totalSize = 0;

		// Do we have to open the file?
		if (!$this->_xform_fp)
		{
			// Get the source path
			$registry = AEFactory::getConfiguration();
			$embedded_installer = $registry->get('akeeba.advanced.embedded_installer');

			// Fetch the name of the installer image
			$installerDescriptors = AEUtilInihelper::getInstallerList();
			$xform_source = AEPlatform::getInstance()->get_installer_images_path() . DIRECTORY_SEPARATOR .
				'foobar.jpa'; // We need this as a "safe fallback"

			if (array_key_exists($embedded_installer, $installerDescriptors))
			{
				$packages = $installerDescriptors[$embedded_installer]['package'];

				if (empty($packages))
				{
					// No installer package specified. Pretend we are done!
					$retArray = array(
						"filename" => '', // File name extracted
						"data"     => '', // File data
						"index"    => 0, // How many source JPA files I have
						"offset"   => 0, // Offset in JPA file
						"skip"     => false, // Skip this?
						"done"     => true, // Are we done yet?
						"filesize" => 0
					);

					return $retArray;
				}
				else
				{
					$packages = explode(',', $packages);
					$totalSize = 0;
					$pathPrefix = AEPlatform::getInstance()->get_installer_images_path() . DIRECTORY_SEPARATOR;

					foreach ($packages as $package)
					{
						$filePath = $pathPrefix . $package;
						$totalSize += (int)@filesize($filePath);
					}

					if (count($packages) < $index)
					{
						$this->setError(__CLASS__ . ":: Installer package index $index not found for embedded installer $embedded_installer");

						return false;
					}

					$package = $packages[$index];

					// A package is specified, use it!
					$xform_source = $pathPrefix . $package;
				}
			}

			// 2.3: Try to use sane default if the indicated installer doesn't exist
			if (!file_exists($xform_source) && (basename($xform_source) != 'angie.jpa'))
			{
				if ($index == 0)
				{
					$this->setWarning(__CLASS__ . ":: Selected embedded installer not found, using ANGIE instead");
					$xform_source = dirname($xform_source) . '/angie.jpa';
				}
				else
				{
					$this->setError(__CLASS__ . ":: Installer package $xform_source not found.");

					return false;
				}
			}

			// Try opening the file
			if (file_exists($xform_source))
			{
				$this->_xform_fp = @fopen($xform_source, 'r');

				if ($this->_xform_fp === false)
				{
					$this->setError(__CLASS__ . ":: Can't seed archive with installer package " . $xform_source);

					return false;
				}
			}
			else
			{
				$this->setError(__CLASS__ . ":: Installer package " . $xform_source . " does not exist!");

				return false;
			}
		}

		$headerDataLength = 0;

		if (!$offset)
		{
			// First run detected!
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'Initializing with JPA package ' . $xform_source);

			$offset = 0;

			// Skip over the header and check no problem exists
			$offset = $this->_xformReadHeader();

			if ($offset === false)
			{
				$this->setError('JPA package file was not read');

				return false; // Oops! The package file doesn't exist or is corrupt
			}

			$headerDataLength = $offset;
		}

		$ret = $this->_xformExtract($offset);

		$ret['index'] = $index;

		if (is_array($ret))
		{
			$ret['chunkProcessed'] = $headerDataLength + $ret['offset'] - $offset;
			$offset = $ret['offset'];

			if (!$ret['skip'] && !$ret['done'])
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, '  Adding ' . $ret['filename'] . '; Next offset:' . $offset);
				$this->addVirtualFile($ret['filename'], '', $ret['data']);

				if ($this->getError())
				{
					return false;
				}
			}
			elseif ($ret['done'])
			{
				$registry = AEFactory::getConfiguration();
				$embedded_installer = $registry->get('akeeba.advanced.embedded_installer');
				$installerDescriptors = AEUtilInihelper::getInstallerList();
				$packages = $installerDescriptors[$embedded_installer]['package'];
				$packages = explode(',', $packages);

				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, '  Done with package ' . $packages[$index]);

				if (count($packages) > ($index + 1))
				{
					$ret['done'] = false;
					$ret['index'] = $index + 1;
					$ret['offset'] = 0;
					$this->_xform_fp = null;
				}
				else
				{
					AEUtilLogger::WriteLog(_AE_LOG_DEBUG, '  Done with installer seeding.');
				}
			}
			else // if ($ret['skip'])
			{
				$reason = '  Skipping ' . $ret['filename'];
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, $reason);
			}
		}
		else
		{
			$this->setError('JPA extraction returned FALSE. The installer image is corrupt.');

			return false;
		}

		if ($ret['done'])
		{
			// We are finished! Close the file
			fclose($this->_xform_fp);
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'Initializing with JPA package has finished');
		}

		$ret['filesize'] = $totalSize;

		return $ret;
	}

	/**
	 * Extracts a file from the JPA archive and returns an in-memory array containing it
	 * and its file data. The data returned is an array, consisting of the following keys:
	 * "filename" => relative file path stored in the archive
	 * "data"     => file data
	 * "offset"   => next offset to use
	 * "skip"     => if this is not a file, just skip it...
	 * "done"     => No more files left in archive
	 *
	 * @param   integer  $offset  The absolute data offset from archive's header
	 *
	 * @return  array  See description for more information
	 */
	private final function &_xformExtract($offset)
	{
		$false = false; // Used to return false values in case an error occurs

		// Generate a return array
		$retArray = array(
			"filename" => '', // File name extracted
			"data"     => '', // File data
			"offset"   => 0, // Offset in ZIP file
			"skip"     => false, // Skip this?
			"done"     => false // Are we done yet?
		);

		// If we can't open the file, return an error condition
		if ($this->_xform_fp === false)
		{
			return $false;
		}

		// Go to the offset specified
		if (!fseek($this->_xform_fp, $offset) == 0)
		{
			return $false;
		}

		// Get and decode Entity Description Block
		$signature = fread($this->_xform_fp, 3);

		// Check signature
		if ($signature == 'JPF')
		{
			// This a JPA Entity Block. Process the header.

			// Read length of EDB and of the Entity Path Data
			$length_array = unpack('vblocksize/vpathsize', fread($this->_xform_fp, 4));
			// Read the path data
			$file = fread($this->_xform_fp, $length_array['pathsize']);
			// Read and parse the known data portion
			$bin_data = fread($this->_xform_fp, 14);
			$header_data = unpack('Ctype/Ccompression/Vcompsize/Vuncompsize/Vperms', $bin_data);
			// Read any unknwon data
			$restBytes = $length_array['blocksize'] - (21 + $length_array['pathsize']);

			if ($restBytes > 0)
			{
				$junk = fread($this->_xform_fp, $restBytes);
			}

			$compressionType = $header_data['compression'];

			// Populate the return array
			$retArray['filename'] = $file;
			$retArray['skip'] = ($header_data['compsize'] == 0); // Skip over directories

			switch ($header_data['type'])
			{
				case 0:
					// directory
					break;

				case 1:
					// file
					switch ($compressionType)
					{
						case 0: // No compression
							if ($header_data['compsize'] > 0) // 0 byte files do not have data to be read
							{
								$retArray['data'] = fread($this->_xform_fp, $header_data['compsize']);
							}
							break;

						case 1: // GZip compression
							$zipData = fread($this->_xform_fp, $header_data['compsize']);
							$retArray['data'] = gzinflate($zipData);
							break;

						case 2: // BZip2 compression
							$zipData = fread($this->_xform_fp, $header_data['compsize']);
							$retArray['data'] = bzdecompress($zipData);
							break;
					}
					break;
			}
		}
		else
		{
			// This is not a file header. This means we are done.
			$retArray['done'] = true;
		}

		$retArray['offset'] = ftell($this->_xform_fp);

		return $retArray;
	}

	/**
	 * Skips over the JPA header entry and returns the offset file data starts from
	 *
	 * @return  boolean|integer  False on failure, offset otherwise
	 */
	private final function _xformReadHeader()
	{
		// Fail for unreadable files
		if ($this->_xform_fp === false)
		{
			return false;
		}

		// Go to the beggining of the file
		rewind($this->_xform_fp);

		// Read the signature
		$sig = fread($this->_xform_fp, 3);

		// Not a JPA Archive?
		if ($sig != 'JPA')
		{
			return false;
		}

		// Read and parse header length
		$header_length_array = unpack('v', fread($this->_xform_fp, 2));
		$header_length = $header_length_array[1];

		// Read and parse the known portion of header data (14 bytes)
		$bin_data = fread($this->_xform_fp, 14);
		$header_data = unpack('Cmajor/Cminor/Vcount/Vuncsize/Vcsize', $bin_data);

		// Load any remaining header data (forward compatibility)
		$rest_length = $header_length - 19;

		if ($rest_length > 0)
		{
			$junk = fread($this->_xform_fp, $rest_length);
		}

		return ftell($this->_xform_fp);
	}
}