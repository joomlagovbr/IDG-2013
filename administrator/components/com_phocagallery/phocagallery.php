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
defined('_JEXEC') or die;

if (!JFactory::getUser()->authorise('core.manage', 'com_phocagallery')) {
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 404);
}
if (! class_exists('PhocaGalleryLoader')) {
    require_once( JPATH_ADMINISTRATOR.'/components/com_phocagallery/libraries/loader.php');
}

require_once( JPATH_COMPONENT.'/controller.php' );
phocagalleryimport('phocagallery.utils.settings');
phocagalleryimport('phocagallery.utils.utils');
phocagalleryimport('phocagallery.utils.exception');
phocagalleryimport('phocagallery.path.path');
phocagalleryimport('phocagallery.file.file');
phocagalleryimport('phocagallery.file.filethumbnail');
phocagalleryimport('phocagallery.file.fileupload');
phocagalleryimport('phocagallery.render.renderadmin');
phocagalleryimport('phocagallery.render.renderadminview');
phocagalleryimport('phocagallery.render.renderadminviews');
phocagalleryimport('phocagallery.text.text');
phocagalleryimport('phocagallery.render.renderprocess');
//phocagalleryimport('phocagallery.html.grid');
phocagalleryimport('phocagallery.html.jgrid');
phocagalleryimport('phocagallery.html.category');
phocagalleryimport('phocagallery.html.batch');
phocagalleryimport('phocagallery.image.image');
phocagalleryimport('phocagallery.access.access');

jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('PhocaGalleryCp');

$controller->execute(JFactory::getApplication()->input->get('task'));

$controller->redirect();


?>
