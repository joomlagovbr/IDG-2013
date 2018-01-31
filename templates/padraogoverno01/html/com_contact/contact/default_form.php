<?php

 /**
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
 if (isset($this->error)) : ?>
	<div class="contact-error">
		<?php echo $this->error; ?>
	</div>
<?php endif; ?>

<div class="contact-form">
	<form id="contact-form" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate form-horizontal">
		<fieldset>
			<legend class="hide"><?php echo JText::_('COM_CONTACT_EMAIL_FORM'); ?></legend>
			
			
			<div class="control-group">
              <label class="control-label required" for="jform_contact_name">
				Nome
              	<span class="star"> *</span>
              </label>
              <div class="controls">
                <?php echo $this->form->getInput('contact_name'); ?>
              </div>
            </div>

			<div class="control-group">
              <label class="control-label required" for="jform_contact_email">
				E-mail
              	<span class="star"> *</span>
              </label>
              <div class="controls">
                <?php echo $this->form->getInput('contact_email'); ?>
              </div>
            </div>

			<div class="control-group">
              <label class="control-label required" for="jform_contact_subject">
				Assunto
              	<span class="star"> *</span>
              </label>
              <div class="controls">
                <?php echo $this->form->getInput('contact_subject'); ?>
              </div>
            </div>

			<div class="control-group">
              <label class="control-label required" for="jform_contact_message">
				Mensagem
              	<span class="star"> *</span>
              </label>
              <div class="controls">
                <?php echo $this->form->getInput('contact_message'); ?>
              </div>
            </div>
			
			<?php 	if ($this->params->get('show_email_copy')){ ?>
			<div class="control-group offset4">
				<label for="jform_contact_email_copy" class="checkbox">
					<?php echo $this->form->getInput('contact_email_copy'); ?>
					Enviar cópia da mensagem.
				</label>
			</div>
			<?php 	} ?>



			<?php //Dynamically load any additional fields from plugins. ?>
			     <?php foreach ($this->form->getFieldsets() as $fieldset): ?>
			          <?php if ($fieldset->name != 'contact'):?>
			               <?php $fields = $this->form->getFieldset($fieldset->name);?>
			               <?php foreach($fields as $field): ?>
			                    <?php if ($field->hidden): ?>
			                         <?php echo $field->input;?>
			                    <?php else:?>
			                         <dt>
			                            <?php echo $field->label; ?>
			                            <?php if (!$field->required && $field->type != "Spacer"): ?>
			                               <span class="optional"><?php echo JText::_('COM_CONTACT_OPTIONAL');?></span>
			                            <?php endif; ?>
			                         </dt>
			                         <dd><?php echo $field->input;?></dd>
			                    <?php endif;?>
			               <?php endforeach;?>
			          <?php endif ?>
			     <?php endforeach;?>
				<dt></dt>
				<div class="offset4">
				<button class="button btn btn-primary" type="submit"><?php echo JText::_('COM_CONTACT_CONTACT_SEND'); ?></button>
					
				</div>
					<input type="hidden" name="option" value="com_contact" />
					<input type="hidden" name="task" value="contact.submit" />
					<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
					<input type="hidden" name="id" value="<?php echo $this->contact->slug; ?>" />
					<?php echo JHtml::_( 'form.token' ); ?>
				
			
		</fieldset>
	</form>
</div>
