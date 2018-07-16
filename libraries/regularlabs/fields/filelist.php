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

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

require_once JPATH_LIBRARIES . '/joomla/form/fields/list.php';

use RegularLabs\Library\RegEx as RL_RegEx;

class JFormFieldRL_FileList extends JFormFieldList
{
	public  $type   = 'FileList';
	private $params = null;

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		return parent::getInput();
	}

	protected function getOptions()
	{
		$options = [];

		$path = $this->get('folder');

		if ( ! is_dir($path))
		{
			$path = JPATH_ROOT . '/' . $path;
		}

		// Prepend some default options based on field attributes.
		if ( ! $this->get('hidenone', 0))
		{
			$options[] = JHtml::_('select.option', '-1', JText::alt('JOPTION_DO_NOT_USE',
				RL_RegEx::replace('[^a-z0-9_\-]', '_', $this->fieldname)));
		}

		if ( ! $this->get('hidedefault', 0))
		{
			$options[] = JHtml::_('select.option', '', JText::alt('JOPTION_USE_DEFAULT',
				RL_RegEx::replace('[^a-z0-9_\-]', '_', $this->fieldname)));
		}

		// Get a list of files in the search path with the given filter.
		$files = JFolder::files($path, $this->get('filter'));

		// Build the options list from the list of files.
		if (is_array($files))
		{
			foreach ($files as $file)
			{
				// Check to see if the file is in the exclude mask.
				if ($this->get('exclude'))
				{
					if (RL_RegEx::match(chr(1) . $this->get('exclude') . chr(1), $file))
					{
						continue;
					}
				}

				// If the extension is to be stripped, do it.
				if ($this->get('stripext', 1))
				{
					$file = JFile::stripExt($file);
				}

				$label = $file;
				if ($this->get('language_prefix'))
				{
					$label = JText::_($this->get('language_prefix') . strtoupper($label));
				}

				$options[] = JHtml::_('select.option', $file, $label);
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

	private function get($val, $default = '')
	{
		if ( ! isset($this->params[$val]) || (string) $this->params[$val] == '')
		{
			return $default;
		}

		return (string) $this->params[$val];
	}
}
