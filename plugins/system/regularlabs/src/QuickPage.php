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

namespace RegularLabs\Plugin\System\RegularLabs;

defined('_JEXEC') or die;

use JFactory;
use JHtml;
use JUri;
use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\RegEx as RL_RegEx;

class QuickPage
{
	public static function render()
	{
		if ( ! JFactory::getApplication()->input->getInt('rl_qp', 0))
		{
			return;
		}

		$url = JFactory::getApplication()->input->getString('url', '');

		if ($url)
		{
			echo \RegularLabs\Library\Http::getFromServer($url, JFactory::getApplication()->input->getInt('timeout', ''));

			die;
		}

		$allowed = [
			'administrator/components/com_dbreplacer/ajax.php',
			'administrator/modules/mod_addtomenu/popup.php',
			'media/rereplacer/images/popup.php',
			'plugins/editors-xtd/articlesanywhere/popup.php',
			'plugins/editors-xtd/conditionalcontent/popup.php',
			'plugins/editors-xtd/contenttemplater/data.php',
			'plugins/editors-xtd/contenttemplater/popup.php',
			'plugins/editors-xtd/dummycontent/popup.php',
			'plugins/editors-xtd/modals/popup.php',
			'plugins/editors-xtd/modulesanywhere/popup.php',
			'plugins/editors-xtd/sliders/data.php',
			'plugins/editors-xtd/sliders/popup.php',
			'plugins/editors-xtd/snippets/popup.php',
			'plugins/editors-xtd/sourcerer/popup.php',
			'plugins/editors-xtd/tabs/data.php',
			'plugins/editors-xtd/tabs/popup.php',
			'plugins/editors-xtd/tooltips/popup.php',
		];

		$file   = JFactory::getApplication()->input->getString('file', '');
		$folder = JFactory::getApplication()->input->getString('folder', '');

		if ($folder)
		{
			$file = implode('/', explode('.', $folder)) . '/' . $file;
		}

		if ( ! $file || in_array($file, $allowed) === false)
		{
			die;
		}

		jimport('joomla.filesystem.file');

		if (RL_Document::isClient('site'))
		{
			JFactory::getApplication()->setTemplate('../administrator/templates/isis');
		}

		$_REQUEST['tmpl'] = 'component';
		JFactory::getApplication()->input->set('option', 'com_content');

		switch (JFactory::getApplication()->input->getCmd('format', 'html'))
		{
			case 'json' :
				$format = 'application/json';
				break;

			default:
			case 'html' :
				$format = 'text/html';
				break;
		}

		header('Content-Type: ' . $format . '; charset=utf-8');
		JHtml::_('bootstrap.framework');
		JFactory::getDocument()->addScript(JUri::root(true) . '/administrator/templates/isis/js/template.js');
		JFactory::getDocument()->addStylesheet(JUri::root(true) . '/administrator/templates/isis/css/template.css');

		RL_Document::style('regularlabs/popup.min.css');

		$file = JPATH_SITE . '/' . $file;

		$html = '';
		if (is_file($file))
		{
			ob_start();
			include $file;
			$html = ob_get_contents();
			ob_end_clean();
		}

		RL_Document::setBuffer($html);

		$app = new Application;
		$app->render();

		$html = JFactory::getApplication()->getBody();

		$html = RL_RegEx::replace('\s*<link [^>]*href="[^"]*templates/system/[^"]*\.css[^"]*"[^>]*( /)?>', '', $html);
		$html = RL_RegEx::replace('(<body [^>]*class=")', '\1reglab-popup ', $html);
		$html = str_replace('<body>', '<body class="reglab-popup"', $html);

		echo $html;

		die;
	}
}

