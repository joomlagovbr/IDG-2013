<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 3.0.1
 */

defined('_JEXEC') or die();

class AkeebaHelperEscape
{

	/**
	 * Escapes a string gotten from JText::_() for use with Javascript
	 * @param $string string The string to escape
	 * @param $extras string The characters to escape
	 * @return string
	 */
	static function escapeJS($string, $extras = '')
	{
		static $gpc = null;

		if(is_null($gpc))
		{
			// Fetch the state of Magic Quotes GPC
			if(function_exists('magic_quotes_gpc')) {
				$gpc = magic_quotes_gpc();
			} else {
				$gpc = false;
			}
		}

		// Make sure we escape single quotes, slashes and brackets
		if(empty($extras)) $extras = "'\\[]";
		
		if($gpc) {
			// When Magic Quotes GPC is on, the string is already escaped, so...
			$string = stripslashes($string);
		}
		
		return addcslashes($string, $extras);
	}
}
