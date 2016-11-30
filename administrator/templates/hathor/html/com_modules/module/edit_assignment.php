<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Initialise related data.
JLoader::register('MenusHelper', JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');
$menuTypes = MenusHelper::getMenuLinks();

JFactory::getDocument()->addScriptDeclaration("
	window.addEvent('domready', function(){
		validate();
		document.getElements('select').addEvent('change', function(e){validate();});
	});
	function validate(){
		var value = document.id('jform_assignment').value;
		var list  = document.id('menu-assignment');
		if (value == '-' || value == '0'){
			$$('.jform-assignments-button').each(function(el) {el.setProperty('disabled', true); });
			list.getElements('input').each(function(el){
				el.setProperty('disabled', true);
				if (value == '-'){
					el.setProperty('checked', false);
				} else {
					el.setProperty('checked', true);
				}
			});
		} else {
			$$('.jform-assignments-button').each(function(el) {el.setProperty('disabled', false); });
			list.getElements('input').each(function(el){
				el.setProperty('disabled', false);
			});
		}
	}
");
?>

		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_MODULES_MENU_ASSIGNMENT'); ?></legend>
			<label id="jform_menus-lbl" for="jform_menus"><?php echo JText::_('COM_MODULES_MODULE_ASSIGN'); ?></label>

			<fieldset id="jform_menus" class="radio">
				<select name="jform[assignment]" id="jform_assignment">
					<?php echo JHtml::_('select.options', ModulesHelper::getAssignmentOptions($this->item->client_id), 'value', 'text', $this->item->assignment, true);?>
				</select>

			</fieldset>

			<label id="jform_menuselect-lbl" for="jform_menuselect"><?php echo JText::_('JGLOBAL_MENU_SELECTION'); ?></label>

			<button type="button" class="jform-assignments-button jform-rightbtn" onclick="$$('.chkbox').each(function(el) { el.checked = !el.checked; });">
				<?php echo JText::_('JGLOBAL_SELECTION_INVERT_ALL'); ?>
			</button>

			<button type="button" class="jform-assignments-button jform-rightbtn" onclick="$$('.chkbox').each(function(el) { el.checked = false; });">
				<?php echo JText::_('JGLOBAL_SELECTION_NONE'); ?>
			</button>

			<button type="button" class="jform-assignments-button jform-rightbtn" onclick="$$('.chkbox').each(function(el) { el.checked = true; });">
				<?php echo JText::_('JGLOBAL_SELECTION_ALL'); ?>
			</button>

			<div class="clr"></div>

			<div id="menu-assignment">

			<?php echo JHtml::_('tabs.start', 'module-menu-assignment-tabs', array('useCookie' => 1));?>

			<?php foreach ($menuTypes as &$type) :
				echo JHtml::_('tabs.panel', $type->title ? $type->title : $type->menutype, $type->menutype.'-details');

				$chkbox_class = 'chk-menulink-' . $type->id; ?>

				<button type="button" class="jform-assignments-button jform-rightbtn" onclick="$$('.<?php echo $chkbox_class; ?>').each(function(el) { el.checked = !el.checked; });">
					<?php echo JText::_('JGLOBAL_SELECTION_INVERT'); ?>
				</button>

				<button type="button" class="jform-assignments-button jform-rightbtn" onclick="$$('.<?php echo $chkbox_class; ?>').each(function(el) { el.checked = false; });">
					<?php echo JText::_('JGLOBAL_SELECTION_NONE'); ?>
				</button>

				<button type="button" class="jform-assignments-button jform-rightbtn" onclick="$$('.<?php echo $chkbox_class; ?>').each(function(el) { el.checked = true; });">
					<?php echo JText::_('JGLOBAL_SELECTION_ALL'); ?>
				</button>

				<div class="clr"></div>

				<?php $count = count($type->links); ?>
				<?php $i     = 0; ?>
				<?php if ($count) : ?>
				<ul class="menu-links">
					<?php
					foreach ($type->links as $link) :
						if (trim($this->item->assignment) == '-') :
							$checked = '';
						elseif ($this->item->assignment == 0) :
							$checked = ' checked="checked"';
						elseif ($this->item->assignment < 0) :
							$checked = in_array(-$link->value, $this->item->assigned) ? ' checked="checked"' : '';
						elseif ($this->item->assignment > 0) :
							$checked = in_array($link->value, $this->item->assigned) ? ' checked="checked"' : '';
						endif;
					?>
					<li class="menu-link">
						<input type="checkbox" class="chkbox <?php echo $chkbox_class; ?>" name="jform[assigned][]" value="<?php echo (int) $link->value;?>" id="link<?php echo (int) $link->value;?>"<?php echo $checked;?>/>
						<label for="link<?php echo (int) $link->value;?>">
							<?php echo $link->text; ?>
						</label>
					</li>
					<?php if ($count > 20 && ++$i == ceil($count / 2)) :?>
					</ul><ul class="menu-links">
					<?php endif; ?>
					<?php endforeach; ?>
				</ul>
				<div class="clr"></div>
				<?php endif; ?>
			<?php endforeach; ?>

			<?php echo JHtml::_('tabs.end');?>

			</div>
		</fieldset>
