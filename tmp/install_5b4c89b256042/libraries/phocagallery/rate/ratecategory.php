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

class PhocaGalleryRateCategory
{
	public static function updateVoteStatistics( $catid ) {
		
		$db =JFactory::getDBO();
		
		// Get AVG and COUNT
		$query = 'SELECT COUNT(vs.id) AS count, AVG(vs.rating) AS average'
				.' FROM #__phocagallery_votes AS vs'
			    .' WHERE vs.catid = '.(int) $catid;
		//		.' AND vs.published = 1';
		$db->setQuery($query, 0, 1);
		$votesStatistics = $db->loadObject();
		// if no count, set the average to 0
		if($votesStatistics->count == 0) {
			$votesStatistics->count = (int)0;
			$votesStatistics->average = (float)0;
		}
		
		if (isset($votesStatistics->count) && isset($votesStatistics->average)) {
			// Insert or update
			$query = 'SELECT vs.id AS id'
					.' FROM #__phocagallery_votes_statistics AS vs'
				    .' WHERE vs.catid = '.(int) $catid
					.' ORDER BY vs.id';
			$db->setQuery($query, 0, 1);
			$votesStatisticsId = $db->loadObject();
		
			// Yes, there is id (UPDATE) x No, there isn't (INSERT)
			if (!empty($votesStatisticsId->id)) {
			
				$query = 'UPDATE #__phocagallery_votes_statistics'
					.' SET count = ' .(int)$votesStatistics->count
					.' , average = ' .(float)$votesStatistics->average
				    .' WHERE catid = '.(int) $catid;
				$db->setQuery($query);
				$db->execute();
				
				
			
			} else {
			
				$query = 'INSERT into #__phocagallery_votes_statistics'
					.' (id, catid, count, average)'
				    .' VALUES (null, '.(int)$catid
					.' , '.(int)$votesStatistics->count
					.' , '.(float)$votesStatistics->average
					.')';
				$db->setQuery($query);
				$db->execute();
				
				
			
			}
		} else {
			return false;
		}
		return true;
	}
	
	public static function getVotesStatistics($id) {
	
		$db =JFactory::getDBO();
		$query = 'SELECT vs.count AS count, vs.average AS average'
				.' FROM #__phocagallery_votes_statistics AS vs'
			    .' WHERE vs.catid = '.(int) $id;
		$db->setQuery($query, 0, 1);
		$votesStatistics = $db->loadObject();
			
		return $votesStatistics;
	}
	
	public static function checkUserVote($catid, $userid) {
		
		$db =JFactory::getDBO();
		$query = 'SELECT v.id AS id'
			    .' FROM #__phocagallery_votes AS v'
			    .' WHERE v.catid = '. (int)$catid 
				.' AND v.userid = '. (int)$userid
				.' ORDER BY v.id';
		$db->setQuery($query, 0, 1);
		$checkUserVote = $db->loadObject();
			
		if ($checkUserVote) {
			return true;
		}
		return false;
	}
}
?>