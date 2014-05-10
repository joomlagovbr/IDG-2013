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

/**
 * Akeeba Engine post processing abstract class
 * @author Nicholas
 *
 */
abstract class AEAbstractPostproc extends AEAbstractObject
{
	/** @var bool Should we break the step before post-processing? */
	public $break_before = true;

	/** @var bool Should we break the step after post-processing? */
	public $break_after = true;

	/** @var bool Does this engine processes the files in a way that makes deleting the originals safe? */
	public $allow_deletes = true;

	/** @var bool Does this engine support remote file deletes? */
	public $can_delete = false;

	/** @var bool Does this engine support downloads to files? */
	public $can_download_to_file = false;

	/** @var bool Does this engine support downloads to browser? */
	public $can_download_to_browser = false;

	/** @var bool Set to true if raw data will be dumped to the browser when downloading the file to the browser. Set to false if a URL is returned instead. */
	public $downloads_to_browser_inline = false;

	/**
	 * The remote absolute path to the file which was just processed. Leave null if the file is meant to
	 * be non-retrievable, i.e. sent to email or any other one way service.
	 * @var string
	 */
	public $remote_path = null;

	/**
	 * This function takes care of post-processing a backup archive's part, or the
	 * whole backup archive if it's not a split archive type. If the process fails
	 * it should return false. If it succeeds and the entirety of the file has been
	 * processed, it should return true. If only a part of the file has been uploaded,
	 * it must return 1.
	 *
	 * @param   string  $absolute_filename  Absolute path to the part we'll have to process
	 * @param   string  $upload_as          Base name of the uploaded file, skip to use $absolute_filename's
	 *
	 * @return  boolean|integer  False on failure, true on success, 1 if more work is required
	 */
	public abstract function processPart($absolute_filename, $upload_as = null);

	/**
	 * Deletes a remote file
	 *
	 * @param $path string Absolute path to the file we're deleting
	 *
	 * @return bool|int False on failure, true on success, 1 if more work is required
	 */
	public function delete($path)
	{
		return false;
	}

	/**
	 * Downloads a remote file to a local file, optionally doing a range download. If the
	 * download fails we return false. If the download succeeds we return true. If range
	 * downloads are not supported, -1 is returned and nothing is written to disk.
	 *
	 * @param $remotePath string The path to the remote file
	 * @param $localFile  string The absolute path to the local file we're writing to
	 * @param $fromOffset int|null The offset (in bytes) to start downloading from
	 * @param $toOffset   int|null The amount of data (in bytes) to download
	 *
	 * @return bool|int True on success, false on failure, -1 if ranges are not supported
	 */
	public function downloadToFile($remotePath, $localFile, $fromOffset = null, $length = null)
	{
		return false;
	}

	/**
	 * Returns a public download URL or starts a browser-side download of a remote file.
	 * In the case of a public download URL, a string is returned. If a browser-side
	 * download is initiated, it returns true. In any other case (e.g. unsupported, not
	 * found, etc) it returns false.
	 *
	 * @param $remotePath string The file to download
	 *
	 * @return string|bool
	 */
	public function downloadToBrowser($remotePath)
	{
		return false;
	}

	/**
	 * Used to call arbitrary methods in this engine through an AJAX call
	 *
	 * @param string $method The method to call.
	 * @param array  $params Any parameters to send to the method, in array format
	 *
	 * @return mixed Whatever the method has to return. It will be JSON encoded by the AJAX handler.
	 */
	public function customAPICall($method, $params = array())
	{
		if (!method_exists($this, $method))
		{
			header('HTTP/1.0 501 Not Implemented');
			exit();
		}
		else
		{
			return call_user_func_array(array($this, $method), $params);
		}
	}

	/**
	 * Opens an OAuth window (perform redirection), or return false if this is not supported
	 *
	 * @param array $params Any parameters required to launch OAuth
	 *
	 * @return boolean|void False if not supported, or nothing (redirection performed) otherwise.
	 */
	public function oauthOpen($params = array())
	{
		return false;
	}
}