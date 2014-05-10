<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 3.0
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class AkeebaModelSftpbrowsers extends F0FModel
{
	/** @var string The SFTP server hostname */
	public $host = '';
	/** @var int The SFTP server port number (default: 22) */
	public $port = 22;
	/** @var string Username for logging in */
	public $username = '';
	/** @var string Password for logging in or private key file's passphrase */
	public $password = '';
	/** @var string Private key file for logging in */
	public $privkey = '';
	/** @var string Public key file for logging in */
	public $pubkey = '';
	/** @var string The directory to browse */
	public $directory = '';

	/** @var array Breadcrumbs to the current directory */
	public $parts = array();
	/** @var string Path to the parent directory */
	public $parent_directory = null;

	public function getListing()
	{
		$dir = $this->directory;

		// Parse directory to parts
		$parsed_dir = trim($dir,'/');
		$this->parts = empty($parsed_dir) ? array() : explode('/', $parsed_dir);

		// Find the path to the parent directory
		if(!empty($parts)) {
			$copy_of_parts = $parts;
			array_pop($copy_of_parts);
			if(!empty($copy_of_parts)) {
				$this->parent_directory = '/' . implode('/', $copy_of_parts);
			} else {
				$this->parent_directory = '/';
			}
		} else {
			$this->parent_directory = '';
		}

		// Initialise
		$connection = null;
		$sftphandle = null;

		// Open a connection
		if(!function_exists('ssh2_connect'))
		{
			$this->setError("Your web server does not have the SSH2 PHP module, therefore can not connect and upload archives to SFTP servers.");

			return false;
		}

		$connection = ssh2_connect($this->host, $this->port);

		if ($connection === false)
		{
			$this->setError("Invalid SFTP hostname or port ({$this->host}:{$this->port}) or the connection is blocked by your web server's firewall.");

			return false;
		}

		// Connect to the server

		if(!empty($this->pubkey) && !empty($this->privkey))
		{
			if(!ssh2_auth_pubkey_file($connection, $this->username, $this->pubkey, $this->privkey, $this->password))
			{
				$this->setError('Certificate error');

				return false;
			}
		}
		else
		{
			if(!ssh2_auth_password($connection, $this->username, $this->password))
			{
				$this->setError('Could not authenticate access to SFTP server; check your username and password.');

				return false;
			}
		}

		$sftphandle = ssh2_sftp($connection);

		if($sftphandle === false)
		{
			$this->setWarning("Your SSH server does not allow SFTP connections");

			return false;
		}

		// Get a raw directory listing (hoping it's a UNIX server!)
		$list = array();
		$dir = ltrim($dir, '/');

		$handle = opendir("ssh2.sftp://$sftphandle/$dir");

		if (!is_resource($handle))
		{
			$this->setError(JText::_('SFTPBROWSER_ERROR_NOACCESS'));

			return false;
		}

		while (($entry = readdir($handle)) !== false)
		{
			if (substr($entry, 0, 1) == '.')
			{
				continue;
			}

			if (!is_dir("ssh2.sftp://$sftphandle/$dir/$entry"))
			{
				continue;
			}

			$list[] = $entry;
		}

		closedir($handle);

		if (!empty($list))
		{
			asort($list);
		}

		return $list;
	}

	public function doBrowse()
	{
		$list = $this->getListing();

		$response_array = array(
			'error'			=> $this->getError(),
			'list'			=> $list,
			'breadcrumbs'	=> $this->parts,
			'directory'		=> $this->directory,
			'parent'		=> $this->parent_directory
		);

		return $response_array;
	}
}