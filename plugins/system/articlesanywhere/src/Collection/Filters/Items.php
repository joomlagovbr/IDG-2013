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

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Filters;

use JDatabaseQuery;
use RegularLabs\Library\RegEx as RL_RegEx;

defined('_JEXEC') or die;

class Items extends Filter
{
	public function setFilter(JDatabaseQuery $query, $names = [], $include_type = 'include')
	{
		$include = $include_type != 'exclude';

		$this->setFiltersFromNames($query, 'items', $names, $include);
	}

	public function getOrdering()
	{
		$filter = $this->config->getFilters('items');

		if (empty($filter['include']))
		{
			return false;
		}

		$names_unquoted = implode(',', $filter['include']);
		$names          = implode(',', $this->db->quote($filter['include']));

		// $names are numeric (so assume ids)
		if (RL_RegEx::match('^[0-9,]+$', $names_unquoted))
		{
			return 'FIELD('
				. $this->config->getId('items', true, 'items') . ','
				. $names
				. ')';
		}

		// $names are lowercase (so assume aliases)
		if ( ! RL_RegEx::match('[A-Z]', $names_unquoted, $matches, 's'))
		{
			return 'FIELD('
				. $this->config->getAlias('items', true, 'items') . ','
				. $names
				. ')';
		}

		// Default to title ordering
		return 'FIELD('
			. $this->config->getTitle('items', true, 'items') . ','
			. $names
			. ')';
	}
}
