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


class PhocaGalleryCpViewPhocaGalleryCoImg extends JViewLegacy
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
		
		$itemInfo	= $this->getInfoValues();

		if (isset($itemInfo->image_title)) {
			$this->form->setValue('imagetitle', '', $itemInfo->image_title);
		}
		if (isset($itemInfo->category_title)) {
			$this->form->setValue('cattitle', '', $itemInfo->category_title);
		} 
		if (isset($itemInfo->username) && isset($itemInfo->usernameno)) {
			$this->form->setValue('usertitle', '', $itemInfo->usernameno . ' ('.$itemInfo->username.')');
		}

	
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function getInfoValues() {
		
		if (isset($this->item->id)) {
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);

			// Select the required fields from the table.
			$query->select('a.id');
			
			$query->from('`#__phocagallery_img_comments` AS a');
		
			$query->select('i.title AS image_title, i.id AS image_id');
			$query->join('LEFT', '#__phocagallery AS i ON i.id = a.imgid');

			$query->select('c.title AS category_title, c.id AS category_id');
			$query->join('LEFT', '#__phocagallery_categories AS c ON c.id = i.catid');
			
			$query->select('ua.username AS username, ua.name AS usernameno');
			$query->join('LEFT', '#__users AS ua ON ua.id=a.userid');

			$query->where('a.id = ' . (int) $this->item->id);
	
			$db->setQuery($query);
			$itemInfo = $db->loadObject();
			
			if ($db->getErrorNum()) {
				JError::raiseWarning(500, $db->getErrorMsg());
			}
			
			return $itemInfo;
		}
	}

	protected function addToolbar() {
		
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'phocagallerycoimgs.php';
		JRequest::setVar('hidemainmenu', true);
		$bar 		= JToolBar::getInstance('toolbar');
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= PhocaGalleryCoImgsHelper::getActions($this->state->get('filter.category_id'), $this->item->id);
		$paramsC 	= JComponentHelper::getParams('com_phocagallery');

		$text = $isNew ? JText::_( 'COM_PHOCAGALLERY_NEW' ) : JText::_('COM_PHOCAGALLERY_EDIT');
		JToolBarHelper::title(   JText::_( 'COM_PHOCAGALLERY_IMAGE_COMMENT' ).': <small><small>[ ' . $text.' ]</small></small>' , 'comment');

		// If not checked out, can save the item.
		if (!$checkedOut && $canDo->get('core.edit')){
			JToolBarHelper::apply('phocagallerycoimg.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('phocagallerycoimg.save', 'JTOOLBAR_SAVE');
		}

		JToolBarHelper::cancel('phocagallerycoimg.cancel', 'JTOOLBAR_CLOSE');
		JToolBarHelper::divider();
		JToolBarHelper::help( 'screen.phocagallery', true );
	}

}
?>
