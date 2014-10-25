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
<?php foreach($this->items as $i => $item):

        list($canManage, $canChange) = AgendaDirigentesHelper::getGranularPermissions('dirigentes', $item );

        ?>
        <tr class="row<?php echo $i % 2; ?>">
                <td>
                    <?php if ( $canManage ) : ?>
                        <?php echo $item->id; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ( $canManage || $canChange ) : ?>
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    <?php endif; ?>
                </td>
                <td class="center">
                    <div class="btn-group">
                        <?php
                        echo JHtml::_('jgrid.published', $item->state, $i, 'dirigentes.', $canChange, 'cb'); ?>
                        <?php
                        if($canChange):

                            // Create dropdown items
                            $action = $this->archived ? 'unarchive' : 'archive';
                            JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'dirigentes');

                            $action = $this->trashed ? 'untrash' : 'trash';
                            JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'dirigentes');

                            // Render dropdown list
                            echo JHtml::_('actionsdropdown.render', $this->escape($item->name));

                        endif;
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
                    <?php if($item->sexo=='M' || empty($item->cargo_f)): ?>
                        <?php echo $item->cargo; ?>
                    <?php else: ?>
                        <?php echo $item->cargo_f; ?>
                    <?php endif; ?>
                </td>                
        </tr>
<?php endforeach; ?>