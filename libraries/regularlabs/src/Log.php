<?php
/**
 * @package         Regular Labs Library
 * @version         19.5.762
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use JLoader;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel as JModel;

/**
 * Class Log
 * @package RegularLabs\Library
 */
class Log
{
	public static function add($message, $languageKey, $context)
	{
		$user = JFactory::getUser();

		$message['userid']      = $user->id;
		$message['username']    = $user->username;
		$message['accountlink'] = 'index.php?option=com_users&task=user.edit&id=' . $user->id;

		JLoader::register('ActionlogsHelper', JPATH_ADMINISTRATOR . '/components/com_actionlogs/helpers/actionlogs.php');
		JLoader::register('ActionlogsModelActionlog', JPATH_ADMINISTRATOR . '/components/com_actionlogs/models/actionlog.php');

		$model = JModel::getInstance('Actionlog', 'ActionlogsModel');
		$model->addLog([$message], $languageKey, $context, $user->id);
	}

	public static function save($message, $context, $isNew)
	{
		$languageKey       = $isNew ? 'PLG_SYSTEM_ACTIONLOGS_CONTENT_ADDED' : 'PLG_SYSTEM_ACTIONLOGS_CONTENT_UPDATED';
		$message['action'] = $isNew ? 'add' : 'update';

		self::add($message, $languageKey, $context);
	}

	public static function delete($message, $context)
	{
		$languageKey       = 'PLG_SYSTEM_ACTIONLOGS_CONTENT_DELETED';
		$message['action'] = 'deleted';

		self::add($message, $languageKey, $context);
	}

	public static function changeState($message, $context, $value)
	{
		switch ($value)
		{
			case 0:
				$languageKey       = 'PLG_SYSTEM_ACTIONLOGS_CONTENT_UNPUBLISHED';
				$message['action'] = 'unpublish';
				break;
			case 1:
				$languageKey       = 'PLG_SYSTEM_ACTIONLOGS_CONTENT_PUBLISHED';
				$message['action'] = 'publish';
				break;
			case 2:
				$languageKey       = 'PLG_SYSTEM_ACTIONLOGS_CONTENT_ARCHIVED';
				$message['action'] = 'archive';
				break;
			case -2:
				$languageKey       = 'PLG_SYSTEM_ACTIONLOGS_CONTENT_TRASHED';
				$message['action'] = 'trash';
				break;
			default:
				return;
		}

		self::add($message, $languageKey, $context);
	}

	public static function install($message, $context, $type = 'component')
	{
		$languageKey = 'PLG_ACTIONLOG_JOOMLA_' . strtoupper($type) . '_INSTALLED';
		if ( ! JFactory::getApplication()->getLanguage()->hasKey($languageKey))
		{
			$languageKey = 'PLG_ACTIONLOG_JOOMLA_EXTENSION_INSTALLED';
		}

		$message['action'] = 'install';
		$message['type']   = 'PLG_ACTIONLOG_JOOMLA_TYPE_' . strtoupper($type);

		self::add($message, $languageKey, $context);
	}

	public static function uninstall($message, $context, $type = 'component')
	{
		$languageKey = 'PLG_ACTIONLOG_JOOMLA_EXTENSION_UNINSTALLED';

		$message['action'] = 'uninstall';
		$message['type']   = 'PLG_ACTIONLOG_JOOMLA_TYPE_' . strtoupper($type);

		self::add($message, $languageKey, $context);
	}
}
