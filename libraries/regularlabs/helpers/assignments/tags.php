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

class RLAssignmentsTags extends RLAssignment
{
	public function passTags()
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
			return $this->pass(false);
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
				return $this->pass(false);
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
				. ' AND m.content_item_id IN ( ' . $this->request->id . ')'
			);
		$this->db->setQuery($query);
		$tags = $this->db->loadObjectList();

		if (empty($tags))
		{
			return $this->pass(false);
		}

		foreach ($tags as $tag)
		{
			if ( ! $this->passTag($tag->id) && ! $this->passTag($tag->title))
			{
				continue;
			}

			return $this->pass(true);
		}

		return $this->pass(false);
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
}
