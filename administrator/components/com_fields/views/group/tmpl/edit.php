<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_fields
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tabstate');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$input = $app->input;

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "group.cancel" || document.formvalidator.isValid(document.getElementById("item-form")))
		{
			Joomla.submitform(task, document.getElementById("item-form"));
		}
	};
');
?>

<form action="<?php echo JRoute::_('index.php?option=com_fields&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_FIELDS_VIEW_FIELD_FIELDSET_GENERAL', true)); ?>
		<div class="row-fluid">
			<div class="span9">
				<?php echo $this->form->renderField('label'); ?>
				<?php echo $this->form->renderField('context'); ?>
				<?php echo $this->form->renderField('description'); ?>
			</div>
			<div class="span3">
				<?php $this->set('fields',
						array(
							array(
								'published',
								'state',
								'enabled',
							),
							'access',
							'language',
							'note',
						)
				); ?>
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
				<?php $this->set('fields', null); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span6">
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php $this->set('ignore_fieldsets', array('fieldparams')); ?>
		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>
		<?php if ($this->canDo->get('core.admin')) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'rules', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
			<?php echo $this->form->getInput('rules'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		<?php echo $this->form->getInput('context'); ?>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
