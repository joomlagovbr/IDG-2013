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

class JFormFieldRL_VirtueMart extends \RegularLabs\Library\FieldGroup
{
	public $type     = 'VirtueMart';
	public $language = null;

	protected function getInput()
	{
		if ($error = $this->missingFilesOrTables(['categories', 'products']))
		{
			return $error;
		}

		return $this->getSelectList();
	}

	function getCategories()
	{
		$query = $this->db->getQuery(true)
			->select('COUNT(*)')
			->from('#__virtuemart_categories AS c')
			->where('c.published > -1');
		$this->db->setQuery($query);
		$total = $this->db->loadResult();

		if ($total > $this->max_list_count)
		{
			return -1;
		}

		$query->clear()
			->select('c.virtuemart_category_id as id, cc.category_parent_id AS parent_id, l.category_name AS title, c.published')
			->from('#__virtuemart_categories_' . $this->getActiveLanguage() . ' AS l')
			->join('', '#__virtuemart_categories AS c using (virtuemart_category_id)')
			->join('LEFT', '#__virtuemart_category_categories AS cc ON l.virtuemart_category_id = cc.category_child_id')
			->where('c.published > -1')
			->group('c.virtuemart_category_id')
			->order('c.ordering, l.category_name');
		$this->db->setQuery($query);
		$items = $this->db->loadObjectList();

		return $this->getOptionsTreeByList($items);
	}

	function getProducts()
	{
		$query = $this->db->getQuery(true)
			->select('COUNT(*)')
			->from('#__virtuemart_products AS p')
			->where('p.published > -1');
		$this->db->setQuery($query);
		$total = $this->db->loadResult();

		if ($total > $this->max_list_count)
		{
			return -1;
		}

		$query->clear('select')
			->select('p.virtuemart_product_id as id, l.product_name AS name, p.product_sku as sku, cl.category_name AS cat, p.published')
			->join('LEFT', '#__virtuemart_products_' . $this->getActiveLanguage() . ' AS l ON l.virtuemart_product_id = p.virtuemart_product_id')
			->join('LEFT', '#__virtuemart_product_categories AS x ON x.virtuemart_product_id = p.virtuemart_product_id')
			->join('LEFT', '#__virtuemart_categories AS c ON c.virtuemart_category_id = x.virtuemart_category_id')
			->join('LEFT', '#__virtuemart_categories_' . $this->getActiveLanguage() . ' AS cl ON cl.virtuemart_category_id = c.virtuemart_category_id')
			->group('p.virtuemart_product_id')
			->order('l.product_name, p.product_sku');
		$this->db->setQuery($query);
		$list = $this->db->loadObjectList();

		return $this->getOptionsByList($list, ['sku', 'cat', 'id']);
	}

	private function getActiveLanguage()
	{
		if (isset($this->language))
		{
			return $this->language;
		}

		$this->language = 'en_gb';

		if ( ! class_exists('VmConfig'))
		{
			require_once JPATH_ROOT . '/administrator/components/com_virtuemart/helpers/config.php';
		}

		if ( ! class_exists('VmConfig'))
		{
			return $this->language;
		}

		VmConfig::loadConfig();

		if ( ! empty(VmConfig::$vmlang))
		{
			$this->language = str_replace('-', '_', strtolower(VmConfig::$vmlang));

			return $this->language;
		}

		$active_languages = VmConfig::get('active_languages', []);

		if ( ! isset($active_languages[0]))
		{
			return $this->language;
		}

		$this->language = str_replace('-', '_', strtolower($active_languages[0]));

		return $this->language;
	}
}
