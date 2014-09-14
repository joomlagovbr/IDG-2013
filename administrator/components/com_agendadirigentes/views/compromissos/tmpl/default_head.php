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
        <th width="30">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_ID', 'comp.id', $this->listDirn, $this->listOrder); ?>
        </th>
        <th width="15">
                <?php echo JHtml::_('grid.checkall'); ?>
        </th>
        <th width="60">
                <?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'comp.state', $this->listDirn, $this->listOrder); ?>
        </th>
        <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_TITLE', 'comp.title', $this->listDirn, $this->listOrder); ?>
        </th>
        <th>
                <?php if ($this->status_dono_compromisso): ?>
                        <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_OWNER', 'dc.owner', $this->listDirn, $this->listOrder); ?>
                <?php else: ?>
                        <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DIRIGENTE', 'dc.owner', $this->listDirn, $this->listOrder); ?>
                <?php endif ?>
        </th>
        <!-- <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_CATEGORIA', 'dc.owner', $this->listDirn, $this->listOrder); ?>
        </th> -->
        <th width="120">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DATA_INICIAL', 'comp.data_inicial', $this->listDirn, $this->listOrder); ?>
                &nbsp;|<?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_HORARIO_INICIO', 'comp.horario_inicio', $this->listDirn, $this->listOrder); ?>
        </th>
        <th width="120">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DATA_FINAL', 'comp.data_final', $this->listDirn, $this->listOrder); ?>
                &nbsp;|<?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_HORARIO_FIM', 'comp.horario_fim', $this->listDirn, $this->listOrder); ?>
        </th>
        <th width="70">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DIA_TODO', 'comp.dia_todo', $this->listDirn, $this->listOrder); ?>
        </th>
<!--         <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_HORARIO_INICIO', 'comp.horario_inicio', $this->listDirn, $this->listOrder); ?>
        </th>
        <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_HORARIO_FIM', 'comp.horario_fim', $this->listDirn, $this->listOrder); ?>
        </th> -->
        <!-- <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_LOCAL', 'comp.local', $this->listDirn, $this->listOrder); ?>
        </th> -->
        <th width="80">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_ORDERING', 'comp.ordering', $this->listDirn, $this->listOrder); ?>
        </th>
<!--         <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_OWNER_CARGO', 'car.name', $this->listDirn, $this->listOrder); ?>
        </th> -->
</tr>
<!-- COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_CHECKED_OUT -->










