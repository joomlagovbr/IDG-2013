<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.controllerform');

class PhocaGalleryCpControllerPhocaGalleryc extends JControllerForm
{
	protected	$option 		= 'com_phocagallery';


	function __construct($config=array()) {

		parent::__construct($config);

		// - - - - - - - - - -
		// Load external image - Picasa
		// - - - - - - - - - -
		// In case the "Load" button will be saved, two actions need to be done:
		// 1) Save (apply) the category data (we use Joomla! framework controller so we need to set save method, task = apply
		// 2) load external images - we need to identify "Load", but task is apply, so we use subtask = loadextimg
		$task = JFactory::getApplication()->input->get('task');
		if ((string)$task == 'loadextimgp') {
			if ($this->registerTask( 'loadextimgp', 'save')) {
				JFactory::getApplication()->input->set('task','apply');// we need to apply category data
				JFactory::getApplication()->input->set('subtask','loadextimgp');// we need to get info to run loading images
			}
		}
		if ((string)$task == 'loadextimgf') {
			if ($this->registerTask( 'loadextimgf', 'save')) {
				JFactory::getApplication()->input->set('task','apply');// we need to apply category data
				JFactory::getApplication()->input->set('subtask','loadextimgf');// we need to get info to run loading images
			}
		}
		$this->registerTask( 'uploadextimgf', 'uploadExtImgF');
		$this->registerTask( 'uploadextimgfpgn', 'uploadExtImgFPgn');

		if ((string)$task == 'loadextimgi') {
			if ($this->registerTask( 'loadextimgi', 'save')) {
				JFactory::getApplication()->input->set('task','apply');// we need to apply category data
				JFactory::getApplication()->input->set('subtask','loadextimgi');// we need to get info to run loading images
			}
		}

		// If there will be not used pagination (less than 20 images e.g.) data will be saved in model and images loaded - no redirection
		// If there will be used pagination, don't save the data again, redirect the site with "loadextimgpgn" id value
		$this->registerTask( 'loadextimgpgn', 'loadExtImgPgn');// data stored now we only loading other images
		$this->registerTask( 'loadextimgpgnfb', 'loadExtImgPgnFb');// data stored now we only loading other images
		// - - - - - - - - - -

	}

	function loadExtImgPgn() {

		$picStart	= JFactory::getApplication()->input->get( 'picstart', 0, 'get', 'int' );
		$idCat		= JFactory::getApplication()->input->get( 'id', 0, 'get', 'int' );
		if ($picStart > 0 && $idCat > 0) {
			$model		= $this->getModel();
			$message	= '';
			$loadImg	= $model->loadExtImages($idCat, '', $message);

			$this->setRedirect( 'index.php?option=com_phocagallery&task=phocagalleryc.edit&id='. $idCat, $message );
		}
	}

	function loadExtImgPgnFb() {

		$fbCount	= JFactory::getApplication()->input->get( 'fbcount', 0, 'get', 'int' );
		$idCat		= JFactory::getApplication()->input->get( 'id', 0, 'get', 'int' );
		if ($fbCount > 0 && $idCat > 0) {
			$model		= $this->getModel();
			$message	= '';
			$loadImg	= $model->loadExtImagesFb($idCat, '', $message);
			$this->setRedirect( 'index.php?option=com_phocagallery&task=phocagalleryc.edit&id='. $idCat, $message );
		}
	}
	function uploadExtImgF() {

		$idCat	= JFactory::getApplication()->input->get( 'id', 0, 'get', 'int' );
		$data	= JFactory::getApplication()->input->get('jform', array(), 'post', 'array');

		if (isset($data['extfbuid']) && $data['extfbuid'] > 0 && isset($data['extfbcatid']) && $data['extfbcatid'] != '' ) {
			if ($idCat > 0) {
				$model		= $this->getModel();
				$message	= '';
				$loadImg	= $model->uploadExtImagesFb($idCat, $data, $message);
			}
		} else {
			$message = JText::_('COM_PHOCAGALLERY_ERROR_FB_USER_ALBUM_NOT_SELECTED');
			$this->setRedirect( 'index.php?option=com_phocagallery&task=phocagalleryc.edit&id='. $idCat, $message, 'error' );
		}

	}

	function uploadExtImgFPgn() {

		$fbImg		= JFactory::getApplication()->input->get( 'fbimg', 0, 'get', 'int' );
		$idCat		= JFactory::getApplication()->input->get( 'id', 0, 'get', 'int' );
		if ($fbImg > 0 && $idCat > 0) {
			$model		= $this->getModel();
			$message	= '';
			$loadImg	= $model->uploadExtImagesFb($idCat, '', $message);
			$this->setRedirect( 'index.php?option=com_phocagallery&task=phocagalleryc.edit&id='. $idCat, $message );
		}
	}

	/*
	 * NOT USED IT IS A SUBTASK OF SAVE
	function loadExtImgI() {
		$idCat		= JFactory::getApplication()->input->get( 'id', 0, 'get', 'int' );
		if ($idCat > 0) {
			$model		= $this->getModel();
			$message	= '';
			$loadImg	= $model->loadExtImagesI($idCat, '', $message);

			$this->setRedirect( 'index.php?option=com_phocagallery&task=phocagalleryc.edit&id='. $idCat, $message );
		}
	}*/



