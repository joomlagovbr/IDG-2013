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

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

use RegularLabs\Library\Language as RL_Language;

class JFormFieldRL_LoadLanguage extends \RegularLabs\Library\Field
{
	public $type = 'LoadLanguage';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		$extension = $this->get('extension');
		$admin     = $this->get('admin', 1);

		self::loadLanguage($extension, $admin);

		return '';
	}

	function loadLanguage($extension, $admin = 1)
	{
		if ( ! $extension)
		{
			return;
		}

		RL_Language::load($extension, $admin ? JPATH_ADMINISTRATOR : JPATH_SITE);
	}
}
