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


class PhocaGalleryFbSystem
{	
	public function setSessionData($data) {
		
		$session = array();
		// Don't set the session, in other way the SIG will be not the same
		//$session['uid']				= $session['base_domain'] 	= $session['secret']	= '';
		//$session['access_token'] 	= $session['session_key'] 	= $session['sig']		= '';
		$session['expires']			= 0;

		
		if (isset($data->uid) && $data->uid != '') 					{$session['uid']			= $data->uid;}
		if (isset($data->base_domain) && $data->base_domain != '') 	{$session['base_domain']	= $data->base_domain;}
		if (isset($data->secret) && $data->secret != '') 			{$session['secret']			= $data->secret;}
		if (isset($data->session_key) && $data->session_key != '') 	{$session['session_key']	= $data->session_key;}
		if (isset($data->access_token) && $data->access_token != ''){$session['access_token']	= $data->access_token;}
		if (isset($data->sig) && $data->sig != '') 					{$session['sig']			= $data->sig;}
		
		ksort($session);
		return $session;
	}
	
	public function getFbUserInfo ($id) {
		
		$db = &JFactory::getDBO();

       //build the list of categories
		$query = 'SELECT a.*'
		. ' FROM #__phocagallery_fb_users AS a'
		. ' WHERE a.id ='.(int)$id;
		$db->setQuery( $query );
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		$item = $db->loadObject();
		return $item;
	}
	
	public function getCommentsParams($id) {
	
		$o = array();
		$item = self::getFbUserInfo($id);
		
		if(isset($item->appid)) {
			$o['fb_comment_app_id'] = $item->appid;
		}
		if(isset($item->comments) && $item->comments != '') {
			$registry = new JRegistry;
			$registry->loadString($item->comments);
			$item->comments = $registry->toArray();
			foreach($item->comments as $key => $value) {
				$o[$key] = $value;
			}
			
		}
		return $o;
	}
	
	public function getImageFromCat($idCat, $idImg = 0) {
	
		$db = &JFactory::getDBO();
		
		$nextImg = '';
		if ($idImg > 0) {
			$nextImg = ' AND a.id > '.(int)$idImg;
		}
		
		$query = 'SELECT a.*'
			.' FROM #__phocagallery AS a'
			.' WHERE a.catid = '.(int) $idCat
			.' AND a.published = 1'
			.' AND a.approved = 1'
			. $nextImg
			.' ORDER BY a.id ASC LIMIT 1';
			
		$db->setQuery( $query );
		$item = $db->loadObject();
		
		if(!isset($item->id) || (isset($item->id) && $item->id < 1)) {
			$img['end'] = 1;
			return $img;
		}
	
		if (isset($item->description) && $item->description != '') {
			$img['caption']	= $item->title .  ' - ' .$item->description;
		} else {
			$img['caption']	= $item->title;
		}
		//TODO TEST EXT IMAGE
		if (isset($item->extid) && $item->extid != '') {
			$img['extid']		= $item->extid;
		}
		$img['id']				= $item->id;
		$img['title']			= $item->title;
		$img['filename']		= PhocaGalleryFile::getTitleFromFile($item->filename, 1);
		$img['fileorigabs']		= PhocaGalleryFile::getFileOriginal($item->filename);
		
		return $img;
	
	}
	
	/* 
	 * Used while pagination
	 */
	function renderProcessPage($id, $refreshUrl, $countInfo = '', $import = 0) {
	
		if ($import == 0) {
			$stopText = JText::_( 'COM_PHOCAGALLERY_STOP_UPLOADING_FACEBOOK_IMAGES' );
			$dataText = JText::_('COM_PHOCAGALLERY_FB_UPLOADING_DATA');
		} else {
			$stopText = JText::_( 'COM_PHOCAGALLERY_STOP_IMPORTING_FACEBOOK_IMAGES' );
			$dataText = JText::_('COM_PHOCAGALLERY_FB_IMPORTING_DATA');
		}
		
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
		echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-en" lang="en-en" dir="ltr" >'. "\n";
		echo '<head>'. "\n";
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'. "\n\n";
		echo '<title>'.$dataText.'</title>'. "\n";
		echo '<link rel="stylesheet" href="'.JURI::root(true).'/media/com_phocagallery/css/administrator/phocagallery.css" type="text/css" />';

		echo '</head>'. "\n";
		echo '<body>'. "\n";
		
		echo '<div style="text-align:right;padding:10px"><a style="font-family: sans-serif, Arial;font-weight:bold;color:#fc0000;font-size:14px;" href="index.php?option=com_phocagallery&task=phocagalleryc.edit&id='.(int)$id.'">' .$stopText.'</a></div>';
		
		echo '<div id="loading-ext-img-processp" style="font-family: sans-serif, Arial;font-weight:normal;color:#666;font-size:14px;padding:10px"><div class="loading"><div><center>'. JHTML::_('image', 'media/com_phocagallery/images/administrator/icon-loading.gif', JText::_('COM_PHOCAGALLERY_LOADING') ) .'</center></div><div>&nbsp;</div><div><center>'.$dataText.'</center></div>';
		
		echo $countInfo;
		echo '</div></div>';
		
		echo '<meta http-equiv="refresh" content="2;url='.$refreshUrl.'" />';
		echo '</body></html>';
		exit;
	}
}