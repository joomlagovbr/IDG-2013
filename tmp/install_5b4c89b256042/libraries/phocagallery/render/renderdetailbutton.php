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

class PhocaGalleryRenderDetailButton
{
	protected $_imgordering		= array();
	public $type				= '';
	
	public function __construct() {
		$this->_setImageOrdering();
		$this->type = '';
	}

	protected function _setImageOrdering() {
		
		if (empty($this->_imgordering)) {
			$app				= JFactory::getApplication();
			$paramsC 			= JComponentHelper::getParams('com_phocagallery') ;
			$image_ordering		= $paramsC->get( 'image_ordering', 1 );
			$this->_imgordering = PhocaGalleryOrdering::getOrderingString($app->getUserStateFromRequest('com_phocagallery.category.' .'imgordering', 'imgordering', $image_ordering, 'int'));
		}
		return true;
		
	}
	
	public function setType($type) {
		$this->type = $type;
	}
	
	/*
	* Get the next button in Gallery - in opened window
	*/
	public function getNext ($catid, $id, $ordering, $hrefOnly = 0)  {
	
		$app			= JFactory::getApplication();
		$db 			= JFactory::getDBO();
		$paramsC 		= JComponentHelper::getParams('com_phocagallery') ;
		$detailWindow	= $paramsC->get( 'detail_window', 0 );
		
		if ($detailWindow == 7) {
			$tmplCom = '';
		} else {
			$tmplCom = '&tmpl=component';
		}
		
		$c 	= $this->_imgordering['column'];
		$s 	= $this->_imgordering['sort'];
		$sP	= ($s == 'DESC') ? '<' : '>';
		$sR	= ($s == 'ASC') ? 'DESC' : 'ASC';
		//Select all ids from db_gallery - we search for next_id (!!! next_id can be id without file
		//in the server. If the next id has no file in the server we must go from next_id to next next_id
		if ($c == 'count' || $c == 'average') {
		
			$query = 'SELECT a.id, a.alias, c.id as catid, c.alias as catalias, a.filename as filename, b.id AS currentid,'
			.' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug,'
			.' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug'
			.' FROM #__phocagallery AS a'
			.' LEFT JOIN #__phocagallery_img_votes_statistics AS ra ON ra.imgid = a.id,'
			.' #__phocagallery AS b'
			.' LEFT JOIN #__phocagallery_categories AS c ON c.id = b.catid'
			.' LEFT JOIN #__phocagallery_img_votes_statistics AS rb ON rb.imgid = b.id'
			.' WHERE a.catid = ' . (int)$catid
			.' AND b.id = ' . (int)$id
			.' AND ('
			.' (ra.'.$c.' = rb.'.$c.' AND a.id '.$sP.' b.id) OR '
			.' (CASE WHEN ra.'.$c.' IS NOT NULL AND rb.'.$c.' IS NOT NULL THEN ra.'.$c.' '.$sP.' rb.'.$c.' END) OR '
			.' (CASE WHEN ra.'.$c.' IS NULL AND rb.'.$c.' IS NOT NULL THEN 0 '.$sP.' rb.'.$c.' END) OR '
			.' (CASE WHEN ra.'.$c.' IS NOT NULL AND rb.'.$c.' IS NULL THEN ra.'.$c.' '.$sP.' 0 END) OR '
			.' (CASE WHEN ra.'.$c.' IS NULL AND rb.'.$c.' IS NULL THEN a.id '.$sP.' b.id END) '
			.')'
			.' AND a.published = 1'
			.' ORDER BY ra.'.$c.' '.$s.', a.id '.$s;
		} else {
			$query = 'SELECT a.id, a.alias, c.id as catid, c.alias as catalias, a.filename as filename, b.id AS currentid,'
			.' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug,'
			.' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug'
			.' FROM #__phocagallery AS a,'
			.' #__phocagallery AS b'
			.' LEFT JOIN #__phocagallery_categories AS c ON c.id = b.catid'
			.' WHERE a.catid = ' . (int)$catid
			.' AND b.id = ' . (int)$id
			.' AND (a.'.$c.' '.$sP.' b.'.$c.' OR (a.'.$c.' = b.'.$c.' and a.id '.$sP.' b.id))'
			.' AND a.published = 1'
			.' ORDER BY a.'.$c.' '.$s.', a.id '.$s;
		}
				
		$db->setQuery($query);
		$nextAll = $db->loadObjectList();
		
		$class 		= 'pg-imgbgd';
		$imgName	= 'icon-next';
		$idCss		= '';
		if ($this->type == 'multibox') {
			$class 		= 'pg-imgbd-multibox-next';
			$imgName	= 'icon-next-multibox';
			$idCss		= ' id="phocagallerymultiboxnext" ';
		}
		
		$href = '';
		$next = '<div class="'.$class.'"'.$idCss.'>';
		
		if ($this->type == 'multibox') {
			$next .= JHTML::_('image', 'media/com_phocagallery/images/'.$imgName.'-grey.png', JText::_( 'COM_PHOCAGALLERY_NEXT_IMAGE' )).'</div>';
		} else {
			$next .= PhocaGalleryRenderFront::renderIcon('next', 'media/com_phocagallery/images/icon-next-grey.png', JText::_( 'COM_PHOCAGALLERY_NEXT_IMAGE' ),  'ph-icon-disabled').'</div>';
		}
		//non-active button will be displayed as Default, we will see if we find active link
		foreach ($nextAll as $key => $value) {
			
			// Is there some next id, if not end this and return grey link
			if (isset($value->id) && $value->id > 0) {

				// onclick="disableBackAndNext()"
				//$href	= JRoute::_('index.php?option=com_phocagallery&view=detail&catid='. $value->catslug.'&id='.$value->slug.$tmplCom.'&Itemid='. JFactory::getApplication()->input->get('Itemid', 1, 'get', 'int'));
				
				$href	= JRoute::_(PhocaGalleryRoute::getImageRoute($value->id, $value->catid, $value->alias, $value->catalias) . $tmplCom);
				
				$next = '<div class="'.$class.'"'.$idCss.'>' // because of not conflict with beez
				.'<a href="'.$href.'"'
				.' title="'.JText::_( 'COM_PHOCAGALLERY_NEXT_IMAGE' ).'" id="next" >';
				
				if ($this->type == 'multibox') {
					$next .= JHTML::_('image', 'media/com_phocagallery/images/'.$imgName.'.png', JText::_( 'COM_PHOCAGALLERY_NEXT_IMAGE' )).'</div>';
				} else {
					$next .= PhocaGalleryRenderFront::renderIcon('next', 'media/com_phocagallery/images/icon-next.png', JText::_( 'COM_PHOCAGALLERY_NEXT_IMAGE' )).'</div>';
				}
				
				break;// end it, we must need not to find next ordering
				
			} else {
				$href = '';
				$next = '<div class="'.$class.'"'.$idCss.'>';
				
				if ($this->type == 'multibox') {
					$next .= JHTML::_('image', 'media/com_phocagallery/images/'.$imgName.'-grey.png', JText::_( 'COM_PHOCAGALLERY_NEXT_IMAGE' )).'</div>';
				} else {
					$next .= PhocaGalleryRenderFront::renderIcon('next', 'media/com_phocagallery/images/icon-next-grey.png', JText::_( 'COM_PHOCAGALLERY_NEXT_IMAGE' ),  'ph-icon-disabled').'</div>';
				}
				//.JHTML::_('image', 'media/com_phocagallery/images/'.$imgName.'-grey.png', JText::_( 'COM_PHOCAGALLERY_NEXT_IMAGE' )).'</div>';
				break;// end it, we must need not to find next ordering
			}
		} 
		if ($hrefOnly == 1) {
			return $href;
		}
		return $next;
	}
	
