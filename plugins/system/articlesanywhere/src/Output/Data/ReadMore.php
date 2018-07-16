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

use JComponentHelper;
use JHtml;
use JLayoutHelper;
use JText;
use RegularLabs\Library\Language as RL_Language;

defined('_JEXEC') or die;

class ReadMore extends Data
{
	public function get($key, $attributes)
	{
		if ( ! $link = $this->getUrl())
		{
			return false;
		}

		// load the content language file
		RL_Language::load('com_content', JPATH_SITE);

		if ( ! empty($attributes->class))
		{
			return '<a class="' . trim($attributes->class) . '" href="' . $link . '">' . $this->getText($attributes) . '</a>';
		}

		$config = JComponentHelper::getParams('com_content');
		$config->set('access-view', true);

		if ($text = $this->getCustomText($attributes))
		{
			$this->item->set('alternative_readmore', $text);
			$config->set('show_readmore_title', false);
		}

		$this->item->set('alternative_readmore', $this->item->get('alternative_readmore', ''));

		return JLayoutHelper::render('joomla.content.readmore',
			[
				'item'   => $this->item->get(),
				'params' => $config,
				'link'   => $link,
			]
		);
	}

	protected function getUrl()
	{
		return (new Url($this->config, $this->item, $this->values))->getArticleUrl();
	}

	private function getCustomText($attributes)
	{
		if (empty($attributes->text))
		{
			return '';
		}

		$title = trim($attributes->text);
		$text  = JText::sprintf($title, $this->item->get('title'));

		return $text ?: $title;
	}

	private function getText($attributes)
	{
		if ($text = $this->getCustomText($attributes))
		{
			return $text;
		}

		$config = JComponentHelper::getParams('com_content');

		$alternative_readmore = $this->item->get('alternative_readmore');

		switch (true)
		{
			case ( ! empty($alternative_readmore)) :
				$text = $alternative_readmore;
				break;
			case ( ! $config->get('show_readmore_title', 0)) :
				$text = JText::_('COM_CONTENT_READ_MORE_TITLE');
				break;
			default:
				$text = JText::_('COM_CONTENT_READ_MORE');
				break;
		}

		if ( ! $config->get('show_readmore_title', 0))
		{
			return $text;
		}

		return $text . JHtml::_('string.truncate', ($this->item->get('title')), $config->get('readmore_limit'));
	}

}
