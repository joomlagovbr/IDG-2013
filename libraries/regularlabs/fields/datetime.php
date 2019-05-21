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
use Joomla\CMS\Language\Text as JText;
use RegularLabs\Library\Date as RL_Date;

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

class JFormFieldRL_DateTime extends \RegularLabs\Library\Field
{
	public $type = 'DateTime';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
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
