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
if (! class_exists('JHtmlGrid')) {
	require_once( JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'html'.DS.'html'.DS.'grid.php' );
}



class PhocaGalleryGrid extends JHtmlGrid
{	
	public static function id($rowNum, $recId, $checkedOut = false, $name = 'cid')
	{
		if ($checkedOut)
		{
			return '';
		}
		else
		{
			return '<input type="checkbox" id="cb' . $rowNum . '" name="' . $name . '[]" value="' . $recId
				. '" onclick="Joomla.isChecked(this.checked, \'undefined\');" title="' . JText::sprintf('JGRID_CHECKBOX_ROW_N', ($rowNum + 1)) . '" />';
			//return '<input type="checkbox" id="cb' . $rowNum . '" name="' . $name . '[]" value="' . $recId
			//	. '"  title="' . JText::sprintf('JGRID_CHECKBOX_ROW_N', ($rowNum + 1)) . '" />';
		}
	}
}
