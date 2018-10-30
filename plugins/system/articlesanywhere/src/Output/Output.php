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

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Output;

use JEventDispatcher;
use Joomla\Registry\Registry;
use JPluginHelper;
use RegularLabs\Library\Html as RL_Html;
use RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Item;
use RegularLabs\Plugin\System\ArticlesAnywhere\Config;
use RegularLabs\Plugin\System\ArticlesAnywhere\CurrentArticle;
use RegularLabs\Plugin\System\ArticlesAnywhere\Output\Data\Numbers;
use RegularLabs\Plugin\System\ArticlesAnywhere\Params;
use RegularLabs\Plugin\System\ArticlesAnywhere\Protect;

defined('_JEXEC') or die;

class Output
{
	protected $config;
	protected $content;

	protected $numbers;

	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	public function get($items, $total_before_limit)
	{
		if (empty($items))
		{
			return '';
		}

		$this->numbers = new Numbers($total_before_limit, count($items));

		$html = [];

		/** @var Item $item */
		foreach ($items as $count => $item)
		{
			$this->numbers->setCount($count + 1)
				->setCurrent($item->getId() == CurrentArticle::get('id', $this->config->getComponentName()));

			$html[] = $this->renderOutput($item);
		}

		$separator = '';
		if (isset($this->config->getData('attributes')->separator))
		{
			$separator = $this->config->getData('attributes')->separator;
		}

		$html = implode($separator, $html);

		$params = Params::get();

		if ($params->force_content_triggers && strpos($html, '<!-- AA:CT -->') === false)
		{
			$html = $this->triggerContentPlugins($html, $item);
		}

		$html = str_replace('<!-- AA:CT -->', '', $html);

		$attributes = $item->getConfigData('attributes');

		$fix_html = isset($attributes->fixhtml) ? $attributes->fixhtml : $params->fix_html_syntax;

		$surrounding_tags = $item->getConfigData('surrounding_tags');

		if (empty($html) || ! $fix_html)
		{
			return
				$surrounding_tags->opening
				. $html
				. $surrounding_tags->closing;
		}

		if (empty($surrounding_tags->opening) || empty($surrounding_tags->closing))
		{
			return
				$surrounding_tags->opening
				. self::fixBrokenHtmlTags($html)
				. $surrounding_tags->closing;
		}

		return self::fixBrokenHtmlTags(
			$surrounding_tags->opening
			. $html
			. $surrounding_tags->closing
		);
	}

	public function renderOutput(Item $item)
	{
		$content = $this->config->getContent();

		// Default to full article layout if content is empty
		if ($content == '')
		{
			list($data_tag_start, $data_tag_end) = Params::getDataTagCharacters();
			$content = $data_tag_start . 'article' . $data_tag_end;
		}

		(new IfStructures($this->config, $item, $this->numbers))->handle($content);
		(new DataTags($this->config, $item, $this->numbers))->handle($content);

		return $content;
	}

	private static function fixBrokenHtmlTags($string)
	{
		$params = Params::get();

		$string = RL_Html::fix($string);

		if ( ! $params->place_comments)
		{
			return $string;
		}

		return Protect::wrapInCommentTags($string);
	}

	private function triggerContentPlugins($string, Item $item)
	{
		$item          = $item->get();
		$item->text    = $string;
		$item->slug    = '';
		$item->catslug = '';

		$article_params = new Registry;
		$article_params->loadArray(['inline' => false]);

		$dispatcher = JEventDispatcher::getInstance();
		JPluginHelper::importPlugin('content');

		$dispatcher->trigger('onContentPrepare', ['com_content.article', &$item, &$article_params, 0]);

		return $item->text;
	}
}
