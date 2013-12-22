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
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaGalleryComment
{
	function closeTags($comment, $tag, $endTag) {
		if (substr_count(strtolower($comment), $tag) > substr_count(strtolower($comment), $endTag)) {
			$comment .= $endTag;
			$comment = PhocaGalleryComment::closeTags($comment, $tag, $endTag);
		} 
			return $comment;
		
	}
	
	function getSmileys() {
		$smileys = array();
			$smileys[':)'] 		= 'icon-s-smile';
			$smileys[':lol:'] 	= 'icon-s-lol';
			$smileys[':('] 		= 'icon-s-sad';
			$smileys[':?'] 		= 'icon-s-confused';
			$smileys[':wink:'] 	= 'icon-s-wink';
		return $smileys;
	}

	/*
	 * @based based on Seb's BB-Code-Parser script by seb
	 * @url http://www.traum-projekt.com/forum/54-traum-scripts/25292-sebs-bb-code-parser.html 
	 */
	function bbCodeReplace($string, $currentString = '') {
	 
	    while($currentString != $string) {
			$currentString 	= $string;
			$string 		= preg_replace_callback('{\[(\w+)((=)(.+)|())\]((.|\n)*)\[/\1\]}U', array('PhocaGalleryComment', 'bbCodeCallback'), $string);
	    }
	    return $string;
	}

	/*
	 * @based based on Seb's BB-Code-Parser script by seb
	 * @url http://www.traum-projekt.com/forum/54-traum-scripts/25292-sebs-bb-code-parser.html 
	 */
	function bbCodeCallback($matches) {
		$tag 			= trim($matches[1]);
		$bodyString 	= $matches[6];
		$argument 		= $matches[4];
	    
	    switch($tag) {
			case 'b':
			case 'i':
			case 'u':
				$replacement = '<'.$tag.'>'.$bodyString.'</'.$tag.'>';
	            break;

	        Default:    // unknown tag => reconstruct and return original expression
	            $replacement = '[' . $tag . ']' . $bodyString . '[/' . $tag .']';
	            break;
	    }
		return $replacement;
	}
}
?>