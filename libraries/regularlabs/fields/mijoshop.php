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

defined('_JEXEC') or die;

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

class JFormFieldRL_MijoShop extends \RegularLabs\Library\FieldGroup
{
	public $type        = 'MijoShop';
	public $store_id    = 0;
	public $language_id = 1;

	protected function getInput()
	{
		if ($error = $this->missingFilesOrTables(['categories' => 'category', 'products' => 'product']))
		{
			return $error;
		}

		if ( ! class_exists('MijoShop'))
		{
			require_once(JPATH_ROOT . '/components/com_mijoshop/mijoshop/mijoshop.php');
		}

		$this->store_id    = (int) MijoShop::get('opencart')->get('config')->get('config_store_id');
		$this->language_id = (int) MijoShop::get('opencart')->get('config')->get('config_language_id');

		return $this->getSelectList();
	}

	function getCategories()
	{
		$query = $this->db->getQuery(true)
			->select('COUNT(*)')
			->from('#__mijoshop_category AS c')
			->join('INNER', '#__mijoshop_category_description AS cd ON c.category_id = cd.category_id')
			->join('INNER', '#__mijoshop_category_to_store AS cts ON c.category_id = cts.category_id')
			->where('c.status = 1')
			->where('cd.language_id = ' . $this->language_id)
			->where('cts.store_id = ' . $this->store_id)
			->group('c.category_id');
		$this->db->setQuery($query);
		$total = $this->db->loadResult();

		if ($total > $this->max_list_count)
		{
			return -1;
		}

		$query->clear('select')
			->select('c.category_id AS id, c.parent_id, cd.name AS title, c.status AS published')
			->order('c.sort_order, cd.name');
		$this->db->setQuery($query);
		$items = $this->db->loadObjectList();

		return $this->getOptionsTreeByList($items);
	}

	function getProducts()
	{
		$query = $this->db->getQuery(true)
			->select('COUNT(*)')
			->from('#__mijoshop_product AS p')
			->join('INNER', '#__mijoshop_product_description AS pd ON p.product_id = pd.product_id')
			->join('INNER', '#__mijoshop_product_to_store AS pts ON p.product_id = pts.product_id')->where('p.status = 1')
			->where('p.date_available <= NOW()')
			->where('pd.language_id = ' . $this->language_id)
			->group('p.product_id');
		$this->db->setQuery($query);
		$total = $this->db->loadResult();

		if ($total > $this->max_list_count)
		{
			return -1;
		}

		$query->clear('select')
			->select('p.product_id as id, pd.name, p.model as model, cd.name AS cat, p.status AS published')
			->join('LEFT', '#__mijoshop_product_to_category AS ptc ON p.product_id = ptc.product_id')
			->join('LEFT', '#__mijoshop_category_description AS cd ON ptc.category_id = cd.category_id')
			->join('LEFT', '#__mijoshop_category_to_store AS cts ON ptc.category_id = cts.category_id')
			->where('cts.store_id = ' . $this->store_id)
			->where('cd.language_id = ' . $this->language_id)
			->where('cts.store_id = ' . $this->store_id)
			->order('pd.name, p.model');
		$this->db->setQuery($query);
		$list = $this->db->loadObjectList();

		return $this->getOptionsByList($list, ['model', 'cat', 'id']);
	}
}
