<?php
/**
* @package PortalPadrao
* @subpackage com_agendadirigentes
*
* @copyright Copyright (C) 2005 - 2014 Joomla Calango. All rights reserved.
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/
 
// impedir acesso direto ao arquivo
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<form action="<?php echo JRoute::_('index.php?option=com_agendadirigentes&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_AGENDADIRIGENTES_CARGO_DETAILS'); ?></legend>
            <div class="row-fluid">
                <div class="span6">
                    <?php foreach ($this->form->getFieldset('details') as $field): ?>
                      <?php if($field->fieldname!='featured' || $this->showFeatured): ?>
                        <div class="control-group">
                          <?php if($field->fieldname=='published'):
                              if(! $this->canChange) $field->readonly = true;
                          endif; ?>
                          <?php if (!$field->hidden): ?>
                          <div class="control-label"><?php echo $field->label; ?></div>
                          <?php endif; ?>
                          <div class="controls"><?php echo $field->input; ?></div>
                        </div>
                      <?php else: ?>
                        <input type="hidden" name="jform[featured]" value="<?php echo $field->value; ?>" />
                      <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </fieldset>
    </div>
   <!-- begin ACL definition-->
 
   <div class="clr"></div>
 
   <!-- end ACL definition-->
    <input type="hidden" name="task" value="cargo.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>