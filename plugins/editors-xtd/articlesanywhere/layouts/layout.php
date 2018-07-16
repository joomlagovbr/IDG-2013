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
		<label id="data_layout_enable-lbl" for="data_layout_enable" class="control-label"
		       rel="tooltip" title="<?php echo JText::_('AA_FULL_ARTICLE_TAG_DESC'); ?>">
			<?php echo JText::_('AA_FULL_ARTICLE'); ?>
		</label>

		<div class="controls">
			<fieldset id="data_layout_enable" class="radio btn-group">
				<input type="radio" id="data_layout_enable0" name="data_layout_enable"
				       value="0" <?php echo ! $params->data_layout_enable ? 'checked="checked"' : ''; ?>>
				<label for="data_layout_enable0"><?php echo JText::_('JNO'); ?></label>
				<input type="radio" id="data_layout_enable1" name="data_layout_enable"
				       value="1" <?php echo $params->data_layout_enable ? 'checked="checked"' : ''; ?>>
				<label for="data_layout_enable1"><?php echo JText::_('JYES'); ?></label>
			</fieldset>
		</div>
	</div>

	<div rel="data_layout_enable" class="toggle_div" style="display:none;">
		<div class="control-group">
			<label id="data_layout_layout-lbl" for="data_layout_layout" class="control-label" rel="tooltip"
			       title="<?php echo JText::_('AA_FULL_ARTICLE_LAYOUT_DESC'); ?>">
				<?php echo JText::_('AA_FULL_ARTICLE_LAYOUT'); ?>
			</label>

			<div class="controls">
				<input type="text" name="data_layout_layout" id="data_layout_layout"
				       value="<?php echo $params->data_layout_layout; ?>">
			</div>
		</div>
	</div>
</div>
