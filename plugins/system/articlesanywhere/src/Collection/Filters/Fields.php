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
use RegularLabs\Library\DB as RL_DB;

defined('_JEXEC') or die;

class Fields extends Filter
{
	public function setFilter(JDatabaseQuery $query, $names = [], $include_type = 'include')
	{
		$include = $include_type != 'exclude';

		foreach ($names as $field => $values)
		{
			$query->where($this->db->quoteName('items.' . $field) . RL_DB::in($values, $include));
		}
	}
}
