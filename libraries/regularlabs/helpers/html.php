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

/* @DEPRECATED */

defined('_JEXEC') or die;

if (is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';
}

use RegularLabs\Library\Form as RL_Form;

class RLHtml
{
	static function selectlist(&$options, $name, $value, $id, $size = 0, $multiple = 0, $simple = 0)
	{
		return RL_Form::selectList($options, $name, $value, $id, $size, $multiple, $simple);
	}

	static function selectlistsimple(&$options, $name, $value, $id, $size = 0, $multiple = 0)
	{
		return RL_Form::selectListSimple($options, $name, $value, $id, $size, $multiple);
	}
}
