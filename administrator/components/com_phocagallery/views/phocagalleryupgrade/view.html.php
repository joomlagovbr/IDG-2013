<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view' );

class PhocaGalleryCpViewPhocaGalleryUpgrade extends JViewLegacy
{
	function display($tpl = null) {
		global $mainframe, $option;
		
		JHTML::stylesheet( 'phocagallery.css', 'administrator/components/com_phocagallery/assets/' );

		$uri 	=& JFactory::getURI();
		$tmpl	= array();
		$tmpl2	= array();
		
		$tmpl2['cat-geotitle']		= JRequest::getVar( 'cat-geotitle', 0, 'get', 'int' );
		$tmpl2['cat-zoom']			= JRequest::getVar( 'cat-zoom', 0, 'get', 'int' );
		$tmpl2['cat-longitude']		= JRequest::getVar( 'cat-longitude', 0, 'get', 'int' );
		$tmpl2['cat-latitude']		= JRequest::getVar( 'cat-latitude', 0, 'get', 'int' );
		$tmpl2['cat-userfolder']		= JRequest::getVar( 'cat-userfolder', 0, 'get', 'int' );
		$tmpl2['cat-deleteuserid']	= JRequest::getVar( 'cat-deleteuserid', 0, 'get', 'int' );
		$tmpl2['cat-uploaduserid']	= JRequest::getVar( 'cat-uploaduserid', 0, 'get', 'int' );
		$tmpl2['cat-accessuserid']	= JRequest::getVar( 'cat-accessuserid', 0, 'get', 'int' );
		
		$tmpl2['img-geotitle']		= JRequest::getVar( 'img-geotitle', 0, 'get', 'int' );
		$tmpl2['img-zoom']			= JRequest::getVar( 'img-zoom', 0, 'get', 'int' );
		$tmpl2['img-longitude']		= JRequest::getVar( 'img-longitude', 0, 'get', 'int' );
		$tmpl2['img-latitude']		= JRequest::getVar( 'img-latitude', 0, 'get', 'int' );
		$tmpl2['img-videocode']		= JRequest::getVar( 'img-videocode', 0, 'get', 'int' );
		$tmpl2['img-vmproductid']	= JRequest::getVar( 'img-vmproductid', 0, 'get', 'int' );
		
		$tmpl2['startimg']		= JRequest::getVar( 'startimg', 0, 'get', 'int' );
		$tmpl2['lengthimg']		= JRequest::getVar( 'lengthimg', 100, 'get', 'int' );
		
		
		$tmpl2['startcat']		= JRequest::getVar( 'startcat', 0, 'get', 'int' );
		$tmpl2['lengthcat']		= JRequest::getVar( 'lengthcat', 100, 'get', 'int' );
		
		$tmpl2['task']			= JRequest::getVar( 'task', '', 'get', 'string' );
		$newLengthImg			= 0;
		$newLengthCat			= 0;
		$linkToGallery			= '';
		
		
		// Because there is a pagination we must check more clauses
		// First - where we are: process = 0 ... images, process = 1 ... categories
		// Second - are all items empty (it means no column from image or from categories should be actualized
		//          then got to next step process = 0 -> process = 1 -> process = 2
		// Third - if we end with images we do next step to cateogires, from categories to end (process = 2)
		
		// We begin with images:
		$process = 0;// 0 ... image, 1 ... image
		
		// we are still by images
		if ($tmpl2['task'] == 'convertimg') {
			$process = 0;
		}
		// now we are in categories
		if ($tmpl2['task'] == 'convertcat') {
			$process = 1;
		}
		$tmpl['messageimg']	=	'';
		$tmpl['messagecat']	=	'';
		
	
		$db			=& JFactory::getDBO();
		$dbPref 	= $db->getPrefix();
		$msgSQL 	= '';
		$msgFile	= '';
		$msgError	= '';
		
		// - - - - - - - - - - - - -
		// IMAGES
	
		if ($process == 0 &&
		$tmpl2['img-geotitle']		== 0 &&
		$tmpl2['img-zoom']			== 0 &&
		$tmpl2['img-longitude']		== 0 &&
		$tmpl2['img-latitude']		== 0 &&
		$tmpl2['img-videocode']		== 0 &&
		$tmpl2['img-vmproductid'] == 0) {
			$process = 1;
		}
		
		if ($process == 0) {
		
			// Get count of images
			$msgSQL = '';
			$tmpl['img-count'] = 0;
			$query=' SELECT COUNT(id) FROM #__phocagallery LIMIT 1';
			$db->setQuery( $query );
			if (!$db->query()) {
				$msgSQL .= $db->getErrorMsg(). '<br />';
			}
			$result = $db->loadResult();
			$tmpl['img-count'] = (int)$result - 1;
			
			$newLengthImg 	= $tmpl2['lengthimg'];
			$newStartImg 	= $tmpl2['startimg'];

			
			if ($tmpl2['task']	== 'convertimg') {

				// Select images to convert
				$query=' SELECT * FROM #__phocagallery ORDER BY ordering LIMIT '.(int)$newStartImg  .', '.(int)$newLengthImg ;
				$db->setQuery( $query );
				if (!$db->query()) {
					$msgSQL .= $db->getErrorMsg(). '<br />';
				}
				$imgToConvert = $db->loadObjectList();

				
				$setQuery = array();
				foreach ($imgToConvert as $key => $value) {

					$setCode = array();
					// geotitle
					if ($tmpl2['img-geotitle'] == 1) {
						$geotitle	= PhocaGalleryCpViewPhocaGalleryUpgrade::getParamsArray($value->params, 'geotitle');
						if (isset($geotitle[0]) && $geotitle[0] != '') {
							$setCode[] = 'geotitle = '. $db->Quote($geotitle[0]);
						}
					}
					// zoom
					if ($tmpl2['img-zoom'] == 1) {
						$zoom	= PhocaGalleryCpViewPhocaGalleryUpgrade::getParamsArray($value->params, 'zoom');
						if (isset($zoom[0]) && $zoom[0] != '') {
							$setCode[] = 'zoom = '. $db->Quote($zoom[0]);
						}
					}
					// longitude
					if ($tmpl2['img-longitude'] == 1) {
						$longitude	= PhocaGalleryCpViewPhocaGalleryUpgrade::getParamsArray($value->params, 'longitude');
						if (isset($longitude[0]) && $longitude[0] != '') {
							$setCode[] = 'longitude = '. $db->Quote($longitude[0]);
						}
					}
					// latitude
					if ($tmpl2['img-latitude'] == 1) {
						$latitude	= PhocaGalleryCpViewPhocaGalleryUpgrade::getParamsArray($value->params, 'latitude');
						if (isset($latitude[0]) && $latitude[0] != '') {
							$setCode[] = 'latitude = '. $db->Quote($latitude[0]);
						}
					}
					// videocode
					if ($tmpl2['img-videocode'] == 1) {
						$videocode	= PhocaGalleryCpViewPhocaGalleryUpgrade::getParamsArray($value->params, 'videocode');
						if (isset($videocode[0]) && $videocode[0] != '') {
							$setCode[] = 'videocode = '. $db->Quote($videocode[0]);
						}
					}
					// vmproductid
					if ($tmpl2['img-vmproductid'] == 1) {
						$vmproductid	= PhocaGalleryCpViewPhocaGalleryUpgrade::getParamsArray($value->params, 'vmproductid');
						if (isset($vmproductid[0]) && $vmproductid[0] != '') {
							$setCode[] = 'vmproductid = '. $db->Quote($vmproductid[0]);
						}
					}
					
					if (!empty($setCode)) {
						$setQuery[] = 'UPDATE #__phocagallery SET ' . implode (', ', $setCode) . ' WHERE id = '.(int)$value->id . "\n";
					}
				}
				if (!empty($setQuery)) {
					//$setQueryAll = implode (';', $setQuery);
					foreach ($setQuery as $keyQ =>$valueQ) {
						$db->setQuery( $valueQ );
						if (!$db->query()) {
							$msgSQL .= $db->getErrorMsg(). '<br />';
						}
					}
				}
				$newStartImg = $newStartImg + $newLengthImg;

			}
			
			$tmpl['messageimg'] = '';
			if ($newStartImg > $tmpl['img-count']) {
				if ($msgSQL != '') {
					$tmpl['messageimg']	= '<div style="color:#fc0000;font-weight:bold;">' . $msgSQL . '</div>';
				} else {
					$tmpl['messageimg']	= '<div style="color:#009900;font-weight:bold;">' . JText::_('Data in Phoca Gallery Image table converted') . '</div>';
				}
				$buttonImg = '';
				$process = 1;
			} else {
			
				$endImg = (int)$newStartImg  + (int)$newLengthImg;
				$buttonImg = JText::_('Upgrade') . ': ( '.$newStartImg . ' - '.$endImg.' ) '.JText::_('image(s)');
			}
		}
		
		
		
		
		
		
		
		// - - - - - - - - - - - - -
		// Categories
		if ( $process == 1 &&
		$tmpl2['cat-geotitle']		== 0 &&
		$tmpl2['cat-zoom']			== 0 &&
		$tmpl2['cat-longitude']		== 0 &&
		$tmpl2['cat-latitude']		== 0 &&
		$tmpl2['cat-userfolder']	== 0 &&
		$tmpl2['cat-deleteuserid']	== 0 &&
		$tmpl2['cat-uploaduserid']	== 0 &&
		$tmpl2['cat-accessuserid']	== 0) {
			$process = 2;
		}
		
		if ($process == 1) {
			
			// Get count of images
			$msgSQL = '';
			$tmpl['cat-count'] = 0;
			$query=' SELECT COUNT(id) FROM #__phocagallery_categories LIMIT 1;'."\n";
			$db->setQuery( $query );
			if (!$db->query()) {
				$msgSQL .= $db->getErrorMsg(). '<br />';
			}
			$result = $db->loadResult();
			$tmpl['cat-count'] = (int)$result - 1;
			
			$newLengthCat 	= $tmpl2['lengthcat'];
			$newStartCat 	= $tmpl2['startcat'];

			
			if ($tmpl2['task']	== 'convertcat') {

				// Select images to convert
				$query=' SELECT * FROM #__phocagallery_categories ORDER BY ordering LIMIT '.(int)$newStartCat  .', '.(int)$newLengthCat ;
				$db->setQuery( $query );
				if (!$db->query()) {
					$msgSQL .= $db->getErrorMsg(). '<br />';
				}
				$catToConvert = $db->loadObjectList();
				$setQuery = array();
				foreach ($catToConvert as $key => $value) {

					$setCode = array();
					// geotitle
					if ($tmpl2['cat-geotitle'] == 1) {
						$geotitle	= PhocaGalleryCpViewPhocaGalleryUpgrade::getParamsArray($value->params, 'geotitle');
						if (isset($geotitle[0]) && $geotitle[0] != '') {
							$setCode[] = 'geotitle = '. $db->Quote($geotitle[0]);
						}
					}
					// zoom
					if ($tmpl2['cat-zoom'] == 1) {
						$zoom	= PhocaGalleryCpViewPhocaGalleryUpgrade::getParamsArray($value->params, 'zoom');
						if (isset($zoom[0]) && $zoom[0] != '') {
							$setCode[] = 'zoom = '. $db->Quote($zoom[0]);
						}
					}
					// longitude
					if ($tmpl2['cat-longitude'] == 1) {
						$longitude	= PhocaGalleryCpViewPhocaGalleryUpgrade::getParamsArray($value->params, 'longitude');
						if (isset($longitude[0]) && $longitude[0] != '') {
							$setCode[] = 'longitude = '. $db->Quote($longitude[0]);
						}
					}
					// latitude
					if ($tmpl2['cat-latitude'] == 1) {
						$latitude	= PhocaGalleryCpViewPhocaGalleryUpgrade::getParamsArray($value->params, 'latitude');
						if (isset($latitude[0]) && $latitude[0] != '') {
							$setCode[] = 'latitude = '. $db->Quote($latitude[0]);
						}
					}
					// userfolder
					if ($tmpl2['cat-userfolder'] == 1) {
						$userfolder	= PhocaGalleryCpViewPhocaGalleryUpgrade::getParamsArray($value->params, 'userfolder');
						if (isset($userfolder[0]) && $userfolder[0] != '') {
							$setCode[] = 'userfolder = '. $db->Quote($userfolder[0]);
						}
					}
					// deleteuserid
					if ($tmpl2['cat-deleteuserid'] == 1) {
						$deleteuserid	= PhocaGalleryCpViewPhocaGalleryUpgrade::getParamsArray($value->params, 'deleteuserid');
						if (isset($deleteuserid[0]) && $deleteuserid[0] != '') {
							$setCode[] = 'deleteuserid = '. $db->Quote($deleteuserid[0]);
						}
					}
					// accessuserid
					if ($tmpl2['cat-accessuserid'] == 1) {
						$accessuserid	= PhocaGalleryCpViewPhocaGalleryUpgrade::getParamsArray($value->params, 'accessuserid');
						if (isset($accessuserid[0]) && $accessuserid[0] != '') {
							$setCode[] = 'accessuserid = '. $db->Quote($accessuserid[0]);
						}
					}
					// uploaduserid
					if ($tmpl2['cat-uploaduserid'] == 1) {
						$uploaduserid	= PhocaGalleryCpViewPhocaGalleryUpgrade::getParamsArray($value->params, 'uploaduserid');
						if (isset($uploaduserid[0]) && $uploaduserid[0] != '') {
							$setCode[] = 'uploaduserid = '. $db->Quote($uploaduserid[0]);
						}
					}
					
					
					if (!empty($setCode)) {
						$setQuery[] = 'UPDATE #__phocagallery_categories SET ' . implode (', ', $setCode) . ' WHERE id = '.(int)$value->id . "\n";
					}
				}
				if (!empty($setQuery)) {
					//$setQueryAll = implode (';', $setQuery);
					foreach ($setQuery as $keyQ =>$valueQ) {
						$db->setQuery( $valueQ );
						if (!$db->query()) {
							$msgSQL .= $db->getErrorMsg(). '<br />';
						}
					}
				}
				$newStartCat = $newStartCat + $newLengthCat;

			}
			
			$tmpl['messagecat'] = '';
			if ($newStartCat > $tmpl['cat-count']) {
				if ($msgSQL != '') {
					$tmpl['messagecat']	= '<div style="color:#fc0000;font-weight:bold;">'. $msgSQL . '</div>';
				} else {
					$tmpl['messagecat']	= '<div style="color:#009900;font-weight:bold;">' . JText::_('Data in Phoca Gallery Category table converted') . '</div>';
				}
				$buttonCat = '';
				$process = 2;
			} else {
			
				$endCat = (int)$newStartCat  + (int)$newLengthCat;
				$buttonCat = JText::_('Upgrade') . ': ( '.$newStartCat . ' - '.$endCat.' ) '.JText::_('category(s)');
			}
		}

		// Link
		$linkUpgrade = '';
		foreach ($tmpl2 as $key => $value) {
			if ($value == 1) {
				$linkUpgrade .= '&'.$key.'=1';
			}
		}
		
		if ($linkUpgrade != '') {
			switch ($process) {
				case 0:
					$linkUpgrade = '&task=convertimg&startimg='.$newStartImg.'&lengthimg='.$newLengthImg.$linkUpgrade;
				break;
				case 1:
					$linkUpgrade = '&task=convertcat&startcat='.$newStartCat.'&lengthcat='.$newLengthCat.$linkUpgrade;
				break;
				Default:
					$linkUpgrade = '';
				break;
			}
		}
		if ($linkUpgrade != '') {
			$linkUpgrade = 'index.php?option=com_phocagallery&view=phocagalleryupgrade'.$linkUpgrade;
		}
		
		// Button
		if ($process == 0) {
			//$buttonImg = $buttonImg;
			$buttonCat = '';
		if ($process == 1)
			//$buttonCat = $buttonCat;
			$buttonImg = '';
		}
		if ($process > 1) {
			$buttonImg = '';
			$buttonCat = '';
			$linkToGallery = '<p><a href="index.php?option=com_phocagallery" style="text-decoration:underline">'.JText::_('Go to Phoca Gallery Control Panel').'</a></p>';
		}
	

		$this->assignRef('request_url',	$uri->toString());
		$this->assignRef('linkupgrade',	$linkUpgrade);
		$this->assignRef('buttonimg',	$buttonImg);
		$this->assignRef('buttoncat',	$buttonCat);
		$this->assignRef('linktogallery',	$linkToGallery);
		$this->assignRef('tmpl',	$tmpl);

		parent::display($tpl);
			$this->_setToolbar();
	}
	
	function _setToolbar() {
		JToolBarHelper::title(   JText::_( 'Phoca Gallery Upgrade' ), 'phoca' );
		JToolBarHelper::help( 'screen.phocagallery', true );
	}
	
	function getParamsArray($params='', $param='accessuserid')  {	
		// All params from category / params for userid only
		if ($params != '') {
			$paramsArray	= trim ($params);
			$paramsArray	= explode( ';', $params );
								
			if (is_array($paramsArray))
			{
				foreach ($paramsArray as $value)
				{
					$find = '/'.$param.'=/i';
					$replace = $param.'=';
					
					$idParam = preg_match( "".$find."" , $value );
					if ($idParam) {
						$paramsId = str_replace($replace, '', $value);
						if ($paramsId != '') {
							$paramsIdArray	= trim ($paramsId);
							$paramsIdArray	= explode( ',', $paramsId );
							// Unset empty keys
							foreach ($paramsIdArray as $key2 => $value2)
							{
								if ($value2 == '') {
									unset($paramsIdArray[$key2]);
								}
							}
							
							return $paramsIdArray;
						}
					}
				}
			}
		}
		return array();
	}
}
// utf-8 äëüěščřž
?>
