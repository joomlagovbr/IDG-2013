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

jimport('joomla.form.formfield');

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\RegEx as RL_RegEx;

class JFormFieldRL_Dependency extends \RegularLabs\Library\Field
{
	public $type = 'Dependency';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		JHtml::_('jquery.framework');
		RL_Document::script('regularlabs/script.min.js');

		if ($file = $this->get('file'))
		{
			$label = $this->get('label', 'the main extension');

			RLFieldDependency::setMessage($file, $label);

			return '';
		}

		$path      = ($this->get('path') == 'site') ? '' : '/administrator';
		$label     = $this->get('label');
		$file      = $this->get('alias', $label);
		$file      = RL_RegEx::replace('[^a-z-]', '', strtolower($file));
		$extension = $this->get('extension');

		switch ($extension)
		{
			case 'com';
				$file = $path . '/components/com_' . $file . '/com_' . $file . '.xml';
				break;
			case 'mod';
				$file = $path . '/modules/mod_' . $file . '/mod_' . $file . '.xml';
				break;
			default:
				$file = '/plugins/' . str_replace('plg_', '', $extension) . '/' . $file . '.xml';
				break;
		}

		$label = JText::_($label) . ' (' . JText::_('RL_' . strtoupper($extension)) . ')';

		RLFieldDependency::setMessage($file, $label);

		return '';
	}
}

class RLFieldDependency
{
	static function setMessage($file, $name)
	{
		jimport('joomla.filesystem.file');

		$file = str_replace('\\', '/', $file);
		if (strpos($file, '/administrator') === 0)
		{
			$file = str_replace('/administrator', JPATH_ADMINISTRATOR, $file);
		}
		else
		{
			$file = JPATH_SITE . '/' . $file;
		}
		$file = str_replace('//', '/', $file);

		$file_alt = RL_RegEx::replace('(com|mod)_([a-z-_]+\.)', '\2', $file);

		if ( ! JFile::exists($file) && ! JFile::exists($file_alt))
		{
			$msg          = JText::sprintf('RL_THIS_EXTENSION_NEEDS_THE_MAIN_EXTENSION_TO_FUNCTION', JText::_($name));
			$message_set  = 0;
			$messageQueue = JFactory::getApplication()->getMessageQueue();
			foreach ($messageQueue as $queue_message)
			{
				if ($queue_message['type'] == 'error' && $queue_message['message'] == $msg)
				{
					$message_set = 1;
					break;
				}
			}
			if ( ! $message_set)
			{
				JFactory::getApplication()->enqueueMessage($msg, 'error');
			}
		}
	}
}
