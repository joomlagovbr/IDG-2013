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
            <legend><?php echo JText::_('COM_AGENDADIRIGENTES_COMPROMISSO_DETAILS'); ?></legend>
            <div class="row-fluid">
                <div class="span8">
                    <?php foreach ($this->form->getFieldset('details') as $field): ?>
                        <div class="control-group">
                            <?php if (!$field->hidden): ?>
                            <div class="control-label"><?php echo $field->label; ?></div>
                            <?php endif; ?>
                            <div class="controls"><?php echo $field->input; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="span4">
                    <?php foreach ($this->form->getFieldset('display') as $field): ?>
                        <div class="control-group">
                            <?php if (!$field->hidden): ?>
                            <div class="control-label"><?php echo $field->label; ?></div>
                            <?php endif; ?>
                            <div class="controls"><?php echo $field->input; ?></div>
                        </div>
                    <?php endforeach; ?>
                    <?php //echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'display')); ?>
                    <?php //echo JLayoutHelper::render('joomla.edit.params', $this); ?>
                    <?php //echo JHtml::_('bootstrap.endTabSet'); ?>
                </div>
                <!-- <div class="span3">
                    <?php //echo JLayoutHelper::render('joomla.edit.global', $this); ?>
                </div> -->
            </div>
        </fieldset>
    </div>
    <input type="hidden" name="task" value="compromisso.edit" />
    <?php echo JHtml::_('form.token'); ?>
</form>