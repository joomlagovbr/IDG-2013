<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view' );

class phocaGalleryViewphocaGalleryLinkCats extends JViewLegacy
{
	function display($tpl = null) {
		$app	= JFactory::getApplication();
		JHtml::_('behavior.tooltip');
		JHtml::_('behavior.formvalidation');
		JHtml::_('behavior.keepalive');
		JHtml::_('formbehavior.chosen', 'select');

		//Frontend Changes
		$tUri = '';
		if (!$app->isClient('administrator')) {
			$tUri = JURI::base();
			phocagalleryimport('phocagallery.render.renderadmin');
		}


		$document	= JFactory::getDocument();
		$uri		= \Joomla\CMS\Uri\Uri::getInstance();
		JHTML::stylesheet( 'media/com_phocagallery/css/administrator/phocagallery.css' );
		$eName				= $app->input->get('e_name', '', 'cmd');
		$tmpl['ename']		= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
		$tmpl['backlink']	= $tUri.'index.php?option=com_phocagallery&amp;view=phocagallerylinks&amp;tmpl=component&amp;e_name='.$tmpl['ename'];


		// Category Tree
		$db = JFactory::getDBO();
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid'
		. ' FROM #__phocagallery_categories AS a'
	//	. ' WHERE a.published = 1' You can hide not published and not authorized categories too
	//	. ' AND a.approved = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$categories = $db->loadObjectList();

		$tree = array();
		$text = '';
		$tree = PhocaGalleryCategory::CategoryTreeOption($categories, $tree, 0, $text, -1);
		//-----------------------------------------------------------------------

		// Multiple
		$ctrl	= 'hidecategories';
		$attribs	= ' ';
		$attribs	.= ' size="5"';
		//$attribs	.= 'class="'.$v.'"';
		$attribs	.= ' class="inputbox"';
		$attribs	.= ' multiple="multiple"';
		$ctrl		.= '';
		//$value		= implode( '|', )

		$categoriesOutput = JHTML::_('select.genericlist', $tree, $ctrl, $attribs, 'value', 'text', 0, 'hidecategories' );

		$this->assignRef('categoriesoutput',	$categoriesOutput);
		$this->assignRef('tmpl',	$tmpl);
		parent::display($tpl);
	}
}
?>