	protected function allowAdd($data = array()) {
		$user		= JFactory::getUser();
		$allow		= null;
		$allow	= $user->authorise('core.create', 'com_phocagallery');
		if ($allow === null) {
			return parent::allowAdd($data);
		} else {
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'id') {
		$user		= JFactory::getUser();
		$allow		= null;
		$allow	= $user->authorise('core.edit', 'com_phocagallery');
		if ($allow === null) {
			return parent::allowEdit($data, $key);
		} else {
			return $allow;
		}
	}


	public function save($key = null, $urlVar = null)
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app		= JFactory::getApplication();
		$lang		= JFactory::getLanguage();
		$model		= $this->getModel();
		$table		= $model->getTable();
		//$data		= JFactory::getApplication()->input->get('jform', array(), 'post', 'array');
		$data		= $app->input->post->get('jform', array(), 'array');
		$checkin	= property_exists($table, 'checked_out');
		$context	= "$this->option.edit.$this->context";
		$task		= $this->getTask();

		// Determine the name of the primary key for the data.
		if (empty($key)) {
			$key = $table->getKeyName();
		}

		// The urlVar may be different from the primary key to avoid data collisions.
		if (empty($urlVar)) {
			$urlVar = $key;
		}

		$recordId	= JFactory::getApplication()->input->getInt($urlVar);

		$session	= JFactory::getSession();
		$registry	= $session->get('registry');

		if (!$this->checkEditId($context, $recordId)) {
			// Somehow the person just went to the form and saved it - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $recordId));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.$this->getRedirectToListAppend(), false));

			return false;
		}

		// Populate the row id from the session.
		$data[$key] = $recordId;

		// The save2copy task needs to be handled slightly differently.
		if ($task == 'save2copy') {
			// Check-in the original row.
			if ($checkin  && $model->checkin($data[$key]) === false) {
				// Check-in failed, go back to the item and display a notice.
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect('index.php?option='.$this->option.'&view='.$this->view_item.$this->getRedirectToItemAppend($recordId, $urlVar));

				return false;
			}

			// Reset the ID and then treat the request as for Apply.
			$data[$key]	= 0;
			$task		= 'apply';
		}

		// Access check.
		if (!$this->allowSave($data)) {
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.$this->getRedirectToListAppend(), false));

			return false;
		}

		// Validate the posted data.
		// Sometimes the form needs some posted data, such as for plugins and modules.
		$form = $model->getForm($data, false);

		if (!$form) {
			$app->enqueueMessage($model->getError(), 'error');

			return false;
		}

		// Test if the data is valid.
		$validData = $model->validate($form, $data);

		// Check for validation errors.
		if ($validData === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if (!empty($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState($context.'.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$this->getRedirectToItemAppend($recordId, $key), false));

			return false;
		}

		// Attempt to save the data.
		// PHOCAEDIT
		$extImgError = false;
		if (!$model->save($validData, $extImgError)) {
			// Save the data in the session.
			$app->setUserState($context.'.data', $validData);

			// Redirect back to the edit screen.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$this->getRedirectToItemAppend($recordId, $key), false));

			return false;
		}

		// Save succeeded, check-in the record.
		if ($checkin && $model->checkin($validData[$key]) === false) {
			// Save the data in the session.
			$app->setUserState($context.'.data', $validData);

			// Check-in failed, go back to the record and display a notice.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect('index.php?option='.$this->option.'&view='.$this->view_item.$this->getRedirectToItemAppend($recordId, $key));

			return false;
		}

		$this->setMessage(JText::_(($lang->hasKey($this->text_prefix.($recordId==0 && $app->isClient('site') ? '_SUBMIT' : '').'_SAVE_SUCCESS') ? $this->text_prefix : 'JLIB_APPLICATION') . ($recordId==0 && $app->isClient('site') ? '_SUBMIT' : '') . '_SAVE_SUCCESS'));

		// Redirect the user and adjust session state based on the chosen task.

		// Category Saved but not loaded images
		//PHOCAEDIT
		if ($extImgError) {
			// NOT MORE USED - app enque message used
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

		}
		//PHOCAEDIT

		switch ($task)
		{
			case 'apply':
				// Set the record data in the session.
				$recordId = $model->getState($this->context.'.id');
				$this->holdEditId($context, $recordId);
				$app->setUserState($context.'.data', null);

				// Redirect back to the edit screen.

				$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$this->getRedirectToItemAppend($recordId, $key), false));
				break;

			case 'save2new':
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context.'.data', null);

				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$this->getRedirectToItemAppend(null, $key), false));
				break;

			default:
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context.'.data', null);


				// Redirect to the list screen.
				$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.$this->getRedirectToListAppend(), false));
				break;
		}

		// Invoke the postSave method to allow for the child class to access the model.
		$this->postSaveHook($model, $validData);

		return true;
	}

	public function batch($model = null) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model	= $this->getModel('phocagalleryc', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_phocagallery&view=phocagallerycs'.$this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
}
?>
