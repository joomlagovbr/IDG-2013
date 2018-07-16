<?php
/**
 * @package         Articles Anywhere
 * @version         8.0.3
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;
?>
<div class="well">
	<div class="control-group">
		<label id="data_intro_image_enable-lbl" for="data_intro_image_enable" class="control-label"
		       rel="tooltip" title="<?php echo JText::_('AA_INTRO_IMAGE_TAG_DESC'); ?>">
			<?php echo JText::_('AA_INTRO_IMAGE'); ?>
		</label>

		<div class="controls">
			<fieldset id="data_intro_image_enable" class="radio btn-group">
				<input type="radio" id="data_intro_image_enable0" name="data_intro_image_enable"
				       value="0" <?php echo ! $params->data_intro_image_enable ? 'checked="checked"' : ''; ?>>
				<label for="data_intro_image_enable0"><?php echo JText::_('JNO'); ?></label>
				<input type="radio" id="data_intro_image_enable1" name="data_intro_image_enable"
				       value="1" <?php echo $params->data_intro_image_enable ? 'checked="checked"' : ''; ?>>
				<label for="data_intro_image_enable1"><?php echo JText::_('JYES'); ?></label>
			</fieldset>
		</div>
	</div>
</div>
