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
 * Youtube Gallery - Video Lists Table class
 */
class YoutubeGalleryTableVideolists extends JTable
{
        /**
         * Constructor
         *
         * @param object Database connector object
         */

		var $id = null;
		var $listname = null;
		var $videolist = null;
		var $catid = null;
		var $updateperiod = null;
		var $lastplaylistupdate = null;
		var $description = null;
		var $author = null;
		var $watchusergroup = null;
		
		var $authorurl = null;
		var $image = null;
		var $note = null;
  
        function __construct(&$db) 
        {
                parent::__construct('#__youtubegallery_videolists', 'id', $db);
        }
}

?>