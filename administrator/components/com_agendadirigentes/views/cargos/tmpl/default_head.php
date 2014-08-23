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
 
?>
<tr>
        <th width="5">
                <?php echo JText::_('COM_AGENDADIRIGENTES_CARGOS_HEADING_ID'); ?>
        </th>
        <th width="20">
                <?php echo JHtml::_('grid.checkall'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_AGENDADIRIGENTES_CARGOS_HEADING_CARGO'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_AGENDADIRIGENTES_CARGOS_HEADING_CATEGORIA'); ?>
        </th>
</tr>