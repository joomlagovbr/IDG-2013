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
                
        // $canDo = AgendaDirigentesHelper::getActions('com_agendadirigentes', 'category', $item->catid);
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
                <td class="center">
                    <div class="btn-group">
                        <?php
                        $canChange = true;
                        echo JHtml::_('jgrid.published', $item->state, $i, 'dirigentes.', $canChange, 'cb'); ?>
                        <?php
                        // Create dropdown items
                        $action = $this->archived ? 'unarchive' : 'archive';
                        JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'dirigentes');

                        $action = $this->trashed ? 'untrash' : 'trash';
                        JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'dirigentes');

                        // Render dropdown list
                        echo JHtml::_('actionsdropdown.render', $this->escape($item->name));
                        ?>
                    </div>
                </td>
                <td>
                    <?php if ( $canManage ) : ?>
                        <a href="<?php echo JRoute::_('index.php?option=com_agendadirigentes&task=dirigente.edit&id=' . (int) $item->id); ?>">
                            <?php echo $this->escape($item->name); ?></a>
                    <?php else : ?>
                        <?php echo $this->escape($item->name); ?>
                    <?php endif; ?>
                </td>
                <td>
                        <?php echo $item->categoria; ?>
                </td>
                <td>
                        <?php echo $item->cargo; ?>
                </td>
                <td>
                        <?php echo $item->ordering; ?>
                </td>
                <!-- <td>
                        <?php //echo $item->categoria; ?>
                </td> -->
                <td>
                        <?php echo $item->block; ?>
                </td>
        </tr>
<?php endforeach; ?>