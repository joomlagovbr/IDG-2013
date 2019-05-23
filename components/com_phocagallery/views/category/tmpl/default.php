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
echo '<div id="phocagallery" class="pg-category-view'.$this->params->get( 'pageclass_sfx' ).' pg-cv">';
// Heading
$heading = '';



if ($this->params->get( 'page_heading' ) != '') {
	$heading .= $this->params->get( 'page_heading' );
}

// Category Name Title
if ( $this->tmpl['display_cat_name_title'] == 1) {
	if (isset($this->category->title) && $this->category->title != '') {
		if ($heading != '') {
			$heading .= ' - ';
		}
		$heading .= $this->category->title;
	}
}
// Pagetitle
if ($this->tmpl['show_page_heading'] != 0) {
	if ( $heading != '') {
		echo '<div class="page-header"><h1>'. $this->escape($heading) . '</h1></div>';
	}
}

if (isset($this->category->id) && (int)$this->category->id > 0) {

	echo '<div id="pg-icons">';
	echo PhocaGalleryRenderFront::renderFeedIcon('category', 1, $this->category->id, $this->category->alias);
	echo '</div>';

}
echo '<div style="clear:both"></div>';

// Category Description
if (isset($this->category->description) && $this->category->description != '' ) {
	echo '<div class="pg-cv-desc">'. JHTML::_('content.prepare', $this->category->description) .'</div>'. "\n";
}

$this->checkRights = 1;

