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
?>
<form action="<?php echo JRoute::_('index.php?option=com_agendadirigentes'); ?>" method="post" name="adminForm" id="adminForm">
        <?php if (!empty( $this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
        <?php else : ?>
        <div id="j-main-container">
        <?php endif;?>       
                <table class="table table-striped">
                        <thead><?php echo $this->loadTemplate('head');?></thead>
                        <tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
                        <tbody><?php echo $this->loadTemplate('body');?></tbody>
                </table>
                <div>
                        <input type="hidden" name="task" value="" />
                        <input type="hidden" name="boxchecked" value="0" />
                        <?php echo JHtml::_('form.token'); ?>
                </div>
        </div>
</form>