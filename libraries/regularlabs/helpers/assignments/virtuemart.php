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

class RLAssignmentsVirtueMart extends RLAssignment
{
	public function init()
	{
		$virtuemart_product_id  = JFactory::getApplication()->input->get('virtuemart_product_id', [], 'array');
		$virtuemart_category_id = JFactory::getApplication()->input->get('virtuemart_category_id', [], 'array');

		$this->request->item_id     = isset($virtuemart_product_id[0]) ? $virtuemart_product_id[0] : null;
		$this->request->category_id = isset($virtuemart_category_id[0]) ? $virtuemart_category_id[0] : null;
		$this->request->id          = ($this->request->item_id) ? $this->request->item_id : $this->request->category_id;
	}

	public function passPageTypes()
	{
		// Because VM sucks, we have to get the view again
		$this->request->view = JFactory::getApplication()->input->getString('view');

		return $this->passByPageTypes('com_virtuemart', $this->selection, $this->assignment, true);
	}

	public function passCategories()
	{
		if ($this->request->option != 'com_virtuemart')
		{
			return $this->pass(false);
		}

		// Because VM sucks, we have to get the view again
		$this->request->view = JFactory::getApplication()->input->getString('view');

		$pass = (($this->params->inc_categories && in_array($this->request->view, ['categories', 'category']))
			|| ($this->params->inc_items && $this->request->view == 'productdetails')
		);

		if ( ! $pass)
		{
			return $this->pass(false);
		}

		$cats = [];
		if ($this->request->view == 'productdetails' && $this->request->item_id)
		{
			$query = $this->db->getQuery(true)
				->select('x.virtuemart_category_id')
				->from('#__virtuemart_product_categories AS x')
				->where('x.virtuemart_product_id = ' . (int) $this->request->item_id);
			$this->db->setQuery($query);
			$cats = $this->db->loadColumn();
		}
		else if ($this->request->category_id)
		{
			$cats = $this->request->category_id;
			if ( ! is_numeric($cats))
			{
				$query = $this->db->getQuery(true)
					->select('config')
					->from('#__virtuemart_configs')
					->where('virtuemart_config_id = 1');
				$this->db->setQuery($query);
				$config = $this->db->loadResult();
				$lang   = substr($config, strpos($config, 'vmlang='));
				$lang   = substr($lang, 0, strpos($lang, '|'));
				if (preg_match('#"([^"]*_[^"]*)"#', $lang, $lang))
				{
					$lang = $lang[1];
				}
				else
				{
					$lang = 'en_gb';
				}

				$query = $this->db->getQuery(true)
					->select('l.virtuemart_category_id')
					->from('#__virtuemart_categories_' . $lang . ' AS l')
					->where('l.slug = ' . $this->db->quote($cats));
				$this->db->setQuery($query);
				$cats = $this->db->loadResult();
			}
		}

		$cats = $this->makeArray($cats);

		$pass = $this->passSimple($cats, 'include');

		if ($pass && $this->params->inc_children == 2)
		{
			return $this->pass(false);
		}

		if ( ! $pass && $this->params->inc_children)
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
		// Because VM sucks, we have to get the view again
		$this->request->view = JFactory::getApplication()->input->getString('view');

		if ( ! $this->request->id || $this->request->option != 'com_virtuemart' || $this->request->view != 'productdetails')
		{
			return $this->pass(false);
		}

		return $this->passSimple($this->request->id);
	}

	private function getCatParentIds($id = 0)
	{
		return $this->getParentIds($id, 'virtuemart_category_categories', 'category_parent_id', 'category_child_id');
	}
}
