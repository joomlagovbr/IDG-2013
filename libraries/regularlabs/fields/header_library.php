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

defined('_JEXEC') or die;

require_once __DIR__ . '/header.php';

class JFormFieldRL_Header_Library extends JFormFieldRL_Header
{
	protected function getInput()
	{
		$extensions = [
			'Add to Menu',
			'Advanced Module Manager',
			'Advanced Template Manager',
			'Articles Anywhere',
			'Better Preview',
			'Cache Cleaner',
			'CDN for Joomla!',
			'Components Anywhere',
			'Content Templater',
			'DB Replacer',
			'Dummy Content',
			'Email Protector',
			'GeoIp',
			'IP Login',
			'Modals',
			'Modules Anywhere',
			'Regular Labs Extension Manager',
			'ReReplacer',
			'Sliders',
			'Snippets',
			'Sourcerer',
			'Tabs',
			'Tooltips',
			'What? Nothing!',
		];

		$list = '<ul><li>' . implode('</li><li>', $extensions) . '</li></ul>';

		$attributes = $this->element->attributes();

		$warning = '';
		if (isset($attributes['warning']))
		{
			$warning = '<div class="alert alert-danger">' . JText::_($attributes['warning']) . '</div>';
		}

		$this->element->attributes()['description'] = JText::sprintf($attributes['description'], $warning, $list);

		return parent::getInput();
	}
}
