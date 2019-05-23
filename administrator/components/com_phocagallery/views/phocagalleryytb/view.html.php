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


class PhocaGalleryCpViewPhocagalleryYtb extends JViewLegacy
{
	protected $field;
	protected $fce;
	protected $context 	= 'com_phocagallery.phocagalleryytjjb';

	public function display($tpl = null) {

		$params = JComponentHelper::getParams( 'com_phocagallery' );
		$app 	= JFactory::getApplication();
		$app->allowCache(false);
		JHTML::stylesheet('media/com_phocagallery/css/administrator/phocagallery.css' );

		$document	= JFactory::getDocument();
		//$document->addCustomTag(PhocaGalleryRenderAdmin::renderIeCssLink(1));

		$this->tmpl['catid']		= JFactory::getApplication()->input->get( 'catid', 0, 'int' );
		$this->tmpl['field']		= JFactory::getApplication()->input->get( 'field', '', 'string');
		$this->tmpl['import']		= JFactory::getApplication()->input->get( 'import', 0, 'int' );



		$this->tmpl['ytblink'] 		= '';
		$this->tmpl['ytbtitle'] 	= '';
		$this->tmpl['ytbdesc'] 		= '';
		$this->tmpl['ytbfilename'] 	= '';

		if ($this->tmpl['import'] == '1') {
			$this->tmpl['ytblink'] = $app->getUserStateFromRequest( $this->context.'.ytb_link', 'ytb_link', $this->tmpl['ytblink'], 'string' );
			$this->tmpl['ytbtitle'] = $app->getUserStateFromRequest( $this->context.'.ytb_title', 'ytb_titel', $this->tmpl['ytbtitle'], 'string' );
			$this->tmpl['ytbdesc'] = $app->getUserStateFromRequest( $this->context.'.ytb_desc', 'ytb_desc', $this->tmpl['ytbdesc'], 'string' );
			$this->tmpl['ytbfilename'] = $app->getUserStateFromRequest( $this->context.'.ytb_filename', 'ytb_filename', $this->tmpl['ytbfilename'], 'string' );
		}

		parent::display($tpl);
	}

}
?>
