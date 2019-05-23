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
phocagalleryimport('phocagallery.utils.utils');

class PhocaGalleryImgur
{

	public static function getSize() {


		$paramsC 	= JComponentHelper::getParams('com_phocagallery');
		$lw 		= $paramsC->get( 'large_image_width', 640 );
		$mw 		= $paramsC->get( 'medium_image_width', 100 );
		$sw 		= $paramsC->get( 'small_image_width', 50 );
        $crop 		= $paramsC->get( 'crop_thumbnail', 5 );


        // Small Crop
        if ($crop == 3 || $crop == 5 || $crop == 6 ||$crop == 7) {
            $tbS = 'b';
        } else {
            $tbS = 't';
        }

        // Medium Crop
        if ($crop == 2 || $crop == 4 || $crop == 5 ||$crop == 7) {
            $tbM = 'b';
        } else {
            $tbM = 't';
        }

		$iL         = array('l' => 640, 'h' => 1024);
		$iM         = array($tbM => 160, 'm' => 320, 'l' => 640, 'h' => 1024);
		$iS         = array($tbS => 160);


		$sizes      = array();


		$sizes['s'][0] = $tbS;// default
        $sizes['s'][1] = 160;// default


        $sizes['m'][0] = 'l';// default
        $sizes['m'][1] = 640;// default
		foreach($iM as $k => $v) {
		    if ($v >= $mw) {
		        $sizes['m'][0] = $k;
                $sizes['m'][1] = $v;
		        break;
            }
        }

        $sizes['l'][0] = 'h';// default
        $sizes['l'][1] = '1024';// default
        foreach($iL as $k => $v) {
            if ($v >= $lw) {
                $sizes['l'][0] = $k;
                $sizes['l'][1] = $v;
                break;
            }
        }



        return $sizes;

	}

}
?>
