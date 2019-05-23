<?php
/**
 * @package         Regular Labs Library
 * @version         19.5.762
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Plugin\CMSPlugin as JPlugin;
use Joomla\CMS\Uri\Uri as JUri;
use Joomla\Registry\Registry;
use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\Parameters as RL_Parameters;
use RegularLabs\Library\Uri as RL_Uri;
use RegularLabs\Plugin\System\RegularLabs\AdminMenu as RL_AdminMenu;
use RegularLabs\Plugin\System\RegularLabs\DownloadKey as RL_DownloadKey;
use RegularLabs\Plugin\System\RegularLabs\QuickPage as RL_QuickPage;
use RegularLabs\Plugin\System\RegularLabs\SearchHelper as RL_SearchHelper;

if ( ! is_file(__DIR__ . '/vendor/autoload.php'))
{
	return;
}

require_once __DIR__ . '/vendor/autoload.php';

if (is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';
}

JFactory::getLanguage()->load('plg_system_regularlabs', __DIR__);

class PlgSystemRegularLabs extends JPlugin
{
	public function onAfterRoute()
	{
		if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
		{
			if (JFactory::getApplication()->isClient('administrator'))
			{
				JFactory::getApplication()->enqueueMessage('The Regular Labs Library folder is missing or incomplete: ' . JPATH_LIBRARIES . '/regularlabs', 'error');
			}

			return;
		}

		RL_DownloadKey::update();

		RL_SearchHelper::load();

		RL_QuickPage::render();
	}

	public function onAfterDispatch()
	{
		if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
		{
			return;
		}

		if ( ! RL_Document::isAdmin(true) || ! RL_Document::isHtml()
		)
		{
			return;
		}

		RL_Document::loadMainDependencies();
	}

	public function onAfterRender()
	{
		if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
		{
			return;
		}

		if ( ! RL_Document::isAdmin(true) || ! RL_Document::isHtml()
		)
		{
			return;
		}

		$this->fixQuotesInTooltips();

		RL_AdminMenu::combine();

		RL_AdminMenu::addHelpItem();
	}

	private function fixQuotesInTooltips()
	{
		$html = JFactory::getApplication()->getBody();

		if ($html == '')
		{
			return;
		}

		if (strpos($html, '&amp;quot;rl_code&amp;quot;') === false)
		{
			return;
		}

		$html = str_replace('&amp;quot;rl_code&amp;quot;', '&quot;rl_code&quot;', $html);

		JFactory::getApplication()->setBody($html);
	}

	public function onInstallerBeforePackageDownload(&$url, &$headers)
	{
		$uri  = JUri::getInstance($url);
		$host = $uri->getHost();

		if (
			strpos($host, 'regularlabs.com') === false
			&& strpos($host, 'nonumber.nl') === false
		)
		{
			return true;
		}

		$uri->setScheme('https');
		$uri->setHost('download.regularlabs.com');
		$uri->delVar('pro');
		$url = $uri->toString();

		$params = RL_Parameters::getInstance()->getComponentParams('regularlabsmanager');

		if (empty($params) || empty($params->key))
		{
			return true;
		}

		$uri->setVar('k', $params->key);
		$url = $uri->toString();

		return true;
	}

	public function onAjaxRegularLabs()
	{
		$input = JFactory::getApplication()->input;

		$format = $input->getString('format', 'json');

		$attributes = RL_Uri::getCompressedAttributes();
		$attributes = new Registry($attributes);

		$field      = $attributes->get('field');
		$field_type = $attributes->get('fieldtype');

		$class = $this->getAjaxClass($field, $field_type);

		if (empty($class) || ! class_exists($class))
		{
			return false;
		}

		$type = isset($attributes->type) ? $attributes->type : '';

		$method = 'getAjax' . ucfirst($format) . ucfirst($type);

		$class = new $class;

		if ( ! method_exists($class, $method))
		{
			return false;
		}

		echo $class->$method($attributes);
	}

	public function getAjaxClass($field, $field_type = '')
	{
		if (empty($field))
		{
			return false;
		}

		if ($field_type)
		{
			return $this->getFieldClass($field, $field_type);
		}

		$file = JPATH_LIBRARIES . '/regularlabs/fields/' . strtolower($field) . '.php';

		if ( ! file_exists($file))
		{
			return false;
		}

		require_once $file;

		return 'JFormFieldRL_' . ucfirst($field);
	}

	public function getFieldClass($field, $field_type)
	{
		$file = JPATH_PLUGINS . '/fields/' . strtolower($field_type) . '/fields/' . strtolower($field) . '.php';

		if ( ! file_exists($file))
		{
			return false;
		}

		require_once $file;

		return 'JFormField' . ucfirst($field);
	}
}

