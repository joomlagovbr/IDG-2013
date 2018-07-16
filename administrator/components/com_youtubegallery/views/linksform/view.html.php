<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
        
// import Joomla view library
jimport('joomla.application.component.view');


jimport('joomla.version');
$version = new JVersion();
$JoomlaVersionRelease=$version->RELEASE;

if($JoomlaVersionRelease>=3.0)
{
        //joomla 3.x
 
/**
 * Youtube Gallery - Links Form View
 */
class YoutubeGalleryViewLinksForm extends JViewLegacy
{
        /**
         * display method of Youtube Gallery view
         * @return void
         */
        public function display($tpl = null) 
        {

                $form = $this->get('Form');

                $item = $this->get('Item');

                $script = $this->get('Script');

                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JFactory::getApplication()->enqueueMessage( implode('<br />', $errors), 'error');
                        return false;
                }
                
                // Assign the Data
                $this->form = $form;
                $this->item = $item;
                $this->script = $script;

                // Set the toolbar
                $this->addToolBar();
 
                // Display the template
                parent::display($tpl);
                
                // Set the document
        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                $jinput = JFactory::getApplication()->input;
$jinput->get->set('hidemainmenu',true);

                $isNew = ($this->item->id == 0);
                JToolBarHelper::title($isNew ? JText::_('COM_YOUTUBEGALLERY_LINKSFORM_NEW') : JText::_('COM_YOUTUBEGALLERY_LINKSFORM_EDIT'));
                JToolBarHelper::apply('linksform.apply');
                JToolBarHelper::save('linksform.save');
                JToolBarHelper::cancel('linksform.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
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
                $document->setTitle($isNew ? JText::_('COM_YOUTUBEGALLERY_LINKSFORM_NEW') : JText::_('COM_YOUTUBEGALLERY_LINKSFORM_EDIT'));
                $document->addScript(JURI::root() . $this->script);
                $document->addScript(JURI::root() . "/administrator/components/com_youtubegallery/views/linksform/submitbutton.js");
                JText::script('COM_YOUTUBEGALLERY_FORMEDIT_ERROR_UNACCEPTABLE');
        }
}//class

}
else
{
        // for joomla 2.5
        /**
 * Youtube Gallery - Links Form View
 */
class YoutubeGalleryViewLinksForm extends JView
{
        /**
         * display method of Youtube Gallery view
         * @return void
         */
        public function display($tpl = null) 
        {

                $form = $this->get('Form');

                $item = $this->get('Item');

                $script = $this->get('Script');

                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JFactory::getApplication()->enqueueMessage( implode('<br />', $errors), 'error');
                        return false;
                }
                
                // Assign the Data
                $this->form = $form;
                $this->item = $item;
                $this->script = $script;

                // Set the toolbar
                $this->addToolBar();
 
                // Display the template
                parent::display($tpl);
                
                // Set the document
        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                $jinput = JFactory::getApplication()->input;
$jinput->get->set('hidemainmenu',true);

                $isNew = ($this->item->id == 0);
                JToolBarHelper::title($isNew ? JText::_('COM_YOUTUBEGALLERY_LINKSFORM_NEW') : JText::_('COM_YOUTUBEGALLERY_LINKSFORM_EDIT'));
                JToolBarHelper::apply('linksform.apply');
                JToolBarHelper::save('linksform.save');
                JToolBarHelper::cancel('linksform.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
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
                $document->setTitle($isNew ? JText::_('COM_YOUTUBEGALLERY_LINKSFORM_NEW') : JText::_('COM_YOUTUBEGALLERY_LINKSFORM_EDIT'));
                $document->addScript(JURI::root() . $this->script);
                $document->addScript(JURI::root() . "/administrator/components/com_youtubegallery/views/linksform/submitbutton.js");
                JText::script('COM_YOUTUBEGALLERY_FORMEDIT_ERROR_UNACCEPTABLE');
        }
}//class

}


?>