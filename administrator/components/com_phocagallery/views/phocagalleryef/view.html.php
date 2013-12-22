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


class PhocaGalleryCpViewPhocaGalleryEf extends JViewLegacy
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
		$this->ftp		= JClientHelper::setCredentialsFromRequest('ftp');
		$this->tmpl		= new StdClass;
		$model 			= $this->getModel();
		
		// Set CSS for codemirror
		JFactory::getApplication()->setUserState('editor.source.syntax', 'css');
		
		
		// New or edit
		if (!$this->form->getValue('id') || $this->form->getValue('id') == 0) {
			$this->form->setValue('source', null, '');
			$this->form->setValue('type', null, 2);
			$this->tmpl->suffixtype = JText::_('COM_PHOCAGALERY_WILL_BE_CREATED_FROM_TITLE');
		
		} else {
			$this->source	= $model->getSource($this->form->getValue('id'), $this->form->getValue('filename'), $this->form->getValue('type'));
			$this->form->setValue('source', null, $this->source->source);
			$this->tmpl->suffixtype = '';
		}
		
		// Only help input form field - to display Main instead of 1 and Custom instead of 2
		if ($this->form->getValue('type') == 1) { 
			$this->form->setValue('typeoutput', null, JText::_('COM_PHOCAGALLERY_MAIN_CSS'));
		} else {
			$this->form->setValue('typeoutput', null, JText::_('COM_PHOCAGALLERY_CUSTOM_CSS'));
		}
		
		
		
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'phocagalleryefs.php';
		JRequest::setVar('hidemainmenu', true);
		$bar 		= JToolBar::getInstance('toolbar');
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= PhocaGalleryEfsHelper::getActions($this->state->get('filter.category_id'), $this->item->id);
		$paramsC 	= JComponentHelper::getParams('com_phocagallery');

		$text = $isNew ? JText::_( 'COM_PHOCAGALLERY_NEW' ) : JText::_('COM_PHOCAGALLERY_EDIT');
		JToolBarHelper::title(   JText::_( 'COM_PHOCAGALLERY_STYLE' ).': <small><small>[ ' . $text.' ]</small></small>' , 'eye');

		// If not checked out, can save the item.
		if (!$checkedOut && $canDo->get('core.edit')){
			JToolBarHelper::apply('phocagalleryef.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('phocagalleryef.save', 'JTOOLBAR_SAVE');
		}

		JToolBarHelper::cancel('phocagalleryef.cancel', 'JTOOLBAR_CLOSE');
		JToolBarHelper::divider();
		JToolBarHelper::help( 'screen.phocagallery', true );
	}

}
?>