	  /*
	* Get the prev button in Gallery - in openwindow
	*/
	public function getPrevious ($catid, $id, $ordering) {
	
		$app	= JFactory::getApplication();
		$db 			= JFactory::getDBO();
		$params			= $app->getParams();
		$detailWindow	= $params->get( 'detail_window', 0 );
		if ($detailWindow == 7) {
			$tmplCom = '';
		} else {
			$tmplCom = '&tmpl=component';
		}
		
		$c 	= $this->_imgordering['column'];
		$s 	= $this->_imgordering['sort'];
		$sP	= ($s == 'ASC') ? '<' : '>'; 
		$sR	= ($s == 'ASC') ? 'DESC' : 'ASC';
		//Select all ids from db_gallery - we search for next_id (!!! next_id can be id without file
		//in the server. If the next id has no file in the server we must go from next_id to next next_id
		if ($c == 'count' || $c == 'average') {
		
			$query = 'SELECT a.id, a.alias, c.id as catid, c.alias as catalias, a.filename as filename, b.id AS currentid,'
			.' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug,'
			.' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug'
			.' FROM #__phocagallery AS a'
			.' LEFT JOIN #__phocagallery_img_votes_statistics AS ra ON ra.imgid = a.id,'
			.' #__phocagallery AS b'
			.' LEFT JOIN #__phocagallery_categories AS c ON c.id = b.catid'
			.' LEFT JOIN #__phocagallery_img_votes_statistics AS rb ON rb.imgid = b.id'
			.' WHERE a.catid = ' . (int)$catid
			.' AND b.id = ' . (int)$id
			.' AND ('
			.' (ra.'.$c.' = rb.'.$c.' AND a.id '.$sP.' b.id) OR '
			.' (CASE WHEN ra.'.$c.' IS NOT NULL AND rb.'.$c.' IS NOT NULL THEN ra.'.$c.' '.$sP.' rb.'.$c.' END) OR '
			.' (CASE WHEN ra.'.$c.' IS NULL AND rb.'.$c.' IS NOT NULL THEN 0 '.$sP.' rb.'.$c.' END) OR '
			.' (CASE WHEN ra.'.$c.' IS NOT NULL AND rb.'.$c.' IS NULL THEN ra.'.$c.' '.$sP.' 0 END) OR '
			.' (CASE WHEN ra.'.$c.' IS NULL AND rb.'.$c.' IS NULL THEN a.id '.$sP.' b.id END) '
			.')'
			.' AND a.published = 1'
			.' ORDER BY ra.'.$c.' '.$sR.', a.id '.$sR;
		} else {
			$query = 'SELECT a.id, a.alias, c.id as catid, c.alias as catalias, a.filename as filename, b.id AS currentid,'
			.' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as catslug,'
			.' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug'
			.' FROM #__phocagallery AS a,'
			.' #__phocagallery AS b'
			.' LEFT JOIN #__phocagallery_categories AS c ON c.id = b.catid'
			.' WHERE a.catid = ' . (int)$catid
			.' AND b.id = ' . (int)$id
			.' AND (a.'.$c.' '.$sP.' b.'.$c.' OR (a.'.$c.' = b.'.$c.' and a.id '.$sP.' b.id))'
			.' AND a.published = 1'
			.' ORDER BY a.'.$c.' '.$sR.', a.id '.$sR;
		}
		
		$db->setQuery($query);
		$prevAll = $db->loadObjectList();
		
		$class 		= 'pg-imgbgd';
		$imgName	= 'icon-prev';
		$idCss		= '';
		if ($this->type == 'multibox') {
			$class 		= 'pg-imgbd-multibox-prev';
			$imgName	= 'icon-prev-multibox';
			$idCss		= ' id="phocagallerymultiboxprev" ';
		}
	
		$prev = '<div class="'.$class.'"'.$idCss.'>';
		//.JHTML::_('image', 'media/com_phocagallery/images/'.$imgName.'-grey.png', JText::_( 'COM_PHOCAGALLERY_PREV_IMAGE' )).'</div>';
		if ($this->type == 'multibox') {
			$prev .= JHTML::_('image', 'media/com_phocagallery/images/'.$imgName.'-grey.png', JText::_( 'COM_PHOCAGALLERY_PREV_IMAGE' )).'</div>';
		} else {
			$prev .= PhocaGalleryRenderFront::renderIcon('prev', 'media/com_phocagallery/images/icon-prev-grey.png', JText::_( 'COM_PHOCAGALLERY_PREV_IMAGE' ),  'ph-icon-disabled').'</div>';
		}
		
		//non-active button will be displayed as Default, we will see if we find active link
		foreach ($prevAll as $key => $value) {
			
			// Is there some next id, if not end this and return grey link
			if (isset($value->id) && $value->id > 0) {
				
				$href	= JRoute::_(PhocaGalleryRoute::getImageRoute($value->id, $value->catid, $value->alias, $value->catalias).$tmplCom);
				//onclick="disableBackAndPrev()"
				$prev = '<div class="'.$class.'"'.$idCss.'>' // because of not conflict with beez 
				.'<a href="'.$href.'"'
				.' title="'.JText::_( 'COM_PHOCAGALLERY_PREV_IMAGE' ).'" id="prev" >';
				
				if ($this->type == 'multibox') {
					$prev .= JHTML::_('image', 'media/com_phocagallery/images/'.$imgName.'.png', JText::_( 'COM_PHOCAGALLERY_PREV_IMAGE' )).'</a></div>';
				} else {
					$prev .= PhocaGalleryRenderFront::renderIcon('prev', 'media/com_phocagallery/images/icon-prev.png', JText::_( 'COM_PHOCAGALLERY_PREV_IMAGE' ),  '').'</a></div>';
				}
		
				
				break;// end it, we must need not to find next ordering
				
			} else {
				$prev = '<div class="'.$class.'"'.$idCss.'>';
				
				if ($this->type == 'multibox') {
					$prev .= JHTML::_('image', 'media/com_phocagallery/images/'.$imgName.'-grey.png', JText::_( 'COM_PHOCAGALLERY_PREV_IMAGE' )).'</div>';
				} else {
					$prev .= PhocaGalleryRenderFront::renderIcon('prev', 'media/com_phocagallery/images/icon-prev-grey.png', JText::_( 'COM_PHOCAGALLERY_PREV_IMAGE' ),  'ph-icon-disabled').'</div>';
				}
				
				
				break;// end it, we must need not to find next ordering
			}
		} 
		return $prev;
	}
	
