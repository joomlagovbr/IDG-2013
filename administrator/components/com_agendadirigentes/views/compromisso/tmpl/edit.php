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
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::script( JURI::base() . 'components/com_agendadirigentes/assets/js/jquery.maskedinput.min.js');
?>
<script type="text/javascript">
function disableField(aim, message)
{
    message = "<?php echo JText::_('COM_AGENDADIRIGENTES_COMPROMISSO_VIEW_DISABLE_MESSAGE'); ?>";
    if(jQuery('#permitir_participantes_locais').val()==1)
    {
        if(document.owner_old_val != '')
        {
            if(message == '' || message == null)
                message = true;
            else
                message = window.confirm(message);

            if(!message)
            {
                jQuery('#jform_owner').val( document.owner_old_val );
                jQuery('#jform_owner').trigger('liszt:updated');
                return;
            }
        }
        document.owner_old_val = jQuery('#jform_owner').val();

        jQuery('#jform_'+aim+' option').each(function(){
            if ( isNaN(jQuery(this).val())==false )
            {
                jQuery(this).remove();                                 
            } 
        });
        jQuery('#jform_'+aim).trigger('liszt:updated');
        jQuery('#jform_'+aim+'_chzn').html('<?php echo JText::_("COM_AGENDADIRIGENTES_COMPROMISSO_VIEW_SAVE_OR_UPDATE_PAGE") ?>');
    }    
}
function removeselected(elm, aim)
{
    jQuery('#jform_'+aim+' option:disabled').attr("disabled", false);
    jQuery('#jform_'+aim+' option').each(function(){
        if (jQuery(this).val()==jQuery(elm).val())
        {
            jQuery(this).attr("disabled", "true");                                 
            jQuery('#jform_'+aim).trigger('liszt:updated');
            return;
        } 
    });
}
function injectSelected(elm, aim)
{
    items = jQuery(elm).val();
    items = items.split(';');
    for (var i = 0; i < items.length; i++) {
        items[i] = items[i].replace(/^\s+|\s+$/gm,''); //trim
        jQuery('#jform_'+aim).find('option[value="'+items[i]+'"]').attr('selected', true);
        jQuery('#jform_'+aim).trigger('liszt:updated');
    };
}
jQuery(document).ready(function(){
    removeselected('#jform_owner', 'dirigentes');
    injectSelected('#jform_participantes_externos', 'dirigentes');
    jQuery('#jform_horario_inicio').mask("99:99");
    jQuery('#jform_horario_fim').mask("99:99");
    document.owner_old_val = jQuery('#jform_owner').val();
});
</script>
<form action="<?php echo JRoute::_('index.php?option=com_agendadirigentes&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_AGENDADIRIGENTES_COMPROMISSO_DETAILS'); ?></legend>
            <div class="row-fluid">
                <div class="span8">
                    <?php foreach ($this->form->getFieldset('details') as $field): ?>
                        <?php if(($field->fieldname!='featured' || $this->showFeatured) && $field->fieldname!='dirigentes' ): ?>
                            <div class="control-group">
                                <?php if (!$field->hidden): ?>
                                    <?php if($field->fieldname=='state'):                          
                                        if(! $this->canChange) $field->readonly = true;
                                    endif; ?>
                                <div class="control-label"><?php echo $field->label; ?></div>
                                <?php endif; ?>
                                <div class="controls"><?php echo $field->input; ?></div>
                            </div>
                        <?php elseif($field->fieldname=='dirigentes'): ?>
                            <?php if (!$this->permitir_participantes_locais && !$this->permitir_participantes_externos): ?>
                                <input type="hidden" name="jform[dirigentes]" value="" />
                            <?php else: ?>
                                <div class="control-group">
                                    <div class="control-label"><?php echo $field->label; ?></div>
                                    <div class="controls"><?php echo $field->input; ?></div>
                                </div>
                            <?php endif; ?>
                        <?php elseif($field->fieldname=='featured'): ?>
                            <input type="hidden" name="jform[featured]" value="<?php echo $field->value; ?>" />
                        <?php endif; ?>                            
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
                </div>
            </div>
        </fieldset>
    </div>
    <input type="hidden" name="task" value="compromisso.edit" />
    <input type="hidden" name="permitir_participantes_locais" id="permitir_participantes_locais" value="<?php echo $this->permitir_participantes_locais; ?>" />
    <input type="hidden" name="permitir_participantes_externos" id="permitir_participantes_externos" value="<?php echo $this->permitir_participantes_externos; ?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>