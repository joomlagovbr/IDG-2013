<?php

/**
 * @package   	JCE
 * @copyright 	Copyright (c) 2009-2013 Ryan Demmer. All rights reserved.
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
defined('_JEXEC') or die('RESTRICTED');
?>
<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">
    <div id="jce" class="loading">
        <div class="progress progress-striped active">
            <div class="bar" style="width: 100%;"><?php echo JText :: _('WF_MESSAGE_LOAD');?></div>
        </div>
        <fieldset id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left fltlft">
                <input type="text" name="search" id="search" size="30" value="<?php echo $this->lists['search']; ?>" class="text_area" onchange="document.adminForm.submit();" placeholder="<?php echo WFText::_('WF_LABEL_SEARCH'); ?>" />
            </div>
            <div class="btn-group fltlft">
                <button id="filter_go" onclick="this.form.submit();" class="btn" title="<?php echo WFText::_('WF_LABEL_SEARCH'); ?>"><i class="icon-search"></i>&nbsp;<?php echo WFText::_('WF_LABEL_SEARCH'); ?></button>
                <button id="filter_reset" onclick="document.getElementById('search').value='';this.form.submit();" class="btn" title="<?php echo WFText::_('WF_LABEL_CLEAR'); ?>"><i class="icon-remove"></i>&nbsp;<?php echo WFText::_('WF_LABEL_CLEAR'); ?></button>
            </div>
            <div class="filter-search fltrt btn-group pull-right visible-desktop">
                <div class="upload-container btn-group pull-right">
                    <div class="input-append upload_button_container">
                        <input type="file" name="import" size="30" id="upload" accept="application/xml" placeholder="<?php echo WFText::_('WF_PROFILES_IMPORT'); ?>" />
                        <button id="upload_button" class="btn upload-import"><i class="icon-arrow-up"></i>&nbsp;<?php echo WFText::_('WF_PROFILES_IMPORT_IMPORT'); ?></button>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="clr clearfix"></div>
        <table id="profiles-list" class="table table-striped" style="position: relative;">
            <thead>
                <tr>
                    <th class="hidden-phone"></th>
                    <th>
                        <input type="checkbox" value="" />
                    </th>
                    <th>
                        <?php echo JHTML::_('grid.sort', 'WF_PROFILES_NAME', 'p.name', @$this->lists['order_Dir'], @$this->lists['order']); ?>
                    </th>
                    <th>
                        <?php echo JHTML::_('grid.sort', 'WF_PROFILES_STATE', 'p.published', @$this->lists['order_Dir'], @$this->lists['order']); ?>
                    </th>
                    <th>
                        <?php echo JHTML::_('grid.sort', 'WF_PROFILES_ORDERING', 'p.ordering', @$this->lists['order_Dir'], @$this->lists['order']); ?>
                        <?php
                        if (count($this->rows) > 1) {
                            echo JHTML::_('grid.order', $this->rows);
                        }
                        ?>
                    </th>
                    <th>
                        <?php echo JHTML::_('grid.sort', 'WF_LABEL_ID', 'p.id', @$this->lists['order_Dir'], @$this->lists['order']); ?>
                    </th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
                </tr>
            </tfoot>
            <tbody>
                <?php
                $rows = $this->rows;
                $k = 0;
                for ($i = 0, $n = count($rows); $i < $n; $i++) {
                    $row = $rows[$i];
                    
                    $profile = JTable::getInstance('profiles', 'WFTable');
                    $profile->bind($row);
                    $profile->editor = null;

                    $link = JRoute::_('index.php?option=com_jce&view=profiles&task=edit&cid[]=' . $row->id);

                    // state
                    $state      = JHTML::_('grid.published', $profile, $i);

                    // checked out
                    $checked    = JHTML::_('grid.checkedout', $profile, $i);
                    ?>
                    <tr>
                        <td class="order nowrap center hidden-phone">
                            <span class="sortable-handle">
                                <i class="icon-th"></i>
                            </span>
                        </td>
                        <td align="center">
                            <?php echo $checked; ?>
                        </td>
                        <td>
                            <?php
                            if ($profile->isCheckedOut($this->user->get('id'), $row->checked_out)) {
                                echo $row->name;
                            } else {
                                ?>
                                <span class="editlinktip wf-tooltip" title="<?php echo WFText::_('WF_PROFILES_EDIT'); ?>::<?php echo $row->name; ?>">
                                    <a href="<?php echo $link; ?>">
                                        <?php echo $row->name; ?></a></span>
                            <?php } ?>
                            <p class="smallsub"><?php echo $row->description; ?></p>
                        </td>
                        <td align="center">
                            <?php echo $state; ?>
                        </td>
                        <td class="order" align="center">
                            <span class="order-up">
                                <a title="<?php echo WFText::_('WF_PROFILES_MOVE_UP');?>" href="#" class="btn btn-micro jgrid"><i class="icon-uparrow icon-chevron-up"></i></a>
                            </span>
                            <span class="order-down">
                                <a title="<?php echo WFText::_('WF_PROFILES_MOVE_DOWN');?>" href="#" class="btn btn-micro jgrid"><i class="icon-downarrow icon-chevron-down"></i></a>
                            </span>
                            <?php $disabled = $n > 1 ? '' : 'disabled="disabled"'; ?>
                            <input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
                        </td>
                        <td align="center">
                            <?php echo $row->id; ?>
                        </td>
                    </tr>
                    <?php
                    $k = 1 - $k;
                }
                ?>
            </tbody>
        </table>
    </div>
    <input type="hidden" name="option" value="com_jce" />
    <input type="hidden" name="view" value="profiles" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
    <?php echo JHTML::_('form.token'); ?>
</form>