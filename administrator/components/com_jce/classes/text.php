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

defined('_JEXEC') or die('RESTRICTED');

abstract class WFText
{
	/**
	 * Transalate a language string.
	 * @param $string 	Language string
	 * @param $default 	Default
	 */
	public static function _($string, $default = '')
	{
		$language = JFactory::getLanguage();

		// replace legacy JCE_ prefix
		$string 	= str_replace('JCE_', 'WF_', $string);		
		$translated = $language->_($string);
		
		if ($translated == $string) {
			if ($default) {
				return $default;
			}
			
			if (strpos($string, 'WF_') !== false) {
				$view = JRequest::getWord('view', '');
				// remove prefix
				$translated = preg_replace(array('#^(WF_)#', '#(LABEL|OPTION|FILEGROUP|' . strtoupper($view) . ')_#', '#_(DESC|TITLE)#'), '', $string);			
				$translated = ucwords(strtolower(str_replace('_', ' ', $translated)));
			}
		}
		
		return $translated;
	}
	
	/**
	 * Translate a string with variables
	 * @param string $string
	 * @copyright 	Copyright (c) 2005 - 2007 Open Source Matters. All rights reserved.
	 */
	public static function sprintf($string)
	{
		$language = JFactory::getLanguage();
		
		$args = func_get_args();
		
		if (count($args) > 0) {
			$args[0] = $language->_($args[0]);
			return call_user_func_array('sprintf', $args);
		}
		
		return '';
	}
}