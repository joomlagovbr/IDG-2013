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

$task		= 'phocagalleryimg';

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

echo '<div class="phoca-thumb-status">' . $this->tmpl['enablethumbcreationstatus'] .'</div>';
//echo '<div class="clearfix"></div>';

echo $r->startForm($option, $tasks, 'adminForm');
echo $r->startFilter();
echo $r->endFilter();

echo $r->startMainContainer();
if (isset($this->tmpl['notapproved']->count) && (int)$this->tmpl['notapproved']->count > 0 ) {
	echo '<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">&times;</a>'.JText::_($OPT.'_NOT_APPROVED_IMAGE_IN_GALLERY').': '
	.(int)$this->tmpl['notapproved']->count.'</div>';
}

echo $r->startFilterBar();
echo $r->inputFilterSearch($OPT.'_FILTER_SEARCH_LABEL', $OPT.'_FILTER_SEARCH_DESC',
							$this->escape($this->state->get('filter.search')));
echo $r->inputFilterSearchClear('JSEARCH_FILTER_SUBMIT', 'JSEARCH_FILTER_CLEAR');
echo $r->inputFilterSearchLimit('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC', $this->pagination->getLimitBox());
echo $r->selectFilterDirection('JFIELD_ORDERING_DESC', 'JGLOBAL_ORDER_ASCENDING', 'JGLOBAL_ORDER_DESCENDING', $listDirn);
echo $r->selectFilterSortBy('JGLOBAL_SORT_BY', $sortFields, $listOrder);

echo $r->startFilterBar(2);
echo $r->selectFilterPublished('JOPTION_SELECT_PUBLISHED', $this->state->get('filter.state'));
echo $r->selectFilterLanguage('JOPTION_SELECT_LANGUAGE', $this->state->get('filter.language'));
echo $r->selectFilterCategory(PhocaGalleryCategory::options($option, 1), 'JOPTION_SELECT_CATEGORY', $this->state->get('filter.category_id'));
echo $r->endFilterBar();

echo $r->endFilterBar();

echo $r->startTable('categoryList');

echo $r->startTblHeader();

