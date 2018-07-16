<?php
/**
 * YoutubeGallery Joomla! Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * Youtube Gallery - Themes Table class
 */
class YoutubeGalleryTableThemes extends JTable
{
        /**
         * Constructor
         *
         * @param object Database connector object
         */
       	var $id = null;
        var $themename = null;
	
        var $width = null;
        var $height = null;
        var $playvideo = null;
        var $repeat = null;
        var $fullscreen = null;
        var $autoplay = null;
        var $related = null;
        var $showinfo = null;
        var $bgcolor = null;
        var $cols = null;
        var $showtitle = null;

        var $cssstyle = null;
        var $navbarstyle = null;
        var $thumbnailstyle = null;
        var $linestyle = null;
	
	var $showlistname = null;
        var $listnamestyle = null;
		
        var $showactivevideotitle = null;
        var $activevideotitlestyle = null;
	
        var $description = null;
        var $descr_position = null;
        var $descr_style = null;
	
	
        var $color1 = null;
        var $color2 = null;
	
        var $border = null;
        
        var $openinnewwindow = null;
        var $rel = null;
        var $hrefaddon = null;
	
	var $pagination = null;
	var $customlimit = null;
		

	var $controls = null;
	var $youtubeparams = null;
	var $playertype = null;
	var $useglass = null;
	var $logocover = null;
		
	var $customlayout = null;
	var $prepareheadtags = null;
		
	var $muteonplay = null;
	var $lastplaylistupdate = null;
	var $volume = null;

	var $orderby = null;
	var $customnavlayout = null;
	var $responsive = null;
	
	var $mediafolder = null;
	var $readonly = null;
	var $headscript = null;
	var $themedescription = null;
	var $nocookie = null;
	var $changepagetitle = null;

	var $allowplaylist = null;

        function __construct(&$db) 
        {
                parent::__construct('#__youtubegallery_themes', 'id', $db);
        }
}

?>