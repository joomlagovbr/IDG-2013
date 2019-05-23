<?php
/**
 * @package   Phoca Gallery
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaGalleryRenderProcess
{	
	//public $stopThumbnailsCreating; // display the posibility (link) to disable the thumbnails creating
	//public $headerAdded;// HTML Header was added by Stop Thumbnails creating, don't add it into a site again;
	
	private static $renderProcess = array();
	private static $renderHeader = array();
	
	private function __construct(){}


	public static function getProcessPage ($filename, $thumbInfo, $refresh_url, $errorMsg = '' ) {
		
		$countImg 		= (int)JFactory::getApplication()->input->get( 'countimg', 0, 'get', 'INT' );
		$currentImg 	= (int)JFactory::getApplication()->input->get( 'currentimg',0, 'get','INT' );
		$paths			= PhocaGalleryPath::getPath();
		
		if ($currentImg == 0) {
			$currentImg = 1;
		}
		$nextImg = $currentImg + 1;
		
		$view 		= JFactory::getApplication()->input->get( 'view', '', 'get', 'string' );

		//we are in whole window - not in modal box
		
		if ($view == 'phocagalleryi' || $view == 'phocagalleryd') {
			$header = self::getHeader('processpage');
			
			if ($header != '') {
				echo $header;
				$boxStyle = self::getBoxStyle();
				echo '<div style="'.$boxStyle.'">';
			}
		}
		
		echo '<span>'. JText::_( 'COM_PHOCAGALLERY_THUMBNAIL_GENERATING_WAIT' ) . '</span>';
		
		if ( $errorMsg == '' ) {
			echo '<p>' .JText::_( 'COM_PHOCAGALLERY_THUMBNAIL_GENERATING' ) 
			.' <span style="color:#0066cc;">'. $filename . '</span>' 
			.' ... <b style="color:#009900">'.JText::_( 'COM_PHOCAGALLERY_OK' ).'</b><br />'
			.'(<span style="color:#0066cc;">' . $thumbInfo . '</span>)</p>';
		} else {
			echo '<p>' .JText::_( 'COM_PHOCAGALLERY_THUMBNAIL_GENERATING' ) 
			.' <span style="color:#0066cc;padding:0;margin:0"> '. $filename . '</span>' 
			.' ... <b style="color:#fc0000">'.JText::_( 'COM_PHOCAGALLERY_ERROR' ).'</b><br />'
			.'(<span style="color:#0066cc;">' . $thumbInfo . '</span>)</p>';
			
		}
	
		if ($countImg == 0) {
			// BEGIN ---------------------------------------------------------------------------
			echo '<div>'. JHTML::_('image', 'media/com_phocagallery/images/administrator/icon-loading.gif', JText::_('COM_PHOCAGALLERY_LOADING') ) .'</div><div>&nbsp;</div><div>'. JText::_('COM_PHOCAGALLERY_REBUILDING_PROCESS_WAIT') . '</div>';
			// END -----------------------------------------------------------------------------
		} else {
			// Creating thumbnails info
			$per = 0; // display percents
			if ($countImg > 0) {
				$per = round(($currentImg / $countImg)*100, 0);
			}
			$perCSS = ($per * 400/100) - 400;
			$bgCSS = 'background: #e6e6e6 url(\''. $paths->media_img_rel_full . 'administrator/process2.png\') '.$perCSS.'px 0 repeat-y;';
			
			// BEGIN -----------------------------------------------------------------------
			//echo '<p>' . JText::_('COM_PHOCAGALLERY_GENERATING'). ': <span style="color:#0066cc">'. $currentImg .'</span> '.JText::_('COM_PHOCAGALLERY_FROM'). ' <span style="color:#0066cc">'. $countImg .'</span> '.JText::_('COM_PHOCAGALLERY_THUMBNAIL_S').'</p>';
			
			echo '<p>' . JText::sprintf('COM_PHOCAGALLERY_GENERATING_FROM_THUMBNAIL_S', '<span style="color:#0066cc">'. $currentImg .'</span> ', ' <span style="color:#0066cc">'. $countImg .'</span> ').'</p>';
			
			//echo '<p>'.$per.' &#37;</p>';
			//echo '<div style="width:400px;height:20px;font-size:20px;border-top:2px solid #666;border-left:2px solid #666;border-bottom:2px solid #ccc;border-right:2px solid #ccc;'.$bgCSS.'"><span style="font-size:10px;font-weight:bold">'.$per.' &#37;</div>';
			
			echo '<div style="width:400px;height:20px;font-size:20px;border: 1px solid #ccc; vertical-align: middle;display: inline-block; -moz-border-radius: 2px; -webkit-border-radius: 2px; border-radius: 2px;'.$bgCSS.'"><div style="font-size:12px;font-weight:bold;color: #777;margin-top:2px;">'.$per.' &#37;</div></div>';
			// END -------------------------------------------------------------------------
		}

		if ( $errorMsg != '' ) {
		
			$errorMessage = '';
			switch ($errorMsg) {
				case 'ErrorNotSupportedImage':
				$errorMessage = JText::_('COM_PHOCAGALLERY_ERROR_NOTSUPPORTEDIMAGE');
				break;
				
				case 'ErrorNoJPGFunction':
				$errorMessage = JText::_('COM_PHOCAGALLERY_ERROR_NOJPGFUNCTION');
				break;
				
				case 'ErrorNoPNGFunction':
				$errorMessage = JText::_('COM_PHOCAGALLERY_ERROR_NOPNGFUNCTION');
				break;
				
				case 'ErrorNoGIFFunction':
				$errorMessage = JText::_('COM_PHOCAGALLERY_ERROR_NOGIFFUNCTION');
				break;
				
				case 'ErrorNoWEBPFunction':
				$errorMessage = JText::_('COM_PHOCAGALLERY_ERROR_NOWEBPFUNCTION');
				break;
				
				case 'ErrorNoWBMPFunction':
				$errorMessage = JText::_('COM_PHOCAGALLERY_ERROR_NOWBMPFUNCTION');
				break;
				
				case 'ErrorWriteFile':
				$errorMessage = JText::_('COM_PHOCAGALLERY_ERROR_WRITEFILE');
				break;
				
				case 'ErrorFileOriginalNotExists':
				$errorMessage = JText::_('COM_PHOCAGALLERY_ERROR_FILEORIGINALNOTEXISTS');
				break;

				case 'ErrorCreatingFolder':
				$errorMessage = JText::_('COM_PHOCAGALLERY_ERROR_CREATINGFOLDER');
				break;
				
				case 'ErrorNoImageCreateTruecolor':
				$errorMessage = JText::_('COM_PHOCAGALLERY_ERROR_NOIMAGECREATETRUECOLOR');
				break;
				
				case 'Error1':
				case 'Error2':
				case 'Error3':
				case 'Error4':
				case 'Error5':
				Default:
					$errorMessage = JText::_('COM_PHOCAGALLERY_ERROR_WHILECREATINGTHUMB') . ' ('.$errorMsg.')';
				break;	
			}
			
			//$view 		= JFactory::getApplication()->input->get( 'view' );

			//we are in whole window - not in modal box
			if ($view != 'phocagalleryi' && $view != 'phocagalleryd') {
			
				echo '<div style="text-align:left;margin: 10px 5px">';
				echo '<table border="0" cellpadding="7"><tr><td>'.JText::_('COM_PHOCAGALLERY_ERROR_MESSAGE').':</td><td><span style="color:#fc0000">'.$errorMessage.'</span></td></tr>';
				
				echo '<tr><td colspan="1" rowspan="4" valign="top" >'.JText::_('COM_PHOCAGALLERY_WHAT_TO_DO_NOW').' :</td>';
				
				echo '<td>- ' .JText::_( 'COM_PHOCAGALLERY_SOLUTION_BEGIN' ).' <br /><ul><li>'.JText::_( 'COM_PHOCAGALLERY_SOLUTION_IMAGE' ).'</li><li>'.JText::_( 'COM_PHOCAGALLERY_SOLUTION_GD' ).'</li><li>'.JText::_( 'COM_PHOCAGALLERY_SOLUTION_PERMISSION' ).'</li></ul>'.JText::_( 'COM_PHOCAGALLERY_SOLUTION_END' ).'<br /> <a href="'.$refresh_url.'&countimg='.$countImg.'&currentimg='.$currentImg .'">' .JText::_( 'COM_PHOCAGALLERY_BACK_PHOCA_GALLERY' ).'</a><div class="hr"></div></td></tr>';
				
				echo '<tr><td>- ' .JText::_( 'COM_PHOCAGALLERY_DISABLE_CREATING_THUMBS_SOLUTION' ).' <br /> <a href="index.php?option=com_phocagallery&task=phocagalleryimg.disablethumbs">' .JText::_( 'COM_PHOCAGALLERY_BACK_DISABLE_THUMBS_GENERATING' ).'</a> <br />'.JText::_( 'COM_PHOCAGALLERY_ENABLE_THUMBS_GENERATING_OPTIONS' ).'<div class="hr"></div></td></tr>';
				
				echo '<tr><td>- ' .JText::_( 'COM_PHOCAGALLERY_MEDIA_MANAGER_SOLUTION' ).' <br /> <a href="index.php?option=com_media">' .JText::_( 'COM_PHOCAGALLERY_MEDIA_MANAGER_LINK' ).'</a><div class="hr"></div></td></tr>';
				
				echo '<tr><td>- <a href="https://www.phoca.cz/documentation/" target="_blank">' .JText::_( 'COM_PHOCAGALLERY_GO_TO_PHOCA_GALLERY_USER_MANUAL' ).'</a></td></tr>';
				
				echo '</table>';
				echo '</div>';

			}
			else //we are in modal box
			{
				echo '<div style="text-align:left">';
				echo '<table border="0" cellpadding="3"
			cellspacing="3"><tr><td>'.JText::_('COM_PHOCAGALLERY_ERROR_MESSAGE').':</td><td><span style="color:#fc0000">'.$errorMessage.'</span></td></tr>';
				
				echo '<tr><td colspan="1" rowspan="3" valign="top">'.JText::_('COM_PHOCAGALLERY_WHAT_TO_DO_NOW').' :</td>';
				
				echo '<td>- ' .JText::_( 'COM_PHOCAGALLERY_SOLUTION_BEGIN' ).' <br /><ul><li>'.JText::_( 'PG COM_PHOCAGALLERY_SOLUTION_IMAGE' ).'</li><li>'.JText::_( 'COM_PHOCAGALLERY_SOLUTION_GD' ).'</li><li>'.JText::_( 'COM_PHOCAGALLERY_SOLUTION_PERMISSION' ).'</li></ul>'.JText::_( 'COM_PHOCAGALLERY_SOLUTION_END' ).'<br /> <a href="'.$refresh_url.'&countimg='.$countImg.'&currentimg='.$currentImg .'">' .JText::_( 'COM_PHOCAGALLERY_BACK_PHOCA_GALLERY' ).'</a><div class="hr"></div></td></tr>';
				
				echo '<td>- ' .JText::_( 'COM_PHOCAGALLERY_NO_SOLUTION' ).' <br /> <a href="#" onclick="SqueezeBox.close();">' .JText::_( 'COM_PHOCAGALLERY_BACK_PHOCA_GALLERY' ).'</a></td></tr>';
				
				echo '</table>';
				echo '</div>';
			}
			
			echo '</div></body></html>';
			exit;
		}
		
		if ($countImg ==  $currentImg || $currentImg > $countImg) {
			
			/*$imageSid	= false;
			$imageSid 	= preg_match("/imagesid/i", $refresh_url);
			if (!$imageSid) {
				$refresh_url = $refresh_url . '&imagesid='.md5(time());
			}*/
			
			echo '<meta http-equiv="refresh" content="1;url='.$refresh_url.'" />';
		} else {
			echo '<meta http-equiv="refresh" content="0;url='.$refresh_url.'&countimg='.$countImg.'&currentimg='.$nextImg.'" />';
		}
		
		echo '</div></body></html>';
		exit;
	}
	
	
	public static function displayStopThumbnailsCreating($element = null) {
		
		if( is_null( $element ) ) {
			throw new Exception('Function Error: No element added', 500);
			return false;
		}
		
		// 1 ... link was displayed
		// 0 ... display the link "Stop ThumbnailsCreation
		$view 		= JFactory::getApplication()->input->get( 'view' );

		//we are in whole window - not in modal box
		if ($view == 'phocagalleryi' || $view == 'phocagalleryd') {
			//$this->stopThumbnailsCreating = 1;
			self::$renderProcess[$element] = '';
			return self::$renderProcess[$element];
		} else {
			
			
		
			if( !array_key_exists( $element, self::$renderProcess ) ) {
				
			//if (!isset($this->stopThumbnailsCreating) || (isset($this->stopThumbnailsCreating) && $this->stopThumbnailsCreating == 0)) {
				// Add stop thumbnails creation in case e.g. of Fatal Error which returns 'ImageCreateFromJPEG'
				$stopText = self::getHeader('processpage');
				$boxStyle = self::getBoxStyle();
				$stopText .= '<div style="'.$boxStyle.'">';// End will be added getProcessPage
				$stopText .= '<div style="text-align:right;margin-bottom: 15px;"><a style="font-family: sans-serif, Arial;font-weight:bold;color:#e33131;font-size:12px;" href="index.php?option=com_phocagallery&task=phocagalleryimg.disablethumbs" title="' .JText::_( 'COM_PHOCAGALLERY_STOP_THUMBNAIL_GENERATION_DESC' ).'">' .JText::_( 'COM_PHOCAGALLERY_STOP_THUMBNAIL_GENERATION' ).'</a></div>';
				//$this->stopThumbnailsCreating = 1;// it was added to the site, don't add the same code (because there are 3 thumnails - small, medium, large)
				//$this->headerAdded = 1;
				self::$renderProcess[$element] = $stopText;
			} else {
				self::$renderProcess[$element] = '';
			}
			return self::$renderProcess[$element];
		}
	}
	
	
	protected static function getHeader( $element = null) {
	
		if( is_null( $element ) ) {
			throw new Exception('Function Error: No element added', 500);
			return false;
		}
		
		if( !array_key_exists( $element, self::$renderHeader ) ) {
			// test utf-8 ä, ö, ü, č, ř, ž, ß
			$paths	= PhocaGalleryPath::getPath();
			$bgImg 	= JURI::root(true).'/media/com_phocagallery/images/administrator/image-bg.jpg';
			
			$o = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
			$o .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-en" lang="en-en" dir="ltr" >'. "\n";
			$o .= '<head>'. "\n";
			$o .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'. "\n\n";
			$o .= '<title>'.JText::_( 'COM_PHOCAGALLERY_THUMBNAIL_GENERATING').'</title>'. "\n";
			$o .= '<link rel="stylesheet" href="'.$paths->media_css_rel_full.'administrator/phocagallery.css" type="text/css" />';
			
			$o .= "\n" . '<style type="text/css"> html { 
			background: url('.$bgImg.') no-repeat center center fixed; 
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			}' . "\n" . '.hr { border-bottom: 1px solid #ccc; margin-top: 10px;}'. "\n" . '</style>' . "\n";
			
			$o .= "\n" . '<!--[if IE]>' . '<style type="text/css">' ."\n";
			$o .= "\n" . 'html { background-image: none;}';
			$o .= "\n" . '<![endif]-->' . "\n" . '</style>' . "\n";
			
			$o .= '</head>'. "\n";
			$o .= '<body>'. "\n";
			self::$renderHeader[$element] = $o;
		} else {
			self::$renderHeader[$element] = '';
		}
		
		return self::$renderHeader[$element];
	}
	
	protected static function getBoxStyle() {
		$o = 'position: absolute; 
		min-width: 430px; top: 20px; right: 20px; 
		color: #555; background: #fff;  
		font-family: sans-serif, arial; font-weight:normal; font-size: 12px; 
		-webkit-border-radius: 3px 3px 3px 3px; border-radius: 3px 3px 3px 3px; 
		padding:10px 10px 20px 10px;
		text-align: center;';
		return $o;
	}
}
?>