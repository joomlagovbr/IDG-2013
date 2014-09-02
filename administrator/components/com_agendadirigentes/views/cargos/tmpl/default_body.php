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
$user = JFactory::getUser();
?>
<?php foreach($this->items as $i => $item):
        
       $canManage = $user->authorise( "dirigentes.manage", "com_agendadirigentes.category." . $item->catid );
        ?>
        <tr class="row<?php echo $i % 2; ?>">
                <td>
                    <?php if ( $canManage ) : ?>
                        <?php echo $item->id; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ( $canManage ) : ?>
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php 
                        if ( $canManage ) : ?>
                        <a href="<?php echo JRoute::_('index.php?option=com_agendadirigentes&task=cargo.edit&id=' . (int) $item->id); ?>">
                            <?php echo $this->escape($item->name); ?></a>
                    <?php else : ?>
                        <?php echo $this->escape($item->name); ?>
                    <?php endif; ?>
                </td>
                <td>
                        <?php echo $item->titleCategory; ?>
                </td>
        </tr>
<?php endforeach; ?>