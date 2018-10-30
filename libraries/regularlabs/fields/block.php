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

use RegularLabs\Library\Document as RL_Document;

class JFormFieldRL_Block extends \RegularLabs\Library\Field
{
	public $type = 'Block';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		RL_Document::stylesheet('regularlabs/style.min.css');

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
