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

use RegularLabs\Library\Date as RL_Date;

class JFormFieldRL_DateTime extends \RegularLabs\Library\Field
{
	public $type = 'DateTime';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		$label  = $this->get('label');
		$format = $this->get('format');

		$date = JFactory::getDate();

		$tz = new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
		$date->setTimeZone($tz);

		if ($format)
		{
			if (strpos($format, '%') !== false)
			{
				$format = RL_Date::strftimeToDateFormat($format);
			}
			$html = $date->format($format, true);
		}
		else
		{
			$html = $date->format('', true);
		}

		if ($label)
		{
			$html = JText::sprintf($label, $html);
		}

		return '</div><div>' . $html;
	}
}
