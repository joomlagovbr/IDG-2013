<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die;

class PhocaGalleryTagHelper
{
	public function getTags($fileId, $select = 0) {

		$db =JFactory::getDBO();

		if ($select == 1) {
			$query = 'SELECT r.tagid';
		} else {
			$query = 'SELECT a.*';
		}
		$query .= ' FROM #__phocagallery_tags AS a'
				//.' LEFT JOIN #__phocagallery AS f ON f.id = r.fileid'
				.' LEFT JOIN #__phocagallery_tags_ref AS r ON a.id = r.tagid'
			    .' WHERE r.fileid = '.(int) $fileId;
		$db->setQuery($query);


		if ($select == 1) {
			$tags = $db->loadColumn();
		} else {
			$tags = $db->loadObjectList();
		}

		return $tags;
	}

	public function storeTags($tagsArray, $fileId) {


		if ((int)$fileId > 0) {
			$db =JFactory::getDBO();
			$query = ' DELETE '
					.' FROM #__phocagallery_tags_ref'
					. ' WHERE fileid = '. (int)$fileId;
			$db->setQuery($query);
			if (!$db->execute()) {
				$this->setError('Database Error - Deleting FileId Tags');
				return false;
			}

			if (!empty($tagsArray)) {

				$values 		= array();
				$valuesString 	= '';

				foreach($tagsArray as $k => $v) {
					$values[] = ' ('.(int)$fileId.', '.(int)$v.')';
				}

				if (!empty($values)) {
					$valuesString = implode($values, ',');

					$query = ' INSERT INTO #__phocagallery_tags_ref (fileid, tagid)'
								.' VALUES '.(string)$valuesString;

					$db->setQuery($query);
					if (!$db->execute()) {
						$this->setError('Database Error - Insert FileId Tags');
						return false;
					}

				}
			}
		}

	}

	public function getAllTagsSelectBox($name, $id, $activeArray, $javascript = NULL, $order = 'id' ) {

		$db =JFactory::getDBO();
		$query = 'SELECT a.id AS value, a.title AS text'
				.' FROM #__phocagallery_tags AS a'
				. ' ORDER BY '. $order;
		$db->setQuery($query);

		/*if (!$db->query()) {
			$this->setError('Database Error - Getting All Tags');
			return false;
		}*/

		$tags = $db->loadObjectList();

		$tagsO = JHTML::_('select.genericlist', $tags, $name, 'class="inputbox" size="4" multiple="multiple"'. $javascript, 'value', 'text', $activeArray, $id);

		return $tagsO;
	}
}
