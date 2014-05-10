<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * Controller class for Profiles Administration page
 *
 */
class AkeebaControllerProfiles extends AkeebaControllerDefault
{
	public function  __construct($config = array()) {
		parent::__construct($config);

		$base_path = JPATH_COMPONENT_ADMINISTRATOR.'/plugins';
		$model_path = $base_path.'/models';
		$view_path = $base_path.'/views';
		$this->addModelPath($model_path);
		$this->addViewPath($view_path);
	}

	/**
	 * Copies the selected profile into a new record at the end of the list
	 *
	 */
	public function copy()
	{
		// CSRF prevention
		if($this->csrfProtection) {
			$this->_csrfProtection();
		}

		$model = $this->getThisModel();
		if($model->copy())
		{
			// Show a "COPY OK" message
			$message = JText::_('PROFILE_COPY_OK');
			$type = 'message';

			$session = JFactory::getSession();
			$session->set('profile', $model->getId(), 'akeeba');
		}
		else
		{
			// Show message on failure
			$message = JText::_('PROFILE_COPY_ERROR');
			$message .= ' ['.$model->getError().']';
			$type = 'error';
		}
		// Redirect
		$this->setRedirect('index.php?option=com_akeeba&view=profiles', $message, $type);
	}

	/**
	 * Imports an exported profile .json file
	 */
	public function import()
	{
		$this->_csrfProtection();

		$user = JFactory::getUser();
		if (!$user->authorise('akeeba.configure', 'com_akeeba')) {
			return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		// Get the user
		$user		= JFactory::getUser();

		// Get some data from the request
		$file		= F0FInput::getVar('importfile', '', $_FILES, 'array');

		if (isset($file['name']))
		{
			// Load the file data
			$data = JFile::read($file['tmp_name']);
			@unlink($file['tmp_name']);

			// JSON decode
			$data = json_decode($data, true);

			// Check for data validity
			$isValid = is_array($data) && !empty($data);
			if($isValid) {
				$isValid = $isValid && array_key_exists('description', $data);
			}
			if($isValid) {
				$isValid = $isValid && array_key_exists('configuration', $data);
			}
			if($isValid) {
				$isValid = $isValid && array_key_exists('filters', $data);
			}

			if(!$isValid) {
				$this->setRedirect('index.php?option=com_akeeba&view=profiles', JText::_('COM_AKEEBA_PROFILES_ERR_IMPORT_INVALID'), 'error');
				return false;
			}

			// Unset the id, if it exists
			if(array_key_exists('id', $data)) {
				unset($data['id']);
			}

			// Try saving the profile
			$result = $this->getThisModel()->getTable()->save($data);

			if($result) {
				$this->setRedirect('index.php?option=com_akeeba&view=profiles', JText::_('COM_AKEEBA_PROFILES_MSG_IMPORT_COMPLETE'));
			} else {
				$this->setRedirect('index.php?option=com_akeeba&view=profiles', JText::_('COM_AKEEBA_PROFILES_ERR_IMPORT_FAILED'), 'error');
			}
		}
		else
		{
			$this->setRedirect('index.php?option=com_akeeba&view=profiles', JText::_('MSG_UPLOAD_INVALID_REQUEST'), 'error');
			return false;
		}

	}
}