<?php
/**
 * @package   Phoca Gallery
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaGalleryException
{
	
	public static function renderErrorInfo ($msg, $jText = false){
	
		if ($jText) {
			return '<div class="pg-error-info">'.JText::_($msg).'</div>';
		} else {
			return '<div class="pg-error-info">'.$msg.'</div>';
		}
	}
}
?>