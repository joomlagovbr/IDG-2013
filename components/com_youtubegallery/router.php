<?php
/**
 * YouTubeGallery Joomla! 3.0 Native Component
 * @version 3.5.9
 * @author DesignCompass Corp <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// no direct access
defined('_JEXEC') or die('Restricted access');
function YouTubeGalleryBuildRoute(&$query) {

       $segments = array();
       if(isset($query['view']))
       {
                $segments[] = $query['view'];
                unset( $query['view'] );
       }
	   
	   /*if(isset($query['videoid']))
       {
                $segments[] = $query['videoid'];
                unset( $query['videoid'] );
       }*/

       return $segments;


}
function YouTubeGalleryParseRoute($segments) {

  $vars = array();
       /*switch($segments[0])
       {
               //case 'youtubegallery':
                    //   $vars['view'] = 'youtubegallery';
					  // $vars['videoid'] = $segments[1];
                       break;
              
       }*/
       return $vars;


}
?>