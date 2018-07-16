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

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Filters;

use JDatabaseQuery;
use RegularLabs\Library\DB as RL_DB;

defined('_JEXEC') or die;

class Fields extends Filter
{
	public function setFilter(JDatabaseQuery $query, $filters = [])
	{
		foreach ($filters as $key => $value)
		{
			$query->where($this->db->quoteName('items.' . $key) . RL_DB::in($value));
		}
	}
}
