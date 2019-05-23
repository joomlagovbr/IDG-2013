<?php
/**
 * @package         Articles Anywhere
 * @version         9.3.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere;

defined('_JEXEC') or die;

use JEventDispatcher;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel as JModel;
use Joomla\CMS\Plugin\PluginHelper as JPluginHelper;

class CurrentItem
{
	var    $config;
	static $item;

	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	public static function get($key = null)
	{
		$item = self::getCurrentItem();

		if (is_null($key))
		{
			return $item ?: (object) [];
		}

		return isset($item->{$key}) ? $item->{$key} : null;
	}

	public static function set($item)
	{
		if ( ! isset($item->id))
		{
			return;
		}

		self::$item = $item;
	}

	public static function exists()
	{
		return ! is_null(self::$item);
	}

	private static function getCurrentItem()
	{
		if ( ! is_null(self::$item))
		{
			return self::$item;
		}

		$input = JFactory::getApplication()->input;

		if ($input->get('option') != 'com_content' || $input->get('view') != 'article')
		{
			return null;
		}

		if ( ! $id = $input->get('id'))
		{
			return null;
		}

		if ( ! class_exists('ContentModelArticle'))
		{
			require_once JPATH_SITE . '/components/com_content/models/article.php';
		}

		$model = JModel::getInstance('article', 'contentModel');

		if ( ! method_exists($model, 'getItem'))
		{
			return null;
		}

		$item = $model->getItem($id);

		if (empty($item->id))
		{
			return null;
		}

		if ( ! isset($item->jcfields) && JPluginHelper::importPlugin('system', 'fields'))
		{
			$dispatcher = JEventDispatcher::getInstance();
			$params     = (array) JPluginHelper::getPlugin('system', 'fields');
			$plugin     = new \PlgSystemFields($dispatcher, $params);
			$plugin->onContentPrepare('com_content.article', $item);
		}

		self::$item = $item;

		return self::$item;
	}
}
