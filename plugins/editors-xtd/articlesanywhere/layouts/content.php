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
		<label id="data_text_enable-lbl" for="data_text_enable" class="control-label" rel="tooltip"
		       title="<?php echo JText::_('AA_TEXT_TAG_DESC'); ?>">
			<?php echo JText::_('RL_CONTENT'); ?>
		</label>

		<div class="controls">
			<fieldset id="data_text_enable" class="radio btn-group">
				<input type="radio" id="data_text_enable0" name="data_text_enable"
				       value="0" <?php echo ! $params->data_text_enable ? 'checked="checked"' : ''; ?>
				       onclick="toggleDivs();" onchange="toggleDivs();">
				<label for="data_text_enable0"><?php echo JText::_('JNO'); ?></label>
				<input type="radio" id="data_text_enable1" name="data_text_enable"
				       value="1" <?php echo $params->data_text_enable ? 'checked="checked"' : ''; ?>
				       onclick="toggleDivs();" onchange="toggleDivs();">
				<label for="data_text_enable1"><?php echo JText::_('JYES'); ?></label>
			</fieldset>
		</div>
	</div>

	<div rel="data_text_enable" class="toggle_div" style="display:none;">
		<div class="control-group">
			<label id="data_text_type-lbl" for="data_text_type" class="control-label" rel="tooltip"
			       title="<?php echo JText::_('AA_TEXT_TYPE_DESC'); ?>">
				<?php echo JText::_('AA_TEXT_TYPE'); ?>
			</label>

			<div class="controls">
				<select name="data_text_type">
					<option value="text"<?php echo $params->data_text_type == 'text' ? 'selected="selected"' : ''; ?>>
						<?php echo JText::_('AA_ALL_TEXT'); ?>
					</option>
					<option value="introtext"<?php echo $params->data_text_type == 'introtext' ? 'selected="selected"' : ''; ?>>
						<?php echo JText::_('AA_INTRO_TEXT'); ?>
					</option>
					<option value="fulltext"<?php echo $params->data_text_type == 'fulltext' ? 'selected="selected"' : ''; ?>>
						<?php echo JText::_('AA_FULL_TEXT'); ?>
					</option>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label id="data_text_length-lbl" for="data_text_length" class="control-label"
			       rel="tooltip" title="<?php echo JText::_('AA_MAXIMUM_TEXT_LENGTH_DESC'); ?>">
				<?php echo JText::_('AA_MAXIMUM_TEXT_LENGTH'); ?>
			</label>

			<div class="controls">
				<input type="text" name="data_text_length" id="data_text_length"
				       value="<?php echo $params->data_text_length; ?>" size="4"
				       style="width:50px;text-align: right;">
			</div>
		</div>
		<div class="control-group">
			<label id="data_text_strip-lbl" for="data_text_strip" class="control-label"
			       rel="tooltip" title="<?php echo JText::_('AA_STRIP_HTML_TAGS_DESC'); ?>">
				<?php echo JText::_('AA_STRIP_HTML_TAGS'); ?>
			</label>

			<div class="controls">
				<fieldset id="data_text_strip" class="radio btn-group">
					<input type="radio" id="data_text_strip0" name="data_text_strip"
					       value="0" <?php echo ! $params->data_text_strip ? 'checked="checked"' : ''; ?>>
					<label for="data_text_strip0"><?php echo JText::_('JNO'); ?></label>
					<input type="radio" id="data_text_strip1" name="data_text_strip"
					       value="1" <?php echo $params->data_text_strip ? 'checked="checked"' : ''; ?>>
					<label for="data_text_strip1"><?php echo JText::_('JYES'); ?></label>
				</fieldset>
			</div>
		</div>
	</div>
</div>
