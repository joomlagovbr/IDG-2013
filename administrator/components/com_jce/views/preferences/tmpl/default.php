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
<form action="index.php" method="post" name="adminForm">
    <div id="jce">
        <div class="btn-group pull-right fltrgt">
            <button class="btn" id="apply"><i class="icon-ok"></i>&nbsp;<?php echo WFText::_('WF_LABEL_SAVE');?></button>
            <button class="btn" id="save"><i class="icon-edit"></i>&nbsp;<?php echo WFText::_('WF_LABEL_SAVECLOSE');?></button>
            <button class="btn" id="cancel"><i class="icon-remove"></i>&nbsp;<?php echo WFText::_('WF_LABEL_CANCEL');?></button>
    	</div>
    	<div class="clr clearfix"></div>
        <div id="tabs" class="tab-pane">
            <ul class="nav nav-tabs">
                <?php foreach ($this->params->getGroups() as $group) : ?>
                    <li><a href="#tabs-<?php echo $group; ?>"><?php echo JText :: _('WF_PREFERENCES_' . strtoupper($group)); ?></a></li>
                <?php endforeach; ?>
                <?php if ($this->permissons) : ?>
                    <li><a href="#tabs-access"><?php echo JText :: _('WF_PREFERENCES_PERMISSIONS'); ?></a></li>
                <?php endif; ?>		
            </ul>	
            <?php foreach ($this->params->getGroups() as $group) : ?>
                <div id="tabs-<?php echo $group ?>">
                    <?php echo $this->params->render('params[preferences]', $group); ?>
                </div>
            <?php endforeach; ?>
            <?php if ($this->permissons) : ?>
                <div id="tabs-access">
                    <?php
                    if (!class_exists('JForm')) :
                        echo '<div id="tabs-access-permissions" class="tabbable tabs-left">';
                        echo $this->permissons;
                        echo '</div>';
                    endif;
                    ?>
                </div>
                <?php endif; ?>
        </div>
    </div>
    <input type="hidden" name="option" value="com_jce" />
    <input type="hidden" name="view" value="preferences" />
    <input type="hidden" name="task" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>