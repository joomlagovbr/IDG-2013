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
defined('_JEXEC') or die('Restricted access'); ?>

<div class="phocagallery-box-file-i">
	<center>
		<div class="phocagallery-box-file-first-i">
			<div class="phocagallery-box-file-second">
				<div class="phocagallery-box-file-third">
					<center>
					<a href="index.php?option=com_phocagallery&amp;view=phocagalleryf&amp;tmpl=component&amp;folder=<?php echo $this->state->parent; ?>&amp;field=<?php echo $this->field; ?>" ><?php echo JHTML::_( 'image', 'media/com_phocagallery/images/administrator/icon-64-up.png', ''); ?></a>
					</center>
				</div>
			</div>
		</div>
	</center>
	
	<div class="name"><a href="index.php?option=com_phocagallery&amp;view=phocagalleryf&amp;tmpl=component&amp;folder=<?php echo $this->state->parent; ?>&amp;field=<?php echo $this->field; ?>" >..</a></div>
		<div class="detail" style="text-align:right">&nbsp;</div>
	<div style="clear:both"></div>
</div>