if ((int)$this->tagId > 0) {

	// Search by tags
	$this->checkRights = 1;

	// Categories View in Category View
	if ($this->tmpl['display_categories_cv']) {
		echo $this->loadTemplate('categories');
	}

	echo $this->loadTemplate('images');
	echo '<div class="ph-cb"></div><div>&nbsp;</div>';
	echo $this->loadTemplate('pagination');
	//echo '</div>'. "\n";

} else {
	// Standard category displaying
	$this->checkRights = 0;


	// Switch image
	$noBaseImg 	= false;
	$noBaseImg	= preg_match("/phoca_thumb_l_no_image/i", $this->tmpl['basic_image']);
	if ($this->tmpl['switch_image'] == 1 && $noBaseImg == false) {
		$switchImage = PhocaGalleryImage::correctSwitchSize($this->tmpl['switch_height'], $this->tmpl['switch_width']);
		echo '<div class="main-switch-image"><center>'
			.'<table border="0" cellspacing="5" cellpadding="5" class="main-switch-image-table">'
			.'<tr>'
			.'<td align="center" valign="middle" style="text-align:center;'
			.'width: '.$switchImage['width'].'px;'
			.'height: '.$switchImage['height'].'px;'
			.'background: url(\''.$this->tmpl['wait_image'].'\') '
			.$switchImage['centerw'] .'px '
			.$switchImage['centerh'].'px no-repeat;margin:0px;padding:0px;">'
			.$this->tmpl['basic_image'] .'</td>'
			.'</tr></table></center></div>'. "\n";
	}

	// Categories View in Category View
	if ($this->tmpl['display_categories_cv']) {
		echo $this->loadTemplate('categories');
	}


	// Rendering images
	echo $this->loadTemplate('images');


	echo '<div class="ph-cb">&nbsp;</div>';


	echo $this->loadTemplate('pagination');

	if ($this->tmpl['displaytabs'] > 0) {
		echo '<div id="phocagallery-pane">';
		echo JHtml::_('tabs.start', 'config-tabs-com_phocagallery-category', array('useCookie'=>1, 'startOffset'=> $this->tmpl['tab']));

		if ((int)$this->tmpl['display_rating'] == 1) {
			echo JHtml::_('tabs.panel', PhocaGalleryRenderFront::renderIcon('vote', 'media/com_phocagallery/images/icon-vote.png', ''). '&nbsp;'. JText::_('COM_PHOCAGALLERY_RATING'), 'pgvotes' );

			echo $this->loadTemplate('rating');
		}

		if ((int)$this->tmpl['display_comment'] == 1) {
			//$commentImg = ($this->tmpl['externalcommentsystem'] == 2) ? 'icon-comment-fb' : 'icon-comment';
			//echo JHtml::_('tabs.panel', JHtml::_( 'image', 'media/com_phocagallery/images/'.$commentImg.'.png','') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_COMMENTS'), 'pgcomments' );

			if ($this->tmpl['externalcommentsystem'] == 2) {
				echo JHtml::_('tabs.panel', PhocaGalleryRenderFront::renderIcon('comment-fb', 'media/com_phocagallery/images/icon-comment-fb-small.png', ''). '&nbsp;'.JText::_('COM_PHOCAGALLERY_COMMENTS'), 'pgcomments' );
			} else {
				echo JHtml::_('tabs.panel', PhocaGalleryRenderFront::renderIcon('comment', 'media/com_phocagallery/images/icon-comment.png', ''). '&nbsp;'.JText::_('COM_PHOCAGALLERY_COMMENTS'), 'pgcomments' );
			}


			if ($this->tmpl['externalcommentsystem'] == 1) {
				if (JComponentHelper::isEnabled('com_jcomments', true)) {
					include_once(JPATH_BASE.'/components/com_jcomments/jcomments.php');
					echo JComments::showComments($this->category->id, 'com_phocagallery', JText::_('COM_PHOCAGALLERY_CATEGORY') .' '. $this->category->title);
				}
			} else if($this->tmpl['externalcommentsystem'] == 2) {
				echo $this->loadTemplate('comments-fb');
			} else {
				echo $this->loadTemplate('comments');
			}
		}

		if ((int)$this->tmpl['displaycategorystatistics'] == 1) {
			//echo JHtml::_('tabs.panel', JHtml::_( 'image', 'media/com_phocagallery/images/icon-statistics.png', '') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_STATISTICS'), 'pgstatistics' );
			echo JHtml::_('tabs.panel', PhocaGalleryRenderFront::renderIcon('statistics', 'media/com_phocagallery/images/icon-statistics.png', '') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_STATISTICS'), 'pgstatistics' );

			echo $this->loadTemplate('statistics');
		}

		if ((int)$this->tmpl['displaycategorygeotagging'] == 1) {
			if ($this->map['longitude'] == '' || $this->map['latitude'] == '') {
				//echo '<p>' . JText::_('COM_PHOCAGALLERY_ERROR_MAP_NO_DATA') . '</p>';
			} else {
				//echo JHtml::_('tabs.panel', JHtml::_( 'image', 'media/com_phocagallery/images/icon-geo.png','') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_GEOTAGGING'), 'pggeotagging' );
				echo JHtml::_('tabs.panel', PhocaGalleryRenderFront::renderIcon('geo', 'media/com_phocagallery/images/icon-geo.png', '') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_GEOTAGGING'), 'pggeotagging' );
				
				if ($this->tmpl['map_type'] == 2){
					echo $this->loadTemplate('geotagging_osm');
				} else {
					echo $this->loadTemplate('geotagging');
				}
				
			}
		}
		if ((int)$this->tmpl['displaycreatecat'] == 1)
		{
			echo JHtml::_('tabs.panel', PhocaGalleryRenderFront::renderIcon('subcategory', 'media/com_phocagallery/images/icon-subcategories.png', '') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_CATEGORY'), 'pgnewcategory' );
			echo $this->loadTemplate('newcategory');
		}

		if ((int)$this->tmpl['displayupload'] == 1) {
			echo JHtml::_('tabs.panel', PhocaGalleryRenderFront::renderIcon('upload', 'media/com_phocagallery/images/icon-upload.png', '') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_UPLOAD'), 'pgupload' );
			echo $this->loadTemplate('upload');
		}

		if ((int)$this->tmpl['ytbupload'] == 1 && $this->tmpl['displayupload'] == 1 ) {
			echo JHtml::_('tabs.panel', PhocaGalleryRenderFront::renderIcon('upload-ytb', 'media/com_phocagallery/images/icon-upload-ytb.png', '') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_YTB_UPLOAD'), 'pgytbupload' );
			echo $this->loadTemplate('ytbupload');
		}

		if((int)$this->tmpl['enablemultiple']  == 1 && (int)$this->tmpl['displayupload'] == 1) {
			echo JHtml::_('tabs.panel', PhocaGalleryRenderFront::renderIcon('upload-multiple', 'media/com_phocagallery/images/icon-upload-multiple.png', '') . '&nbsp;'.JText::_('COM_PHOCAGALLERY_MULTIPLE_UPLOAD'), 'pgmultipleupload' );
			echo $this->loadTemplate('multipleupload');
		}

		if($this->tmpl['enablejava'] == 1 && (int)$this->tmpl['displayupload'] == 1) {
			echo JHtml::_('tabs.panel', PhocaGalleryRenderFront::renderIcon('upload-java', 'media/com_phocagallery/images/icon-upload-java.png', ''). '&nbsp;'.JText::_('COM_PHOCAGALLERY_JAVA_UPLOAD'), 'pgjavaupload' );
			echo $this->loadTemplate('javaupload');
		}

		echo JHtml::_('tabs.end');
		echo '</div>'. "\n";// end phocagallery-pane
	}
}

if ($this->tmpl['detail_window'] == 6) {
	?><script type="text/javascript">
	var gjaks = new SZN.LightBox(dataJakJs, optgjaks);
	</script><?php
}

if ($this->tmpl['detail_window'] == 14) {
	echo PhocaGalleryRenderDetailWindow::loadPhotoswipeBottom();
}

echo PhocaGalleryUtils::getInfo();
echo '</div>';
?>
