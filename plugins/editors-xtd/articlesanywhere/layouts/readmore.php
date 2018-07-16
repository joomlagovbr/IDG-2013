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
		<label id="data_readmore_enable-lbl" for="data_readmore_enable" class="control-label"
		       rel="tooltip" title="<?php echo JText::_('AA_READMORE_TAG_DESC'); ?>">
			<?php echo JText::_('AA_READMORE_LINK'); ?>
		</label>

		<div class="controls">
			<fieldset id="data_readmore_enable" class="radio btn-group">
				<input type="radio" id="data_readmore_enable0" name="data_readmore_enable"
				       value="0" <?php echo ! $params->data_readmore_enable ? 'checked="checked"' : ''; ?>>
				<label for="data_readmore_enable0"><?php echo JText::_('JNO'); ?></label>
				<input type="radio" id="data_readmore_enable1" name="data_readmore_enable"
				       value="1" <?php echo $params->data_readmore_enable ? 'checked="checked"' : ''; ?>>
				<label for="data_readmore_enable1"><?php echo JText::_('JYES'); ?></label>
			</fieldset>
		</div>
	</div>

	<div rel="data_readmore_enable" class="toggle_div" style="display:none;">
		<div class="control-group">
			<label id="data_readmore_text-lbl" for="data_readmore_text" class="control-label"
			       rel="tooltip" title="<?php echo JText::_('AA_READMORE_TEXT_DESC'); ?>">
				<?php echo JText::_('AA_READMORE_TEXT'); ?>
			</label>

			<div class="controls">
				<input type="text" name="data_readmore_text" id="data_readmore_text"
				       value="<?php echo $params->data_readmore_text; ?>">
			</div>
		</div>
		<div class="control-group">
			<label id="data_readmore_class-lbl" for="data_readmore_class" class="control-label"
			       rel="tooltip" title="<?php echo JText::_('AA_CLASSNAME_DESC'); ?>">
				<?php echo JText::_('AA_CLASSNAME'); ?>
			</label>

			<div class="controls">
				<input type="text" name="data_readmore_class" id="data_readmore_class"
				       value="<?php echo $params->data_readmore_class; ?>">
			</div>
		</div>
	</div>
</div>
