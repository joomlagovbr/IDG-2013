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

use Joomla\Registry\Registry;

class JFormFieldRL_Agents extends \RegularLabs\Library\Field
{
	public $type = 'Agents';

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		if ( ! is_array($this->value))
		{
			$this->value = explode(',', $this->value);
		}

		$size  = (int) $this->get('size');
		$group = $this->get('group', 'os');

		return $this->selectListSimpleAjax(
			$this->type, $this->name, $this->value, $this->id,
			compact('size', 'group')
		);
	}

	function getAjaxRaw(Registry $attributes)
	{
		$name  = $attributes->get('name', $this->type);
		$id    = $attributes->get('id', strtolower($name));
		$value = $attributes->get('value', []);
		$size  = $attributes->get('size');

		$options = $this->getAgents(
			$attributes->get('group')
		);

		return $this->selectListSimple($options, $name, $value, $id, $size, true);
	}

	function getAgents($group = 'os')
	{
		$agents = [];
		switch ($group)
		{
			/* OS */
			case 'os':
				$agents[] = ['Windows (' . JText::_('JALL') . ')', 'Windows'];
				$agents[] = ['Windows 10', 'Windows nt 10.0'];
				$agents[] = ['Windows 8', 'Windows nt 6.2'];
				$agents[] = ['Windows 7', 'Windows nt 6.1'];
				$agents[] = ['Windows Vista', 'Windows nt 6.0'];
				$agents[] = ['Windows Server 2003', 'Windows nt 5.2'];
				$agents[] = ['Windows XP', 'Windows nt 5.1'];
				$agents[] = ['Windows 2000 sp1', 'Windows nt 5.01'];
				$agents[] = ['Windows 2000', 'Windows nt 5.0'];
				$agents[] = ['Windows NT 4.0', 'Windows nt 4.0'];
				$agents[] = ['Windows Me', 'Win 9x 4.9'];
				$agents[] = ['Windows 98', 'Windows 98'];
				$agents[] = ['Windows 95', 'Windows 95'];
				$agents[] = ['Windows CE', 'Windows ce'];
				$agents[] = ['Mac OS (' . JText::_('JALL') . ')', '#(Mac OS|Mac_PowerPC|Macintosh)#'];
				$agents[] = ['Mac OSX (' . JText::_('JALL') . ')', 'Mac OS X'];
				$agents[] = ['Mac OSX El Capitan', 'Mac OS X 10.11'];
				$agents[] = ['Mac OSX Yosemite', 'Mac OS X 10.10'];
				$agents[] = ['Mac OSX Mavericks', 'Mac OS X 10.9'];
				$agents[] = ['Mac OSX Mountain Lion', 'Mac OS X 10.8'];
				$agents[] = ['Mac OSX Lion', 'Mac OS X 10.7'];
				$agents[] = ['Mac OSX Snow Leopard', 'Mac OS X 10.6'];
				$agents[] = ['Mac OSX Leopard', 'Mac OS X 10.5'];
				$agents[] = ['Mac OSX Tiger', 'Mac OS X 10.4'];
				$agents[] = ['Mac OSX Panther', 'Mac OS X 10.3'];
				$agents[] = ['Mac OSX Jaguar', 'Mac OS X 10.2'];
				$agents[] = ['Mac OSX Puma', 'Mac OS X 10.1'];
				$agents[] = ['Mac OSX Cheetah', 'Mac OS X 10.0'];
				$agents[] = ['Mac OS (classic)', '#(Mac_PowerPC|Macintosh)#'];
				$agents[] = ['Linux', '#(Linux|X11)#'];
				$agents[] = ['Open BSD', 'OpenBSD'];
				$agents[] = ['Sun OS', 'SunOS'];
				$agents[] = ['QNX', 'QNX'];
				$agents[] = ['BeOS', 'BeOS'];
				$agents[] = ['OS/2', 'OS/2'];
				break;

			/* Browsers */
			case 'browsers':
				if ($this->get('simple') && $this->get('simple') !== 'false')
				{

					$agents[] = ['Chrome', 'Chrome'];
					$agents[] = ['Firefox', 'Firefox'];
					$agents[] = ['Edge', 'Edge'];
					$agents[] = ['Internet Explorer', 'MSIE'];
					$agents[] = ['Opera', 'Opera'];
					$agents[] = ['Safari', 'Safari'];
					break;
				}

				$agents[] = ['Chrome (' . JText::_('JALL') . ')', 'Chrome'];
				$agents[] = ['Chrome 61-70', '#Chrome/(6[1-9]|70)\.#'];
				$agents[] = ['Chrome 51-60', '#Chrome/(5[1-9]|60)\.#'];
				$agents[] = ['Chrome 41-50', '#Chrome/(4[1-9]|50)\.#'];
				$agents[] = ['Chrome 31-40', '#Chrome/(3[1-9]|40)\.#'];
				$agents[] = ['Chrome 21-30', '#Chrome/(2[1-9]|30)\.#'];
				$agents[] = ['Chrome 11-20', '#Chrome/(1[1-9]|20)\.#'];
				$agents[] = ['Chrome 1-10', '#Chrome/([1-9]|10)\.#'];
				$agents[] = ['Firefox (' . JText::_('JALL') . ')', 'Firefox'];
				$agents[] = ['Firefox 51-60', '#Firefox/(5[1-9]|60)\.#'];
				$agents[] = ['Firefox 41-50', '#Firefox/(4[1-9]|50)\.#'];
				$agents[] = ['Firefox 31-40', '#Firefox/(3[1-9]|40)\.#'];
				$agents[] = ['Firefox 21-30', '#Firefox/(2[1-9]|30)\.#'];
				$agents[] = ['Firefox 11-20', '#Firefox/(1[1-9]|20)\.#'];
				$agents[] = ['Firefox 1-10', '#Firefox/([1-9]|10)\.#'];
				$agents[] = ['Internet Explorer (' . JText::_('JALL') . ')', 'MSIE'];
				$agents[] = ['Internet Explorer Edge', 'MSIE Edge']; // missing MSIE is added to agent string in assignments/agents.php
				$agents[] = ['Edge 13', 'Edge/13'];
				$agents[] = ['Edge 12', 'Edge/12'];
				$agents[] = ['Internet Explorer 11', 'MSIE 11']; // missing MSIE is added to agent string in assignments/agents.php
				$agents[] = ['Internet Explorer 10.6', 'MSIE 10.6'];
				$agents[] = ['Internet Explorer 10.0', 'MSIE 10.0'];
				$agents[] = ['Internet Explorer 10', 'MSIE 10.'];
				$agents[] = ['Internet Explorer 9', 'MSIE 9.'];
				$agents[] = ['Internet Explorer 8', 'MSIE 8.'];
				$agents[] = ['Internet Explorer 7', 'MSIE 7.'];
				$agents[] = ['Internet Explorer 1-6', '#MSIE [1-6]\.#'];
				$agents[] = ['Opera (' . JText::_('JALL') . ')', 'Opera'];
				$agents[] = ['Opera 41-50', '#Opera/(4[1-9]|50)\.#'];
				$agents[] = ['Opera 31-40', '#Opera/(3[1-9]|40)\.#'];
				$agents[] = ['Opera 21-30', '#Opera/(2[1-9]|30)\.#'];
				$agents[] = ['Opera 11-20', '#Opera/(1[1-9]|20)\.#'];
				$agents[] = ['Opera 1-10', '#Opera/([1-9]|10)\.#'];
				$agents[] = ['Safari (' . JText::_('JALL') . ')', 'Safari'];
				$agents[] = ['Safari 11', '#Version/11\..*Safari/#'];
				$agents[] = ['Safari 10', '#Version/10\..*Safari/#'];
				$agents[] = ['Safari 9', '#Version/9\..*Safari/#'];
				$agents[] = ['Safari 8', '#Version/8\..*Safari/#'];
				$agents[] = ['Safari 7', '#Version/7\..*Safari/#'];
				$agents[] = ['Safari 6', '#Version/6\..*Safari/#'];
				$agents[] = ['Safari 5', '#Version/5\..*Safari/#'];
				$agents[] = ['Safari 4', '#Version/4\..*Safari/#'];
				$agents[] = ['Safari 1-3', '#Version/[1-3]\..*Safari/#'];
				break;

			/* Mobile browsers */
			case 'mobile':
				$agents[] = [JText::_('JALL'), 'mobile'];
				$agents[] = ['Android', 'Android'];
				$agents[] = ['Android Chrome', '#Android.*Chrome#'];
				$agents[] = ['Blackberry', 'Blackberry'];
				$agents[] = ['IE Mobile', 'IEMobile'];
				$agents[] = ['iPad', 'iPad'];
				$agents[] = ['iPhone', 'iPhone'];
				$agents[] = ['iPod Touch', 'iPod'];
				$agents[] = ['NetFront', 'NetFront'];
				$agents[] = ['Nokia', 'NokiaBrowser'];
				$agents[] = ['Opera Mini', 'Opera Mini'];
				$agents[] = ['Opera Mobile', 'Opera Mobi'];
				$agents[] = ['UC Browser', 'UC Browser'];
				break;
		}

		$options = [];
		foreach ($agents as $agent)
		{
			$option    = JHtml::_('select.option', $agent[1], $agent[0]);
			$options[] = $option;
		}

		return $options;
	}
}
