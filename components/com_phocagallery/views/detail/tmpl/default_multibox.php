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
echo '<div id="phocagallery" class="pg-detail-view-multibox'.$this->params->get( 'pageclass_sfx' ).'">';
if ($this->tmpl['backbutton'] != '') {
	echo $this->tmpl['backbutton'];
	echo '<p>&nbsp;</p>';
}

// Min Width
$wRightO = $wLeftO = $wLeftOIE = '';
if ($this->tmpl['multibox_fixed_cols'] == 1) {
	$wLeft 		= $this->tmpl['large_image_width'];
	$wRight 	= (int)$this->tmpl['multibox_width'] - (int)$wLeft - 20;//margin 4 x 5px
	$wRightO 	= 'min-width: '.$wRight.'px';
	$wLeftO 	= 'min-width: '.$wLeft.'px';
	$wLeftOIE	= 'width: '.$wLeft.'px';

	// IE7

	$document = JFactory::getDocument();
	$document->addCustomTag("<!--[if lt IE 8 ]>\n<style type=\"text/css\"> \n"
			." #phocagallery.pg-detail-view-multibox table tr td.pg-multibox-lefttd {"
			." ".$wLeftOIE."; "
			."} \n"
			." </style>\n<![endif]-->");
}


// - - - - -
// LEFT
// - - - - -
echo '<table id="pg-multibox-table" cellpadding="0" cellspacing="0" border="0" style="height: '.$this->tmpl['multibox_height'].'px;">'. "\n";
echo '<tr>'. "\n";

echo '<td valign="middle" align="center" class="pg-multibox-lefttd pg-dv-multibox-left">'. "\n";

$over = 'onmouseover="document.getElementById(\'phocagallerymultiboxnext\').style.display = \'block\';document.getElementById(\'phocagallerymultiboxprev\').style.display = \'block\';" ';
$out = 'onmouseout="document.getElementById(\'phocagallerymultiboxnext\').style.display = \'none\';document.getElementById(\'phocagallerymultiboxprev\').style.display = \'none\';"';

echo '<div class="pg-multibox-left pg-dv-multibox-left" '.$over.$out.' style="'.$wLeftO.'">';

if ($this->item->download	 == 1) {
	echo $this->loadTemplate('download');
} else {

	if ($this->item->videocode != '') {
		$this->item->linkimage = $this->item->videocode;
	}

	if ($this->item->nextbuttonhref != '') {
		echo '<a href="'.$this->item->nextbuttonhref.'">'.$this->item->linkimage.'</a>';
	} else {
		echo '<span >'.$this->item->linkimage.'</span>';
	}

	echo $this->item->prevbutton;
	echo $this->item->nextbutton;
}
echo '</div>'. "\n";
echo '</td>'. "\n";

// - - - - -
// RIGHT
// - - - - -
echo '<td valign="top" class="pg-multibox-righttd  pg-dv-multibox-right">'. "\n";
echo '<div class="pg-multibox-right pg-dv-multibox-right" style="height: '.$this->tmpl['multibox_height_overflow'].'px;'.$wRightO.'">'. "\n";

// Title
if ($this->tmpl['mb_title']) {
echo '<div class="pg-multibox-title">'.$this->item->title.'</div>'. "\n";
}

// Description
if ($this->tmpl['mb_desc']) {

//echo '<div class="pg-multibox-desc">'.$this->item->description.'</div>'. "\n";
echo '<div class="pg-multibox-desc">'.JHtml::_('content.prepare', $this->item->description, 'com_phocagallery.item').'</div>'. "\n";
}

// Uploaded By
if ($this->tmpl['mb_uploaded_by']) {
	echo '<div class="pg-multibox-user" >' . JText::_('COM_PHOCAGALLERY_UPLOADED_BY') . '</div>';
	if ($this->tmpl['useravatarimg'] != '') {
	echo '<div class="pg-multibox-avatar">'.$this->tmpl['useravatarimg'].'</div>';
	}
	echo '<div class="pg-multibox-username" style="padding-top: '.$this->tmpl['useravatarmiddle'].'px;">'.$this->item->usernameno.'</div>';
	echo '<div style="clear:both"></div>';
}

// Rating
if ($this->tmpl['mb_rating']) {
	echo "\n";
	echo $this->loadTemplate('rating');
	echo "\n";
}

