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

jimport('joomla.form.formfield');

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

use RegularLabs\Library\Extension as RL_Extension;

class JFormFieldRL_IsInstalled extends \RegularLabs\Library\Field
{
	public $type = 'IsInstalled';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		$is_installed = RL_Extension::isInstalled($this->get('extension'), $this->get('extension_type'), $this->get('folder'));

		return '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '" value="' . (int) $is_installed . '">';
	}
}
