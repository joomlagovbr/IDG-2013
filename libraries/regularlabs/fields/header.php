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

use Joomla\CMS\Application\ApplicationHelper as JApplicationHelper;
use Joomla\CMS\Language\Text as JText;
use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Library\StringHelper as RL_String;

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

class JFormFieldRL_Header extends \RegularLabs\Library\Field
{
	public $type = 'Header';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$title       = $this->get('label');
		$description = $this->get('description');
		$xml         = $this->get('xml');
		$url         = $this->get('url');

		if ($description)
		{
			$description = RL_String::html_entity_decoder(trim(JText::_($description)));
		}

		if ($title)
		{
			$title = JText::_($title);
		}

		if ($description)
		{
			// Replace inline monospace style with rl_code classname
			$description = str_replace('span style="font-family:monospace;"', 'span class="rl_code"', $description);

			// 'Break' plugin style tags
			$description = str_replace(['{', '['], ['<span>{</span>', '<span>[</span>'], $description);

			// Wrap in paragraph (if not already starting with an html tag)
			if ($description[0] != '<')
			{
				$description = '<p>' . $description . '</p>';
			}
		}

		if ( ! $xml && $this->form->getValue('element'))
		{
			if ($this->form->getValue('folder'))
			{
				$xml = 'plugins/' . $this->form->getValue('folder') . '/' . $this->form->getValue('element') . '/' . $this->form->getValue('element') . '.xml';
			}
			else
			{
				$xml = 'administrator/modules/' . $this->form->getValue('element') . '/' . $this->form->getValue('element') . '.xml';
			}
		}

		if ($xml)
		{
			$xml     = JApplicationHelper::parseXMLInstallFile(JPATH_SITE . '/' . $xml);
			$version = 0;
			if ($xml && isset($xml['version']))
			{
				$version = $xml['version'];
			}
			if ($version)
			{
				if (strpos($version, 'PRO') !== false)
				{
					$version = str_replace('PRO', '', $version);
					$version .= ' <small style="color:green">[PRO]</small>';
				}
				else if (strpos($version, 'FREE') !== false)
				{
					$version = str_replace('FREE', '', $version);
					$version .= ' <small style="color:green">[FREE]</small>';
				}
				if ($title)
				{
					$title .= ' v';
				}
				else
				{
					$title = JText::_('Version') . ' ';
				}
				$title .= $version;
			}
		}
		$html = [];

		if ($title)
		{
			if ($url)
			{
				$title = '<a href="' . $url . '" target="_blank" title="'
					. RL_RegEx::replace('<[^>]*>', '', $title) . '">' . $title . '</a>';
			}
			$html[] = '<h4>' . RL_String::html_entity_decoder($title) . '</h4>';
		}

		if ($description)
		{
			$html[] = $description;
		}

		if ($url)
		{
			$html[] = '<p><a href="' . $url . '" class="btn btn-default" target="_blank" title="' . JText::_('RL_MORE_INFO') . '">' . JText::_('RL_MORE_INFO') . ' >></a></p>';
		}

		return '</div><div>' . implode('', $html);
	}
}
