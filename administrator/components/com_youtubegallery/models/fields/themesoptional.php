<?php
/**
 * YoutubeGallery Joomla! Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * YoutubeGallery Form Field class for the Youtube Gallery component
 */
class JFormFieldThemesOptional extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'themesoptional';
 
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions() 
        {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('id,themename');
                $query->from('#__youtubegallery_themes');
                $db->setQuery((string)$query);
                $messages = $db->loadObjectList();
                $options = array();
                if ($messages)
                {
                        $options[] = JHtml::_('select.option', 0, " - Select Theme");
                        foreach($messages as $message) 
                        {
                                $options[] = JHtml::_('select.option', $message->id, $message->themename);
                                
                        }
                }

                
                $options = array_merge(parent::getOptions(), $options);
                return $options;
        }
}
