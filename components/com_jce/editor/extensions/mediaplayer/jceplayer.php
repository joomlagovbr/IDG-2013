<?php

/**
 * @package   	JCE
 * @copyright 	Copyright (c) 2009-2013 Ryan Demmer. All rights reserved.
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

defined('_WF_EXT') or die('RESTRICTED');

class WFMediaPlayerExtension_Jceplayer extends WFMediaPlayerExtension
{
	/**
	* Constructor activating the default information of the class
	*
	* @access	protected
	*/
	function __construct($options = array()){
		$options = array(
			'name' 	=> 'jceplayer',
			'title'	=> 'JCE MediaPlayer',
			'params'=> self::getParams()
		);
		
		parent::__construct($options);
	}
	
	function getParams()
	{
		$plugin = WFEditorPlugin::getInstance();
	
		return array(
			'extensions'	=>	$plugin->getParam('jceplayer.extensions', 'flv,f4v,mp3,mp4'),
			'dimensions'	=>	array(
				'audio'	=> array('width'=>	300, 'height' => 35)
			),
			'path'			=>	$plugin->getParam('jceplayer.path', 'media/jce/mediaplayer/mediaplayer.swf')
		);
	}
	
	function isEnabled()
	{
		return true;
	}
}
?>