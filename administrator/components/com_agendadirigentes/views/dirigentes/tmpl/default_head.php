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
                <?php echo JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_ID'); ?>
        </th>
        <th width="20">
                <?php echo JHtml::_('grid.checkall'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_NOME'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_CARGO'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_CATEGORIA'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_PROPRIETARIO'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_USUARIOS_HABILITADOS'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_BLOQUEADO'); ?>
        </th>
        <th>
                <?php echo JText::_('COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_ORDEM'); ?>
        </th>
</tr>