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


$videofile= $_GET['videofile'];
$videofile= '../../../'.urldecode($videofile);
//$videofile= urldecode($videofile);
if(!file_exists($videofile))
{
    echo 'File "'.$videofile.'" not found.';
    die;
}

require_once('flv4php/FLV.php');

$flv = new FLV($videofile);

echo $flv->getFlvThumb();

?>