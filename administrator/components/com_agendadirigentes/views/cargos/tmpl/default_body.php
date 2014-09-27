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
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
?>
<?php foreach($this->items as $i => $item):
        
       $canManage = $this->user->authorise( "dirigentes.manage", "com_agendadirigentes.category." . $item->catid );
       $canChange = true;
        ?>
        <tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->catid; ?>">
                <td class="order nowrap center hidden-phone">
                    <?php
                    $iconClass = '';
                    if (!$canChange)
                    {
                        $iconClass = ' inactive';
                    }
                    elseif (!$this->saveOrder)
                    {
                        $iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
                    }
                    ?>
                    <span class="sortable-handler <?php echo $iconClass ?>">
                        <i class="icon-menu"></i>
                    </span>
                    <?php if ($canChange && $this->saveOrder) : ?>
                        <input type="text" style="display:none" name="order[]" size="5"
                            value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
                    <?php endif; ?>
                </td>
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
                        <?php
                        $canChange = true;
                        echo JHtml::_('jgrid.published', $item->published, $i, 'cargos.', $canChange, 'cb');
                        echo JHtml::_('agendadirigenteshelper.featured', $item->featured, $i, $canChange, 'cargos');
                        ?>
                </td>
                <td>
                    <?php if ( $canManage ) : ?>
                        <a href="<?php echo JRoute::_('index.php?option=com_agendadirigentes&task=cargo.edit&id=' . (int) $item->id); ?>">
                            <?php echo $this->escape($item->name); ?></a>
                    <?php else : ?>
                        <?php echo $this->escape($item->name); ?>
                    <?php endif; ?>
                    <?php if (!empty($item->name_f)): ?>
                        <small style="color:#999"><small>/ <?php echo $item->name_f; ?></small></small>
                    <?php endif ?>
                </td>
                <td>
                        <?php echo $item->titleCategory; ?>
                </td>
        </tr>
<?php endforeach; ?>