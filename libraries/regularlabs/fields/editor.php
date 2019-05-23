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

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

class JFormFieldRL_Editor extends \RegularLabs\Library\Field
{
	public $type = 'Editor';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$width  = $this->get('width', '100%');
		$height = $this->get('height', 400);

		$this->value = htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');

		// Get an editor object.
		$editor = JFactory::getEditor();
		$html   = $editor->display($this->name, $this->value, $width, $height, true, $this->id);

		return '</div><div>' . $html;
	}
}
