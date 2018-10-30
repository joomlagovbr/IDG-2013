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
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
phocagalleryimport( 'phocagallery.facebook.fb' );
phocagalleryimport( 'phocagallery.facebook.fbsystem' );


class PhocaGalleryCpViewPhocaGalleryFb extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		JHTML::stylesheet('media/com_phocagallery/css/administrator/phocagallery.css' );
		
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');


		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		
		require_once JPATH_COMPONENT.'/helpers/phocagalleryfbs.php';
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$bar 		= JToolbar::getInstance('toolbar');
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= PhocaGalleryFbsHelper::getActions( $this->item->id);
		$paramsC 	= JComponentHelper::getParams('com_phocagallery');

		$text = $isNew ? JText::_( 'COM_PHOCAGALLERY_NEW' ) : JText::_('COM_PHOCAGALLERY_EDIT');
		JToolbarHelper ::title(   JText::_( 'COM_PHOCAGALLERY_FB_USER' ).': <small><small>[ ' . $text.' ]</small></small>' , 'user');

		// If not checked out, can save the item.
		if (!$checkedOut && $canDo->get('core.edit')){
			JToolbarHelper ::apply('phocagalleryfb.apply', 'JToolbar_APPLY');
			JToolbarHelper ::save('phocagalleryfb.save', 'JToolbar_SAVE');
		}

		JToolbarHelper ::cancel('phocagalleryfb.cancel', 'JToolbar_CLOSE');
		JToolbarHelper ::divider();
		JToolbarHelper ::help( 'screen.phocagallery', true );
	}

}

?>
