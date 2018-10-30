<?php
/**
 * @package         Articles Anywhere
 * @version         8.0.3
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Output\Data;

use ContentHelperRoute;
use JFactory;
use JRoute;
use JText;
use JUri;
use RegularLabs\Library\HtmlTag as RL_HtmlTag;

defined('_JEXEC') or die;

class Url extends Data
{
	public function get($key, $attributes)
	{
		switch ($key)
		{

			case 'link':
				return $this->getArticleLink($attributes);

			case 'sefurl':
				return JRoute::_($this->getArticleUrl());

			default:
			case 'url':
			case 'nonsefurl':
				return $this->getArticleUrl();
		}
	}

	public function getLink($url, $attributes = [])
	{
		$url = $url ?: '#';

		$attributes = array_merge(
			['href' => $url],
			(array) $attributes
		);

		return '<a ' . RL_HtmlTag::flattenAttributes($attributes) . '">';
	}

	public function getArticleLink($attributes)
	{
		return $this->getLink($this->getArticleUrl(), $attributes);
	}

	public function getArticleUrl()
	{
		$url = $this->item->get('url');

		if ( ! is_null($url))
		{
			return $url;
		}

		$id = $this->item->getId();
		if ( ! $id)
		{
			return false;
		}

		if ( ! class_exists('ContentHelperRoute'))
		{
			require_once JPATH_SITE . '/components/com_content/helpers/route.php';
		}

		$this->item->set('url', ContentHelperRoute::getArticleRoute($id, $this->item->get('catid'), $this->item->get('language')));

		if ( ! $this->item->hasAccess())
		{
			$this->item->set('url', $this->getRestrictedUrl($this->item->get('url')));
		}

		return $this->item->get('url');
	}


	protected function getRestrictedUrl($url)
	{
		$menu   = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemId = $active ? $active->id : 0;
		$link   = new JUri(JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false));

		$link->setVar('return', base64_encode(JRoute::_($url, false)));

		return (string) $link;
	}

}
