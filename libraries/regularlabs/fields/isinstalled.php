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

use RegularLabs\Library\Extension as RL_Extension;

jimport('joomla.form.formfield');

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

class JFormFieldRL_IsInstalled extends \RegularLabs\Library\Field
{
	public $type = 'IsInstalled';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$is_installed = RL_Extension::isInstalled($this->get('extension'), $this->get('extension_type'), $this->get('folder'));

		return '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '" value="' . (int) $is_installed . '">';
	}
}
