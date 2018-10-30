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

class RLAssignmentsHikaShop extends RLAssignment
{
	public function passPageTypes()
	{
		if ($this->request->option != 'com_hikashop')
		{
			return $this->pass(false);
		}

		$type = $this->request->view;
		if (
			($type == 'product' && in_array($this->request->layout, ['contact', 'show']))
			|| ($type == 'user' && in_array($this->request->layout, ['cpanel']))
		)
		{
			$type .= '_' . $this->request->layout;
		}

		return $this->passSimple($type);
	}

	public function passCategories()
	{
		if ($this->request->option != 'com_hikashop')
		{
			return $this->pass(false);
		}

		$pass = (
			($this->params->inc_categories
				&& ($this->request->view == 'category' || $this->request->layout == 'listing')
			)
			|| ($this->params->inc_items && $this->request->view == 'product')
		);

		if ( ! $pass)
		{
			return $this->pass(false);
		}

		$cats = $this->getCategories();

		$pass = $this->passSimple($cats, 'include');

		if ($pass && $this->params->inc_children == 2)
		{
			return $this->pass(false);
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

	public function passProducts()
	{
		if ( ! $this->request->id || $this->request->option != 'com_hikashop' || $this->request->view != 'product')
		{
			return $this->pass(false);
		}

		return $this->passSimple($this->request->id);
	}

	private function getCategories()
	{
		switch (true)
		{
			case (($this->request->view == 'category' || $this->request->layout == 'listing') && $this->request->id):
				return [$this->request->id];

			case ($this->request->view == 'category' || $this->request->layout == 'listing'):
				include_once JPATH_ADMINISTRATOR . '/components/com_hikashop/helpers/helper.php';
				$menuClass = hikashop_get('class.menus');
				$menuData  = $menuClass->get($this->request->Itemid);

				return $this->makeArray($menuData->hikashop_params['selectparentlisting']);

			case ($this->request->id):
				$query = $this->db->getQuery(true)
					->select('c.category_id')
					->from('#__hikashop_product_category AS c')
					->where('c.product_id = ' . (int) $this->request->id);
				$this->db->setQuery($query);
				$cats = $this->db->loadColumn();

				return $this->makeArray($cats);

			default:
				return [];
		}
	}

	private function getCatParentIds($id = 0)
	{
		return $this->getParentIds($id, 'hikashop_category', 'category_parent_id', 'category_id');
	}
}
