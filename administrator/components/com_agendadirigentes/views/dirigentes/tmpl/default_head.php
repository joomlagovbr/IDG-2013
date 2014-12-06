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
        <th width="1%" class="nowrap">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_ID', 'dir.id', $this->listDirn, $this->listOrder); ?>
        </th>
        <th width="1%" class="hidden-phone">
                <?php echo JHtml::_('grid.checkall'); ?>
        </th>
        <th width="1%" class="nowrap center">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_STATE', 'dir.state', $this->listDirn, $this->listOrder); ?>
        </th>
        <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_NOME', 'dir.name', $this->listDirn, $this->listOrder); ?>
        </th>
        <th width="25%">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_CATEGORIA', 'cat.title', $this->listDirn, $this->listOrder); ?>
        </th>
        <th width="25%">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_DIRIGENTES_HEADING_CARGO', 'car.name', $this->listDirn, $this->listOrder); ?>
        </th>
</tr>