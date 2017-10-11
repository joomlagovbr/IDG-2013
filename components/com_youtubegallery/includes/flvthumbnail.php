<?php
/**
 * YoutubeGallery for Joomla!
 * @version 4.4.0
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/



// No direct access to this file
//defined('_JEXEC') or die('Restricted access');

//require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');

$videofile= urldecode($_GET['videofile']);
$videofile= html2txt($videofile);//YouTubeGalleryMisc::
$videofile= '../../../'.$videofile;

if(!file_exists($videofile))
{
    echo 'File not found.';
    die;
}

require_once('flv4php/FLV.php');

$flv = new FLV($videofile);

echo $flv->getFlvThumb();

        function html2txt($document)
	{ 
		$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript 
               '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags 
               '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly 
               '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA 
		); 
		$text = preg_replace($search, '', $document); 
		return $text; 
	} 

?>