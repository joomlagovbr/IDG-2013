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
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * ThemeList Model
 */
class YoutubeGalleryModelThemeList extends JModelList
{
        /**
         * Method to build an SQL query to load the list data.
         *
         * @return string  An SQL query
         */
        protected function getListQuery()
        {
				$where=array();
				
				$context= 'com_youtubegallery.themelist.';
                $mainframe = JFactory::getApplication();
                $search			= $mainframe->getUserStateFromRequest($context."search",'search','',	'string' );
				$search			=strtolower(trim(preg_replace("/[^a-zA-Z0-9 ]/", "", $search)));
				
                
				if($search!='')
						$where[]='instr(themename,"'.$search.'")';
				
                // Create a new query object.         
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                // Select some fields
				//,categoryname, count(#__youtubegallery_videos.id) AS number_of_videos
                $query->select('*, #__youtubegallery_themes.id AS id, themename');
                // From the Youtube Gallery table
                $query->from('#__youtubegallery_themes');
				
			
				$query->group('#__youtubegallery_themes.id');
				
				if(count($where)>0)
						$query->where(implode(' AND ',$where));
				
                return $query;
        }
    
        
       	function ConfirmRemove()
        {
				$jinput = JFactory::getApplication()->input;
$jinput->get->set('hidemainmenu',true);

				JToolBarHelper::title(JText::_( 'COM_YOUTUBEGALLERY_DELETE_THEMES_S' ));
		
				$cancellink='index.php?option=com_youtubegallery&view=themelist';
		
				$cids = JFactory::getApplication()->input->getVar( 'cid', array(0), 'post', 'array' );
		
				echo '<h1>'.JText::_( 'COM_YOUTUBEGALLERY_DELETE_THEMES_S' ).'</h1>';
		
				if(count($cids)==0)
						return false;
		
				//Get Table Name
		
				if (count( $cids ))
				{
						
						
						echo '<ul>';
						
						$complete_cids=$cids;
						foreach($cids as $id)
						{
								$row=$this->getThemeItem($id);
								echo '<li>'.$row->themename.'</li>';
						}
						
						echo '</ul>';
						
						if(count($complete_cids)>1)
								echo '<p>Total '.count($complete_cids).' Themes.</p>';
						
						echo '<br/><br/><p style="font-weight:bold;"><a href="'.$cancellink.'">'.JText::_( 'COM_YOUTUBEGALLERY_NO_CANCEL' ).'</a></p>
            <form action="index.php?option=com_youtubegallery" method="post" >
            <input type="hidden" name="task" value="themelist.remove_confirmed" />
';
						$i=0;
						foreach($complete_cids as $cid)
						        echo '<input type="hidden" id="cb'.$i.'" name="cid[]" value="'.$cid.'">';
           
						echo '
            <input type="submit" value="'.JText::_( 'COM_YOUTUBEGALLERY_YES_DELETE' ).'" class="button" />
            </form>
';

						
				}
				else
						echo '<p><a href="'.$cancellink.'">'.JText::_( 'COM_YOUTUBEGALLERY_NO_ITEMS_SELECTED' ).'</a></p>';
		
        }
		
		protected function getThemeItem($id)
		{
				$db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('id, themename');
                $query->from('#__youtubegallery_themes');
				$query->where('id='.$id);
                $db->setQuery((string)$query);
                $rows = $db->loadObjectList();
                if (!$db->query())    die( $db->stderr());
				
				if(count($rows)==0)
						return array();
				return $rows[0];
		}
		
		public function getTable($type = 'ThemeList', $prefix = 'YoutubeGalleryTable', $config = array()) 
        {
                return JTable::getInstance($type, $prefix, $config);
        }
		
		function copyItem($cid, &$msg)
		{


				$item = $this->getTable('themes');
				
	    
		
				foreach( $cid as $id )
				{
			
		
						$item->load( $id );
						//if($item->readonly)
						//{
						//		$msg='To copy imported Themes upgrade to PRO version';
						//		return false;
						//}
						
						$item->id 	= NULL;
		
						$old_title=$item->themename;
						$new_title='Copy of '.$old_title;
		
						$item->themename 	= $new_title;
			
	
		
						if (!$item->check()) {
							return false;
						}
		
						if (!$item->store()) {
							return false;
						}
						$item->checkin();
							
				}//foreach( $cid as $id )
		
				return true;
		}//function copyItem($cid)
    
      
}
