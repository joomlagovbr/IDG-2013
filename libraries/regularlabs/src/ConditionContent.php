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

namespace RegularLabs\Library;

defined('_JEXEC') or die;

/**
 * Class ConditionContent
 * @package RegularLabs\Library
 */
trait ConditionContent
{
	public function passContentId()
	{
		if (empty($this->selection))
		{
			return null;
		}

		return in_array($this->request->id, $this->selection);
	}

	public function passContentKeyword($fields = ['title', 'introtext', 'fulltext'], $text = '')
	{
		if (empty($this->params->content_keywords))
		{
			return null;
		}

		if ( ! $text)
		{
			$item = $this->getItem($fields);

			foreach ($fields as $field)
			{
				if ( ! isset($item->{$field}))
				{
					return false;
				}

				$text = trim($text . ' ' . $item->{$field});
			}
		}

		if (empty($text))
		{
			return false;
		}

		$this->params->content_keywords = $this->makeArray($this->params->content_keywords);

		foreach ($this->params->content_keywords as $keyword)
		{
			if ( ! RegEx::match('\b' . RegEx::quote($keyword) . '\b', $text))
			{
				continue;
			}

			return true;
		}

		return false;
	}

	public function passMetaKeyword($field = 'metakey', $keywords = '')
	{
		if (empty($this->params->meta_keywords))
		{
			return null;
		}

		if ( ! $keywords)
		{
			$item = $this->getItem($field);

			if ( ! isset($item->metakey) || empty($item->metakey))
			{
				return false;
			}

			$keywords = $item->metakey;
		}

		if (empty($keywords))
		{
			return false;
		}

		if (is_string($keywords))
		{
			$keywords = str_replace(' ', ',', $keywords);
		}

		$keywords = $this->makeArray($keywords);

		$this->params->meta_keywords = $this->makeArray($this->params->meta_keywords);

		foreach ($this->params->meta_keywords as $keyword)
		{
			if ( ! $keyword || ! in_array(trim($keyword), $keywords))
			{
				continue;
			}

			return true;
		}

		return false;
	}

	public function passAuthor($field = 'created_by', $author = '')
	{
		if (empty($this->params->authors))
		{
			return null;
		}

		if ( ! $author)
		{
			$item = $this->getItem($field);

			if ( ! isset($item->{$field}))
			{
				return false;
			}

			$author = $item->{$field};
		}

		if (empty($author))
		{
			return false;
		}

		$this->params->authors = $this->makeArray($this->params->authors);

		return in_array($author, $this->params->authors);
	}

	abstract public function getItem($fields = []);
}
