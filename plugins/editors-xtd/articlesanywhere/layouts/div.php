<?php
/**
 * @package         Articles Anywhere
 * @version         7.5.1
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
		<label id="enable_div-lbl" for="enable_div-field" class="control-label" rel="tooltip"
		       title="<?php echo JText::_('AA_EMBED_IN_A_DIV_DESC'); ?>">
			<?php echo JText::_('AA_EMBED_IN_A_DIV'); ?>
		</label>

		<div class="controls">
			<fieldset id="enable_div" class="radio btn-group">
				<input type="radio" id="enable_div0" name="enable_div"
				       value="0" <?php echo ! $params->div_enable ? 'checked="checked"' : ''; ?>
				       onclick="toggleDivs();" onchange="toggleDivs();">
				<label for="enable_div0"><?php echo JText::_('JNO'); ?></label>
				<input type="radio" id="enable_div1" name="enable_div"
				       value="1" <?php echo $params->div_enable ? 'checked="checked"' : ''; ?>
				       onclick="toggleDivs();" onchange="toggleDivs();">
				<label for="enable_div1"><?php echo JText::_('JYES'); ?></label>
			</fieldset>
		</div>
	</div>
	<div rel="enable_div" class="toggle_div" style="display:none;">
		<div class="control-group">
			<label id="div_width-lbl" for="div_width" class="control-label" rel="tooltip"
			       title="<?php echo JText::_('AA_WIDTH_DESC'); ?>">
				<?php echo JText::_('RL_WIDTH'); ?>
			</label>

			<div class="controls">
				<input type="text" class="text_area" name="div_width" id="div_width"
				       value="<?php echo $params->div_width; ?>" size="4"
				       style="width:50px;text-align: right;">
			</div>
		</div>
		<div class="control-group">
			<label id="div_height-lbl" for="div_height" class="control-label" rel="tooltip"
			       title="<?php echo JText::_('AA_HEIGHT_DESC'); ?>">
				<?php echo JText::_('RL_HEIGHT'); ?>
			</label>

			<div class="controls">
				<input type="text" class="text_area" name="div_height" id="div_height"
				       value="<?php echo $params->div_height; ?>" size="4"
				       style="width:50px;text-align: right;">
			</div>
		</div>
		<div class="control-group">
			<label id="div_float-lbl" for="div_float" class="control-label" rel="tooltip"
			       title="<?php echo JText::_('AA_ALIGNMENT_DESC'); ?>">
				<?php echo JText::_('AA_ALIGNMENT'); ?>
			</label>

			<div class="controls">
				<fieldset id="div_float" class="radio btn-group">
					<input type="radio" id="div_float0" name="div_float"
					       value="0" <?php echo ! $params->div_float ? 'checked="checked"' : ''; ?>>
					<label for="div_float0"><?php echo JText::_('JNONE'); ?></label>
					<input type="radio" id="div_float1" name="div_float"
					       value="left" <?php echo $params->div_float == 'left' ? 'checked="checked"' : ''; ?>>
					<label for="div_float1"><?php echo JText::_('JGLOBAL_LEFT'); ?></label>
					<input type="radio" id="div_float2" name="div_float"
					       value="right" <?php echo $params->div_float == 'right' ? 'checked="checked"' : ''; ?>>
					<label for="div_float2"><?php echo JText::_('JGLOBAL_RIGHT'); ?></label>
				</fieldset>
			</div>
		</div>
		<div class="control-group">
			<label id="text_area-lbl" for="text_area" class="control-label" rel="tooltip"
			       title="<?php echo JText::_('AA_DIV_CLASSNAME_DESC'); ?>">
				<?php echo JText::_('AA_DIV_CLASSNAME'); ?>
			</label>

			<div class="controls">
				<input type="text" class="text_area" name="div_class" id="div_class"
				       value="<?php echo $params->div_class; ?>">
			</div>
		</div>
	</div>
</div>
