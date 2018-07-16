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

/* @DEPRECATED */

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/assignment.php';

class RLAssignmentsMenu extends RLAssignment
{
	public function passMenu()
	{
		// return if no Itemid or selection is set
		if ( ! $this->request->Itemid || empty($this->selection))
		{
			return $this->pass($this->params->inc_noitemid);
		}

		$menutype = 'type.' . self::getMenuType();

		// return true if menu type is in selection
		if (in_array($menutype, $this->selection))
		{
			return $this->pass(true);
		}

		// return true if menu is in selection
		if (in_array($this->request->Itemid, $this->selection))
		{
			return $this->pass(($this->params->inc_children != 2));
		}

		if ( ! $this->params->inc_children)
		{
			return $this->pass(false);
		}

		$parent_ids = $this->getMenuParentIds($this->request->Itemid);
		$parent_ids = array_diff($parent_ids, [1]);
		foreach ($parent_ids as $id)
		{
			if ( ! in_array($id, $this->selection))
			{
				continue;
			}

			return $this->pass(true);
		}

		return $this->pass(false);
	}

	private function getMenuParentIds($id = 0)
	{
		return $this->getParentIds($id, 'menu');
	}

	private function getMenuType()
	{
		if (isset($this->request->menutype))
		{
			return $this->request->menutype;
		}

		if (empty($this->request->Itemid))
		{
			$this->request->menutype = '';

			return $this->request->menutype;
		}

		if (JFactory::getApplication()->isClient('site'))
		{
			$menu = JFactory::getApplication()->getMenu()->getItem((int) $this->request->Itemid);

			$this->request->menutype = isset($menu->menutype) ? $menu->menutype : '';

			return $this->request->menutype;
		}

		$query = $this->db->getQuery(true)
			->select('m.menutype')
			->from('#__menu AS m')
			->where('m.id = ' . (int) $this->request->Itemid);
		$this->db->setQuery($query);
		$this->request->menutype = $this->db->loadResult();

		return $this->request->menutype;
	}
}
