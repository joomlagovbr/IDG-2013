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
	/*	
        public function getTable($type = 'YoutubeGallery', $prefix = 'YoutubeGalleryTable', $config = array()) 
        {
                return JTable::getInstance($type, $prefix, $config);
        }
	*/
        /**
         * Get the message
         * @return actual youtube galley code
         */
        public function getYoutubeGalleryCode() 
        {
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
								//alteracao projeto portal padrao
								$listid=JRequest::getInt('listid');
								$themeid=JRequest::getInt('themeid');
								//fim alteracao projeto portal padrao	
						}
						else
						{
								$listid=(int)$params->get( 'listid' );
								$themeid=(int)$params->get( 'themeid' );
						}
						
                        
                        if($listid!=0 and $themeid!=0)
                        {
								$videoid=JRequest::getVar('videoid');
						
								require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php'); //alteracao projeto portal padrao
								require_once(JPATH_SITE.DS.'templates'.DS.'padraogoverno01'.DS.'html'.DS.'mod_youtubegallery'.DS.'_render.php');
								// require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'render.php');
								//fim alteracao projeto portal padrao
								$misc=new YouTubeGalleryMisc;
                       
								if(!$misc->getVideoListTableRow($listid)) //alteracao projeto portal padrao								
										return '<p>Nenhum v&iacute;deo encontrado.</p>';
								//fim alteracao projeto portal padrao
	
								if(!$misc->getThemeTableRow($themeid)) //alteracao projeto portal padrao
										return '<p>Nenhum v&iacute;deo encontrado.</p>';										
								//fim alteracao projeto portal padrao
								//alteracao projeto portal padrao
								// $renderer= new YouTubeGalleryRenderer;
								$renderer= new YouTubeGalleryRendererPortal;
								//fim alteracao projeto portal padrao
								$total_number_of_rows=0;
								
								$misc->update_playlist();
								
								//if($misc->theme_row->openinnewwindow==4)
								//		$videoid=''; //Hot Video Switch
								//else
								$videoid=JRequest::getVar('videoid');
								
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
								//alteracao projeto portal padrao
								$this->youtubegallerycode = $gallerymodule;
								/*
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
								*/
                        		//fim alteracao projeto portal padrao
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
