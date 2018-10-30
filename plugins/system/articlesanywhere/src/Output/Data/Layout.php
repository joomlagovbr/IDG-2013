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

use ArticlesAnywhereArticleView;
use JFactory;
use JFolder;
use RegularLabs\Plugin\System\ArticlesAnywhere\Factory;
use RegularLabs\Plugin\System\ArticlesAnywhere\Params;

defined('_JEXEC') or die;

class Layout extends Data
{
	public function get($key, $attributes)
	{
		if (
			JFactory::getApplication()->input->get('option') == 'com_finder'
			&& JFactory::getApplication()->input->get('format') == 'json'
		)
		{
			// Force simple layout for finder indexing, as the setParams causes errors
			$text = Factory::getOutput('Text', $this->config, $this->item, $this->values);

			return
				'<h2>' . $this->item->get('title') . '</h2>'
				. $text->get('text', $attributes);
		}

		$params = Params::get();

		list($template, $layout) = $this->getTemplateAndLayout($attributes);

		require_once dirname(dirname(__DIR__)) . '/Helpers/article_view.php';

		$view = new ArticlesAnywhereArticleView;

		$view->setParams($this->item->getId(), $template, $layout, $params);

		return $view->display();
	}

	private function getTemplateAndLayout($data)
	{
		if ( ! isset($data->template) && isset($data->layout) && strpos($data->layout, ':') !== false)
		{
			list($data->template, $data->layout) = explode(':', $data->layout);
		}

		$article_layout = $this->item->get('article_layout');

		$layout = ! empty($data->layout)
			? $data->layout
			: (! empty($article_layout) ? $article_layout : 'default');

		$template = ! empty($data->template)
			? $data->template
			: JFactory::getApplication()->getTemplate();

		if (strpos($layout, ':') !== false)
		{
			list($template, $layout) = explode(':', $layout);
		}

		jimport('joomla.filesystem.folder');

		// Layout is a template, so return default layout
		if (empty($data->template) && JFolder::exists(JPATH_THEMES . '/' . $layout))
		{
			return [$layout, 'default'];
		}

		// Value is not a template, so a layout
		return [$template, $layout];
	}
}
