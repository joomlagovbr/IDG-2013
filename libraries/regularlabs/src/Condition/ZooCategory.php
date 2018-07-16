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
 * Class ZooCategory
 * @package RegularLabs\Library\Condition
 */
class ZooCategory
	extends Zoo
{
	public function pass()
	{
		if ($this->request->option != 'com_zoo')
		{
			return $this->_(false);
		}

		$pass = (
			($this->params->inc_apps && $this->request->view == 'frontpage')
			|| ($this->params->inc_categories && $this->request->view == 'category')
			|| ($this->params->inc_items && $this->request->view == 'item')
		);

		if ( ! $pass)
		{
			return $this->_(false);
		}

		$cats = $this->getCategories();

		if ($cats === false)
		{
			return $this->_(false);
		}

		$cats = $this->makeArray($cats);

		$pass = $this->passSimple($cats, 'include');

		if ($pass && $this->params->inc_children == 2)
		{
			return $this->_(false);
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

	private function getCategories()
	{
		if ($this->article && isset($this->article->catid))
		{
			return [$this->article->catid];
		}

		$menuparams = $this->getMenuItemParams($this->request->Itemid);

		switch ($this->request->view)
		{
			case 'frontpage':
				if ($this->request->id)
				{
					return [$this->request->id];
				}

				if ( ! isset($menuparams->application))
				{
					return [];
				}

				return ['app' . $menuparams->application];

			case 'category':
				$cats = [];

				if ($this->request->id)
				{
					$cats[] = $this->request->id;
				}
				else if (isset($menuparams->category))
				{
					$cats[] = $menuparams->category;
				}

				if (empty($cats[0]))
				{
					return [];
				}

				$query = $this->db->getQuery(true)
					->select('c.application_id')
					->from('#__zoo_category AS c')
					->where('c.id = ' . (int) $cats[0]);
				$this->db->setQuery($query);
				$cats[] = 'app' . $this->db->loadResult();

				return $cats;

			case 'item':
				$id = $this->request->id;

				if ( ! $id && isset($menuparams->item_id))
				{
					$id = $menuparams->item_id;
				}

				if ( ! $id)
				{
					return [];
				}

				$query = $this->db->getQuery(true)
					->select('c.category_id')
					->from('#__zoo_category_item AS c')
					->where('c.item_id = ' . (int) $id)
					->where('c.category_id != 0');
				$this->db->setQuery($query);
				$cats = $this->db->loadColumn();

				$query = $this->db->getQuery(true)
					->select('i.application_id')
					->from('#__zoo_item AS i')
					->where('i.id = ' . (int) $id);
				$this->db->setQuery($query);
				$cats[] = 'app' . $this->db->loadResult();

				return $cats;

			default:
				return false;
		}
	}

	private function getCatParentIds($id = 0)
	{
		$parent_ids = [];

		if ( ! $id)
		{
			return $parent_ids;
		}

		while ($id)
		{
			if (substr($id, 0, 3) == 'app')
			{
				$parent_ids[] = $id;
				break;
			}

			$query = $this->db->getQuery(true)
				->select('c.parent')
				->from('#__zoo_category AS c')
				->where('c.id = ' . (int) $id);
			$this->db->setQuery($query);
			$pid = $this->db->loadResult();

			if ( ! $pid)
			{
				$query = $this->db->getQuery(true)
					->select('c.application_id')
					->from('#__zoo_category AS c')
					->where('c.id = ' . (int) $id);
				$this->db->setQuery($query);
				$app = $this->db->loadResult();

				if ($app)
				{
					$parent_ids[] = 'app' . $app;
				}

				break;
			}

			$parent_ids[] = $pid;

			$id = $pid;
		}

		return $parent_ids;
	}
}
