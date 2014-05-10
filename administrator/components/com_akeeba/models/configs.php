<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 3.2.5
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class AkeebaModelConfigs extends F0FModel
{
	public function saveEngineConfig()
	{
		$data = $this->getState('engineconfig', array());

		// Forbid stupidly selecting the site's root as the output or temporary directory
		if( array_key_exists('akeeba.basic.output_directory', $data) )
		{
			$folder = $data['akeeba.basic.output_directory'];
			$folder = AEUtilFilesystem::translateStockDirs( $folder, true, true );

			$check = AEUtilFilesystem::translateStockDirs( '[SITEROOT]', true, true );

			if($check == $folder)
			{
				JError::raiseWarning(503, JText::_('CONFIG_OUTDIR_ROOT'));
				$data['akeeba.basic.output_directory'] = '[DEFAULT_OUTPUT]';
			}
		}

		// Merge it
		$config = AEFactory::getConfiguration();
		$config->mergeArray($data, false, false);
		// Save configuration
		AEPlatform::getInstance()->save_configuration();
	}

	public function testFTP()
	{
		$config = array(
			'host' => $this->getState('host'),
			'port' => $this->getState('port'),
			'user' => $this->getState('user'),
			'pass' => $this->getState('pass'),
			'initdir' => $this->getState('initdir'),
			'usessl' => $this->getState('usessl'),
			'passive' => $this->getState('passive'),
		);

		// Check for bad settings
		if (substr($config['host'], 0, 6) == 'ftp://')
		{
			return JText::_('CONFIG_FTPTEST_BADPREFIX');
		}

		// Perform the FTP connection test
		$test = new AEArchiverDirectftp();
		$test->initialize('', $config);
		$errors = $test->getError();
		if(empty($errors) || $test->connect_ok)
		{
			$result = true;
		}
		else
		{
			$result = $errors;
		}
		return $result;
	}

	public function testSFTP()
	{
		$config = array(
			'host' => $this->getState('host'),
			'port' => $this->getState('port'),
			'user' => $this->getState('user'),
			'pass' => $this->getState('pass'),
			'privkey' => $this->getState('privkey'),
			'pubkey' => $this->getState('pubkey'),
			'initdir' => $this->getState('initdir'),
		);

		// Check for bad settings
		if (substr($config['host'], 0, 7) == 'sftp://')
		{
			return JText::_('CONFIG_SFTPTEST_BADPREFIX');
		}

		// Perform the FTP connection test
		$test = new AEArchiverDirectsftp();
		$test->initialize('', $config);
		$errors = $test->getWarnings();
		if(empty($errors) || $test->connect_ok)
		{
			$result = true;
		}
		else
		{
			$result = $errors;
		}
		return $result;
	}

	/**
	 * Opens an OAuth window for the selected post-processing engine
	 *
	 * @return boolean
	 */
	public function dpeOuthOpen()
	{
		$engine = $this->getState('engine');
		$params = $this->getState('params', array());

		$engine = AEFactory::getPostprocEngine($engine);
		if($engine === false) return false;
		$engine->oauthOpen($params);
	}

	/**
	 * Runs a custom API call for the selected post-processing engine
	 *
	 * @return boolean
	 */
	public function dpeCustomAPICall()
	{
		$engine = $this->getState('engine');
		$method = $this->getState('method');
		$params = $this->getState('params', array());

		$engine = AEFactory::getPostprocEngine($engine);
		if($engine === false) return false;
		return $engine->customApiCall($method, $params);
	}
}