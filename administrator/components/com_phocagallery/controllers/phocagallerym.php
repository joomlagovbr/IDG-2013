<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport('joomla.application.component.controllerform');

class PhocaGalleryCpControllerPhocaGalleryM extends JControllerForm
{
	protected	$option 		= 'com_phocagallery';
	protected	$view_list		= 'phocagallerym';
	protected	$layout			= 'edit';

	function __construct() {
		parent::__construct();

		$this->layout = 'edit';
		// Register Extra tasks
		//$this->registerTask( 'add'  , 	'eidt' );
		//$view = JFactory::getApplication()->input->get( 'view' );
		
	}

	//public function display() {
		//$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.'&layout='.$this->layout, false));
	//}
	
	

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
	
	/*
	function save() {
	JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$post				= JFactory::getApplication()->input->get('post');
		//$data			= JFactory::getApplication()->input->get('jform', array(0), 'post', 'array');
		$data = $app->input->post->get('jform', array(), 'array');
		
		if(isset($post['foldercid'])) {
			$data['foldercid']	= $post['foldercid'];
		}
		if(isset($post['cid'])) {
			$data['cid']		= $post['cid'];
		}
		
	
	
		
		$model 		= $this->getModel( 'phocagallerym' );

		if ($model->save($data)) {
			$msg = JText::_( 'COM_PHOCAGALLERY_SUCCESS_SAVE_MULTIPLE' );
		} else {
			$msg = JText::_( 'COM_PHOCAGALLERY_ERROR_SAVE_MULTIPLE' );
		}

		$link = 'index.php?option=com_phocagallery&view=phocagalleryimgs';
		$this->setRedirect($link, $msg);
	}*/
	

	
	
	
	function edit($key = NULL, $urlVar = NULL) {
		//JFactory::getApplication()->input->set( 'view', 'phocagallerym' );
		//JFactory::getApplication()->input->set( 'layout', 'Edit'  );
		//JFactory::getApplication()->input->set( 'hidemainmenu', 1 );
		//PhocaGalleryCpControllerPhocaGalleryM::display();
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.'&layout='.$this->layout, false));
	}
	
	function cancel($key = NULL) {
		// Checkin the Phoca Gallery
		//$model = $this->getModel( 'phocagallery' );
		//$model->checkin();

		$this->setRedirect( 'index.php?option=com_phocagallery&view=phocagalleryimgs' );
	}
}
?>
