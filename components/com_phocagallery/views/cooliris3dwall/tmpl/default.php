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
if ($this->tmpl['display_category']	== 0) {
	echo JText::_('COM_PHOCAGALLERY_CATEGORY was not selected in parameters');
} else {

echo '<div id="phocagallery" class="pg-cooliris3dwall-view-view'.$this->params->get( 'pageclass_sfx' ).'">'. "\n";
// Heading
$heading = '';
if ($this->params->get( 'page_title' ) != '') {
	$heading .= $this->params->get( 'page_title' );
}
if ( $this->tmpl['displaycatnametitle'] == 1) {
	if ($this->category->title != '') {
		if ($heading != '') {
			$heading .= ' - ';
		}
		$heading .= $this->category->title;
	}
}

// Pagetitle
if ($this->tmpl['showpageheading'] != 0) {
	if ( $heading != '') {
		echo '<h1>'. $this->escape($heading) . '</h1>';
	}
}

// Category Description
if ( $this->category->description != '' ) {
	echo '<div class="pg-cooliris3dwall-view-desc'.$this->params->get( 'pageclass_sfx' ).'">';
	echo $this->category->description.'</div>'. "\n";
}

?>
<object id="o"
  classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
  width="<?php echo $this->tmpl['cooliris3d_wall_width'];?>"
  height="<?php echo $this->tmpl['cooliris3d_wall_height'];?>">
    <param name="movie"
      value="http://apps.cooliris.com/embed/cooliris.swf" />
    <param name="allowFullScreen" value="true" />
    <param name="allowScriptAccess" value="always" />
	<param name="wmode" value="transparent" />
    <param name="flashvars"
      value="feed=<?php echo JURI::root() . $this->tmpl['path']->image_rel . (int)$this->category->id;?>.rss" />
    <embed type="application/x-shockwave-flash"
      src="http://apps.cooliris.com/embed/cooliris.swf"
	  flashvars="feed=<?php echo JURI::root() . $this->tmpl['path']->image_rel . (int)$this->category->id;?>.rss"
      width="<?php echo $this->tmpl['cooliris3d_wall_width'];?>"
      height="<?php echo $this->tmpl['cooliris3d_wall_height'];?>"
      allowFullScreen="true"
      allowScriptAccess="always"
	  wmode="transparent" >
	  </embed>
</object>
<?php
}
echo '<div>&nbsp;</div>';
echo PhocaGalleryUtils::getInfo();
echo '</div>';
?>
