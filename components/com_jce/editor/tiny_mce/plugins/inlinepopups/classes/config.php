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
class WFInlinepopupsPluginConfig {
	public static function getStyles(){	
		$wf = WFEditor::getInstance(); 
		// only required if we're packing css
		if ($wf->getParam('editor.compress_css', 0)) {
			jimport('joomla.filesystem.folder');
			// get UI Theme
			$theme  = $wf->getParam('editor.dialog_theme', 'jce');
			$ui 	= JFolder::files(WF_EDITOR_LIBRARIES . '/css/jquery/' . $theme, '\.css$');

			$file 	= count($ui) ? basename($ui[0]) : '';
	                    
	 		// add ui theme css file
			return array(
				WF_EDITOR_LIBRARIES . '/jquery/css/jquery-ui.custom.css',
				dirname(dirname(__FILE__)) . '/css/dialog.css'
			);
		}
	}
}
?>