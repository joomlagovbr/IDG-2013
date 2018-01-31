<?php defined('_JEXEC') or die('Restricted access');

$currentFolder = '';
if (isset($this->state->folder) && $this->state->folder != '') {
 $currentFolder = $this->state->folder;
}

echo $this->loadTemplate('up');
if (count($this->folders) > 0) { ?>
<div>
		<?php for ($i=0,$n=count($this->folders); $i<$n; $i++) :
			$this->setFolder($i);
			echo $this->loadTemplate('folder');
		endfor; ?>

</div>
<?php } else { ?>
<div>
	<center style="clear:both;font-size:large;font-weight:bold;color:#b3b3b3;font-family: Helvetica, sans-serif;">
		<?php echo JText::_( 'COM_PHOCAGALLERY_THERE_IS_NO_FOLDER' ); ?>
	</center>
</div>

<?php
}
echo '<div style="clear:both"></div>';
echo PhocaGalleryFileUpload::renderCreateFolder($this->session->getName(), $this->session->getId(), $currentFolder, 'phocagalleryf', 'field='.$this->field);
?>




