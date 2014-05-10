<?php

/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 *
 */
// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * The Backup Administrator class
 *
 */
class AkeebaControllerBuadmin extends AkeebaControllerDefault
{

	public function __construct($config = array())
	{
		$base_path	 = JPATH_ADMINISTRATOR . '/components/com_akeeba/plugins';
		$model_path	 = $base_path . '/models';
		$view_path	 = $base_path . '/views';

		$config['model_path']	 = $model_path;
		$config['modelName']	 = 'AkeebaModelStatistics';

		parent::__construct($config);

		$this->addViewPath($view_path);
	}

	public function execute($task)
	{
		$session = JFactory::getSession();

		switch ($task)
		{
			case 'add':
			case 'default':
			case 'browse':
				$session->set('buadmin.task', 'default', 'akeeba');
				$this->task = 'browse';
				break;

			case 'restorepoint':
				if (!AKEEBA_PRO)
				{
					JError::raiseError('403', JText::_('AKEEBA_POSTSETUP_NOTAVAILABLEINCORE'));
					return false;
				}

				$session->set('buadmin.task', 'restorepoint', 'akeeba');
				$this->task = 'browse';
				break;

			case 'showcomment':
				$this->layout	 = 'comment';
				$this->task		 = 'edit';
				break;

			default:
				$this->task = $task;
				break;
		}

		$this->input->set('task', $this->task);
		$this->getThisView()->setLayout($this->layout);
		parent::execute($this->task);
	}

	/**
	 * Downloads the backup file of a specific backup attempt,
	 * if it's available
	 *
	 */
	public function download()
	{
		$model	 = $this->getThisModel();
		$id		 = $model->getId();

		$part = $this->input->get('part', -1, 'int');

		if ($this->input instanceof F0FInput)
		{
			$cid = $this->input->get('cid', array(), 'array');
		}
		else
		{
			$cid = $this->input->get('cid', array(), 'array');
		}

		if (empty($id))
		{
			if (is_array($cid) && !empty($cid))
			{
				$id = $cid[0];
			}
			else
			{
				$id = -1;
			}
		}

		if ($id <= 0)
		{
			$session = JFactory::getSession();
			$task	 = $session->get('buadmin.task', 'browse', 'akeeba');

			$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task, JText::_('STATS_ERROR_INVALIDID'), 'error');
			parent::display();
			return true;
		}

		$stat			 = AEPlatform::getInstance()->get_statistics($id);
		$allFilenames	 = AEUtilStatistics::get_all_filenames($stat);

		// Check single part files
		if ((count($allFilenames) == 1) && ($part == -1))
		{
			$filename = array_shift($allFilenames);
		}
		elseif ((count($allFilenames) > 0) && (count($allFilenames) > $part) && ($part >= 0))
		{
			$filename = $allFilenames[$part];
		}
		else
		{
			$filename = null;
		}

