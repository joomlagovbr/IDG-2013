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

$task		= 'phocagallerycoimg';

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
//echo $r->selectFilterLanguage('JOPTION_SELECT_LANGUAGE', $this->state->get('filter.language'));
echo $r->selectFilterCategory(PhocaGalleryCategory::options($option), 'JOPTION_SELECT_CATEGORY', $this->state->get('filter.category_id'));
echo $r->endFilterBar();

echo $r->endFilterBar();		

echo $r->startTable('categoryList');

echo $r->startTblHeader();

echo $r->thOrdering('JGRID_HEADING_ORDERING', $listDirn, $listOrder);
echo $r->thCheck('JGLOBAL_CHECK_ALL');
echo '<th class="ph-user">'.JHTML::_('grid.sort',  		$OPT.'_USER', 'ua.username', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-title">'.JHTML::_('grid.sort',  	$OPT.'_TITLE', 'a.title', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-date">'.JHTML::_('grid.sort',  		$OPT.'_DATE', 'a.date', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-published">'.JHTML::_('grid.sort',  $OPT.'_PUBLISHED', 'a.published', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-imagetitle">'.JHTML::_('grid.sort', $OPT.'_IMAGE', 'image_title', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-parentcattitle">'.JHTML::_('grid.sort', $OPT.'_CATEGORY', 'category_title', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-id">'.JHTML::_('grid.sort',  		$OPT.'_ID', 'a.id', $listDirn, $listOrder ).'</th>'."\n";

echo $r->endTblHeader();
			
echo '<tbody>'. "\n";

$originalOrders = array();		
$j 				= 0;

if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
		//if ($i >= (int)$this->pagination->limitstart && $j < (int)$this->pagination->limit) {
			$j++;

$urlEdit		= 'index.php?option='.$option.'&task='.$task.'.edit&id=';
$urlTask		= 'index.php?option='.$option.'&task='.$task;
$orderkey   	= array_search($item->id, $this->ordering[$item->image_id]);		
$ordering		= ($listOrder == 'a.ordering');			
$canCreate		= $user->authorise('core.create', $option);
$canEdit		= $user->authorise('core.edit', $option);
$canCheckin		= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange		= $user->authorise('core.edit.state', $option) && $canCheckin;
$linkEdit 		= JRoute::_( $urlEdit. $item->id );
$linkCat		= JRoute::_( 'index.php?option=com_phocagallery&task=phocagalleryc.edit&id='.(int) $item->category_id );
$canEditCat		= $user->authorise('core.edit', $option);
$linkImg		= JRoute::_( 'index.php?option=com_phocagallery&task=phocagalleryimg.edit&id='.(int) $item->image_id );
$canEditImg		= $user->authorise('core.edit', 'com_phocagallery');

$iD = $i % 2;
echo "\n\n";
//echo '<tr class="row'.$iD.'" sortable-group-id="'.$item->image_id.'" item-id="'.$item->id.'" parents="'.$item->image_id.'" level="0">'. "\n";
echo '<tr class="row'.$iD.'" sortable-group-id="'.$item->image_id.'" >'. "\n";
echo $r->tdOrder($canChange, $saveOrder, $orderkey, $item->ordering);
echo $r->td(JHtml::_('grid.id', $i, $item->id), "small");

$usrO = $item->commentname;
if ($item->commentusername) {$usrO = $usrO . ' ('.$item->commentusername.')';}
echo $r->td($usrO, "small");						
$checkO = '';
if ($item->checked_out) {
	$checkO .= JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, $tasks.'.', $canCheckin);
}
if ($canCreate || $canEdit) {
	$checkO .= '<a href="'. JRoute::_($linkEdit).'">'. $this->escape($item->title).' <small>('.$this->escape($item->comment).')</small></a>';
} else {
	$checkO .= $this->escape($item->title). ' <small>('.$this->escape($item->comment).')</small>';
}

echo $r->td($checkO, "small");
echo $r->td($item->date, "small");

echo $r->td(JHtml::_('jgrid.published', $item->published, $i, $tasks.'.', $canChange), "small");

if ($canEditImg) {
	$imgO = '<a href="'. JRoute::_($linkImg).'">'. $this->escape($item->image_title).'</a>';
} else {
	$imgO = $this->escape($item->image_title);
}
echo $r->td($imgO, "small");
if ($canEditCat) {
	$catO = '<a href="'. JRoute::_($linkCat).'">'. $this->escape($item->category_title).'</a>';
} else {
	$catO = $this->escape($item->category_title);
}

echo $r->td($catO, "small");	
echo $r->td($item->id . " - " . $item->ordering." - ", "small");

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
