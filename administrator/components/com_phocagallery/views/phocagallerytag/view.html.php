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
jimport( 'joomla.application.component.view' );

class PhocaGalleryCpViewPhocaGalleryTag extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;
	protected $tmpl;

	public function display($tpl = null) {
		
		$this->state	= $this->get('State');
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		
		JHTML::stylesheet('media/com_phocagallery/css/administrator/phocagallery.css' );

		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		
		require_once JPATH_COMPONENT.'/helpers/phocagallerytags.php';
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= PhocaGalleryTagsHelper::getActions($this->state->get('filter.tag_id'), $this->item->id);
		//$paramsC 	= JComponentHelper::getParams('COM_PHOCADOWNLOAD');

		

		$text = $isNew ? JText::_( 'COM_PHOCAGALLERY_NEW' ) : JText::_('COM_PHOCAGALLERY_EDIT');
		JToolbarHelper ::title(   JText::_( 'COM_PHOCAGALLERY_TAG' ).': <small><small>[ ' . $text.' ]</small></small>' , 'tags.png');

		// If not checked out, can save the item.
		if (!$checkedOut && $canDo->get('core.edit')){
			JToolbarHelper ::apply('phocagallerytag.apply', 'JToolbar_APPLY');
			JToolbarHelper ::save('phocagallerytag.save', 'JToolbar_SAVE');
			JToolbarHelper ::addNew('phocagallerytag.save2new', 'JToolbar_SAVE_AND_NEW');
		}
	
		if (empty($this->item->id))  {
			JToolbarHelper ::cancel('phocagallerytag.cancel', 'JToolbar_CANCEL');
		}
		else {
			JToolbarHelper ::cancel('phocagallerytag.cancel', 'JToolbar_CLOSE');
		}

		JToolbarHelper ::divider();
		JToolbarHelper ::help( 'screen.phocagallery', true );
	}
}
?>
