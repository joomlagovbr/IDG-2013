<?php
/**
 * YoutubeGallery Joomla! Native Component
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
 * Youtube Gallery Theme Form View
 */
class YoutubeGalleryViewThemeForm extends JViewLegacy
{
        /**
         * display method of Youtube Gallery view
         * @return void
         */
        public function display($tpl = null) 
        {
                // get the Data
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
                
        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                $jinput = JFactory::getApplication()->input;
$jinput->get->set('hidemainmenu',true);

                $isNew = ($this->item->id == 0);
                JToolBarHelper::title($isNew ? JText::_('COM_YOUTUBEGALLERY_THEME_NEW') : JText::_('COM_YOUTUBEGALLERY_THEME_EDIT'));
                JToolBarHelper::apply('themeform.apply');
                JToolBarHelper::save('themeform.save');
                JToolBarHelper::cancel('themeform.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
        }
        
}//class
}
else
{
        /**
         * for jomla 2.5
        * Youtube Gallery Theme Form View
        */
class YoutubeGalleryViewThemeForm extends JView
{
        /**
         * display method of Youtube Gallery view
         * @return void
         */
        public function display($tpl = null) 
        {
                // get the Data
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
                
        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                $jinput = JFactory::getApplication()->input;
$jinput->get->set('hidemainmenu',true);

                $isNew = ($this->item->id == 0);
                JToolBarHelper::title($isNew ? JText::_('COM_YOUTUBEGALLERY_THEME_NEW') : JText::_('COM_YOUTUBEGALLERY_THEME_EDIT'));
                JToolBarHelper::apply('themeform.apply');
                JToolBarHelper::save('themeform.save');
                JToolBarHelper::cancel('themeform.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
        }
        
}//class
}


?>