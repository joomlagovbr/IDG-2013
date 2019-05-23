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

class PhocaGalleryPath extends JObject
{
	public function __construct() {}

	public static function &getInstance() {
		static $instance;
		if (!$instance) {
			$instance = new PhocaGalleryPath();
			//$baseFront 						= str_replace('/administrator', '', JURI::base(true));
			$baseFront						= JURI::root(true);
			$instance->image_abs 			= JPATH_ROOT . '/images/phocagallery/';
			$instance->image_rel			= 'images/phocagallery/';
			$instance->avatar_abs 			= JPATH_ROOT . '/images/phocagallery/avatars/';
			$instance->avatar_rel			= 'images/phocagallery/avatars/';
			$instance->image_rel_full		= $baseFront  . '/' . $instance->image_rel;
			$instance->image_rel_admin 		= 'media/com_phocagallery/images/administrator/';
			$instance->image_rel_admin_full = $baseFront  . '/' . $instance->image_rel_admin;
			$instance->image_rel_front 		= 'media/com_phocagallery/images/';
			$instance->image_rel_front_full = $baseFront  . '/' . $instance->image_rel_front;
			$instance->image_abs_front		= JPATH_ROOT .'/media/com_phocagallery/images/';

			$instance->media_css_abs		= JPATH_ROOT .'/media/com_phocagallery/css/';
			$instance->media_img_abs		= JPATH_ROOT .'/media/com_phocagallery/images/';
			$instance->media_js_abs			= JPATH_ROOT .'/media/com_phocagallery/js/';
			$instance->media_css_rel		= 'media/com_phocagallery/css/';
			$instance->media_img_rel		= 'media/com_phocagallery/images/';
			$instance->media_js_rel			= 'components/com_phocagallery/assets/';
			$instance->media_css_rel_full	= $baseFront  . '/' . $instance->media_css_rel;
			$instance->media_img_rel_full	= $baseFront  . '/' . $instance->media_img_rel;
			$instance->media_js_rel_full	= $baseFront  . '/' . $instance->media_js_rel;
			$instance->assets_abs			= JPATH_ROOT . '/components/com_phocagallery/assets/';
			$instance->assets_rel			= 'components/com_phocagallery/assets/';
		}

		return $instance;
	}

	public static function getPath() {
		$instance 	= PhocaGalleryPath::getInstance();
		return $instance;
	}

}
?>
