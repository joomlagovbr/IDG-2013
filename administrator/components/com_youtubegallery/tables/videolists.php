<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 3.5.9
 * @author DesignCompass corp< <support@joomlaboat.com>
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

        function __construct(&$db) 
        {
                parent::__construct('#__youtubegallery_videolists', 'id', $db);
        }
}

?>