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
class JFormFieldCategoryParent extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'CategoryParent';
 
        /**
         * Method to get a list of options for a list input.
         *
         * @return array An array of JHtml options.
         */
        protected function getOptions() 
        {
                $current_category_id = JFactory::getApplication()->input->getInt('id', '0');
                
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('id, categoryname, parentid');
                $query->from('#__youtubegallery_categories');
                $db->setQuery((string)$query);
                $messages = $db->loadObjectList();
                if (!$db->query())    die( $db->stderr());
                
                $options = array();
                
                $options[] = JHtml::_('select.option', 0, JText::_( 'COM_YOUTUBEGALLERY_SELECT_CATEGORYROOT' ));
                
                $children=$this->getAllChildren($current_category_id);
                
                if ($messages)
                {
                        foreach($messages as $message) 
                        {
                                if($current_category_id==0)
                                        $options[] = JHtml::_('select.option', $message->id, $message->categoryname);
                                else
                                {
                                        if($message->id!=$current_category_id and $message->parentid!=$current_category_id and !in_array($message->id,$children))
                                                $options[] = JHtml::_('select.option', $message->id, $message->categoryname);
                                }
                                
                        }
                }
                $options = array_merge(parent::getOptions(), $options);
                return $options;
        }
        
        protected function getAllChildren($parentid)
        {
                $children=array();
                if($parentid==0)
                        return $children;
                
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('id, parentid');
                $query->from('#__youtubegallery_categories');
                $query->where('parentid='.$parentid);
                $db->setQuery((string)$query);
                if (!$db->query())    die( $db->stderr());
                
                $rows = $db->loadObjectList();
                foreach($rows as $row)
                {
                        $children[]=$row->id;
                        $grand_children=$this->getAllChildren($row->id);
                        if(count($grand_children)>0)
                                $children=array_merge($children,$grand_children);
                }
                return $children;
        }
        
}
