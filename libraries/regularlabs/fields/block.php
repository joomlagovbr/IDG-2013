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

class JFormFieldRL_Block extends \RegularLabs\Library\Field
{
	public $type = 'Block';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$title       = $this->get('label');
		$description = $this->get('description');
		$class       = $this->get('class');
		$showclose   = $this->get('showclose', 0);

		$start = $this->get('start', 0);
		$end   = $this->get('end', 0);

		$html = [];

		if ($start || ! $end)
		{
			$html[] = '</div>';
			if (strpos($class, 'alert') !== false)
			{
				$html[] = '<div class="alert ' . $class . '">';
			}
			else
			{
				$html[] = '<div class="well well-small ' . $class . '">';
			}
			if ($showclose && JFactory::getUser()->authorise('core.admin'))
			{
				$html[] = '<button type="button" class="close rl_remove_assignment">&times;</button>';
			}
			if ($title)
			{
				$html[] = '<h4>' . $this->prepareText($title) . '</h4>';
			}
			if ($description)
			{
				$html[] = '<div>' . $this->prepareText($description) . '</div>';
			}
			$html[] = '<div><div>';
		}

		if ( ! $start && ! $end)
		{
			$html[] = '</div>';
		}

		return '</div>' . implode('', $html);
	}
}
