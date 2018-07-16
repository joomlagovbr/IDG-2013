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
 * Categories Model
 */
class YoutubeGalleryModelCategories extends JModelList
{
        /**
         * Method to build an SQL query to load the list data.
         *
         * @return string  An SQL query
         */
        protected function getListQuery()
        {
                // Create a new query object.         
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                // Select some fields
                $query->select('id,categoryname');
                // From the Youtube Gallery table
                $query->from('#__youtubegallery_categories');
                return $query;
        }
		
	
		
		function &getItems()
		{
				$mainframe = JFactory::getApplication();

				static $items;

				if (isset($items)) {
					return $items;
				}	

				$db = $this->getDBO();

				$context= 'com_youtubegallery.list.';

				$filter_order			= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'id',	'int' );//'m.ordering'
				$filter_order_Dir		= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'ASC',			'word' );
		
				$filter_rootparent		= $mainframe->getUserStateFromRequest( $context.'filter_rootparent','filter_rootparent','','int' );
		
				$limit				= $mainframe->getUserStateFromRequest( 'global.list.limit',							'limit',			$mainframe->getCfg( 'list_limit' ),	'int' );
				$limitstart			= $mainframe->getUserStateFromRequest( $context.'limitstart',		'limitstart',		0,				'int' );
				$levellimit			= $mainframe->getUserStateFromRequest( $context.'levellimit',		'levellimit',		10,				'int' );
				$search				= $mainframe->getUserStateFromRequest( $context.'search',			'search',			'',				'string' );
				$search				= JString::strtolower( $search );

		
				$where = array();
		
				// just in case filter_order get's messed up
				if ($filter_order) {
					$orderby = ' ORDER BY '.$filter_order .' '. $filter_order_Dir .', parentid';//, m.ordering
				} else {
					$orderby = ' ORDER BY parentid';//, m.ordering
				}

				// select the records
				// note, since this is a tree we have to do the limits code-side
				if ($search)
				{
					$query = 'SELECT m.id' .
					' FROM #__youtubegallery_categories AS m' .
					' WHERE ' .
					' LOWER( m.categoryname ) LIKE '.$db->Quote($search);
					
					//AND
					//$and;
					$db->setQuery( $query );
					$search_rows = $db->loadResultArray();
				}
		
		
				if($filter_rootparent)
					$where[]=' ( id='.$filter_rootparent.' OR parentid!=0 )';

				$WhereStr='';
				if(count($where)>0)
				{
					$WhereStr=' WHERE '.implode(' AND ',$where);//$WhereStr;
				}
		
				$query = 'SELECT m.* ' .
				' FROM #__youtubegallery_categories AS m' .
				$WhereStr .
				$orderby;
		
					
				$db->setQuery( $query );
				if (!$db->query())    die( $db->stderr());
		
				$rows = $db->loadObjectList();
				$children = array();
				// first pass - collect children
				foreach ($rows as $v )
				{
					$pt = $v->parentid;
					$list = @$children[$pt] ? $children[$pt] : array();
					array_push( $list, $v );
					$children[$pt] = $list;
				}

				// second pass - get an indent list of the items
		
				//$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, max( 0, $levellimit-1 ) );
				$list = $this->treerecurse(0, '', array(), $children, max( 0, $levellimit-1 ) );
		
				// eventually only pick out the searched items.
				if ($search) {
					$list1 = array();

					foreach ($search_rows as $sid )
					{
						foreach ($list as $item)
						{
							if ($item->id == $sid) {
								$list1[] = $item;
							}
						}
					}
					// replace full list with found items
					$list = $list1;
				}

				$total = count( $list );
		
				jimport('joomla.html.pagination');
				$this->_pagination = new JPagination( $total, $limitstart, $limit );

				// slice out elements based on limits
				$list = array_slice( $list, $this->_pagination->limitstart, $this->_pagination->limit );
		
				$items = $list;

		return $items;
		}

		
		
		function treerecurse($id, $indent, $list, &$children, $maxlevel=9999, $level=0, $type=1)
		{
	
				if (@$children[$id] && $level <= $maxlevel)
				{
				        foreach ($children[$id] as $v)
				        {
				                $id = $v->id;
		
				                if ($type) {
				                        $pre    = '<sup>|_</sup>&nbsp;';
				                        $spacer = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				                } else {
				                        $pre    = '- ';
				                        $spacer = '&nbsp;&nbsp;';
				                }
		
				                if ($v->parentid == 0) {
				                        $txt    = $v->categoryname;
				                } else {
				                        $txt    = $pre . $v->categoryname;
				                }
				                $pt = $v->parentid;
				                $list[$id] = $v;
				                $list[$id]->treename = "$indent$txt";
				                $list[$id]->children = count(@$children[$id]);
				                $list = $this->treerecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level+1, $type);
		                }
		        }
		        return $list;
		}
		
    
        
       	function ConfirmRemove()
        {
				$jinput = JFactory::getApplication()->input;
$jinput->get->set('hidemainmenu',true);

				JToolBarHelper::title(JText::_( 'COM_YOUTUBEGALLERY_DELETE_CATEGORY_S' ));
		
				$cancellink='index.php?option=com_youtubegallery&view=categories';
		
				$cids = JFactory::getApplication()->input->getVar( 'cid', array(0), 'post', 'array' );
				
				
				echo '<h1>'.JText::_( 'COM_YOUTUBEGALLERY_DELETE_CATEGORY_S' ).'</h1>';
				
		
				if(count($cids)==0)
						return false;
		
				//Get Table Name
				$videoLists_found=false;
		
				if (count( $cids ))
				{
						echo '<ul>';
						
						$complete_cids=$cids;
						foreach($cids as $id)
						{
								$row=$this->getGategoryItem($id);
								echo '<li>'.$row->categoryname;
								
								$videolists=$this->getAllVideoList($id);
								if(count($videolists)>0)
								{
										echo '<p style="color:red;">There is Video Lists in this category:<ul>';
										foreach($videolists as $vl)
												echo '<li style="color:red;">'.$vl->listname.'</li>';
												
										echo '</ul></p>';
										$videoLists_found=true;
								}
								
								$children=$this->getAllChildren($id);
								if(count($children)!=0)
								{
										echo '<p style="font-weight:bold;">And all it\'s children:<ul>';
										foreach($children as $c_id)
										{
												$c_row=$this->getGategoryItem($c_id);
												echo '<li>'.$c_row->categoryname;
												
												$videolists=$this->getAllVideoList($c_id);
												if(count($videolists)>0)
												{
														echo '<p style="color:red;">There is Video Lists in this category:<ul>';
														foreach($videolists as $vl)
																echo '<li style="color:red;">'.$vl->listname.'</li>';
														
														echo '</ul></p>';
														$videoLists_found=true;
												}
												
												echo '</li>';
												
										}
										$complete_cids=array_merge($complete_cids,$children);
										echo '</ul></p>';
								}
								
								
								echo '</li>';
						}
						
						echo '</ul>';
						
						if(count($complete_cids)>1)
								echo '<p>Total '.count($complete_cids).' Categories.</p>';
						
						if($videoLists_found)
						{
								echo '<p style="font-weight:bold;"><a href="'.$cancellink.'">Cancel</a></p>';
						}
						else
						{
						
								echo '<br/><br/><p style="font-weight:bold;"><a href="'.$cancellink.'">'.JText::_( 'COM_YOUTUBEGALLERY_NO_CANCEL' ).'</a></p>
            <form action="index.php?option=com_youtubegallery" method="post" >
            <input type="hidden" name="task" value="categories.remove_confirmed" />
';
								$i=0;
								foreach($complete_cids as $cid)
								        echo '<input type="hidden" id="cb'.$i.'" name="cid[]" value="'.$cid.'">';
            
								echo '
            <input type="submit" value="'.JText::_( 'COM_YOUTUBEGALLERY_YES_DELETE' ).'" class="button" />
            </form>
';
						}
				}
				else
						echo '<p><a href="'.$cancellink.'">'.JText::_( 'COM_YOUTUBEGALLERY_NO_CATEGORIES_SELECTED' ).'</a></p>';
				
		
        }
		
		protected function getAllVideoList($catid)
		{
				$db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('id, listname, catid');
                $query->from('#__youtubegallery_videolists');
				$query->where('catid='.$catid);
                $db->setQuery((string)$query);
                $rows = $db->loadObjectList();
                if (!$db->query())    die( $db->stderr());
				
				if(count($rows)==0)
						return array();
						
				return $rows;
		}
		
		protected function getGategoryItem($id)
		{
				$db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('id, categoryname, parentid');
                $query->from('#__youtubegallery_categories');
				$query->where('id='.$id);
                $db->setQuery((string)$query);
                $rows = $db->loadObjectList();
                if (!$db->query())    die( $db->stderr());
				
				if(count($rows)==0)
						return array();
				return $rows[0];
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
		
		
		
		public function getTable($type = 'Categories', $prefix = 'YoutubeGalleryTable', $config = array()) 
        {
                return JTable::getInstance($type, $prefix, $config);
        }
		
		function copyItem($cid)
		{


				$item = $this->getTable('categories');
				
	    
		
				foreach( $cid as $id )
				{
			
		
						$item->load( $id );
						$item->id 	= NULL;
		
						$old_title=$item->categoryname;
						$new_title='Copy of '.$old_title;
		
						$item->categoryname = $new_title;
			
	
		
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
