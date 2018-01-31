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

/*
jimport('joomla.html.grid');
jimport('joomla.html.html.grid');
jimport('joomla.html.html.jgrid');
*/

if (! class_exists('JHtmlJGrid')) {
	require_once( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'html'.DS.'html'.DS.'jgrid.php' );
}

class PhocaGalleryJGrid extends JHtmlJGrid
{
	
	public static function approved($value, $i, $prefix = '', $enabled = true, $checkbox='cb')
	{
		if (is_array($prefix)) {
			$options	= $prefix;
			$enabled	= array_key_exists('enabled',	$options) ? $options['enabled']		: $enabled;
			$checkbox	= array_key_exists('checkbox',	$options) ? $options['checkbox']	: $checkbox;
			$prefix		= array_key_exists('prefix',	$options) ? $options['prefix']		: '';
		}
		$states	= array(
			1	=> array('disapprove',	'COM_PHOCAGALLERY_APPROVED',	'COM_PHOCAGALLERY_NOT_APPROVE_ITEM',	'COM_PHOCAGALLERY_APPROVED',	false,	'publish',		'publish'),
			0	=> array('approve',		'COM_PHOCAGALLERY_NOT_APPROVED',	'COM_PHOCAGALLERY_APPROVE_ITEM',	'COM_PHOCAGALLERY_NOT_APPROVED',	false,	'unpublish',	'unpublish')
		);
		return self::state($states, $value, $i, $prefix, $enabled, true, $checkbox);
	}
	
}
?>