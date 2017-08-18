<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Template.hathor
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');

JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'banner.cancel' || document.formvalidator.isValid(document.getElementById('banner-form')))
		{
			Joomla.submitform(task, document.getElementById('banner-form'));
		}
	}
");
?>
<form action="<?php echo JRoute::_('index.php?option=com_banners&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="banner-form" class="form-validate">
	<div class="col main-section">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_BANNERS_NEW_BANNER') : JText::sprintf('COM_BANNERS_BANNER_DETAILS', $this->item->id); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?></li>

				<li><?php echo $this->form->getLabel('alias'); ?>
				<?php echo $this->form->getInput('alias'); ?></li>

				<li><?php echo $this->form->getLabel('access'); ?>
				<?php echo $this->form->getInput('access'); ?></li>

				<li><?php echo $this->form->getLabel('catid'); ?>
				<?php echo $this->form->getInput('catid'); ?></li>

				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>

				<li><?php echo $this->form->getLabel('type'); ?>
				<?php echo $this->form->getInput('type'); ?></li>
			</ul>
			<ul id="image">
				<?php foreach ($this->form->getFieldset('image') as $field) : ?>
					<li><?php echo $field->label; ?>
						<?php echo $field->input; ?></li>
				<?php endforeach; ?>
			</ul>
			<ul>
				<li><div id="custom">
					<?php echo $this->form->getLabel('custombannercode'); ?>
					<?php echo $this->form->getInput('custombannercode'); ?>
				</div>
				</li>

				<li><div id="url">
				<?php echo $this->form->getLabel('clickurl'); ?>
				<?php echo $this->form->getInput('clickurl'); ?>
				</div>
				</li>

				<li>
				<?php echo $this->form->getLabel('description'); ?>
				<div class="clr"></div>
				<?php echo $this->form->getInput('description'); ?>
				<div class="clr"></div>
				</li>

				<li><?php echo $this->form->getLabel('language'); ?>
				<?php echo $this->form->getInput('language'); ?></li>

				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
			</ul>
			<div class="clr"> </div>

		</fieldset>
	</div>

<div class="col options-section">
	<?php echo JHtml::_('sliders.start', 'banner-sliders-' . $this->item->id, array('useCookie' => 1)); ?>

	<?php echo JHtml::_('sliders.panel', JText::_('COM_BANNERS_GROUP_LABEL_PUBLISHING_DETAILS'), 'publishing-details'); ?>
		<fieldset class="panelform">
		<legend class="element-invisible"><?php echo JText::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></legend>
		<ul class="adminformlist">
			<?php foreach ($this->form->getFieldset('publish') as $field) : ?>
				<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
			<?php endforeach; ?>
			</ul>
		</fieldset>

	<?php echo JHtml::_('sliders.panel', JText::_('COM_BANNERS_GROUP_LABEL_BANNER_DETAILS'), 'otherparams'); ?>
		<fieldset class="panelform">
		<legend class="element-invisible"><?php echo JText::_('COM_BANNERS_BANNER_DETAILS'); ?></legend>

		<ul class="adminformlist">
			<?php foreach ($this->form->getFieldset('otherparams') as $field) : ?>
				<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
			<?php endforeach; ?>
			<?php foreach ($this->form->getFieldset('bannerdetails') as $field) : ?>
				<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
			<?php endforeach; ?>
		</ul>	
		</fieldset>

	<?php echo JHtml::_('sliders.panel', JText::_('JGLOBAL_FIELDSET_METADATA_OPTIONS'), 'metadata'); ?>
		<fieldset class="panelform">
		<legend class="element-invisible"><?php echo JText::_('JGLOBAL_FIELDSET_METADATA_OPTIONS'); ?></legend>
			<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset('metadata') as $field) : ?>
					<li><?php echo $field->label; ?>
						<?php echo $field->input; ?></li>
				<?php endforeach; ?>
			</ul>
		</fieldset>

	<?php echo JHtml::_('sliders.end'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</div>

<div class="clr"></div>
</form>
