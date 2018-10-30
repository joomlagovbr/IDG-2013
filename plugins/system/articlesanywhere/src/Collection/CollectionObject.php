<?php
/**
 * @package         Articles Anywhere
 * @version         8.0.3
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Collection;

use JDatabaseDriver;
use JDatabaseQuery;
use JFactory;
use RegularLabs\Library\ArrayHelper as RL_Array;
use RegularLabs\Library\DB as RL_DB;
use RegularLabs\Plugin\System\ArticlesAnywhere\Config;
use RegularLabs\Plugin\System\ArticlesAnywhere\Factory;

defined('_JEXEC') or die;

class CollectionObject
{
	/* @var Config */
	protected $config;

	/* @var JDatabaseDriver */
	protected $db;

	protected $ignores;

	public function __construct(Config $config)
	{
		$this->config = $config;
		$this->db     = JFactory::getDbo();
	}

	protected function getIdAndNameMatches($ids)
	{
		$numeric      = [];
		$not_nummeric = [];
		$likes        = [];

		$ids = RL_Array::toArray($ids);

		foreach ($ids as $key => $id)
		{
			$check_id = RL_DB::removeOperator($id);

			if (is_numeric($check_id))
			{
				$numeric[] = $id;
				continue;
			}

			if (strpos($id, '*') !== false)
			{
				$likes[] = str_replace('*', '%', $id);
				continue;
			}

			$not_nummeric[] = $id;
		}

		return [$numeric, $not_nummeric, $likes];
	}

	protected function setIgnores(JDatabaseQuery $query, $table = 'items', $group = '')
	{
		if (is_null($this->ignores))
		{
			$this->ignores = Factory::getIgnores($this->config);
		}

		$this->ignores->set($query, $table, $group);
	}
}
