<?php
defined('_JEXEC') or die('Restricted access');
?>

<form action="<?php echo $this->linkupgrade; ?>" method="post" name="adminForm">

<p><?php echo JText::_('Phoca Gallery Upgrade Obsolete Database Structure');?></p>	

<?php if ($this->tmpl['messageimg'] != '') {
	echo $this->tmpl['messageimg'];
}
?>
<?php if ($this->tmpl['messagecat'] != '') {
	echo $this->tmpl['messagecat'];
}
?>

<p>&nbsp;</p>
<?php if ($this->buttonimg != '') {?>
<input type="submit" name="submit" value="<?php echo $this->buttonimg;?>" />
<?php } ?>
<?php if ($this->buttoncat != '') {?>
<input type="submit" name="submit" value="<?php echo $this->buttoncat;?>" />
<?php } ?>
</form>

<?php if ($this->linktogallery != '') {
echo $this->linktogallery;
} ?>