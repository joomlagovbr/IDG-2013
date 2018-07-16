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

use JFactory;

/**
 * Class FlexicontentTag
 * @package RegularLabs\Library\Condition
 */
class FlexicontentTag
	extends Flexicontent
{
	public function pass()
	{
		if ($this->request->option != 'com_flexicontent')
		{
			return $this->_(false);
		}

		$pass = (
			($this->params->inc_tags && $this->request->view == 'tags')
			|| ($this->params->inc_items && in_array($this->request->view, ['item', 'items']))
		);

		if ( ! $pass)
		{
			return $this->_(false);
		}

		if ($this->params->inc_tags && $this->request->view == 'tags')
		{
			$query = $this->db->getQuery(true)
				->select('t.name')
				->from('#__flexicontent_tags AS t')
				->where('t.id = ' . (int) trim(JFactory::getApplication()->input->getInt('id', 0)))
				->where('t.published = 1');
			$this->db->setQuery($query);
			$tag  = $this->db->loadResult();
			$tags = [$tag];
		}
		else
		{
			$query = $this->db->getQuery(true)
				->select('t.name')
				->from('#__flexicontent_tags_item_relations AS x')
				->join('LEFT', '#__flexicontent_tags AS t ON t.id = x.tid')
				->where('x.itemid = ' . (int) $this->request->id)
				->where('t.published = 1');
			$this->db->setQuery($query);
			$tags = $this->db->loadColumn();
		}

		return $this->passSimple($tags, true);
	}
}
