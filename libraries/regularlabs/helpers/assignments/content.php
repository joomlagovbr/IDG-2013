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

class RLAssignmentsContent extends RLAssignment
{
	public function passPageTypes()
	{
		$components = ['com_content', 'com_contentsubmit'];
		if ( ! in_array($this->request->option, $components))
		{
			return $this->pass(false);
		}
		if ($this->request->view == 'category' && $this->request->layout == 'blog')
		{
			$view = 'categoryblog';
		}
		else
		{
			$view = $this->request->view;
		}

		return $this->passSimple($view);
	}

	public function passCategories()
	{
		// components that use the com_content secs/cats
		$components = ['com_content', 'com_flexicontent', 'com_contentsubmit'];
		if ( ! in_array($this->request->option, $components))
		{
			return $this->pass(false);
		}

		if (empty($this->selection))
		{
			return $this->pass(false);
		}

		$is_content  = in_array($this->request->option, ['com_content', 'com_flexicontent']);
		$is_category = in_array($this->request->view, ['category']);
		$is_item     = in_array($this->request->view, ['', 'article', 'item', 'form']);

		if (
			$this->request->option != 'com_contentsubmit'
			&& ! ($this->params->inc_categories && $is_content && $is_category)
			&& ! ($this->params->inc_articles && $is_content && $is_item)
			&& ! ($this->params->inc_others && ! ($is_content && ($is_category || $is_item)))
		)
		{
			return $this->pass(false);
		}

		if ($this->request->option == 'com_contentsubmit')
		{
			// Content Submit
			$contentsubmit_params = new ContentsubmitModelArticle;
			if (in_array($contentsubmit_params->_id, $this->selection))
			{
				return $this->pass(true);
			}

			return $this->pass(false);
		}

		$pass = false;
		if (
			$this->params->inc_others
			&& ! ($is_content && ($is_category || $is_item))
			&& $this->article
		)
		{
			if ( ! isset($this->article->id) && isset($this->article->slug))
			{
				$this->article->id = (int) $this->article->slug;
			}

			if ( ! isset($this->article->catid) && isset($this->article->catslug))
			{
				$this->article->catid = (int) $this->article->catslug;
			}

			$this->request->id   = $this->article->id;
			$this->request->view = 'article';
		}

		$catids = $this->getCategoryIds($is_category);

		foreach ($catids as $catid)
		{
			if ( ! $catid)
			{
				continue;
			}

			$pass = in_array($catid, $this->selection);

			if ($pass && $this->params->inc_children == 2)
			{
				$pass = false;
				continue;
			}

			if ( ! $pass && $this->params->inc_children)
			{
				$parent_ids = $this->getCatParentIds($catid);
				$parent_ids = array_diff($parent_ids, [1]);
				foreach ($parent_ids as $id)
				{
					if (in_array($id, $this->selection))
					{
						$pass = true;
						break;
					}
				}

				unset($parent_ids);
			}
		}

		return $this->pass($pass);
	}

	private function getCategoryIds($is_category = false)
	{
		if ($is_category)
		{
			return (array) $this->request->id;
		}

		if ( ! $this->article && $this->request->id)
		{
			$this->article = JTable::getInstance('content');
			$this->article->load($this->request->id);
		}

		if ($this->article && $this->article->catid)
		{
			return (array) $this->article->catid;
		}

		$catid      = JFactory::getApplication()->input->getInt('catid', JFactory::getApplication()->getUserState('com_content.articles.filter.category_id'));
		$menuparams = $this->getMenuItemParams($this->request->Itemid);

		if ($this->request->view == 'featured')
		{
			$menuparams = $this->getMenuItemParams($this->request->Itemid);

			return isset($menuparams->featured_categories) ? (array) $menuparams->featured_categories : (array) $catid;
		}

		return isset($menuparams->catid) ? (array) $menuparams->catid : (array) $catid;
	}

	public function passArticles()
	{
		if ( ! $this->request->id
			|| ! (($this->request->option == 'com_content' && $this->request->view == 'article')
				|| ($this->request->option == 'com_flexicontent' && $this->request->view == 'item')
			)
		)
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

		// Pass Meta Keywords
		if ( ! $this->passItemByType($pass, 'MetaKeywords'))
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

	public function getItem($fields = [])
	{
		if ($this->article)
		{
			return $this->article;
		}

		if ( ! class_exists('ContentModelArticle'))
		{
			require_once JPATH_SITE . '/components/com_content/models/article.php';
		}

		$model = JModelLegacy::getInstance('article', 'contentModel');

		if ( ! method_exists($model, 'getItem'))
		{
			return null;
		}

		$this->article = $model->getItem($this->request->id);

		return $this->article;
	}

	private function getCatParentIds($id = 0)
	{
		return $this->getParentIds($id, 'categories');
	}
}
