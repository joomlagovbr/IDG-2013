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
                    <?php 
                        $canEdit = true;
                        if ($canEdit) : ?>
                        <a href="<?php echo JRoute::_('index.php?option=com_agendadirigentes&task=dirigente.edit&id=' . (int) $item->id); ?>">
                            <?php echo $this->escape($item->name); ?></a>
                    <?php else : ?>
                        <?php echo $this->escape($item->name); ?>
                    <?php endif; ?>
                </td>
                <td>
                        <?php echo $item->cargo; ?>
                </td>
                <td>
                        <?php echo $item->categoria; ?>
                </td>
                <td>
                        <?php //echo $item->categoria; ?>
                </td>
                <td>
                        <?php //echo $item->categoria; ?>
                </td>
                <td>
                        <?php echo $item->block; ?>
                </td>
                <td>
                        <?php echo $item->ordering; ?>
                </td>
        </tr>
<?php endforeach; ?>