	public function getReload($catidSlug, $idSlug) {
		
		$app	= JFactory::getApplication();
		$params			= $app->getParams();
		$detailWindow	= $params->get( 'detail_window', 0 );
		if ($detailWindow == 7) {
			$tmplCom = '';
		} else {
			$tmplCom = '&tmpl=component';
		}
		
		$i = explode(':', $idSlug);
		$id 		= $i[0];
		$alias		= $i[1];
		$j = explode(':', $catidSlug);
		$catid		= $j[0];
		$catalias	= $j[1];
		$href	= JRoute::_(PhocaGalleryRoute::getImageRoute($id, $catid, $alias, $catalias).$tmplCom);
		
		$reload =  '<div class="pg-imgbgd"><a href="'.$href.'" onclick="%onclickreload%" title="'.JText::_( 'COM_PHOCAGALLERY_REFRESH' ).'" >'. PhocaGalleryRenderFront::renderIcon('reload', 'media/com_phocagallery/images/icon-reload.png', JText::_( 'COM_PHOCAGALLERY_REFRESH' )).'</a></div>';
			
		return $reload;
	}
	
	public function getClose($catidSlug, $idSlug) {
		$app	= JFactory::getApplication();
		$params			= $app->getParams();
		$detailWindow	= $params->get( 'detail_window', 0 );
		
		if ($detailWindow == 7 ) {
			return '';
		}
		
		$onclick = 'onclick="%onclickclose%"';
		if ($detailWindow == 9 || $detailWindow == 10 | $detailWindow == 13) {
			//$onclick = 'onclick="window.parent.pgcbp.close();"';
			return '';// Will be set in boxplus javascript
		}
		$i = explode(':', $idSlug);
		$id 		= $i[0];
		$alias		= $i[1];
		$j = explode(':', $catidSlug);
		$catid		= $j[0];
		$catalias	= $j[1];
		$href	= JRoute::_(PhocaGalleryRoute::getImageRoute($id, $catid, $alias, $catalias));
		$close =  '<div class="pg-imgbgd"><a href="'.$href.'" '.$onclick.' title="'.JText::_( 'COM_PHOCAGALLERY_CLOSE_WINDOW').'" >'. PhocaGalleryRenderFront::renderIcon('off', 'media/com_phocagallery/images/icon-exit.png', JText::_( 'COM_PHOCAGALLERY_CLOSE_WINDOW' )).'</a></div>';

		
		return $close;
	}
	
