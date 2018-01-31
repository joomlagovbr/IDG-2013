<?php
/**
 * @package         Articles Anywhere
 * @version         7.5.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Collection;

use ArticlesAnywhereArticleModel;
use JDatabaseDriver;
use JFactory;
use JHelperTags;
use RegularLabs\Library\DB as RL_DB;
use RegularLabs\Plugin\System\ArticlesAnywhere\Config;
use RegularLabs\Plugin\System\ArticlesAnywhere\Params;

defined('_JEXEC') or die;

class Item
{
	/* @var Config */
	protected $config;
	protected $data;

	/* @var JDatabaseDriver */
	protected $db;

	public function __construct(Config $config, $data)
	{
		$this->config = $config;
		$this->data   = $data;
		$this->db     = JFactory::getDbo();
	}

	public function get($key = '', $default = null)
	{
		if (empty($key))
		{
			return $this->data;
		}

		if ($key == 'has_access')
		{
			return $this->hasAccess();
		}

		if ($key == 'text' && ! isset($this->data->text))
		{
			$this->data->text = (isset($this->data->introtext) ? $this->data->introtext : '')
				. (isset($this->data->introtext) ? $this->data->fulltext : '');
		}

		return isset($this->data->{$key}) ? $this->data->{$key} : $default;
	}

	public function getData()
	{
		return $this->data;
	}

	public function getConfig()
	{
		return $this->config;
	}

	public function getConfigData($name = '', $quote = true, $prefix = '')
	{
		return $this->config->getData($name, $quote, $prefix);
	}

	public function getFromGroup($group = '', $key = '', $default = null)
	{
		$values = $this->getGroupValues($group);

		if (empty($values))
		{
			return $default;
		}

		// See if the key is found in the group
		if (isset($values->{$key}))
		{
			return $values->{$key};
		}

		// See if the key (prepended with the group name) is found in the group
		// Like: metadata_author
		if (isset($values->{$group . '_' . $key}))
		{
			return $values->{$group . '_' . $key};
		}

		return $default;
	}

	public function getGroupValues($group = '')
	{
		if (is_null($this->get($group)))
		{
			return null;
		}

		return json_decode($this->get($group));
	}

	public function set($key, $value)
	{
		return $this->data->{$key} = $value;
	}

	public function getId()
	{
		return $this->get('id', 0);
	}

	public function getTags()
	{
		$tags = new JHelperTags;
		$tags->getItemTags('com_content.article', $this->getId());

		return isset($tags->itemTags) ? $tags->itemTags : [];
	}

	public function hasAccess()
	{
		if ( ! $this->getId())
		{
			return true;
		}
		$query = $this->db->getQuery(true)
			->select($this->db->quoteName('access') . ' ' . RL_DB::in(Params::getAuthorisedViewLevels()))
			->from($this->config->getTableItems())
			->where($this->db->quoteName('id') . ' = ' . (int) $this->getId());

		return (bool) DB::getResults($query, 'loadResult');
	}

	public function hit()
	{
		if ( ! Params::get()->increase_hits_on_text)
		{
			return;
		}

		require_once dirname(__DIR__) . '/Helpers/article_model.php';

		$model = new ArticlesAnywhereArticleModel;

		$model->hit($this->getId());
	}
}
