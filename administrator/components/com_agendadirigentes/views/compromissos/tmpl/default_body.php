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
$user = JFactory::getUser();
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
?>
<?php foreach($this->items as $i => $item):
        // $canManage = $user->authorise( "compromissos.manage", "com_agendadirigentes.category." . $item->catid );
        $canManage = true;
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
                        echo JHtml::_('jgrid.published', $item->state, $i, 'compromissos.', $canChange, 'cb');
                        echo JHtml::_('agendadirigenteshelper.featured', $item->featured, $i, $canChange, 'compromissos');
                        ?>
                        <?php
                        // Create dropdown items
                        // $action = $item->featured ? 'unfeature' : 'feature';
                        // JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'compromissos');

                        $action = $this->archived ? 'unarchive' : 'archive';
                        JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'compromissos');

                        $action = $this->trashed ? 'untrash' : 'trash';
                        JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'compromissos');

                        // Render dropdown list
                        echo JHtml::_('actionsdropdown.render', $this->escape($item->title));
                        ?>
                    </div>
                </td>
                <td>
                    <?php 
                        $canEdit = true;
                        if ($canEdit) : ?>
                        <a href="<?php echo JRoute::_('index.php?option=com_agendadirigentes&task=compromisso.edit&id=' . (int) $item->id); ?>">
                            <?php echo $this->escape($item->title); ?></a>
                    <?php else : ?>
                        <?php echo $this->escape($item->title); ?>
                    <?php endif; ?>
                </td>
                <td>
                        <?php echo $item->nameOwner; ?>
                        <?php if($item->cargoOwner): ?>
                        (<?php echo $item->cargoOwner; ?>)
                        <?php endif; ?>
                        <?php if ( $this->status_dono_compromisso==0 && $item->owner==1): ?>
                        *
                        <?php endif; ?>
                </td>
              <!--   <td>
                        <?php echo $item->titleCategory; ?>
                </td> -->
                <td class="nowrap">
                        <?php 
                        if ($item->data_inicial == '0000-00-00' || empty($item->data_inicial)) {
                            $data_inicial = 'N&atilde;o informado.';
                        }
                        else
                        {
                            $data_inicial = explode('-', $item->data_inicial);
                            $data_inicial = $data_inicial[2].'/'.$data_inicial[1].'/'.$data_inicial[0];
                        }                        
                        ?>
                        <?php echo $data_inicial; ?> <?php echo substr($item->horario_inicio, 0, 5); ?>
                </td>
                <td class="nowrap">
                        <?php 
                        if ($item->data_final == '0000-00-00' || empty($item->data_final)) {
                            $data_final = $data_inicial;
                        }
                        else
                        {
                            $data_final = explode('-', $item->data_final);
                            $data_final = $data_final[2].'/'.$data_final[1].'/'.$data_final[0];
                        }                        
                        ?>
                        <?php echo $data_final; ?> <?php echo substr($item->horario_fim, 0, 5); ?>
                </td>
                <td class="center">
                        <?php echo ($item->dia_todo==1)? 'Sim' : 'N&atilde;o'; ?>
                </td>
        </tr>
<?php endforeach; ?>