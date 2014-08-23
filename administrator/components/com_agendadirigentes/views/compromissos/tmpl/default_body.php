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
?>
<?php foreach($this->items as $i => $item): ?>
        <tr class="row<?php echo $i % 2; ?>">
                <td>
                        <?php echo $item->id; ?>
                </td>
                <td>
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                </td>
                <td>
                        <?php echo $item->title; ?>
                </td>
                <td>
                        <?php echo $item->titleCategory; ?>
                </td>
                <td>
                        <?php echo $item->published; ?>
                </td>
                <td>
                        <?php echo $item->dia_todo; ?>
                </td>
                <td>
                        <?php echo $item->data_inicial; ?>
                </td>
                <td>
                        <?php echo $item->horario_inicio; ?>
                </td>
                <td>
                        <?php echo $item->data_final; ?>
                </td>
                <td>
                        <?php echo $item->horario_fim; ?>
                </td>
                <td>
                        <?php echo $item->ordering; ?>
                </td>
                <td>
                        <?php echo $item->local; ?>
                </td>
                <td>
                        <?php echo $item->nameOwner; ?>
                </td>
                <td>
                        <?php echo $item->cargoOwner; ?>
                </td>
        </tr>
<?php endforeach; ?>