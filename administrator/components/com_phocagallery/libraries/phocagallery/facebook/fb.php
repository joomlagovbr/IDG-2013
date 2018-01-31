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
defined( '_JEXEC' ) or die( 'Restricted access' );
if (is_file( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocagallery'.DS.'libraries'.DS.'phocagallery'.DS.'facebook'.DS.'base_facebook.php') &&
is_file( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocagallery'.DS.'libraries'.DS.'phocagallery'.DS.'facebook'.DS.'facebook.php')) {
	if (class_exists('FacebookApiException') && class_exists('Facebook')) {
	} else {
		require_once(  JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocagallery'.DS.'libraries'.DS.'phocagallery'.DS.'facebook'.DS.'base_facebook.php');
		require_once(  JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocagallery'.DS.'libraries'.DS.'phocagallery'.DS.'facebook'.DS.'facebook.php');
	}
}

class PhocaGalleryFb
{
	private static $fb = array();
	
	private function __construct(){}

	public static function getAppInstance($appid, $appsid) {
	
		if( !array_key_exists( $appid, self::$fb ) ) {
			$facebook = new Facebook(array(
			  'appId'  => $appid,
			  'secret' => $appsid,
			  'cookie' => false,
			));
			
			self::$fb[$appid] = $facebook;
		}
		return self::$fb[$appid];
	}
	
	public static function getSession() {
	
		
	}
	
	public static function getFbStatus($appid, $appsid) {
	
		$facebook 	= self::getAppInstance($appid, $appsid);
		
		$fbLogout = JRequest::getVar('fblogout', 0, '', 'int');
		if($fbLogout == 1) {
			$facebook->destroySession();
		}
		
		$fbuser 	= $facebook->getUser();
		
		$session 				= array();
		$session['uid']			= $facebook->getUser();
		$session['secret']		= $facebook->getApiSecret();
		$session['access_token']= $facebook->getAccessToken();

		$output = array();

		
		$u = null;
		// Session based API call.
		if ($fbuser) {
		  try {
			$u = $facebook->api('/me');
		  } catch (FacebookApiException $e) {
			error_log($e);
		  }
		}

		$uri = JURI::getInstance();
		// login or logout url will be needed depending on current user state.
		if ($u) {
			$uid = $facebook->getUser();
			$params = array('next' => $uri->toString() . '&fblogout=1' );
			$logoutUrl = $facebook->getLogoutUrl($params);
		
			$output['log']	= 1;
			$output['html'] = '<div><img src="https://graph.facebook.com/'.  $uid .'/picture" /></div>';
			$output['html'] .= '<div>'. $u['name'].'</div>';
			//$output['html'] .= '<div><a href="'. $logoutUrl .'"><img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif" /></a></div>';
			$output['html'] .= '<div><a href="'. $logoutUrl .'"><span class="btn btn-primary">'.JText::_('COM_PHOCAGALLERY_FB_LOGOUT').'</span></a></div><p>&nbsp;</p>';
			
			/*
			$script = array();
			$fields = array('name', 'uid', 'base_domain', 'secret', 'session_key', 'access_token', 'sig');
			$script[] = 'function clearFbFields() {';
			foreach ($fields as $field) {
				$script[] = ' document.getElementById(\'jform_'.$field.'\').value = \'\';';
			}
			$script[] = '}';

			// Add the script to the document head.
			JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
			$uri = JURI::getInstance();
			$loginUrl = $facebook->getLoginUrl(array('req_perms' => 'user_photos,user_groups,offline_access,publish_stream', 'cancel_url' => $uri->toString(), 'next' => $uri->toString()));
			
			$output['log']	= 0;
			$output['html'] .= '<div><a onclick="clearFbFields()" href="'. $loginUrl .'">Clear and Fill data bu</a></div>';*/
			
		} else {
			
		
			$loginUrl = $facebook->getLoginUrl(array('req_perms' => 'user_photos,user_groups,offline_access,publish_stream,photo_upload,manage_pages', 'scope' => 'user_photos,user_groups,offline_access,publish_stream,photo_upload,manage_pages', 'cancel_url' => $uri->toString(), 'next' => $uri->toString()));
			
			$output['log']	= 0;
			$output['html'] = '<div><a href="'. $loginUrl .'"><span class="btn btn-primary">'.JText::_('COM_PHOCAGALLERY_FB_LOGIN').'</span></a></div><p>&nbsp;</p>';
			//$output['html'] = '<div><a href="'. $loginUrl .'"><img src="http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif" /></a></div>';
	
		}
		$output['u']		= $u;
		$output['session'] 	= $session;
		
		
		return $output;
	}
	
	public function getFbAlbums ($appid, $appidfanpage, $appsid, $session, $aid = 0) {
		$facebook 	= self::getAppInstance($appid, $appsid);
		$facebook->setAccessToken($session['access_token']);
		
		$albums['data'] = array();
		// Change the uid to fan page id => Fan PAGE has other UID
		$userID = $newUID = $session['uid'];
		if (isset($appidfanpage) && $appidfanpage != '') {
			$newUID 	= $appidfanpage;
			$albums = $facebook->api("/".$newUID."/albums");
		} else {
			$albums = $facebook->api("/me/albums");
		}
		
		/* $loginUrl = $facebook->getLoginUrl(array('scope' => 'user_photos'));
		if ($aid > 0) {
			// TODO - if used
			$albums = $facebook->api(array('method' => 'photos.getAlbums', 'aids' => $aid));
		} else {
			//$albums = $facebook->api(array('method' => 'photos.getAlbums', 'uid' => $newUID));
			//$albums = $facebook->api(array('method' => 'photos.getAlbums'));
			$albums = $facebook->api("/me/albums");
			
		} */
		return $albums['data'];
	}
	
	/* BY ID
	public function getFbAlbumsFan ($appid, $appsid, $session, $id = 0) {
		
		$facebook 	= self::getAppInstance($appid, $appsid, $session);
		$facebook->setSession($session);
		$albums 	= false;
		$userID 	= $session['uid'];

		if ($aid > 0) {
			$albums = $facebook->api('/' . $userID . '/albums');
		} else {
			$albums = $facebook->api('/' . $userID . '/albums');
		}
		return $albums['data'];
	}*/
	
	
	 public function getFbAlbumName ($appid, $appsid, $session, $aid) {
		$facebook 	= self::getAppInstance($appid, $appsid);
		$facebook->setAccessToken($session['access_token']);
		//$album = $facebook->api(array('method' => 'photos.getAlbums', 'aids' => $aid));
		$album = $facebook->api("/".$aid);
		$albumName 	= '';
		if (isset($album['name']) && $album['name'] != '') {
			$albumName = $album['name'];
		}
		return $albumName;
	}
	
	public static function getFbImages ($appid, $appsid, $session, &$fbAfter, $aid = 0, $limit = 0 ) {
		$facebook 	= self::getAppInstance($appid, $appsid);
		$facebook->setAccessToken($session['access_token']);
		$images['data'] = array();
		
		if ($aid > 0) {
			//$images = $facebook->api(array('method' => 'photos.get', 'aid' => $aid));
			if ((int)$limit > 0 && $fbAfter != '') {
				$images = $facebook->api("/".$aid."/photos", 'GET', array('limit' => $limit,'after'  => $fbAfter));
			} else if ((int)$limit > 0 && $fbAfter == '') {
				$images = $facebook->api("/".$aid."/photos", 'GET', array('limit' => $limit));
			} else {
				$images = $facebook->api("/".$aid."/photos");
			}
		}
		
		$fbAfter = '';// Unset this variable and check again if there is still new after value (if there are more images to pagination)
		if (isset($images['paging'])) {
			$paging = $images['paging'];
			if (isset($paging['next']) && $paging['next'] != '') {
				$query = parse_url($paging['next'], PHP_URL_QUERY);
				parse_str($query, $parse);
				if (isset($parse['after'])) {
					$fbAfter = $parse['after']; // we return $fbAfter value in reference - new after value is set
				}
			}
		}
		
		return $images['data'];
	}
	
	/*
	public static function getFbImages ($appid, $appsid, $session, $aid = 0) {
		$facebook 	= self::getAppInstance($appid, $appsid);
		$facebook->setAccessToken($session['access_token']);
		$images['data'] = array();
		
		if ($aid > 0) {
			//$images = $facebook->api(array('method' => 'photos.get', 'aid' => $aid));
			$images = $facebook->api("/".$aid."/photos");
		}
		return $images['data'];
	}
	*/
	/*
	public static function getFbImages ($appid, $appsid, $session, $aid = 0) {
		$facebook 	= self::getAppInstance($appid, $appsid);
		$facebook->setAccessToken($session['access_token']);
		$images['data'] = array();
		
		if ($aid > 0) {
			//$images = $facebook->api(array('method' => 'photos.get', 'aid' => $aid));
			//$images = $facebook->api("/".$aid."/photos");
			$limit		= 25;
			$completeI	= array();
			$partI 		= $facebook->api("/".$aid."/photos", 'GET', array('limit' => $limit) );

			$completeI[0] = $partI['data'];
			$i = 1;
			while ($partI['data']) {
				$completeI[1] = $partI['data'];
				$paging 	= $partI['paging'];
				if (isset($paging['next']) && $paging['next'] != '') {
					$query = parse_url($paging['next'], PHP_URL_QUERY);
					parse_str($query, $par); 
					if (isset($parse['limit']) && isset($parse['after'])) {
						$partI = $facebook->api("/".$aid."/photos", 'GET', array('limit' => $parse['limit'],'after'  => $parse['after']));
						$i++;
					}
				}
			}
			
		}
		return $images['data'];
	} */
	
	/* BY ID
	public static function getFbImagesFan ($appid, $appsid, $session, $id = 0) {
		
		
		$facebook 	= self::getAppInstance($appid, $appsid, $session);
		$facebook->setSession($session);
		$images 	= false;
		if ($id > 0) {
            $imagesFolder = $facebook->api('/' . $id . '/photos?limit=0');
			$images = $imagesFolder['data'];
		}
		return $images;
	}*/
	
	public static function exportFbImage ($appid, $appidfanpage, $appsid, $session, $image, $aid = 0) {
		
		$facebook 	= self::getAppInstance($appid, $appsid);
		$facebook->setAccessToken($session['access_token']);	
		$facebook->setFileUploadSupport(true);
		
		// Change the uid to fan page id => Fan PAGE has other UID
		$userID = $newUID = $session['uid'];
		$newToken = $session['access_token'];//Will be changed if needed (for fan page)
		if (isset($appidfanpage) && $appidfanpage != '') {
			$newUID 	= $appidfanpage;
			$params = array('access_token' => $session['access_token']);
			$accounts = $facebook->api('/'.$session['uid'].'/accounts', 'GET', $params);
			
			foreach($accounts['data'] as $account) {
				if( $account['id'] == $appidfanpage || $account['name'] == $appidfanpage ){
					$newToken = $account['access_token'];
				}
			}
			
		}
		
		if ($aid > 0) {
			//$export = $facebook->api(array('method' => 'photos.upload', 'aid' => $aid, 'uid' => $newUID, 'caption' => $image['caption'], $image['filename'] => '@'.$image['fileorigabs']));
				
			$args = array('caption' => $image['caption'], 'aid' => $aid, 'uid' => $newUID, 'access_token' =>  $newToken);
			$args['image'] = '@'.$image['fileorigabs'];
			$export = $facebook->api('/'. $aid . '/photos', 'post', $args);

			return $export;
		}
		return false;
	}
	
	public final function __clone() {
		JError::raiseWarning(500, 'Function Error: Cannot clone instance of Singleton pattern');// No JText - for developers only
		return false;
	}
}