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
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$app            = JFactory::getApplication();
$user           = JFactory::getUser();
$userId         = $user->get('id');
$listOrder      = $this->escape($this->state->get('list.ordering'));
$listDirn       = $this->escape($this->state->get('list.direction'));
$this->saveOrder      = $listOrder == 'a.ordering';
if ($this->saveOrder)
{
        $saveOrderingUrl = 'index.php?option=com_agendadirigentes&task=cargos.saveOrderAjax&tmpl=component';
        JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
        Joomla.orderTable = function()
        {
                table = document.getElementById("sortTable");
                direction = document.getElementById("directionTable");
                order = table.options[table.selectedIndex].value;
                if (order != '<?php echo $this->listOrder; ?>')
                {
                        dirn = 'asc';
                }
                else
                {
                        dirn = direction.options[direction.selectedIndex].value;
                }
                Joomla.tableOrdering(order, dirn, '');
        }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_agendadirigentes&view=cargos'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
<?php else : ?>
        <div id="j-main-container">
<?php endif;?>  
                <?php
                // Search tools bar
                echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
                ?>
                <?php if (empty($this->items)) : ?>
                        <div class="alert alert-no-items">
                                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                        </div>
                <?php else : ?>
                        <table id="articleList" class="table table-striped">
                                <thead><?php echo $this->loadTemplate('head');?></thead>
                                <tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
                                <tbody><?php echo $this->loadTemplate('body');?></tbody>
                        </table>
                        <?php if ($this->sobreposicaoBloqueada): ?>
                        <div align="right"><?php echo JText::_('COM_AGENDADIRIGENTES_CARGOS_NO_OVERRIDE'); ?></div>
                        <?php endif; ?>
                <?php endif; ?>
                <div>
                        <input type="hidden" name="task" value="" />
                        <input type="hidden" name="boxchecked" value="0" />
                        <?php echo JHtml::_('form.token'); ?>
                </div>
        </div>
</form>