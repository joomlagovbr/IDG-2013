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
 * Class Tag
 * @package RegularLabs\Library\Condition
 */
class Tag
	extends \RegularLabs\Library\Condition
{
	public function pass()
	{
		if (in_array($this->request->option, ['com_content', 'com_flexicontent']))
		{
			return $this->passTagsContent();
		}

		if ($this->request->option != 'com_tags'
			|| $this->request->view != 'tag'
			|| ! $this->request->id
		)
		{
			return $this->_(false);
		}

		return $this->passTag($this->request->id);
	}

	private function passTagsContent()
	{
		$is_item     = in_array($this->request->view, ['', 'article', 'item']);
		$is_category = in_array($this->request->view, ['category']);

		switch (true)
		{
			case ($is_item):
				$prefix = 'com_content.article';
				break;

			case ($is_category):
				$prefix = 'com_content.category';
				break;

			default:
				return $this->_(false);
		}

		// Load the tags.
		$query = $this->db->getQuery(true)
			->select($this->db->quoteName('t.id'))
			->select($this->db->quoteName('t.title'))
			->from('#__tags AS t')
			->join(
				'INNER', '#__contentitem_tag_map AS m'
				. ' ON m.tag_id = t.id'
				. ' AND m.type_alias = ' . $this->db->quote($prefix)
				. ' AND m.content_item_id = ' . $this->request->id
			);
		$this->db->setQuery($query);
		$tags = $this->db->loadObjectList();

		if (empty($tags))
		{
			return $this->_(false);
		}

		return $this->_($this->passTagList($tags));
	}

	private function passTagList($tags)
	{
		if ($this->params->match_all)
		{
			return $this->passTagListMatchAll($tags);
		}

		foreach ($tags as $tag)
		{
			if ( ! $this->passTag($tag->id) && ! $this->passTag($tag->title))
			{
				continue;
			}

			return true;
		}

		return false;
	}

	private function passTag($tag)
	{
		$pass = in_array($tag, $this->selection);

		if ($pass)
		{
			// If passed, return false if assigned to only children
			// Else return true
			return ($this->params->inc_children != 2);
		}

		if ( ! $this->params->inc_children)
		{
			return false;
		}

		// Return true if a parent id is present in the selection
		return array_intersect(
			$this->getTagsParentIds($tag),
			$this->selection
		);
	}

	private function getTagsParentIds($id = 0)
	{
		$parentids = $this->getParentIds($id, 'tags');
		// Remove the root tag
		$parentids = array_diff($parentids, [1]);

		return $parentids;
	}

	private function passTagListMatchAll($tags)
	{
		foreach ($this->selection as $id)
		{
			if ( ! $this->passTagMatchAll($id, $tags))
			{
				return false;
			}
		}

		return true;
	}

	private function passTagMatchAll($id, $tags)
	{

		foreach ($tags as $tag)
		{
			if ($tag->id == $id || $tag->title == $id)
			{
				return true;
			}
		}

		return false;
	}
}
