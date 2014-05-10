<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 2, or later
 *
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

defined('AKEEBA_BACKUP_ORIGIN') or define('AKEEBA_BACKUP_ORIGIN','json');

class AkeebaControllerJson extends F0FController
{
	public function __construct($config = array()) {
		$config['csrf_protection'] = false;
		parent::__construct($config);
	}
	public function execute($task)
	{
		$task = 'json';

		parent::execute($task);
	}

	/**
	 * Handles API calls
	 */
	public function json()
	{
		// Many versions of PHP suffer from a brain-dead buggy JSON library. Let's
		// load our own (actually it's PEAR's Services_JSON).
		require_once JPATH_SITE.'/administrator/components/com_akeeba/helpers/jsonlib.php';

		// Use the model to parse the JSON message
		if(function_exists('ob_start')) @ob_start();
		$sourceJSON = $this->input->get('json', null, 'raw', 2);

		// On some !@#$%^& servers where magic_quotes_gpc is On we might get extra slashes added
		if(function_exists('get_magic_quotes_gpc')) {
			if(get_magic_quotes_gpc()) {
				$sourceJSON = stripslashes($sourceJSON);
			}
		}

		$model = $this->getThisModel();
		$json = $model->execute($sourceJSON);
		if(function_exists('ob_clean')) @ob_clean();

		// Just dump the JSON and tear down the application, without plugins executing
		echo $json;
		$app = JFactory::getApplication();
		$app->close();
	}

}

