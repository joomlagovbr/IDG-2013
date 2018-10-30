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

class RLAssignmentsEasyBlog extends RLAssignment
{
	public function passPageTypes()
	{
		return $this->passByPageTypes('com_easyblog', $this->selection, $this->assignment);
	}

	public function passCategories()
	{
		if ($this->request->option != 'com_easyblog')
		{
			return $this->pass(false);
		}

		$pass = (
			($this->params->inc_categories && $this->request->view == 'categories')
			|| ($this->params->inc_items && $this->request->view == 'entry')
		);

		if ( ! $pass)
		{
			return $this->pass(false);
		}

		$cats = $this->makeArray($this->getCategories());

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

	private function getCategories()
	{
		switch ($this->request->view)
		{
			case 'entry' :
				return $this->getCategoryIDFromItem();
				break;

			case 'categories' :
				return $this->request->id;
				break;

			default:
				return '';
		}
	}

	private function getCategoryIDFromItem()
	{
		$query = $this->db->getQuery(true)
			->select('i.category_id')
			->from('#__easyblog_post AS i')
			->where('i.id = ' . (int) $this->request->id);
		$this->db->setQuery($query);

		return $this->db->loadResult();
	}

	public function passTags()
	{
		if ($this->request->option != 'com_easyblog')
		{
			return $this->pass(false);
		}

		$pass = (
			($this->params->inc_tags && $this->request->layout == 'tag')
			|| ($this->params->inc_items && $this->request->view == 'entry')
		);

		if ( ! $pass)
		{
			return $this->pass(false);
		}

		if ($this->params->inc_tags && $this->request->layout == 'tag')
		{
			$query = $this->db->getQuery(true)
				->select('t.alias')
				->from('#__easyblog_tag AS t')
				->where('t.id = ' . (int) $this->request->id)
				->where('t.published = 1');
			$this->db->setQuery($query);
			$tags = $this->db->loadColumn();

			return $this->passSimple($tags, true);
		}

		$query = $this->db->getQuery(true)
			->select('t.alias')
			->from('#__easyblog_post_tag AS x')
			->join('LEFT', '#__easyblog_tag AS t ON t.id = x.tag_id')
			->where('x.post_id = ' . (int) $this->request->id)
			->where('t.published = 1');
		$this->db->setQuery($query);
		$tags = $this->db->loadColumn();

		return $this->passSimple($tags, true);
	}

	public function passItems()
	{
		if ( ! $this->request->id || $this->request->option != 'com_easyblog' || $this->request->view != 'entry')
		{
			return $this->pass(false);
		}

		$pass = false;

		// Pass Article Id
		if ( ! $this->passItemByType($pass, 'ContentIds'))
		{
			return $this->pass(false);
		}

		// Pass Content Keywords
		if ( ! $this->passItemByType($pass, 'ContentKeywords'))
		{
			return $this->pass(false);
		}

		// Pass Authors
		if ( ! $this->passItemByType($pass, 'Authors'))
		{
			return $this->pass(false);
		}

		return $this->pass($pass);
	}

	public function passContentKeywords($fields = ['title', 'intro', 'content'], $text = '')
	{
		parent::passContentKeywords($fields);
	}

	public function getItem($fields = [])
	{
		$query = $this->db->getQuery(true)
			->select($fields)
			->from('#__easyblog_post')
			->where('id = ' . (int) $this->request->id);
		$this->db->setQuery($query);

		return $this->db->loadObject();
	}

	private function getCatParentIds($id = 0)
	{
		return $this->getParentIds($id, 'easyblog_category', 'parent_id');
	}
}
