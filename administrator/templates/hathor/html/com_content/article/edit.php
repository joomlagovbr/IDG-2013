<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Template.hathor
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');

// Create shortcut to parameters.
$params = $this->state->get('params');
$params = $params->toArray();
$saveHistory = $this->state->get('params')->get('save_history', 0);

// This checks if the config options have ever been saved. If they haven't they will fall back to the original settings.
$editoroptions = isset($params['show_publishing_options']);

$input = JFactory::getApplication()->input;

if (!$editoroptions):
	$params['show_publishing_options'] = '1';
	$params['show_article_options'] = '1';
	$params['show_urls_images_backend'] = '0';
	$params['show_urls_images_frontend'] = '0';
endif;

// Check if the article uses configuration settings besides global. If so, use them.
if (!empty($this->item->attribs['show_publishing_options'])):
		$params['show_publishing_options'] = $this->item->attribs['show_publishing_options'];
endif;
if (!empty($this->item->attribs['show_article_options'])):
		$params['show_article_options'] = $this->item->attribs['show_article_options'];
endif;
if (!empty($this->item->attribs['show_urls_images_backend'])):
		$params['show_urls_images_backend'] = $this->item->attribs['show_urls_images_backend'];
endif;

$assoc = JLanguageAssociations::isEnabled();

JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'article.cancel' || document.formvalidator.isValid(document.getElementById('item-form')))
		{
			" . $this->form->getField('articletext')->save() . "
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
");
?>
<div class="article-edit">

<form action="<?php echo JRoute::_('index.php?option=com_content&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<div class="col main-section">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_CONTENT_NEW_ARTICLE') : JText::sprintf('COM_CONTENT_EDIT_ARTICLE', $this->item->id); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('title'); ?>
				<?php echo $this->form->getInput('title'); ?></li>

				<li><?php echo $this->form->getLabel('alias'); ?>
				<?php echo $this->form->getInput('alias'); ?></li>

				<li><?php echo $this->form->getLabel('catid'); ?>
				<?php echo $this->form->getInput('catid'); ?></li>

				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>

				<li><?php echo $this->form->getLabel('access'); ?>
				<?php echo $this->form->getInput('access'); ?></li>

				<?php if ($this->canDo->get('core.admin')) : ?>
					<li><span class="faux-label"><?php echo JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL'); ?></span>
						<button type="button" onclick="document.location.href='#access-rules';">
							<?php echo JText::_('JGLOBAL_PERMISSIONS_ANCHOR'); ?>
						</button>
					</li>
				<?php endif; ?>

				<li><?php echo $this->form->getLabel('featured'); ?>
				<?php echo $this->form->getInput('featured'); ?></li>

				<li><?php echo $this->form->getLabel('language'); ?>
				<?php echo $this->form->getInput('language'); ?></li>

				<!-- Tag field -->
				<li><?php echo $this->form->getLabel('tags'); ?>
					<div class="is-tagbox">
						<?php echo $this->form->getInput('tags'); ?>
					</div>
				</li>

				<?php if ($saveHistory) : ?>
					<li><?php echo $this->form->getLabel('version_note'); ?>
					<?php echo $this->form->getInput('version_note'); ?></li>
				<?php endif; ?>

				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>

			</ul>

			<div class="clr"></div>
			<?php echo $this->form->getLabel('articletext'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('articletext'); ?>
			<div class="clr"></div>
		</fieldset>
	</div>

	<div class="col options-section">
		<?php echo JHtml::_('sliders.start', 'content-sliders-' . $this->item->id, array('useCookie' => 1)); ?>
		<?php // Do not show the publishing options if the edit form is configured not to. ?>
		<?php  if ($params['show_publishing_options'] || ( $params['show_publishing_options'] = '' && !empty($editoroptions)) ) : ?>
			<?php echo JHtml::_('sliders.panel', JText::_('COM_CONTENT_FIELDSET_PUBLISHING'), 'publishing-details'); ?>
			<fieldset class="panelform">
				<ul class="adminformlist">
					<li><?php echo $this->form->getLabel('created_by'); ?>
					<?php echo $this->form->getInput('created_by'); ?></li>

					<li><?php echo $this->form->getLabel('created_by_alias'); ?>
					<?php echo $this->form->getInput('created_by_alias'); ?></li>

					<li><?php echo $this->form->getLabel('created'); ?>
					<?php echo $this->form->getInput('created'); ?></li>

						<li><?php echo $this->form->getLabel('publish_up'); ?>
						<?php echo $this->form->getInput('publish_up'); ?></li>

					<li><?php echo $this->form->getLabel('publish_down'); ?>
					<?php echo $this->form->getInput('publish_down'); ?></li>

					<?php if ($this->item->modified_by) : ?>
						<li><?php echo $this->form->getLabel('modified_by'); ?>
						<?php echo $this->form->getInput('modified_by'); ?></li>

						<li><?php echo $this->form->getLabel('modified'); ?>
						<?php echo $this->form->getInput('modified'); ?></li>
					<?php endif; ?>

					<?php if ($this->item->version) : ?>
						<li><?php echo $this->form->getLabel('version'); ?>
						<?php echo $this->form->getInput('version'); ?></li>
					<?php endif; ?>

					<?php if ($this->item->hits) : ?>
						<li><?php echo $this->form->getLabel('hits'); ?>
						<?php echo $this->form->getInput('hits'); ?></li>
					<?php endif; ?>
				</ul>
			</fieldset>
		<?php  endif; ?>
		<?php  $fieldSets = $this->form->getFieldsets(); ?>
			<?php foreach ($fieldSets as $name => $fieldSet) : ?>
				<?php
					// If the parameter says to show the article options or if the parameters have never been set, we will
					// show the article options.

					if ($params['show_article_options'] || ($params['show_article_options'] == '' && !empty($editoroptions))):

					// Go through all the fieldsets except the configuration and basic-limited, which are
					// handled separately below.
					if ($name != 'editorConfig' && $name != 'basic-limited' && $name != 'item_associations' && $name != 'jmetadata') : ?>
						<?php echo JHtml::_('sliders.panel', JText::_($fieldSet->label), $name.'-options'); ?>
						<?php if (isset($fieldSet->description) && trim($fieldSet->description)) : ?>
							<p class="tip"><?php echo $this->escape(JText::_($fieldSet->description));?></p>
						<?php endif; ?>
						<fieldset class="panelform">
							<ul class="adminformlist">
							<?php foreach ($this->form->getFieldset($name) as $field) : ?>
								<li><?php echo $field->label; ?>
								<?php echo $field->input; ?></li>
							<?php endforeach; ?>
							</ul>
						</fieldset>
					<?php endif ?>
					<?php // If we are not showing the options we need to use the hidden fields so the values are not lost.  ?>
				<?php  elseif ($name == 'basic-limited') : ?>
						<?php foreach ($this->form->getFieldset('basic-limited') as $field) : ?>
							<?php  echo $field->input; ?>
						<?php endforeach; ?>

				<?php endif; ?>
			<?php endforeach; ?>
			<?php // Not the best place, but here for continuity with 1.5/1/6/1.7 ?>
				<fieldset class="panelform">
				</fieldset>
				<?php
					// We need to make a separate space for the configuration
					// so that those fields always show to those wih permissions
					if ( $this->canDo->get('core.admin')   ):  ?>
					<?php  echo JHtml::_('sliders.panel', JText::_('COM_CONTENT_SLIDER_EDITOR_CONFIG'), 'configure-sliders'); ?>
						<fieldset  class="panelform" >
							<ul class="adminformlist">
							<?php foreach ($this->form->getFieldset('editorConfig') as $field) : ?>
								<li><?php echo $field->label; ?>
								<?php echo $field->input; ?></li>
							<?php endforeach; ?>
							</ul>
						</fieldset>
				<?php endif ?>

		<?php // The URL and images fields only show if the configuration is set to allow them.  ?>
		<?php // This is for legacy reasons. ?>
		<?php if ($params['show_urls_images_backend']) : ?>
			<?php echo JHtml::_('sliders.panel', JText::_('COM_CONTENT_FIELDSET_URLS_AND_IMAGES'), 'urls_and_images-options'); ?>
				<fieldset class="panelform">
				<ul class="adminformlist">
					<li>
					<?php echo $this->form->getLabel('images'); ?>
					<?php echo $this->form->getInput('images'); ?></li>

					<?php foreach ($this->form->getGroup('images') as $field) : ?>
						<li>
							<?php if (!$field->hidden) : ?>
								<?php echo $field->label; ?>
							<?php endif; ?>
							<?php echo $field->input; ?>
						</li>
					<?php endforeach; ?>
						<?php foreach ($this->form->getGroup('urls') as $field) : ?>
						<li>
							<?php if (!$field->hidden) : ?>
								<?php echo $field->label; ?>
							<?php endif; ?>
							<?php echo $field->input; ?>
						</li>
					<?php endforeach; ?>
				</ul>
				</fieldset>
		<?php endif; ?>
		<?php echo JHtml::_('sliders.panel', JText::_('JGLOBAL_FIELDSET_METADATA_OPTIONS'), 'meta-options'); ?>
			<fieldset class="panelform">
			<legend class="element-invisible"><?php echo JText::_('JGLOBAL_FIELDSET_METADATA_OPTIONS'); ?></legend>
				<?php echo $this->loadTemplate('metadata'); ?>
			</fieldset>

		<?php if ($assoc) : ?>
			<?php echo JHtml::_('sliders.panel', JText::_('JGLOBAL_FIELDSET_ASSOCIATIONS'), '-options');?>
			<?php echo $this->loadTemplate('associations'); ?>
		<?php endif; ?>

		<?php echo JHtml::_('sliders.end'); ?>
	</div>

	<div class="clr"></div>
	<?php if ($this->canDo->get('core.admin')) : ?>
		<div  class="col rules-section">
			<?php echo JHtml::_('sliders.start', 'permissions-sliders-' . $this->item->id, array('useCookie' => 1)); ?>

				<?php echo JHtml::_('sliders.panel', JText::_('COM_CONTENT_FIELDSET_RULES'), 'access-rules'); ?>
				<fieldset class="panelform">
					<legend class="element-invisible"><?php echo JText::_('COM_CONTENT_FIELDSET_RULES'); ?></legend>
					<?php echo $this->form->getLabel('rules'); ?>
					<?php echo $this->form->getInput('rules'); ?>
				</fieldset>

			<?php echo JHtml::_('sliders.end'); ?>
		</div>
	<?php endif; ?>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo $input->getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<div class="clr"></div>
</div>
