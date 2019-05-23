<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
jimport( 'joomla.application.component.view' );
phocagalleryimport( 'phocagallery.render.renderinfo' );

class PhocaGalleryCpViewPhocaGallerycp extends JViewLegacy
{
	protected $t;

	public function display($tpl = null) {

		$this->t	= PhocaGalleryUtils::setVars('cp');
		$this->views= array(
		'imgs'		=> $this->t['l'] . '_IMAGES',
		'cs'		=> $this->t['l'] . '_CATEGORIES',
		't'			=> $this->t['l'] . '_THEMES',
		'ra'		=> $this->t['l'] . '_CATEGORY_RATING',
		'raimg'		=> $this->t['l'] . '_IMAGE_RATING',
		'cos'		=> $this->t['l'] . '_CATEGORY_COMMENTS',
		'coimgs'	=> $this->t['l'] . '_IMAGE_COMMENTS',
		'users'		=> $this->t['l'] . '_USERS',
		///'fbs'		=> $this->t['l'] . '_FB',
		'tags'		=> $this->t['l'] . '_TAGS',
		'efs'	=> $this->t['l'] . '_STYLES',
		'in'		=> $this->t['l'] . '_INFO'
		);


		JHTML::stylesheet( $this->t['s'] );
		//JHTML::_('behavior.tooltip');
		$this->t['version'] = PhocaGalleryRenderInfo::getPhocaVersion();

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		require_once JPATH_COMPONENT.'/helpers/phocagallerycp.php';

		$state	= $this->get('State');
		$canDo	= PhocaGalleryCpHelper::getActions();
		JToolbarHelper ::title( JText::_( 'COM_PHOCAGALLERY_PG_CONTROL_PANEL' ), 'home-2 cpanel' );

		// This button is unnecessary but it is displayed because Joomla! design bug
		$bar = JToolbar::getInstance( 'toolbar' );
		$dhtml = '<a href="index.php?option=com_phocagallery" class="btn btn-small"><i class="icon-home-2" title="'.JText::_('COM_PHOCAGALLERY_CONTROL_PANEL').'"></i> '.JText::_('COM_PHOCAGALLERY_CONTROL_PANEL').'</a>';
		$bar->appendButton('Custom', $dhtml);

		if ($canDo->get('core.admin')) {
			JToolbarHelper ::preferences('com_phocagallery');
			JToolbarHelper ::divider();
		}

		JToolbarHelper ::help( 'screen.phocagallery', true );
	}
}
?>
