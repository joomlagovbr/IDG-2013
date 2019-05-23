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
 * Youtube Gallery - Categories Table class
 */
class YoutubeGalleryTableCategories extends JTable
{
        /**
         * Constructor
         *
         * @param object Database connector object
         */
       	var $id = null;
        var $categoryname = null;
	var $description = null;
	var $image = null;
	var $parentid = null;
       
        function __construct(&$db) 
        {
                parent::__construct('#__youtubegallery_categories', 'id', $db);
        }

}

?>