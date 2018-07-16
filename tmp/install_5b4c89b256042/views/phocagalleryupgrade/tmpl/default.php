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