//Thumbnails
if ($this->tmpl['mb_thumbs']) {


	if (!empty($this->tmpl['mb_thumbs_data'])) {
		echo '<div class="pg-multibox-thumbs-box" style="width: '.(int)$this->tmpl['multibox_thubms_box_width'].'px;">';

		foreach ($this->tmpl['mb_thumbs_data'] as $k => $v) {
			$extImage = PhocaGalleryImage::isExtImage($v->extid);

			//$altValue	= PhocaGalleryRenderFront::getAltValue($this->tmpl['altvalue'], $v->title, $v->description, $v->metadesc);
			$altValue = '';//Save resources - not necessary
			if ($extImage) {
				$img = JHtml::_( 'image', $v->exts, $altValue);
			} else {
				$linkthumbnailpath	= PhocaGalleryImageFront::displayCategoryImageOrNoImage($v->filename, 'small');
				$img = JHtml::_( 'image', $linkthumbnailpath, $altValue);
			}

			if ($this->tmpl['detailwindow'] == 7) {
				$tmplCom = '';
			} else {
				$tmplCom = '&tmpl=component';
			}

			echo '<div class="pg-multibox-thumbs-item"><a href="'.JRoute::_('index.php?option=com_phocagallery&view=detail&catid='. $v->catslug.'&id='.$v->slug.$tmplCom.'&Itemid='. $this->itemId).'">';
			echo $img;
			echo '</a></div>';

		}
		echo '<div style="clear:both"></div>';
		echo '</div>';
	}
}

// Map
if ($this->tmpl['mb_maps']) {
	$src = JRoute::_('index.php?option=com_phocagallery&view=map&catid='.$this->item->catid
	.'&id='.$this->item->id.'&mapwidth='
	.$this->tmpl['multibox_map_width'].'&mapheight='
	.$this->tmpl['multibox_map_height'].'&tmpl=component&Itemid='
	.$this->itemId );
	$this->tmpl['multibox_map_width'] = $this->tmpl['multibox_map_width'] + 20;
	$this->tmpl['multibox_map_height'] = $this->tmpl['multibox_map_height'] + 10;
	echo '<iframe src="'.$src.'" width="'
	.$this->tmpl['multibox_map_width'].'" height="'
	.$this->tmpl['multibox_map_height'].'" frameborder="0" style="border:none; overflow:hidden;padding:0px;margin:0px;" name="pgmap"></iframe>'. "\n";
}

// Tags
if ($this->tmpl['mb_tags'] && $this->tmpl['displaying_tags_output'] != '') {
	echo '<div class="pg-multibox-tags-box">';
	echo '<div class="pg-multibox-tags" >' . JText::_('COM_PHOCAGALLERY_TAGS') . '</div>';
	echo '<div class="pg-detail-tags-multibox">'.$this->tmpl['displaying_tags_output'].'</div>';
	echo '</div>';
}

// Comments
if ($this->tmpl['mb_comments']) {
	echo '<div class="pg-multibox-comments">';
	if ((int)$this->tmpl['externalcommentsystem'] == 2) {
		echo $this->loadTemplate('comments-fb');
	} else if ((int)$this->tmpl['externalcommentsystem'] == 1) {
		if (JComponentHelper::isEnabled('com_jcomments', true)) {
			include_once(JPATH_BASE.'/components/com_jcomments/jcomments.php');
			echo JComments::showComments($this->item->id, 'com_phocagallery_images', JText::_('COM_PHOCAGALLERY_IMAGE') .' '. $this->item->title);
		}
	} else {
		$src = JRoute::_('index.php?option=com_phocagallery&view=comment&catid='.$this->item->catid.'&id='.$this->item->id.'&tmpl=component&commentsi=1&Itemid='. $this->itemId );
		echo '<iframe src="'.$src.'" width="'.$this->tmpl['multibox_comments_width'].'" height="'.$this->tmpl['multibox_comments_height'].'" frameborder="0" class="pg-multibox-comments-iframe" name="pgcomment"></iframe>'. "\n";
	}
	echo '</div>';
}


echo '</div>'. "\n";
echo '</td>'. "\n";

echo '</tr>'. "\n";
echo '</table>'. "\n";

echo '</div>'. "\n";

if ($this->tmpl['detailwindow'] == 7) {
	echo '<p>&nbsp;</p>';
    echo PhocaGalleryUtils::getInfo();
}
?>
