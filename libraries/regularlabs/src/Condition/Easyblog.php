<?php
/**
 * @package         Regular Labs Library
 * @version         18.7.10792
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Library\Condition;

defined('_JEXEC') or die;

/**
 * Class Easyblog
 * @package RegularLabs\Library\Condition
 */
abstract class Easyblog
	extends \RegularLabs\Library\Condition
{
	use \RegularLabs\Library\ConditionContent;

	public function getItem($fields = [])
	{
		$query = $this->db->getQuery(true)
			->select($fields)
			->from('#__easyblog_post')
			->where('id = ' . (int) $this->request->id);
		$this->db->setQuery($query);

		return $this->db->loadObject();
	}
}
