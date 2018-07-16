<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
/*********** XML PARAMETERS AND VALUES ************/
$xml_item = "component";// component | template
$xml_file = "phocagallery.xml";		
$xml_name = "com_phocagallery";
$xml_creation_date = "01/01/2018";
$xml_author = "Jan Pavelka (www.phoca.cz)";
$xml_author_email = "";
$xml_author_url = "www.phoca.cz";
$xml_copyright = "Jan Pavelka";
$xml_license = "GNU/GPL";
$xml_version = "4.3.9";
$xml_description = "Phoca Gallery";
$xml_copy_file = 1;//Copy other files in to administration area (only for development), ./front, ./language, ./other
$xml_script_file = 'install/script.php';

$xml_menu = array (0 => "COM_PHOCAGALLERY", 1 => "option=com_phocagallery", 2 => "media/com_phocagallery/images/administrator/icon-16-pg-menu.png", 3 => 'COM_PHOCAGALLERY', 4 => 'phocagallerycp');
$xml_submenu[0] = array (0 => "COM_PHOCAGALLERY_CONTROLPANEL", 1 => "option=com_phocagallery", 2 => "media/com_phocagallery/images/administrator/icon-16-pg-control-panel.png", 3 => 'COM_PHOCAGALLERY_CONTROLPANEL', 4 => 'phocagallerycp');

$xml_submenu[1] = array (0 => "COM_PHOCAGALLERY_IMAGES", 1 => "option=com_phocagallery&view=phocagalleryimgs", 2 => "media/com_phocagallery/images/administrator/icon-16-pg-menu-gal.png", 3 => 'COM_PHOCAGALLERY_IMAGES', 4 => 'phocagalleryimgs');

$xml_submenu[2] = array (0 => "COM_PHOCAGALLERY_CATEGORIES", 1 => "option=com_phocagallery&view=phocagallerycs", 2 => "media/com_phocagallery/images/administrator/icon-16-pg-menu-cat.png", 3 => 'COM_PHOCAGALLERY_CATEGORIES', 4 => 'phocagallerycs');

$xml_submenu[3] = array (0 => "COM_PHOCAGALLERY_THEMES", 1 => "option=com_phocagallery&view=phocagalleryt", 2 => "media/com_phocagallery/images/administrator/icon-16-pg-menu-theme.png", 3 => 'COM_PHOCAGALLERY_THEMES', 4 => 'phocagalleryt');

$xml_submenu[4] = array (0 => "COM_PHOCAGALLERY_CATEGORYRATING", 1 => "option=com_phocagallery&view=phocagalleryra", 2 => "media/com_phocagallery/images/administrator/icon-16-pg-menu-vote.png", 3 => 'COM_PHOCAGALLERY_CATEGORYRATING', 4 => 'phocagalleryra');

$xml_submenu[5] = array (0 => "COM_PHOCAGALLERY_IMAGERATING", 1 => "option=com_phocagallery&view=phocagalleryraimg", 2 => "media/com_phocagallery/images/administrator/icon-16-pg-menu-vote-img.png", 3 => 'COM_PHOCAGALLERY_IMAGERATING', 4 => 'phocagalleryraimg');

$xml_submenu[6] = array (0 => "COM_PHOCAGALLERY_CATEGORYCOMMENTS", 1 => "option=com_phocagallery&view=phocagallerycos", 2 => "media/com_phocagallery/images/administrator/icon-16-pg-menu-comment.png", 3 => 'COM_PHOCAGALLERY_CATEGORYCOMMENTS', 4 => 'phocagallerycos');

$xml_submenu[7] = array (0 => "COM_PHOCAGALLERY_IMAGECOMMENTS", 1 => "option=com_phocagallery&view=phocagallerycoimgs", 2 => "media/com_phocagallery/images/administrator/icon-16-pg-menu-comment-img.png", 3 => 'COM_PHOCAGALLERY_IMAGECOMMENTS', 4 => 'phocagallerycoimgs');

$xml_submenu[8] = array (0 => "COM_PHOCAGALLERY_USERS", 1 => "option=com_phocagallery&view=phocagalleryusers", 2 => "media/com_phocagallery/images/administrator/icon-16-pg-menu-users.png", 3 => 'COM_PHOCAGALLERY_USERS', 4 => 'phocagalleryusers');

$xml_submenu[9] = array (0 => "COM_PHOCAGALLERY_FB", 1 => "option=com_phocagallery&view=phocagalleryfbs", 2 => "media/com_phocagallery/images/administrator/icon-16-pg-menu-fb.png", 3 => 'COM_PHOCAGALLERY_FB', 4 => 'phocagalleryfbs');

$xml_submenu[10] = array (0 => "COM_PHOCAGALLERY_TAGS", 1 => "option=com_phocagallery&view=phocagallerytags", 2 => "media/com_phocagallery/images/administrator/icon-16-pg-menu-tags.png", 3 => 'COM_PHOCAGALLERY_TAGS', 4 => 'phocagallerytags');

$xml_submenu[11] = array (0 => "COM_PHOCAGALLERY_STYLES", 1 => "option=com_phocagallery&view=phocagalleryefs", 2 => "media/com_phocagallery/images/administrator/icon-16-pg-menu-styles.png", 3 => 'COM_PHOCAGALLERY_STYLES', 4 => 'phocagalleryefs');

$xml_submenu[12] = array (0 => "COM_PHOCAGALLERY_INFO", 1 => "option=com_phocagallery&view=phocagalleryin", 2 => "media/com_phocagallery/images/administrator/icon-16-pg-menu-info.png", 3 => 'COM_PHOCAGALLERY_INFO', 4 => 'phocagalleryin');


$xml_install_file = 'install.phocagallery.php'; 
$xml_uninstall_file = 'uninstall.phocagallery.php';
/*********** XML PARAMETERS AND VALUES ************/
?>