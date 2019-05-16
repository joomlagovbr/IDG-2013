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

use Joomla\CMS\Language\Text as JText;

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
			'Articles Field',
			'Better Preview',
			'Better Trash',
			'Cache Cleaner',
			'CDN for Joomla!',
			'Components Anywhere',
			'Conditional Content',
			'Content Templater',
			'DB Replacer',
			'Dummy Content',
			'Email Protector',
			'GeoIP',
			'IP Login',
			'Modals',
			'Modules Anywhere',
			'Quick Index',
			'Regular Labs Extension Manager',
			'ReReplacer',
			'Simple User Notes',
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
