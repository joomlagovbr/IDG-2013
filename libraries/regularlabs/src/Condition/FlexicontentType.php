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
 * Class FlexicontentType
 * @package RegularLabs\Library\Condition
 */
class FlexicontentType
	extends Flexicontent
{
	public function pass()
	{
		if ($this->request->option != 'com_flexicontent')
		{
			return $this->_(false);
		}

		$pass = in_array($this->request->view, ['item', 'items']);

		if ( ! $pass)
		{
			return $this->_(false);
		}

		$query = $this->db->getQuery(true)
			->select('x.type_id')
			->from('#__flexicontent_items_ext AS x')
			->where('x.item_id = ' . (int) $this->request->id);
		$this->db->setQuery($query);
		$type = $this->db->loadResult();

		$types = $this->makeArray($type);

		return $this->passSimple($types);
	}
}
