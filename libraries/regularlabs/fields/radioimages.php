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

use RegularLabs\Library\RegEx as RL_RegEx;

class JFormFieldRL_RadioImages extends \RegularLabs\Library\Field
{
	public $type = 'RadioImages';

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');

		// path to images directory
		$path     = JPATH_ROOT . '/' . $this->get('directory');
		$filter   = $this->get('filter');
		$exclude  = $this->get('exclude');
		$stripExt = $this->get('stripext');
		$files    = JFolder::files($path, $filter);
		$rowcount = $this->get('rowcount');

		$options = [];

		if ( ! $this->get('hide_none'))
		{
			$options[] = JHtml::_('select.option', '-1', JText::_('Do not use') . '<br>');
		}

		if ( ! $this->get('hide_default'))
		{
			$options[] = JHtml::_('select.option', '', JText::_('Use default') . '<br>');
		}

		if (is_array($files))
		{
			$count = 0;
			foreach ($files as $file)
			{
				if ($exclude)
				{
					if (RL_RegEx::match(chr(1) . $exclude . chr(1), $file))
					{
						continue;
					}
				}
				$count++;
				if ($stripExt)
				{
					$file = JFile::stripExt($file);
				}
				$image = '<img src="../' . $this->get('directory') . '/' . $file . '" style="padding-right: 10px;" title="' . $file . '" alt="' . $file . '">';
				if ($rowcount && $count >= $rowcount)
				{
					$image .= '<br>';
					$count = 0;
				}
				$options[] = JHtml::_('select.option', $file, $image);
			}
		}

		$list = JHtml::_('select.radiolist', $options, '' . $this->name . '', '', 'value', 'text', $this->value, $this->id);

		$list = '<div style="float:left;">' . str_replace('<input type="radio"', '</div><div style="float:left;margin:2px 0;"><input type="radio" style="float:left;"', $list) . '</div>';
		$list = str_replace(['<label', '</label>'], ['<span style="float: left;"', '</span>'], $list);
		$list = RL_RegEx::replace('</span>(\s*)</div>', '</span></div>\1', $list);
		$list = str_replace('<br></span></div>', '<br></span></div><div style="clear:both;"></div>', $list);

		$list = '<div style="clear:both;"></div>' . $list;

		return $list;
	}
}
