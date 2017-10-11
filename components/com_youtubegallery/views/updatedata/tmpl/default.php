<?php
/**
 * Youtube Gallery Joomla! 3.0 Native Component
 * @version 4.4.0
 * @author Ivan Komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
        
/*
$videoid=JRequest::getCmd('videoid');
if(isset($_POST['ygvdata']))
{
    $video_data=$_POST['ygvdata'];

    require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');

    $video_data=str_replace('"','\"',$video_data);

    YouTubeGalleryMisc::setRawData($videoid,$video_data);

    $db = JFactory::getDBO();
    $query = 'SELECT * FROM `#__youtubegallery_videos` WHERE `videoid`="'.$videoid.'"';
    $db->setQuery($query);
    if (!$db->query())    die( $db->stderr());
    $videos_rows=$db->loadAssocList();
    
    $misc=new YouTubeGalleryMisc;
    $getinfomethod=YouTubeGalleryMisc::getSettingValue('getinfomethod');
    $misc->RefreshVideoData($videos_rows,$getinfomethod,true);

    $query = 'SELECT * FROM `#__youtubegallery_videos` WHERE `videoid`="'.$videoid.'"';
    $db->setQuery($query);
    if (!$db->query())    die( $db->stderr());
    $videos_rows=$db->loadAssocList();

    if(count($videos_rows)!=0)
    {
        $row=$videos_rows[0];
        echo '*title_start*='.$row['title'].'*title_end*';
        echo '*description_start*='.$row['description'].'*description_end*';
        echo '*lastupdate_start*='.$row['lastupdate'].'*lastupdate_end*';
    }
    else
        echo '*status_start*=video not found*status_end*';
}
else
    echo 'Data not set.';
    
    //echo 'Data Size 1: '.strlen($video_data);
//echo 'Update Data View:<br/>
//'.$video_data;

*/

echo 'Future functionality.';

?>

