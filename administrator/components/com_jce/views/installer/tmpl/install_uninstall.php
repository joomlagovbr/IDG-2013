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
<h4><?php echo WFText::_('WF_INSTALLER_UNINSTALL_DESC'); ?></h4>
<div id="tabs">
    <ul class="nav nav-tabs">
        <li class="wf-tooltip active" title="<?php echo JText :: _('WF_INSTALLER_PLUGINS') . '::' . WFText::_('WF_INSTALLER_PLUGINS_DESC'); ?>"><a href="#tabs-plugins"><?php echo JText :: _('WF_INSTALLER_PLUGINS'); ?></a></li>
        <li class="wf-tooltip" title="<?php echo JText :: _('WF_INSTALLER_EXTENSIONS') . '::' . WFText::_('WF_INSTALLER_EXTENSIONS_DESC'); ?>"><a href="#tabs-extensions"><?php echo JText :: _('WF_INSTALLER_EXTENSIONS'); ?></a></li>
        <li class="wf-tooltip" title="<?php echo JText :: _('WF_INSTALLER_LANGUAGES') . '::' . WFText::_('WF_INSTALLER_LANGUAGES_DESC'); ?>"><a href="#tabs-languages"><?php echo JText :: _('WF_INSTALLER_LANGUAGES'); ?></a></li>
        <li class="wf-tooltip" title="<?php echo JText :: _('WF_INSTALLER_RELATED') . '::' . WFText::_('WF_INSTALLER_RELATED_DESC'); ?>"><a href="#tabs-related"><?php echo JText :: _('WF_INSTALLER_RELATED'); ?></a></li>
    </ul>
    <div class="tab-content">
        <div id="tabs-plugins" class="tab-pane active">
            <?php if (count($this->plugins)) : ?>
                <?php echo $this->loadTemplate('plugins'); ?>
            <?php else : ?>
                <?php echo WFText::_('WF_INSTALLER_NO_PLUGINS'); ?>
            <?php endif; ?>
        </div>
        <div id="tabs-extensions" class="tab-pane">
            <?php if (count($this->extensions)) : ?>
                <?php echo $this->loadTemplate('extensions'); ?>
            <?php else : ?>
                <?php echo WFText::_('WF_INSTALLER_EXTENSIONS'); ?>
            <?php endif; ?>
        </div>
        <div id="tabs-languages" class="tab-pane">
            <?php if (count($this->languages)) : ?>
                <?php echo $this->loadTemplate('languages'); ?>
            <?php else : ?>
                <?php echo WFText::_('WF_INSTALLER_NO_LANGUAGES'); ?>
            <?php endif; ?>
        </div>
        <div id="tabs-related" class="tab-pane">
            <?php if (count($this->related)) : ?>
                <?php echo $this->loadTemplate('related'); ?>
            <?php else : ?>
                <?php echo WFText::_('WF_INSTALLER_NO_RELATED'); ?>
            <?php endif; ?>
        </div>
    </div>
</div>