		if (is_null($filename) || empty($filename) || !@file_exists($filename))
		{
			$session = JFactory::getSession();
			$task	 = $session->get('buadmin.task', 'browse', 'akeeba');

			$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task, JText::_('STATS_ERROR_INVALIDDOWNLOAD'), 'error');
			parent::display();
			return true;
		}
		else
		{
			// For a certain unmentionable browser -- Thank you, Nooku, for the tip
			if (function_exists('ini_get') && function_exists('ini_set'))
			{
				if (ini_get('zlib.output_compression'))
				{
					ini_set('zlib.output_compression', 'Off');
				}
			}

			// Remove php's time limit -- Thank you, Nooku, for the tip
			if (function_exists('ini_get') && function_exists('set_time_limit'))
			{
				if (!ini_get('safe_mode'))
				{
					@set_time_limit(0);
				}
			}

			$basename	 = @basename($filename);
			$filesize	 = @filesize($filename);
			$extension	 = strtolower(str_replace(".", "", strrchr($filename, ".")));

			while (@ob_end_clean());
			@clearstatcache();
			// Send MIME headers
			header('MIME-Version: 1.0');
			header('Content-Disposition: attachment; filename="' . $basename . '"');
			header('Content-Transfer-Encoding: binary');
			header('Accept-Ranges: bytes');

			switch ($extension)
			{
				case 'zip':
					// ZIP MIME type
					header('Content-Type: application/zip');
					break;

				default:
					// Generic binary data MIME type
					header('Content-Type: application/octet-stream');
					break;
			}

			// Notify of filesize, if this info is available
			if ($filesize > 0)
				header('Content-Length: ' . @filesize($filename));
			// Disable caching
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Expires: 0");
			header('Pragma: no-cache');

			flush();

			if ($filesize > 0)
			{
				// If the filesize is reported, use 1M chunks for echoing the data to the browser
				$blocksize	 = 1048756; //1M chunks
				$handle		 = @fopen($filename, "r");
				// Now we need to loop through the file and echo out chunks of file data
				if ($handle !== false)
					while (!@feof($handle))
					{
						echo @fread($handle, $blocksize);
						@ob_flush();
						flush();
					}

				if ($handle !== false)
				{
					@fclose($handle);
				}
			}
			else
			{
				// If the filesize is not reported, hope that readfile works
				@readfile($filename);
			}

			exit(0);
		}
	}

	/**
	 * Deletes one or several backup statistics records and their associated backup files
	 */
	public function remove()
	{
		// CSRF prevention
		if ($this->csrfProtection)
		{
			$this->_csrfProtection();
		}

		if ($this->input instanceof F0FInput)
		{
			$cid = $this->input->get('cid', array(), 'array');
		}
		else
		{
			$cid = $this->input->get('cid', array(), 'array');
		}

		$id = $this->input->get('id', 0, 'int');

		if (empty($id))
		{
			if (!empty($cid) && is_array($cid))
			{
				foreach ($cid as $id)
				{
					$session = JFactory::getSession();
					$task	 = $session->get('buadmin.task', 'browse', 'akeeba');
					$result	 = $this->_remove($id);

					if (!$result)
					{
						$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task, JText::_('STATS_ERROR_INVALIDID'), 'error');
					}
				}
			}
			else
			{
				$session = JFactory::getSession();
				$task	 = $session->get('buadmin.task', 'browse', 'akeeba');
				$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task, JText::_('STATS_ERROR_INVALIDID'), 'error');

				return true;
			}
		}
		else
		{
			$result	 = $this->_remove($id);
			$session = JFactory::getSession();
			$task	 = $session->get('buadmin.task', 'browse', 'akeeba');

			if (!$result)
			{
				$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task, JText::_('STATS_ERROR_INVALIDID'), 'error');
			}
		}

		$session = JFactory::getSession();
		$task	 = $session->get('buadmin.task', 'browse', 'akeeba');

		$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task, JText::_('STATS_MSG_DELETED'));
	}

	/**
	 * Deletes backup files associated to one or several backup statistics records
	 */
	public function deletefiles()
	{
		// CSRF prevention
		if ($this->csrfProtection)
		{
			$this->_csrfProtection();
		}

		if ($this->input instanceof F0FInput)
		{
			$cid = $this->input->get('cid', array(), 'array');
		}
		else
		{
			$cid = $this->input->get('cid', array(), 'array');
		}

		$id		 = $this->input->get('id', 0, 'int');
		$session = JFactory::getSession();
		$task	 = $session->get('buadmin.task', 'browse', 'akeeba');

		if (empty($id))
		{
			if (!empty($cid) && is_array($cid))
			{
				foreach ($cid as $id)
				{
					$result = $this->_removeFiles($id);
					if (!$result)
					{
						$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task, JText::_('STATS_ERROR_INVALIDID'), 'error');
					}
				}
			}
			else
			{
				$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task, JText::_('STATS_ERROR_INVALIDID'), 'error');

				return true;
			}
		}
		else
		{
			$result = $this->_remove($id);

			if (!$result)
			{
				$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task, JText::_('STATS_ERROR_INVALIDID'), 'error');
			}
		}

		$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task, JText::_('STATS_MSG_DELETEDFILE'));
	}

	/**
	 * Removes the backup file linked to a statistics entry and the entry itself
	 *
	 * @return bool True on success
	 */
	private function _remove($id)
	{
		$session = JFactory::getSession();
		$task	 = $session->get('buadmin.task', 'browse', 'akeeba');

		if ($id <= 0)
		{
			$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task, JText::_('STATS_ERROR_INVALIDID'), 'error');
			return true;
		}

		$model = $this->getThisModel();

		$model->setState('id', $id);
		return $model->delete();
	}

	/**
	 * Removes only the backup file linked to a statistics entry
	 *
	 * @return bool True on success
	 */
	private function _removeFiles($id)
	{
		$session = JFactory::getSession();
		$task	 = $session->get('buadmin.task', 'browse', 'akeeba');

		if ($id <= 0)
		{
			$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task, JText::_('STATS_ERROR_INVALIDID'), 'error');

			return true;
		}

		$model = $this->getModel('statistics');
		$model->setState('id', $id);

		return $model->deleteFile();
	}

	public function onBeforeEdit()
	{
		$result = parent::onBeforeEdit();

		if ($result)
		{
			$session = JFactory::getSession();
			$task	 = $session->get('buadmin.task', 'browse', 'akeeba');

			$model	 = $this->getThisModel();
			$id		 = $model->getId();

			if ($id <= 0)
			{
				$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task, JText::_('STATS_ERROR_INVALIDID'), 'error');
				$result = false;
			}
		}

		return $result;
	}

	/**
	 * Save an edited backup record
	 */
	public function save()
	{
		// CSRF prevention
		if ($this->csrfProtection)
		{
			$this->_csrfProtection();
		}

		$id			 = $this->input->get('id', 0, 'int');
		$description = $this->input->get('description', '', 'string');
		$comment	 = $this->input->get('comment', null, 'string', 4);

		$statistic					 = AEPlatform::getInstance()->get_statistics($this->input->get('id', 0, 'int'));
		$statistic['description']	 = $description;
		$statistic['comment']		 = $comment;

		AEPlatform::getInstance()->set_or_update_statistics($this->input->get('id', 0, 'int'), $statistic, $self);

		if (!$this->getError())
		{
			$message = JText::_('STATS_LOG_SAVEDOK');
			$type	 = 'message';
		}
		else
		{
			$message = JText::_('STATS_LOG_SAVEERROR');
			$type	 = 'error';
		}

		$session = JFactory::getSession();
		$task	 = $session->get('buadmin.task', 'browse', 'akeeba');
		$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task, $message, $type);
	}

	public function restore()
	{
		// CSRF prevention
		if ($this->csrfProtection)
		{
			$this->_csrfProtection();
		}

		$id	 = null;
		$cid = $this->input->get('cid', array(), 'array');

		if (!empty($cid))
		{
			$id	 = intval($cid[0]);
			if ($id <= 0)
			{
				$id	 = null;
			}
		}

		if (empty($id))
		{
			$id	 = $this->input->get('id', -1, 'int');
		}

		if ($id <= 0)
		{
			$id	 = null;
		}

		$url = JURI::base() . 'index.php?option=com_akeeba&view=restore&id=' . $id;
		$this->setRedirect($url);
		$this->redirect();

		return true;
	}

	public function cancel()
	{
		// CSRF prevention
		if ($this->csrfProtection)
		{
			$this->_csrfProtection();
		}

		$session = JFactory::getSession();
		$task	 = $session->get('buadmin.task', 'browse', 'akeeba');
		$this->setRedirect(JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=' . $task);
	}

}