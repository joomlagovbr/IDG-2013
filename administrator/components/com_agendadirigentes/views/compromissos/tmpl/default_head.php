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
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_ID', 'comp.id', $this->listDirn, $this->listOrder); ?>
        </th>
        <th width="1%" class="hidden-phone">
                <?php echo JHtml::_('grid.checkall'); ?>
        </th>
        <th width="1%" class="nowrap center">
                <?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'comp.state', $this->listDirn, $this->listOrder); ?>
        </th>
        <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_TITLE', 'comp.title', $this->listDirn, $this->listOrder); ?>
        </th>
        <th>
                <?php if ($this->status_dono_compromisso): ?>
                        <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_OWNER', 'dir.name', $this->listDirn, $this->listOrder); ?>
                <?php else: ?>
                        <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DIRIGENTE', 'dir.name', $this->listDirn, $this->listOrder); ?>
                <?php endif ?>
        </th>
        <th width="1%" class="nowrap">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DATA_INICIAL', 'comp.data_inicial', $this->listDirn, $this->listOrder); ?>
                &nbsp;|<?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_HORARIO_INICIO', 'comp.horario_inicio', $this->listDirn, $this->listOrder); ?>
        </th>
        <th width="1%" class="nowrap">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DATA_FINAL', 'comp.data_final', $this->listDirn, $this->listOrder); ?>
                &nbsp;|<?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_HORARIO_FIM', 'comp.horario_fim', $this->listDirn, $this->listOrder); ?>
        </th>
        <th  width="1%" class="nowrap">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DIA_TODO', 'comp.dia_todo', $this->listDirn, $this->listOrder); ?>
        </th>
</tr>