echo $r->thOrdering('JGRID_HEADING_ORDERING', $listDirn, $listOrder);
echo $r->thCheck('JGLOBAL_CHECK_ALL');
echo '<th class="ph-image">'.JText::_( $OPT. '_IMAGE' ).'</th>'."\n";
echo '<th class="ph-title">'.JHTML::_('grid.sort',  	$OPT.'_TITLE', 'a.title', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-filename">'.JHTML::_('grid.sort',  	$OPT.'_FILENAME', 'a.filename', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-functions">'.JText::_( $OPT. '_FUNCTIONS' ).'</th>'."\n";
echo '<th class="ph-published">'.JHTML::_('grid.sort',  $OPT.'_PUBLISHED', 'a.published', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-approved">'.JHTML::_('grid.sort',  	$OPT.'_APPROVED', 'a.approved', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-parentcattitle">'.JHTML::_('grid.sort', $OPT.'_CATEGORY', 'category_id', $listDirn, $listOrder ).'</th>'."\n";
//echo '<th class="ph-access">'.JTEXT::_($OPT.'_ACCESS').'</th>'."\n";
echo '<th class="ph-owner">'.JHTML::_('grid.sort',  	$OPT.'_OWNER', 'category_owner_id', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-uploaduser">'.JHTML::_('grid.sort', $OPT.'_UPLOADED_BY', 'uploadusername', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-rating">'.JHTML::_('grid.sort',  	$OPT.'_RATING', 'ratingavg', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-hits">'.JHTML::_('grid.sort',  		$OPT.'_HITS', 'a.hits', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-language">'.JHTML::_('grid.sort',  	'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-id">'.JHTML::_('grid.sort',  		$OPT.'_ID', 'a.id', $listDirn, $listOrder ).'</th>'."\n";

echo $r->endTblHeader();

echo '<tbody>'. "\n";

$originalOrders = array();
$parentsStr 	= "";
$j 				= 0;

if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
		//if ($i >= (int)$this->pagination->limitstart && $j < (int)$this->pagination->limit) {
			$j++;

$urlEdit		= 'index.php?option='.$option.'&task='.$task.'.edit&id=';
$urlTask		= 'index.php?option='.$option.'&task='.$task;
$orderkey   	= array_search($item->id, $this->ordering[$item->catid]);
$ordering		= ($listOrder == 'a.ordering');
$canCreate		= $user->authorise('core.create', $option);
$canEdit		= $user->authorise('core.edit', $option);
$canCheckin		= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange		= $user->authorise('core.edit.state', $option) && $canCheckin;
$linkEdit 		= JRoute::_( $urlEdit. $item->id );
$linkRotate90 	= JRoute::_( $urlTask.'.rotate&angle=90&id='. $item->id );
$linkRotate270 	= JRoute::_( $urlTask.'.rotate&angle=270&id='. $item->id );
$linkDeleteThumbs= JRoute::_( $urlTask.'.recreate&cid[]='. (int)$item->id );

$linkCat	= JRoute::_( 'index.php?option=com_phocagallery&task=phocagalleryc.edit&id='.(int) $item->category_id );
$canEditCat	= $user->authorise('core.edit', $option);


$iD = $i % 2;
echo "\n\n";
//echo '<tr class="row'.$iD.'" sortable-group-id="'.$item->category_id.'" item-id="'.$item->id.'" parents="'.$item->category_id.'" level="0">'. "\n";
echo '<tr class="row'.$iD.'" sortable-group-id="'.$item->category_id.'" >'. "\n";

echo $r->tdOrder($canChange, $saveOrder, $orderkey, $item->ordering);
echo $r->td(JHtml::_('grid.id', $i, $item->id), "small");
echo $r->tdImage($item, $this->button, 'COM_PHOCAGALLERY_ENLARGE_IMAGE');
$checkO = '';
if ($item->checked_out) {
	$checkO .= JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, $tasks.'.', $canCheckin);
}
if ($canCreate || $canEdit) {
	$checkO .= '<a href="'. JRoute::_($linkEdit).'">'. $this->escape($item->title).'</a>';
} else {
	$checkO .= $this->escape($item->title);
}
$checkO .= ' <span class="smallsub">(<span>'.JText::_($OPT.'_FIELD_ALIAS_LABEL').':</span>'. $this->escape($item->alias).')</span>';
echo $r->td($checkO, "small");

if (isset($item->extid) && $item->extid !='') {
    if (isset($item->exttype) && $item->exttype == 2) {
        echo $r->td(JText::_('COM_PHOCAGALLERY_IMGUR_STORED_FILE'));
        echo $r->td('');
    } else if (isset($item->exttype) && $item->exttype == 1) {
		echo $r->td(JText::_('COM_PHOCAGALLERY_FACEBOOK_STORED_FILE'));
		echo $r->td('');
	} else {
		echo $r->td(JText::_('COM_PHOCAGALLERY_PICASA_STORED_FILE'));
		echo $r->td('');
	}
} else {
	echo $r->td($item->filename);
	echo '<td align="center">';

	echo '<a href="'. $linkRotate90 .'" title="'. JText::_( 'COM_PHOCAGALLERY_ROTATE_LEFT' ).'">'
		. JHTML::_( 'image', 'media/com_phocagallery/images/administrator/icon-22-rotate-left.png', JText::_( 'COM_PHOCAGALLERY_ROTATE_LEFT' )).'</a> '
		.'<a href="'. $linkRotate270 .'" title="'. JText::_( 'COM_PHOCAGALLERY_ROTATE_RIGHT' ).'">'
		. JHTML::_( 'image', 'media/com_phocagallery/images/administrator/icon-22-rotate-right.png', JText::_( 'COM_PHOCAGALLERY_ROTATE_RIGHT' )).'</a> '
		.'<a href="'. $linkDeleteThumbs.'" title="'. JText::_( 'COM_PHOCAGALLERY_RECREATE_THUMBS' ).'">'. JHTML::_( 'image', 'media/com_phocagallery/images/administrator/icon-22-remove-create.png', JText::_( 'COM_PHOCAGALLERY_DELETE_RECREATE_THUMBS' )).'</a> '
		.'<a href="#" onclick="window.location.reload(true);" title="'. JText::_( 'COM_PHOCAGALLERY_RELOAD_SITE' ).'">'. JHTML::_( 'image', 'media/com_phocagallery/images/administrator/icon-22-reload.png', JText::_( 'COM_PHOCAGALLERY_RELOAD_SITE' )).'</a>';

	echo '</td>';
}

echo $r->td(JHtml::_('jgrid.published', $item->published, $i, $tasks.'.', $canChange), "small");
echo $r->td(PhocaGalleryJGrid::approved( $item->approved, $i, $tasks.'.', $canChange), "small");

if ($canEditCat) {
	$catO = '<a href="'. JRoute::_($linkCat).'">'. $this->escape($item->category_title).'</a>';
} else {
	$catO = $this->escape($item->category_title);
}
echo $r->td($catO, "small");
//echo $r->td($this->escape($item->access_level), "small");

$usrO = $item->usernameno;
if ($item->username) {$usrO = $usrO . ' ('.$item->username.')';}
echo $r->td($usrO, "small");

$usrU = $item->uploadname;
if ($item->uploadusername) {$usrU = $usrU . ' ('.$item->uploadusername.')';}
echo $r->td($usrU, "small");

echo $r->tdRating($item->ratingavg);
echo $r->td($item->hits, "small");
echo $r->tdLanguage($item->language, $item->language_title, $this->escape($item->language_title));
echo $r->td($item->id, "small");

echo '</tr>'. "\n";

		//}
	}
}
echo '</tbody>'. "\n";

echo $r->tblFoot($this->pagination->getListFooter(), 15);
echo $r->endTable();

echo $this->loadTemplate('batch');

echo $r->formInputs($listOrder, $listDirn, $originalOrders);
echo $r->endMainContainer();
echo $r->endForm();
?>
