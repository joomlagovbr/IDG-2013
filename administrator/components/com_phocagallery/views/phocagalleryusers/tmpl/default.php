<?php
/*
 * @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;

$task		= 'phocagalleryuser';

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$r 			=  new PhocaGalleryRenderAdminViews();
$app		= JFactory::getApplication();
$option 	= $app->input->get('option');
$tasks		= $task . 's';
$OPT		= strtoupper($option);
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', $option);
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder) {
	$saveOrderingUrl = 'index.php?option='.$option.'&task='.$tasks.'.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'categoryList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
}
$sortFields = $this->getSortFields();


echo $r->jsJorderTable($listOrder);

echo $r->startForm($option, $tasks, 'adminForm');
echo $r->startFilter();
echo $r->endFilter();

echo $r->startMainContainer();
echo $r->startFilterBar();
echo $r->inputFilterSearch($OPT.'_FILTER_SEARCH_LABEL', $OPT.'_FILTER_SEARCH_DESC',
							$this->escape($this->state->get('filter.search')));
echo $r->inputFilterSearchClear('JSEARCH_FILTER_SUBMIT', 'JSEARCH_FILTER_CLEAR');
echo $r->inputFilterSearchLimit('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC', $this->pagination->getLimitBox());
echo $r->selectFilterDirection('JFIELD_ORDERING_DESC', 'JGLOBAL_ORDER_ASCENDING', 'JGLOBAL_ORDER_DESCENDING', $listDirn);
echo $r->selectFilterSortBy('JGLOBAL_SORT_BY', $sortFields, $listOrder);

echo $r->startFilterBar(2);
echo $r->selectFilterPublished('JOPTION_SELECT_PUBLISHED', $this->state->get('filter.state'));
echo $r->endFilterBar();

echo $r->endFilterBar();		

echo $r->startTable('categoryList');

echo $r->startTblHeader();

echo $r->thOrdering('JGRID_HEADING_ORDERING', $listDirn, $listOrder);
echo $r->thCheck('JGLOBAL_CHECK_ALL');

echo '<th class="ph-image">'.JText::_('COM_PHOCAGALLERY_AVATAR').'</th>'."\n";
echo '<th class="ph-user">'.JHTML::_('grid.sort',  	$OPT.'_USER', 'ua_username', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-user">'.JText::_('COM_PHOCAGALLERY_CATEGORY_COUNT').'</th>'."\n";
echo '<th class="ph-user">'.JText::_('COM_PHOCAGALLERY_IMAGE_COUNT').'</th>'."\n";
echo '<th class="ph-published">'.JHTML::_('grid.sort',  $OPT.'_PUBLISHED_AVATAR', 'a.published', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-approved">'.JHTML::_('grid.sort',  $OPT.'_APPROVED_AVATAR', 'a.approved', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-id">'.JHTML::_('grid.sort',  		$OPT.'_ID', 'a.id', $listDirn, $listOrder ).'</th>'."\n";

echo $r->endTblHeader();
			
echo '<tbody>'. "\n";

$originalOrders = array();		
$j 				= 0;

if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
		//if ($i >= (int)$this->pagination->limitstart && $j < (int)$this->pagination->limit) {
			$j++;

$orderkey   	= array_search($item->id, $this->ordering[0]);		
$ordering		= ($listOrder == 'a.ordering');			
$canCheckin		= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange		= $user->authorise('core.edit.state', $option) && $canCheckin;

$iD = $i % 2;
echo "\n\n";
//echo '<tr class="row'.$iD.'" sortable-group-id="0" item-id="'.$item->id.'" parents="0" level="0">'. "\n";
echo '<tr class="row'.$iD.'" sortable-group-id="0" >'. "\n";

echo $r->tdOrder($canChange, $saveOrder, $orderkey, $item->ordering);
echo $r->td(JHtml::_('grid.id', $i, $item->id), "small");
echo $r->tdImage($item, $this->button, 'COM_PHOCAGALLERY_ENLARGE_IMAGE', '', $this->tmpl['avatarpathabs'], $this->tmpl['avatarpathrel']);	


$usrO = $item->username;
if ($item->usernameno) {$usrO = $usrO . ' ('.$item->usernameno.')';}
echo $r->td($usrO, "small");

if ($item->countcid) {$countCid = $item->countcid;} else {$countCid = '0';}					
echo $r->td($countCid, "small");

if ($item->countiid) {$countIid = $item->countiid;} else {$countIid = '0';}					
echo $r->td($countIid, "small");

echo $r->td(JHtml::_('jgrid.published', $item->published, $i, $tasks.'.', $canChange), "small");
echo $r->td(PhocaGalleryJGrid::approved( $item->approved, $i, $tasks.'.', $canChange), "small");

echo $r->td($item->id, "small");


echo '</tr>'. "\n";
						
		//}
	}
}
echo '</tbody>'. "\n";

echo $r->tblFoot($this->pagination->getListFooter(), 15);
echo $r->endTable();


echo $r->formInputs($listOrder, $listDirn, $originalOrders);
echo $r->endMainContainer();
echo $r->endForm();
?>