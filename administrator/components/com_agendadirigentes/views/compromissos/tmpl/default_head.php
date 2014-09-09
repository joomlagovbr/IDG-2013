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
        <th width="35">
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_ID', 'comp.id', $this->listDirn, $this->listOrder); ?>
        </th>
        <th width="20">
                <?php echo JHtml::_('grid.checkall'); ?>
        </th>
        <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_TITLE', 'comp.title', $this->listDirn, $this->listOrder); ?>
        </th>
        <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_OWNER', 'dc.owner', $this->listDirn, $this->listOrder); ?>
        </th>
        <!-- <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_CATEGORIA', 'dc.owner', $this->listDirn, $this->listOrder); ?>
        </th> -->
        <th>
                <?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'comp.state', $this->listDirn, $this->listOrder); ?>
        </th>
        <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DATA_INICIAL', 'comp.data_inicial', $this->listDirn, $this->listOrder); ?>
        </th>
        <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DATA_FINAL', 'comp.data_final', $this->listDirn, $this->listOrder); ?>
        </th>
        <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_DIA_TODO', 'comp.dia_todo', $this->listDirn, $this->listOrder); ?>
        </th>
        <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_HORARIO_INICIO', 'comp.horario_inicio', $this->listDirn, $this->listOrder); ?>
        </th>
        <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_HORARIO_FIM', 'comp.horario_fim', $this->listDirn, $this->listOrder); ?>
        </th>
        <!-- <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_LOCAL', 'comp.local', $this->listDirn, $this->listOrder); ?>
        </th> -->
        <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_ORDERING', 'comp.ordering', $this->listDirn, $this->listOrder); ?>
        </th>
<!--         <th>
                <?php echo JHtml::_('searchtools.sort', 'COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_OWNER_CARGO', 'car.name', $this->listDirn, $this->listOrder); ?>
        </th> -->
</tr>
<!-- COM_AGENDADIRIGENTES_COMPROMISSOS_HEADING_CHECKED_OUT -->










