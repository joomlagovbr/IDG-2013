<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');


echo '<div id="phocagallery-upload">'.$this->tmpl['iepx'];

if ($this->tmpl['displayupload'] == 1) {
	if ($this->tmpl['categorypublished'] == 0) {
		echo '<p>'.JText::_('COM_PHOCAGALLERY_YOUR_MAIN_CATEGORY_IS_UNPUBLISHED').'</p>';
	} else if ($this->tmpl['task'] == 'editimg' && $this->tmpl['imageedit']) {

?><h4><?php echo JText::_('COM_PHOCAGALLERY_EDIT'); ?></h4>
<form action="<?php echo htmlspecialchars($this->tmpl['action']);?>" name="phocagalleryuploadform" id="phocagallery-upload-form" method="post" >
<table>
	<tr>
		<td><?php echo JText::_('COM_PHOCAGALLERY_TITLE');?>:</td>
		<td><input type="text" id="imagename" name="imagename" maxlength="255" class="comment-input" value="<?php echo $this->tmpl['imageedit']->title ?>" /></td>
	</tr>

	<tr>
		<td><?php echo JText::_( 'COM_PHOCAGALLERY_DESCRIPTION' ); ?>:</td>
		<td><textarea id="phocagallery-upload-description" name="phocagalleryuploaddescription" onkeyup="countCharsUpload();" cols="30" rows="10" class="comment-input"><?php echo $this->tmpl['imageedit']->description; ?></textarea></td>
	</tr>

	<tr>
		<td>&nbsp;</td>
		<td><?php echo JText::_('COM_PHOCAGALLERY_CHARACTERS_WRITTEN');?> <input name="phocagalleryuploadcountin" value="0" readonly="readonly" class="comment-input2" /> <?php echo JText::_('COM_PHOCAGALLERY_AND_LEFT_FOR_DESCRIPTION');?> <input name="phocagalleryuploadcountleft" value="<?php echo $this->tmpl['maxcreatecatchar'];?>" readonly="readonly" class="comment-input2" />
		</td>
	</tr>

	<tr>
		<td>&nbsp;</td>
		<td align="right"><input type="button" onclick="window.location='<?php echo JRoute::_($this->tmpl['pp'].$this->tmpl['psi']);?>'" id="phocagalleryimagecancel" value="<?php echo JText::_('COM_PHOCAGALLERY_CANCEL'); ?>"/> <input type="submit" onclick="return(checkCreateImageForm());" id="phocagalleryimagesubmit" value="<?php echo JText::_('COM_PHOCAGALLERY_EDIT'); ?>"/></td>
	</tr>
</table>

<?php echo JHtml::_( 'form.token' ); ?>
<input type="hidden" name="task" value="editimage"/>
<input type="hidden" name="controller" value="user" />
<input type="hidden" name="view" value="user"/>
<input type="hidden" name="tab" value="<?php echo $this->tmpl['currenttab']['images'];?>" />
<input type="hidden" name="limitstartsubcat" value="<?php echo $this->tmpl['subcategorypagination']->limitstart;?>" />
<input type="hidden" name="limitstartimage" value="<?php echo $this->tmpl['imagepagination']->limitstart;?>" />
<input type="hidden" name="Itemid" value="<?php echo $this->itemId ?>"/>
<input type="hidden" name="id" value="<?php echo $this->tmpl['imageedit']->id ?>"/>
<input type="hidden" name="parentcategoryid" value="<?php echo $this->tmpl['parentcategoryid'] ?>"/>
<input type="hidden" name="filter_order_image" value="<?php echo $this->listsimage['order']; ?>" />
<input type="hidden" name="filter_order_Dir_image" value="" />
</form>
<?php
	} else {


?><div style="float:left" class="filter-search btn-group pull-left" ><h4><?php echo JText::_( 'COM_PHOCAGALLERY_IMAGES' ); ?></h4>
<form action="<?php echo htmlspecialchars($this->tmpl['action']);?>" method="post" name="phocagalleryimageform" id="phocagalleryimageform">


		<?php /* <td align="left" width="100%"><?php echo JText::_( 'COM_PHOCAGALLERY_FILTER' ); ?>:
		<input type="text" name="phocagalleryimagesearch" id="phocagalleryimagesearch" value="<?php echo $this->listsimage['search'];?>" onchange="document.phocagalleryimageform.submit();" />
		<button onclick="this.form.submit();"><?php echo JText::_( 'COM_PHOCAGALLERY_SEARCH' ); ?></button>
		<button onclick="document.getElementById('phocagalleryimagesearch').value='';document.phocagalleryimageform.submit();"><?php echo JText::_( 'COM_PHOCAGALLERY_RESET' ); ?></button></td>
		<td nowrap="nowrap"><?php echo $this->listsimage['catid']; echo $this->listsimage['state'];?></td> */ ?>


		<div class="filter-search btn-group pull-left">
		<label for="filter_search" class="element-invisible"><?php echo JText::_( 'COM_PHOCAGALLERY_FILTER' ); ?></label>
		<input type="text" name="phocagalleryimagesearch" id="phocagalleryimagesearch" placeholder="<?php echo JText::_( 'COM_PHOCAGALLERY_SEARCH' ); ?>" value="<?php echo $this->listsimage['search'];?>" title="<?php echo JText::_( 'COM_PHOCAGALLERY_SEARCH' ); ?>" /></div>

		<div class="btn-group pull-left hidden-phone">
		<button class="btn tip hasTooltip" type="submit" onclick="this.form.submit();"  title="<?php echo JText::_( 'COM_PHOCAGALLERY_SEARCH' ); ?>"><i class="icon-search  glyphicon glyphicon-search"></i></button>
		<button class="btn tip hasTooltip" type="button" onclick="document.getElementById('phocagalleryimagesearch').value='';document.getElementById(\'phocagalleryimageform\').submit();" title="<?php echo JText::_( 'COM_PHOCAGALLERY_CLEAR' ); ?>"><i class="icon-remove  glyphicon glyphicon-remove"></i></button>
		</div>

		</div><div style="float:right">
		<?php echo $this->listsimage['catid'] ?>
		<br />
		<?php echo $this->listsimage['state']; ?>
		</div>

<table class="adminlist">
<thead>
	<tr>
	<th width="5"><?php echo JText::_( 'COM_PHOCAGALLERY_NUM' ); ?></th>
	<th class="image" width="3%" align="center"><?php echo JText::_( 'COM_PHOCAGALLERY_IMAGE' ); ?></th>
	<th class="title" width="15%"><?php echo PhocaGalleryGrid::sort(  'COM_PHOCAGALLERY_TITLE', 'a.title', $this->listsimage['order_Dir'], $this->listsimage['order'], 'image', 'asc', '', 'phocagalleryimageform', '_image'); ?></th>
	<th width="3%" nowrap="nowrap"><?php echo PhocaGalleryGrid::sort(   'COM_PHOCAGALLERY_PUBLISHED', 'a.published', $this->listsimage['order_Dir'], $this->listsimage['order'], 'image', 'asc', '', 'phocagalleryimageform' , '_image'); ?></th>
	<th width="3%" nowrap="nowrap"><?php echo JText::_('COM_PHOCAGALLERY_DELETE'); ?></th>
	<th width="3%" nowrap="nowrap"><?php echo PhocaGalleryGrid::sort(   'COM_PHOCAGALLERY_APPROVED', 'a.approved', $this->listsimage['order_Dir'], $this->listsimage['order'], 'image', 'asc', '', 'phocagalleryimageform', '_image' ); ?></th>
	<th width="80" nowrap="nowrap" align="center">

	<?php echo PhocaGalleryGrid::sort(   'COM_PHOCAGALLERY_ORDER', 'a.ordering', $this->listsimage['order_Dir'], $this->listsimage['order'],'image', 'asc', '', 'phocagalleryimageform', '_image' );
	//$image = '<img src="'.JURI::base(true).'/'.$this->tmpl['pi'].'icon-filesave.png'.'" width="16" height="16" border="0" alt="'.JText::_( 'COM_PHOCAGALLERY_SAVE_ORDER' ).'" />';

	$image = PhocaGalleryRenderFront::renderIcon('save', $this->tmpl['pi'].'icon-filesave.png', JText::_('COM_PHOCAGALLERY_SAVE_ORDER'));

	$task = 'saveordersubcat';
	$href = '<a href="javascript:saveorderimage()" title="'.JText::_( 'COM_PHOCAGALLERY_SAVE_ORDER' ).'"> '.$image.'</a>';
	echo $href;
	?></th>
	<th width="3%" nowrap="nowrap"><?php echo PhocaGalleryGrid::sort(  'COM_PHOCAGALLERY_CATEGORY' , 'a.catid', $this->listsimage['order_Dir'], $this->listsimage['order'], 'image', 'asc', '', 'phocagalleryimageform', '_image' ); ?></th>

	<th width="1%" nowrap="nowrap"><?php echo PhocaGalleryGrid::sort(   'COM_PHOCAGALLERY_ID', 'a.id', $this->listsimage['order_Dir'], $this->listsimage['order'] , 'image',  'asc', '', 'phocagalleryimageform', '_image'); ?></th>
	</tr>
</thead>

<tbody><?php
$k 		= 0;
$i 		= 0;
$n 		= count( $this->tmpl['imageitems'] );
$rows 	= &$this->tmpl['imageitems'];

if (is_array($rows)) {
	foreach ($rows as $row) {
		$linkEdit 	= JRoute::_( $this->tmpl['pp'].'&task=editimg&id='. $row->slug.$this->tmpl['psi'] );

	?><tr class="<?php echo "row$k"; ?>">
	<td>
		<input type="hidden" id="cb<?php echo $k ?>" name="cid[]" value="<?php echo $row->id ?>" />
		<?php
		echo $this->tmpl['imagepagination']->getRowOffset( $i );?>
	</td>
	<td align="center" valign="middle">
	<?php
	$row->linkthumbnailpath = PhocaGalleryImageFront::displayCategoryImageOrNoImage($row->filename, 'small');
	$imageRes	= PhocaGalleryImage::getRealImageSize($row->filename, 'small');
	$correctImageRes = PhocaGalleryImage::correctSizeWithRate($imageRes['w'], $imageRes['h'], 50, 50);
	//echo JHtml::_( 'image', $row->linkthumbnailpath.'?imagesid='.md5(uniqid(time())),'', array('width' => $correctImageRes['width'], 'height' => $correctImageRes['height']));
	echo '<img src="'.JURI::root().$row->linkthumbnailpath.'?imagesid='.md5(uniqid(time())).'" width="'.$correctImageRes['width'].'" height="'.$correctImageRes['height'].'" alt="" />';

	?>
	</td>

	<td><a href="<?php echo $linkEdit; ?>" title="<?php echo JText::_( 'COM_PHOCAGALLERY_EDIT_IMAGE' ); ?>"><?php echo $row->title; ?></a></td>
	<?php

	// Publish Unpublish
	echo '<td align="center">';
	if ($row->published == 1) {
		echo ' <a title="'.JText::_('COM_PHOCAGALLERY_UNPUBLISH').'" href="'. JRoute::_($this->tmpl['pp'].'&id='.$row->slug.'&task=unpublishimage'. $this->tmpl['psi']).'">';
		//echo JHtml::_('image', $this->tmpl['pi'].'icon-publish.png', JText::_('COM_PHOCAGALLERY_UNPUBLISH'))
		echo PhocaGalleryRenderFront::renderIcon('publish', $this->tmpl['pi'].'icon-publish.png', JText::_('COM_PHOCAGALLERY_UNPUBLISH'))
		.'</a>';
	}
	if ($row->published == 0) {
		echo ' <a title="'.JText::_('COM_PHOCAGALLERY_PUBLISH').'" href="'. JRoute::_($this->tmpl['pp'].'&id='.$row->slug.'&task=publishimage'.$this->tmpl['psi']).'">';
		//echo JHtml::_('image', $this->tmpl['pi'].'icon-unpublish.png', JText::_('COM_PHOCAGALLERY_PUBLISH'))
		echo PhocaGalleryRenderFront::renderIcon('unpublish', $this->tmpl['pi'].'icon-unpublish.png', JText::_('COM_PHOCAGALLERY_PUBLISH'))
		.'</a>';
	}
	echo '</td>';

	// Remove
	echo '<td align="center">';

	// USER RIGHT - Delete (Publish/Unpublish) - - - - - - - - - - -
	// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
	// because we cannot check the access and delete in one time
	$rightDisplayDelete	= 0;
	$user 				= JFactory::getUser();
	$model 				= $this->getModel('user');
	$isOwnerCategory 	= $model->isOwnerCategoryImage((int)$user->id, (int)$row->id);

	$catAccess	= PhocaGalleryAccess::getCategoryAccess((int)$isOwnerCategory);
	if (!empty($catAccess)) {
		$rightDisplayDelete = PhocaGalleryAccess::getUserRight('deleteuserid', $catAccess->deleteuserid, 2, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
	}
	// - - - - - - - - - - - - - - - - - - - - - -

	if ($rightDisplayDelete) {
		echo ' <a onclick="return confirm(\''.JText::_('COM_PHOCAGALLERY_WARNING_DELETE_ITEMS').'\')" title="'.JText::_('COM_PHOCAGALLERY_DELETE').'" href="'. JRoute::_($this->tmpl['pp'].'&id='.$row->slug.'&task=removeimage'.$this->tmpl['psi'] ).'">';
		//echo JHtml::_('image',  $this->tmpl['pi'].'icon-trash.png', JText::_('COM_PHOCAGALLERY_DELETE'))
		echo PhocaGalleryRenderFront::renderIcon('trash', $this->tmpl['pi'].'icon-trash.png', JText::_('COM_PHOCAGALLERY_DELETE') )
		.'</a>';
	} else {
		//echo JHTML::_('image', $this->tmpl['pi'].'icon-trash-g.png', JText::_('COM_PHOCAGALLERY_DELETE'));
		echo PhocaGalleryRenderFront::renderIcon('trash', $this->tmpl['pi'].'icon-trash-g.png', JText::_('COM_PHOCAGALLERY_DELETE'),'ph-icon-disabled');
	}
	echo '</td>';

	// Approved
	echo '<td align="center">';
	if ($row->approved == 1) {
		//echo JHtml::_('image', $this->tmpl['pi'].'icon-publish.png', JText::_('COM_PHOCAGALLERY_APPROVED'));
		echo PhocaGalleryRenderFront::renderIcon('publish', $this->tmpl['pi'].'icon-publish.png', JText::_('COM_PHOCAGALLERY_APPROVED'));
	} else {
		//echo JHtml::_('image', $this->tmpl['pi'].'icon-unpublish.png', JText::_('COM_PHOCAGALLERY_NOT_APPROVED'));
		echo PhocaGalleryRenderFront::renderIcon('unpublish', $this->tmpl['pi'].'icon-unpublish.png', JText::_('COM_PHOCAGALLERY_NOT_APPROVED'));
	}
	echo '</td>';

	$linkUp 	= JRoute::_($this->tmpl['pp'].'&id='.$row->slug.'&task=orderupimage'.$this->tmpl['psi']);
	$linkDown 	= JRoute::_($this->tmpl['pp'].'&id='.$row->slug.'&task=orderdownimage'.$this->tmpl['psi']);

	echo '<td class="order" align="right">'
	.'<span>'. $this->tmpl['imagepagination']->orderUpIcon( $i, ($row->catid == @$this->tmpl['imageitems'][$i-1]->catid), $linkUp, 'COM_PHOCAGALLERY_MOVE_UP', $this->tmpl['imageordering']).'</span> '
	.'<span>'. $this->tmpl['imagepagination']->orderDownIcon( $i, $n, ($row->catid == @$this->tmpl['imageitems'][$i+1]->catid), $linkDown, 'COM_PHOCAGALLERY_MOVE_UP', $this->tmpl['imageordering'] ).'</span> ';

	$disabled = $this->tmpl['imageordering'] ?  '' : 'disabled="disabled"';
	echo '<input type="text" name="order[]" size="5" value="'. $row->ordering.'" '. $disabled.' class="inputbox input-mini" style="text-align: center" />';
	echo '</td>';

	echo '<td align="center">'. $row->category .'</td>';
	echo '<td align="center">'. $row->id .'</td>'
	.'</tr>';

		$k = 1 - $k;
		$i++;
	}
}
?></tbody>
<tfoot>
	<tr>
	<td colspan="9" class="footer"><?php

$this->tmpl['imagepagination']->setTab($this->tmpl['currenttab']['images']);
if (count($this->tmpl['imageitems'])) {
	echo '<div class="pagination pg-center">';
	echo '<div class="pg-inline">'
		.JText::_('COM_PHOCAGALLERY_DISPLAY_NUM') .'&nbsp;'
		.$this->tmpl['imagepagination']->getLimitBox()
		.'</div>';
	echo '<div style="margin:0 10px 0 10px;display:inline;" class="sectiontablefooter'.$this->params->get( 'pageclass_sfx' ).'" >'
		.$this->tmpl['imagepagination']->getPagesLinks()
		.'</div>'
		.'<div style="margin:0 10px 0 10px;display:inline;" class="pagecounter">'
		.$this->tmpl['imagepagination']->getPagesCounter()
		.'</div>';
	echo '</div>';
}

?></td>
	</tr>
</tfoot>
</table>


<?php echo JHtml::_( 'form.token' ); ?>

<input type="hidden" name="controller" value="user" />
<input type="hidden" name="task" value=""/>
<input type="hidden" name="view" value="user"/>
<input type="hidden" name="tab" value="<?php echo $this->tmpl['currenttab']['images'];?>" />
<input type="hidden" name="limitstartsubcat" value="<?php echo $this->tmpl['subcategorypagination']->limitstart;?>" />
<input type="hidden" name="limitstartimage" value="<?php echo $this->tmpl['imagepagination']->limitstart;?>" />
<input type="hidden" name="Itemid" value="<?php echo $this->itemId ?>"/>
<input type="hidden" name="catid" value="<?php echo $this->tmpl['catidimage'] ?>"/>
<input type="hidden" name="filter_order_image" value="<?php echo $this->listsimage['order']; ?>" />
<input type="hidden" name="filter_order_Dir_image" value="" />

</form>
<p>&nbsp;</p>
<?php


	if ((int)$this->tmpl['displayupload'] == 1) {
		echo '<h4>'. JText::_('COM_PHOCAGALLERY_SINGLE_FILE_UPLOAD').'</h4>';
		echo $this->loadTemplate('upload');
	}

	if ((int)$this->tmpl['ytbupload'] > 0) {
		echo '<h4>'. JText::_('COM_PHOCAGALLERY_YTB_UPLOAD').'</h4>';
		echo $this->loadTemplate('ytbupload');
	}

	if((int)$this->tmpl['enablemultiple']  == 1) {
		echo '<h4>'. JText::_('COM_PHOCAGALLERY_MULTPLE_FILE_UPLOAD').'</h4>';
		echo $this->loadTemplate('multipleupload');
	}

	if($this->tmpl['enablejava'] == 1) {
		echo '<h4>'. JText::_('COM_PHOCAGALLERY_JAVA_UPLOAD').'</h4>';
		echo $this->loadTemplate('javaupload');
	}


	}
} else {
	echo '<div>'.JText::_('COM_PHOCAGALLERY_NO_CATEGORY_TO_UPLOAD_IMAGES').'</div>';
	echo '<div>'.JText::_('COM_PHOCAGALLERY_NO_CATEGORY_TO_UPLOAD_IMAGES_ADMIN').'</div>';
}
echo '</div>';
?>
