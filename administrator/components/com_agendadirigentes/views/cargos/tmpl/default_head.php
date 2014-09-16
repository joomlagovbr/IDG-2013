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
        <th width="1%" class="nowrap center hidden-phone">
                <?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $this->listDirn, $this->listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
        </th>
        <th width="1%" class="nowrap">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_CARGOS_HEADING_ID', 'a.id', $this->listDirn, $this->listOrder); ?>
        </th>
        <th width="1%" class="hidden-phone">
                <?php echo JHtml::_('grid.checkall'); ?>
        </th>
        <th width="1%" class="nowrap center">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_CARGOS_HEADING_PUBLISHED', 'a.published', $this->listDirn, $this->listOrder); ?>
        </th>
        <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_CARGOS_HEADING_CARGO', 'a.name', $this->listDirn, $this->listOrder); ?>
        </th>
        <th width="30%" class="nowrap">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_CARGOS_HEADING_CATEGORIA', 'b.title', $this->listDirn, $this->listOrder); ?>
        </th>        
</tr>