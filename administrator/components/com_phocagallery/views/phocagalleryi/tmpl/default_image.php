<?php defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.filesystem.file' );
$image['width'] = $image['height'] = 100;

if (JFile::exists( $this->_tmp_img->linkthumbnailpathabs )) {
	list($width, $height) = GetImageSize( $this->_tmp_img->linkthumbnailpathabs );
	$image = PhocaGalleryImage::correctSizeWithRate($width, $height);
}

?><div class="phocagallery-box-file-i">
	<center>
		<div class="phocagallery-box-file-first-i">
			<div class="phocagallery-box-file-second">
				<div class="phocagallery-box-file-third">
					<center>
					<a href="#" onclick="if (window.parent) window.parent.<?php echo $this->fce; ?>('<?php echo $this->_tmp_img->nameno; ?>');">
	<?php
	
	$imageRes	= PhocaGalleryImage::getRealImageSize($this->_tmp_img->nameno, 'medium');
	$correctImageRes = PhocaGalleryImage::correctSizeWithRate($imageRes['w'], $imageRes['h'], 100, 100);
	echo JHTML::_( 'image', $this->_tmp_img->linkthumbnailpath, '', array('width' => $image['width'], 'height' => $image['height']), '', null); ?></a>
					</center>
				</div>
			</div>
		</div>
	</center>
	
	<div class="name"><?php echo $this->_tmp_img->name; ?></div>
		<div class="detail" style="text-align:right">
			<a href="#" onclick="if (window.parent) window.parent.<?php echo $this->fce; ?>('<?php echo $this->_tmp_img->nameno; ?>');"><?php echo JHTML::_( 'image', 'media/com_phocagallery/images/administrator/icon-insert.gif', JText::_('COM_PHOCAGALLERY_INSERT_IMAGE'), array('title' => JText::_('COM_PHOCAGALLERY_INSERT_IMAGE'))); ?></a>
		</div>
	<div style="clear:both"></div>
</div>
