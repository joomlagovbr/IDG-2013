<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 3.5.9
 * @author DesignCompass corp< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');



 
/**
 * Youtube Category Form
 */
class YoutubeGalleryViewCategoryForm extends JViewLegacy
{
        /**
         * display method of Youtube Gallery view
         * @return void
         */
        public function display($tpl = null) 
        {
                //echo 'ddd';
                // get the Data
                //echo 'dddzz';
                $form = $this->get('Form');
                //echo 'dddb';
                $item = $this->get('Item');
                //echo 'dddc';
                $script = $this->get('Script');

                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                       // echo 'ddd1';
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                
                //echo 'ddda';
                // Assign the Data
                $this->form = $form;
                $this->item = $item;
                $this->script = $script;

 
                // Set the toolbar
                $this->addToolBar();
 
                // Display the template
                //echo 'ddd2';
                parent::display($tpl);
                
                // Set the document
                //$this->setDocument();

        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                JRequest::setVar('hidemainmenu', true);
                $isNew = ($this->item->id == 0);
                JToolBarHelper::title($isNew ? JText::_('COM_YOUTUBEGALLERY_NEW_CATEGORY') : JText::_('COM_YOUTUBEGALLERY_EDIT_CATEGORY'));
                JToolBarHelper::apply('categoryform.apply');
                JToolBarHelper::save('categoryform.save');
                JToolBarHelper::cancel('categoryform.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
        }
        
        /**
        * Method to set up the document properties
        *
        * @return void
        */
        protected function setDocument() 
        {
                $isNew = ($this->item->id < 1);
                $document = JFactory::getDocument();
                $document->setTitle($isNew ? JText::_('COM_YOUTUBEGALLERY_NEW_CATEGORY') : JText::_('COM_YOUTUBEGALLERY_EDIT_CATEGORY'));
                $document->addScript(JURI::root() . $this->script);
                $document->addScript(JURI::root() . "/administrator/components/com_youtubegallery/views/categoryform/submitbutton.js");
                JText::script('COM_YOUTUBEGALLERY_CATEGORYFORM_ERROR_UNACCEPTABLE');
        }
}


?>