	public function getCloseText($catidSlug, $idSlug) {
		$app	= JFactory::getApplication();
		$params			= $app->getParams();
		$detailWindow	= $params->get( 'detail_window', 0 );
		if ($detailWindow == 7) {
			return '';
		}
		
		$onclick = 'onclick="%onclickclose%"';
		if ($detailWindow == 9 || $detailWindow == 10) {
			//$onclick = 'onclick="window.parent.pgcbpi.close();"';
			return '';// Will be set in boxplus javascript
		}
		$i = explode(':', $idSlug);
		$id 		= $i[0];
		$alias		= $i[1];
		$j = explode(':', $catidSlug);
		$catid		= $j[0];
		$catalias	= $j[1];
		$href	= JRoute::_(PhocaGalleryRoute::getImageRoute($id, $catid, $alias, $catalias));
		
		$close =  '<a style="text-decoration:underline" href="'.$href.'" '.$onclick.' title="'.JText::_( 'COM_PHOCAGALLERY_CLOSE_WINDOW').'" >'. JText::_( 'COM_PHOCAGALLERY_CLOSE_WINDOW' ).'</a>';
		
		return $close;
	}
	
	/*
	* Get Slideshow  - 1. data for javascript, 2. data for displaying buttons
	*/
	public function getJsSlideshow($catid, $id, $slideshow = 0, $catidSlug, $idSlug) {
		
		jimport('joomla.filesystem.file');
		phocagalleryimport('phocagallery.file.filethumbnail');
		$app	= JFactory::getApplication();
		$db 				= JFactory::getDBO();
		$params				= $app->getParams();
		//$image_ordering		= $params->get( 'image_ordering', 1 );
		//$imageOrdering 		= PhocaGalleryOrdering::getOrderingString($image_ordering);
		$detailWindow		= $params->get( 'detail_window', 0 );
		if ($detailWindow == 7) {
			$tmplCom = '';
		} else {
			$tmplCom = '&tmpl=component';
		}
		
		$i = explode(':', $idSlug);
		$id 		= $i[0];
		$alias		= $i[1];
		$j = explode(':', $catidSlug);
		$catid		= $j[0];
		$catalias	= $j[1];
		$href	= PhocaGalleryRoute::getImageRoute($id, $catid, $alias, $catalias) . $tmplCom;
		
		// 1. GET DATA FOR JAVASCRIPT
		$jsSlideshowData['files'] = '';
		
		//Get filename of all photos
		
		
		
		$query = 'SELECT a.id, a.filename, a.extl, a.description' 
		.' FROM #__phocagallery AS a'
		.' LEFT JOIN #__phocagallery_img_votes_statistics AS r ON r.imgid = a.id'
		.' WHERE a.catid='.(int) $catid
		.' AND a.published = 1 AND a.approved = 1'
		. $this->_imgordering['output'];

		$db->setQuery($query);
		$filenameAll = $db->loadObjectList();
		$countImg 	= 0;
		$endComma	= ',';
		if (!empty($filenameAll)) {
			
			$countFilename = count($filenameAll);
			foreach ($filenameAll as $key => $value) {

				$countImg++;
				if ($countImg == $countFilename) {
					$endComma = '';
				}
				
				$filterTags		= '';
				$filterAttrs	= '';
				$filter	= new JFilterInput( $filterTags, $filterAttrs, 1, 1, 1 );
				
				
				
				$description	= $filter->clean( PhocaGalleryText::strTrimAll($value->description), 'html' );
				$description	= addslashes($value->description);
				$description 	= trim($description);
				$description 	= str_replace("\n", '', $description);
				$description 	= str_replace("\r", '', $description);
				if (isset($value->extl) && $value->extl != '') {
					$jsSlideshowData['files'] .= '["'. $value->extl .'", "", "", "'.$description.'"]'.$endComma."\n"; 
				} else {
					$fileThumbnail 	= PhocaGalleryFileThumbnail::getThumbnailName($value->filename, 'large');
					$imgLink		= JURI::base(true) . '/' . $fileThumbnail->rel;
					if (JFile::exists($fileThumbnail->abs)) {
						$jsSlideshowData['files'] .= '["'. $imgLink .'", "", "", "'.$description.'"]'.$endComma."\n"; ; 
					} else {
						$fileThumbnail = JURI::base(true).'/' . "media/com_phocagallery/images/phoca_thumb_l_no_image.png";
						$jsSlideshowData['files'] .= '["'.$fileThumbnail.'", "", "", ""]'.$endComma."\n";
					}
				}
				
				
				
			}
		}
	
		// 2. GET DATA FOR DISPLAYING SLIDESHOW BUTTONS
		//We can display slideshow option if there is more than one foto
		//But in database there can be more photos - more rows but if file is in db but it doesn't exist, we don't count it
		//$countImg = SQLQuery::selectOne($mdb2, "SELECT COUNT(*) FROM $db_gallery WHERE siteid=$id");
		if ($countImg > 1) {
			//Data from GET['COM_PHOCAGALLERY_SLIDESHOW']
			if ($slideshow==1) {
				
				$jsSlideshowData['icons'] = '<div class="pg-imgbgd">' // because of not conflict with beez
				.'<a href="'.JRoute::_($href.'&phocaslideshow=0').'" title="'.JText::_( 'COM_PHOCAGALLERY_STOP_SLIDESHOW' ).'" >'
				
				//.JHTML::_('image', 'media/com_phocagallery/images/icon-stop.png', JText::_( 'COM_PHOCAGALLERY_STOP_SLIDESHOW' )).'</a></div>'
				.PhocaGalleryRenderFront::renderIcon('stop', 'media/com_phocagallery/images/icon-stop.png', JText::_( 'COM_PHOCAGALLERY_STOP_SLIDESHOW' )).'</a></div>'
				.'</td><td align="center">'//.'&nbsp;'
				//.JHTML::_('image', 'media/com_phocagallery/images/icon-play-grey.png', JText::_( 'COM_PHOCAGALLERY_START_SLIDESHOW' ));
				.PhocaGalleryRenderFront::renderIcon('play', 'media/com_phocagallery/images/icon-play-grey.png', JText::_( 'COM_PHOCAGALLERY_STOP_SLIDESHOW' ), 'ph-icon-disabled');
			} else {
				$jsSlideshowData['icons'] = PhocaGalleryRenderFront::renderIcon('stop', 'media/com_phocagallery/images/icon-stop-grey.png', JText::_( 'COM_PHOCAGALLERY_STOP_SLIDESHOW' ), 'ph-icon-disabled')
				//JHTML::_('image', 'media/com_phocagallery/images/icon-stop-grey.png', JText::_( 'COM_PHOCAGALLERY_STOP_SLIDESHOW' ))
				.'</td><td align="center">'//.'&nbsp;'
				.'<div class="pg-imgbgd">' // because of not conflict with beez
				.'<a href="'.JRoute::_($href.'&phocaslideshow=1').'" title="'.JText::_( 'COM_PHOCAGALLERY_START_SLIDESHOW' ).'">'
				
				//. JHTML::_('image', 'media/com_phocagallery/images/icon-play.png', JText::_( 'COM_PHOCAGALLERY_START_SLIDESHOW' )).'</a></div>';
				.PhocaGalleryRenderFront::renderIcon('play', 'media/com_phocagallery/images/icon-play.png', JText::_( 'COM_PHOCAGALLERY_START_SLIDESHOW' )).'</a></div>';
			}
		} else {
			$jsSlideshowData['icons'] = '';
		}
		
		return $jsSlideshowData;//files (javascript) and icons (buttons)		
	}
		
}
?>