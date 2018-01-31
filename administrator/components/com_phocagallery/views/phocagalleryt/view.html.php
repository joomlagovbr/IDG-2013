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
jimport( 'joomla.application.component.view' );
jimport( 'joomla.client.helper' );
phocagalleryimport( 'phocagallery.image.image' );
class PhocaGalleryCpViewPhocaGalleryT extends JViewLegacy
{

	protected $require_ftp;
	protected $theme_name;
	protected $files;

	public function display($tpl = null) {
		
		$document	= &JFactory::getDocument();
		JHTML::stylesheet( 'media/com_phocagallery/css/administrator/phocagallery.css' );
		JHTML::stylesheet( 'administrator/components/com_phocagallery/assets/jcp/picker.css' );
		$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/jcp/picker.js');
		
		$this->require_ftp	= JClientHelper::setCredentialsFromRequest('ftp');
		$this->files	= $this->get('Files');
		$this->form		= $this->get('Form');
		
		if($this->themeName()) {
			$this->theme_name = $this->themeName();
		}
		// Background Image
		/*
		$params = JComponentHelper::getParams('com_phocagallery');
		
		
		// Small
		$this->tmpl['siw']		= $params->get('small_image_width', 50 );
		$this->tmpl['sih']		= $params->get('small_image_height', 50 );
		
		//After creating an image (post with data);
		$this->tmpl['ssbgc']	= JRequest::getVar( 'ssbgc', '', '', 'string' );
		$this->tmpl['sibgc']	= JRequest::getVar( 'sibgc', '', '', 'string' );
		$this->tmpl['sibrdc']	= JRequest::getVar( 'sibrdc', '', '', 'string' );
		$this->tmpl['sie']		= JRequest::getVar( 'sie', '', '', 'int' );
		$this->tmpl['siec']		= JRequest::getVar( 'siec', '', '', 'string' );
		$siw					= JRequest::getVar( 'siw', '', '', 'int' );
		$sih					= JRequest::getVar( 'sih', '', '', 'int' );
		
		$this->tmpl['ssbgc']	= PhocaGalleryUtils::filterInput($this->tmpl['ssbgc']);
		$this->tmpl['sibgc']	= PhocaGalleryUtils::filterInput($this->tmpl['sibgc']);
		$this->tmpl['sibrdc']	= PhocaGalleryUtils::filterInput($this->tmpl['sibrdc']);
		$this->tmpl['siec']		= PhocaGalleryUtils::filterInput($this->tmpl['siec']);
			
		if($this->tmpl['ssbgc'] 	!= '') 	{$this->tmpl['ssbgc'] = '#'.$this->tmpl['ssbgc'];}
		if($this->tmpl['sibgc'] 	!= '') 	{$this->tmpl['sibgc'] = '#'.$this->tmpl['sibgc'];}
		if($this->tmpl['sibrdc'] 	!= '') 	{$this->tmpl['sibrdc'] = '#'.$this->tmpl['sibrdc'];}
		if($this->tmpl['siec'] 		!= '') 	{$this->tmpl['siec'] = '#'.$this->tmpl['siec'];}
		if ((int)$siw > 0) 			{$this->tmpl['siw'] = (int)$siw;}
		if ((int)$sih > 0) 			{$this->tmpl['sih'] = (int)$sih;}
		
		// Medium
		$this->tmpl['miw']		= $params->get('medium_image_width', 100 );
		$this->tmpl['mih']		= $params->get('medium_image_height', 100 );
		
		//After creating an image (post with data);
		$this->tmpl['msbgc']	= JRequest::getVar( 'msbgc', '', '', 'string' );
		$this->tmpl['mibgc']	= JRequest::getVar( 'mibgc', '', '', 'string' );
		$this->tmpl['mibrdc']	= JRequest::getVar( 'mibrdc', '', '', 'string' );
		$this->tmpl['mie']		= JRequest::getVar( 'mie', '', '', 'int' );
		$this->tmpl['miec']		= JRequest::getVar( 'miec', '', '', 'string' );
		$miw					= JRequest::getVar( 'miw', '', '', 'int' );
		$mih					= JRequest::getVar( 'mih', '', '', 'int' );
		
		$this->tmpl['msbgc']	= PhocaGalleryUtils::filterInput($this->tmpl['msbgc']);
		$this->tmpl['mibgc']	= PhocaGalleryUtils::filterInput($this->tmpl['mibgc']);
		$this->tmpl['mibrdc']	= PhocaGalleryUtils::filterInput($this->tmpl['mibrdc']);
		$this->tmpl['miec']		= PhocaGalleryUtils::filterInput($this->tmpl['miec']);
			
		if($this->tmpl['msbgc']		!= '') 	{$this->tmpl['msbgc'] = '#'.$this->tmpl['msbgc'];}
		if($this->tmpl['mibgc'] 	!= '') 	{$this->tmpl['mibgc'] = '#'.$this->tmpl['mibgc'];}
		if($this->tmpl['mibrdc']	!= '') 	{$this->tmpl['mibrdc'] = '#'.$this->tmpl['mibrdc'];}
		if($this->tmpl['miec'] 		!= '') 	{$this->tmpl['miec'] = '#'.$this->tmpl['miec'];}
		if ((int)$miw > 0) 			{$this->tmpl['miw'] = (int)$miw;}
		if ((int)$mih > 0) 			{$this->tmpl['mih'] = (int)$mih;}*/
		
		$this->addToolbar();		
		parent::display($tpl);
		
	}
	

	protected function addToolbar() {
		
		JToolBarHelper::title(   JText::_( 'COM_PHOCAGALLERY_THEMES' ), 'grid-view-2');
		JToolBarHelper::cancel('phocagalleryt.cancel', 'JTOOLBAR_CLOSE');
		JToolBarHelper::divider();
		JToolBarHelper::help( 'screen.phocagallery', true );
	}
	
	function themeName() {
		// Get an array of all the xml files from teh installation directory
		$path		= PhocaGalleryPath::getPath();
		$xmlFiles 	= JFolder::files($path->image_abs_front, '.xml$', 1, true);
	
		// If at least one xml file exists
		if (count($xmlFiles) > 0) {
			foreach ($xmlFiles as $file)
			{
				// Is it a valid joomla installation manifest file?
				$manifest = $this->_isManifest($file);	
			
				if(!is_null($manifest)) {
					foreach ($manifest->children() as $key => $value){
						if ((string)$value->name() == 'name') {
							return (string)$value;
						}
					}
				}
				return false;
			}
			return false;
		} else {
			return false;
		}
	}
	
	function _isManifest($file) {
		$xml	= JFactory::getXML($file, true);
		if (!$xml) {
			unset ($xml);
			return null;
		}
		if (!is_object($xml) || ($xml->name() != 'install' )) {
			unset ($xml);
			return null;
		}
		return $xml;
	}
}
?>
