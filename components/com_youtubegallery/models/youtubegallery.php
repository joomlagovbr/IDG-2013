<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 4.4.0
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

jimport( 'joomla.application.menu' );


 
/**
 * YoutubeGallery Model
 */
class YoutubeGalleryModelYoutubeGallery extends JModelItem
{

        protected $youtubegallerycode;
	
        /**
         * Get the message
         * @return actual youtube galley code
         */
        public function getYoutubeGalleryCode() 
        {
		require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');
		
		$errorreporting=(bool)YouTubeGalleryMisc::getSettingValue('errorreporting');
		if($errorreporting)
			error_reporting(E_ALL);
		else
			error_reporting(0);
			
			
		jimport('joomla.version');
		$version = new JVersion();
		$JoomlaVersionRelease=$version->RELEASE;
		
		$result='';
		
		$app	= JFactory::getApplication();
		$params	= $app->getParams();
				 
                if (!isset($this->youtubegallerycode)) 
                {
						if(JRequest::getInt('listid'))
						{
								//Shadow Box
								$listid=(int)JRequest::getInt('listid');
								
								
								//Get Theme
								$m_themeid=(int)JRequest::getInt('mobilethemeid');
								if($m_themeid!=0)
								{
									if(YouTubeGalleryMisc::check_user_agent('mobile'))
										$themeid=$m_themeid;
									else
										$themeid=(int)JRequest::getInt('themeid');
								}
								else
									$themeid=(int)JRequest::getInt('themeid');
						}
						else
						{
								$listid=(int)$params->get( 'listid' );
								//Get Theme
								$m_themeid=(int)$params->get( 'mobilethemeid' );
								if($m_themeid!=0)
								{
									if(YouTubeGalleryMisc::check_user_agent('mobile'))
										$themeid=$m_themeid;
									else
										$themeid=(int)$params->get( 'themeid' );
								}
								else
									$themeid=(int)$params->get( 'themeid' );
						}
						
                        
                        if($listid!=0 and $themeid!=0)
                        {
								$videoid=JRequest::getCmd('videoid');
						
								
								require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'render.php');

								$misc=new YouTubeGalleryMisc;
                       
								if(!$misc->getVideoListTableRow($listid))
										return '<p>No video found</p>';
	
								if(!$misc->getThemeTableRow($themeid))
										return  '<p>No video found</p>';

								$renderer= new YouTubeGalleryRenderer;
								
								$total_number_of_rows=0;
								
								$misc->update_playlist();
								
								
								
								if($misc->theme_row->playvideo==1 and $videoid!='')
										$misc->theme_row->autoplay=1;
								
								$videoid_new=$videoid;
								$videolist=$misc->getVideoList_FromCache_From_Table($videoid_new,$total_number_of_rows);

								if($videoid=='')
								{
									if($videoid_new!='')
										JRequest::setVar('videoid',$videoid_new);
				
									if($misc->theme_row->playvideo==1 and $videoid_new!='')
										$videoid=$videoid_new;
								}
										
								$gallerymodule=$renderer->render(
										$videolist,
										$misc->videolist_row,
										$misc->theme_row,
										$total_number_of_rows,
										$videoid
								);
								
                               
                                $align=$params->get( 'align' );
								
								
                                switch($align)
                                {
                                	case 'left' :
                                		$this->youtubegallerycode = '<div style="float:left;">'.$gallerymodule.'</div>';
                                		break;
        	
                                	case 'center' :
										if(((int)$misc->theme_row->width)>0)
												$this->youtubegallerycode = '<div style="width:'.$misc->theme_row->width.'px;margin: 0 auto;">'.$gallerymodule.'</div>';
										else
												$this->youtubegallerycode = $gallerymodule;
										
                                		break;
        	
                                	case 'right' :
                                		$this->youtubegallerycode = '<div style="float:right;">'.$gallerymodule.'</div>';
                                		break;
	
                                	default :
                                		$this->youtubegallerycode = $gallerymodule;
                                		break;
	
                                }

                        
                        } //if($listid!=0 and $themeid!=0)
						elseif($listid==0 and $themeid!=0)
								$this->youtubegallerycode='<p>Youtube Gallery: List not selected.</p>';
						elseif($themeid==0 and $listid!=0)
								$this->youtubegallerycode='<p>Youtube Gallery: Theme not selected.</p>';
						else
								$this->youtubegallerycode='<p>Youtube Gallery: List and Theme not selected.</p>';
                        
                }
				
				
				
				if($params->get( 'allowcontentplugins' ))
				{
								$o = new stdClass();
								$o->text=$this->youtubegallerycode;
							
								$dispatcher	= JDispatcher::getInstance();
							
								JPluginHelper::importPlugin('content');
							
								$r = $dispatcher->trigger('onContentPrepare', array ('com_content.article', &$o, &$params_, 0));
							
								$this->youtubegallerycode=$o->text;
				}
				
				$result.=$this->youtubegallerycode;
				
				
                return $result;
        }
}
