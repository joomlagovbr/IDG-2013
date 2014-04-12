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
<form action="index.php?option=com_jce&tmpl=component" method="post" name="adminForm" id="adminForm" class="form-horizontal">
    <div id="jce">
        <fieldset id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left fltlft">
                <input type="text" name="search" id="search" size="30" value="<?php echo $this->lists['search']; ?>" class="text_area" onchange="document.adminForm.submit();" placeholder="<?php echo WFText::_('WF_LABEL_SEARCH'); ?>" />
            </div>
            <div class="btn-group fltlft">
                <button id="filter_go" onclick="this.form.submit();" class="btn" title="<?php echo WFText::_('WF_LABEL_SEARCH'); ?>"><i class="icon-search"></i>&nbsp;<?php echo WFText::_('WF_LABEL_SEARCH'); ?></button>
                <button id="filter_reset" onclick="document.getElementById('search').value='';this.form.submit();" class="btn" title="<?php echo WFText::_('WF_LABEL_CLEAR'); ?>"><i class="icon-remove"></i>&nbsp;<?php echo WFText::_('WF_LABEL_CLEAR'); ?></button>
            </div>
            <div class="btn-group fltrt pull-right">
                <?php echo $this->lists['group']; ?>
            </div>
        </fieldset>
        <div class="clr clearfix"></div>
        <div class="container">
            <table id="users-list" class="table table-striped" style="position: relative;">
                <thead>
                    <tr>
                        <th class="title">
                            <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                        </th>
                        <th class="title">
                            <?php echo JHTML::_('grid.sort', 'WF_USERS_NAME', 'a.name', @$this->lists['order_Dir'], @$this->lists['order']); ?>
                        </th>
                        <th class="title" >
                            <?php echo JHTML::_('grid.sort', 'WF_USERS_USERNAME', 'a.username', @$this->lists['order_Dir'], @$this->lists['order']); ?>
                        </th>
                        <th class="title">
                            <?php echo JHTML::_('grid.sort', 'WF_USERS_GROUP', 'groupname', @$this->lists['order_Dir'], @$this->lists['order']); ?>
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="4">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $k = 0;
                    for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                        $row = $this->items[$i];
                        ?>
                        <tr>
                            <td>
                                <?php echo JHTML::_('grid.id', $i, $row->id); ?>
                            </td>
                            <td>
                                <?php echo $row->name; ?>
                            </td>
                            <td>
                                <span id="username_<?php echo $row->id; ?>"><?php echo $row->username; ?></span>
                            </td>
                            <td>
                                <?php echo WFText::_($row->groupname); ?>
                            </td>
                        </tr>
                        <?php
                        $k = 1 - $k;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="btn-group fltrgt pull-right">
            <button id="select" class="btn" title="<?php echo WFText::_('WF_LABEL_SELECT'); ?>"><i class="icon-ok"></i>&nbsp;<?php echo WFText::_('WF_LABEL_SELECT'); ?></button>
            <button id="cancel" class="btn" title="<?php echo WFText::_('WF_LABEL_CANCEL'); ?>"><i class="icon-remove"></i>&nbsp;<?php echo WFText::_('WF_LABEL_CANCEL'); ?></button>
        </div>
    </div>
    <input type="hidden" name="option" value="com_jce" />
    <input type="hidden" name="view" value="users" />
    <input type="hidden" name="task" value="addusers" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
    <?php echo JHTML::_('form.token'); ?>
</form>