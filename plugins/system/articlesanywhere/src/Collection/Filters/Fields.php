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

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Filters;

defined('_JEXEC') or die;

use JDatabaseQuery;

class Fields extends Filter
{
	public function setFilter(JDatabaseQuery $query, $filters = [])
	{
		foreach ($filters as $key => $value)
		{
			$conditions = $this->getConditionsFromValues('items.' . $key, $value);

			$query->where($conditions);
		}
	}
}
