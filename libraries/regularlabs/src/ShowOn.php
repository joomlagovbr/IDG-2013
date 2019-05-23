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

namespace RegularLabs\Library;

use Joomla\CMS\Form\FormHelper as JFormHelper;
use RegularLabs\Library\Document as RL_Document;

defined('_JEXEC') or die;

/**
 * Class ShowOn
 * @package RegularLabs\Library
 */
class ShowOn
{
	public static function open($condition = '', $formControl = '', $group = '', $class = '')
	{
		if ( ! $condition)
		{
			return self::close();
		}

		RL_Document::loadFormDependencies();

		$json = json_encode(JFormHelper::parseShowOnConditions($condition, $formControl, $group));

		$class = $class ? ' class="' . $class . '"' : '';

		return '<div data-showon=\'' . $json . '\' style="display: none;"' . $class . '>';
	}

	public static function close()
	{
		return '</div>';
	}

	public static function show($string = '', $condition = '', $formControl = '', $group = '', $animate = true, $class = '')
	{
		if ( ! $condition || ! $string)
		{
			return $string;
		}

		return self::open($condition, $formControl, $group, $animate, $class)
			. $string
			. self::close();
	}
}
