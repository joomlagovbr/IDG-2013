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
 * Class K2Category
 * @package RegularLabs\Library\Condition
 */
class K2Category
	extends K2
{
	public function pass()
	{
		if ($this->request->option != 'com_k2')
		{
			return $this->_(false);
		}

		$pass = (
			($this->params->inc_categories
				&& (($this->request->view == 'itemlist' && $this->request->task == 'category')
					|| $this->request->view == 'latest'
				)
			)
			|| ($this->params->inc_items && $this->request->view == 'item')
		);

		if ( ! $pass)
		{
			return $this->_(false);
		}

		$cats = $this->makeArray($this->getCategories());
		$pass = $this->passSimple($cats, 'include');

		if ($pass && $this->params->inc_children == 2)
		{
			return $this->_(false);
		}
		else if ( ! $pass && $this->params->inc_children)
		{
			foreach ($cats as $cat)
			{
				$cats = array_merge($cats, $this->getCatParentIds($cat));
			}
		}

		return $this->passSimple($cats);
	}

	private function getCategories()
	{
		switch ($this->request->view)
		{
			case 'item' :
				return $this->getCategoryIDFromItem();
				break;

			case 'itemlist' :
				return $this->getCategoryID();
				break;

			default:
				return '';
		}
	}

	private function getCategoryID()
	{
		return $this->request->id ?: JFactory::getApplication()->getUserStateFromRequest('com_k2itemsfilter_category', 'catid', 0, 'int');
	}

	private function getCategoryIDFromItem()
	{
		if ($this->article && isset($this->article->catid))
		{
			return $this->article->catid;
		}

		$query = $this->db->getQuery(true)
			->select('i.catid')
			->from('#__k2_items AS i')
			->where('i.id = ' . (int) $this->request->id);
		$this->db->setQuery($query);

		return $this->db->loadResult();
	}

	private function getCatParentIds($id = 0)
	{
		$parent_field = RL_K2_VERSION == 3 ? 'parent_id' : 'parent';

		return $this->getParentIds($id, 'k2_categories', $parent_field);
	}
}
