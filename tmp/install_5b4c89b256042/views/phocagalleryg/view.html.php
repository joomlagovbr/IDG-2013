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
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');

class PhocaGalleryCpViewPhocagalleryG extends JViewLegacy
{
	protected $latitude;
	protected $longitude;
	protected $zoom;
	
	public function display($tpl = null) {

		$params	 			= JComponentHelper::getParams( 'com_phocagallery' );
		$this->latitude		= JFactory::getApplication()->input->get( 'lat', '50.079623358200884', 'get', 'string' );
		$this->longitude	= JFactory::getApplication()->input->get( 'lng', '14.429919719696045', 'get', 'string' );
		$this->zoom			= JFactory::getApplication()->input->get( 'zoom', '2', 'get', 'string' );
		parent::display($tpl);
	}
}
?>