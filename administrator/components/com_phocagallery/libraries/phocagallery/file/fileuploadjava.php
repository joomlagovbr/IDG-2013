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

class PhocaGalleryFileUploadJava
{
	public $returnUrl;
	public $url;
	public $source;
	public $height;
	public $width;
	public $resizeheight;
	public $resizewidth;
	public $uploadmaxsize;

	public function __construct() {}
	
	public function getJavaUploadHTML() {
	

		
		$html = '<!--[if !IE]> -->' . "\n"  

.'<object classid="java:wjhk.jupload2.JUploadApplet" type="application/x-java-applet"'
.' archive="http://localhost/'. $this->source.'" height="'.$this->height.'" width="'.$this->width.'" >' . "\n"
.'<param name="archive" value="'. $this->source.'" />' . "\n"
.'<param name="postURL" value="'. $this->url.'"/>' . "\n"
.'<param name="afterUploadURL" value="'. $this->returnUrl.'"/>' . "\n"
.'<param name="allowedFileExtensions" value="jpg/gif/png/" />'		 . "\n"            
.'<param name="uploadPolicy" value="PictureUploadPolicy" />'     . "\n"        
.'<param name="nbFilesPerRequest" value="1" />' . "\n"
.'<param name="maxPicHeight" value="'. $this->resizeheight .'" />' . "\n"
.'<param name="maxPicWidth" value="'. $this->resizewidth .'" />' . "\n"
.'<param name="maxFileSize" value="'. $this->uploadmaxsize .'" />'	 . "\n"
.'<param name="pictureTransmitMetadata" value="true" />' . "\n"
.'<param name="showLogWindow" value="false" />' . "\n"
.'<param name="showStatusBar" value="false" />' . "\n"
.'<param name="pictureCompressionQuality" value="1" />' . "\n"
.'<param name="lookAndFeel"  value="system"/>' . "\n"
.'<!--<![endif]-->'. "\n"  
.'<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" codebase="http://java.sun.com/update/1.5.0/jinstall-1_5_0-windows-i586.cab"'
.' height="'.$this->height.'" width="'.$this->width.'" >'. "\n"  
.'<param name="code" value="wjhk.jupload2.JUploadApplet" />'. "\n"  
.'<param name="archive" value="'. $this->source .'" />'. "\n"  
.'<param name="postURL" value="'. $this->url .'"/>'. "\n"  
.'<param name="afterUploadURL" value="'. $this->returnUrl.'"/>'. "\n"  
.'<param name="allowedFileExtensions" value="jpg/gif/png" />'	  . "\n"            
.'<param name="uploadPolicy" value="PictureUploadPolicy" /> '    . "\n"        
.'<param name="nbFilesPerRequest" value="1" />'. "\n"  
.'<param name="maxPicHeight" value="'. $this->resizeheight .'" />'. "\n"  
.'<param name="maxPicWidth" value="'. $this->resizewidth .'" />'. "\n"  
.'<param name="maxFileSize" value="'. $this->uploadmaxsize .'" />'	. "\n"  
.'<param name="pictureTransmitMetadata" value="true" />'. "\n"  
.'<param name="showLogWindow" value="false" />'. "\n"  
.'<param name="showStatusBar" value="false" />'. "\n"  
.'<param name="pictureCompressionQuality" value="1" />'. "\n"  
.'<param name="lookAndFeel"  value="system"/>' . "\n"  
.'<div style="color:#cc0000">'.JText::_('COM_PHOCAGALLERY_JAVA_PLUGIN_MUST_BE_ENABLED').'</div>'. "\n"  
.'</object>'. "\n"  
.'<!--[if !IE]> -->'. "\n"  
.'</object>' . "\n"
.'<!--<![endif]-->'. "\n"  
.'</fieldset>' . "\n";
		
		return $html;
		
	}
}
?>