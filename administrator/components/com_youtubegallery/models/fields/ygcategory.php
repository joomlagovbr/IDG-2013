<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
//jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * YoutubeGallery Form Field class for the Youtube Gallery component
 */
class JFormFieldYGCategory extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        public $type = 'YGCategory';
 
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions() 
        {
                //echo 'zuka';
                //die;
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('id,categoryname');
                $query->from('#__youtubegallery_categories');
                $db->setQuery((string)$query);
                $messages = $db->loadObjectList();
                
                $options = array();
                
                $options[] = JHtml::_('select.option', 0, JText::_( 'COM_YOUTUBEGALLERY_SELECT_CATEGORY' ));
                
                if ($messages)
                {
                        foreach($messages as $message) 
                        {
                                $options[] = JHtml::_('select.option', $message->id, $message->categoryname);
                                
                        }
                }
                $options = array_merge(parent::getOptions(), $options);
                
                return $options;
        }
}
