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
class YoutubeGalleryTableSettings extends JTable
{
        /**
         * Constructor
         *
         * @param object Database connector object
         */

		var $id = null;
		var $option = null;
		var $value = null;

        function __construct(&$db) 
        {
                parent::__construct('#__youtubegallery_settings', 'id', $db);
        }
}

?>