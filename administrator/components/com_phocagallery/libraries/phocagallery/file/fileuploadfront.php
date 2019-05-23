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

class PhocaGalleryFileUploadFront
{
	public static function getSizeAllOriginalImages($fileSize, $userId) {		
		
		$db 			=JFactory::getDBO();
		$allFileSize	= 0;
		$query = 'SELECT SUM(a.imgorigsize) AS sumimgorigsize'
				.' FROM #__phocagallery AS a'
				.' LEFT JOIN #__phocagallery_categories AS cc ON a.catid = cc.id'
			    .' WHERE cc.owner_id = '.(int)$userId;
		$db->setQuery($query, 0, 1);
		$sumImgOrigSize = $db->loadObject();
		
		if(isset($sumImgOrigSize->sumimgorigsize) && (int)$sumImgOrigSize->sumimgorigsize > 0) {
			$allFileSize = (int)$allFileSize + (int)$sumImgOrigSize->sumimgorigsize;
		}
		
		if (isset($fileSize)) {
				$allFileSize = (int)$allFileSize + (int)$fileSize;
		}
		return (int)$allFileSize;
	}
}
?>