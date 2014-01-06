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
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">
    <div id="jce" class="loading">
        <div id="tabs">
            <ul class="nav nav-tabs">
                <li class="active wf-tooltip" title="<?php echo JText :: _('WF_PROFILES_SETUP') . '::' . JText :: _('WF_PROFILES_SETUP_DESC'); ?>"><a href="#tabs-setup"><?php echo JText :: _('WF_PROFILES_SETUP'); ?></a></li>
                <li class="wf-tooltip" title="<?php echo JText :: _('WF_PROFILES_FEATURES') . '::' . JText :: _('WF_PROFILES_FEATURES_DESC'); ?>"><a href="#tabs-features"><?php echo JText :: _('WF_PROFILES_FEATURES'); ?></a></li>
                <li class="wf-tooltip" title="<?php echo JText :: _('WF_PROFILES_EDITOR_PARAMETERS') . '::' . JText :: _('WF_PROFILES_EDITOR_PARAMETERS_DESC'); ?>"><a href="#tabs-editor"><?php echo JText :: _('WF_PROFILES_EDITOR_PARAMETERS'); ?></a></li>
                <li class="wf-tooltip" title="<?php echo JText :: _('WF_PROFILES_PLUGIN_PARAMETERS') . '::' . JText :: _('WF_PROFILES_PLUGIN_PARAMETERS_DESC'); ?>"><a href="#tabs-plugins"><?php echo JText :: _('WF_PROFILES_PLUGIN_PARAMETERS'); ?></a></li>
            </ul>
            <div class="tab-content">
                <div id="tabs-setup" class="tab-pane active">
                    <?php echo $this->loadTemplate('setup'); ?>
                </div>
                <div id="tabs-features" class="tab-pane">
                    <?php echo $this->loadTemplate('features'); ?>
                </div>
                <div id="tabs-editor" class="tab-pane tabbable tabs-left">
                    <ul class="nav nav-tabs">
                        <?php
                        $x = 0;
                        foreach ($this->profile->editor_groups as $group) :                            
                            echo '<li><a href="#tabs-editor-' . $group . '"><span>' . WFText::_('WF_PROFILES_EDITOR_' . strtoupper($group)) . '</span></a></li>';
                            $x++;
                        endforeach;
                        ?>
                    </ul>
                    <?php echo $this->loadTemplate('editor'); ?>
                </div>
                <div id="tabs-plugins" class="tab-pane tabbable tabs-left">
                    <ul class="nav nav-tabs">
                        <?php
                        // Build tabs
                        foreach ($this->plugins as $plugin) :
                            if ($plugin->editable && is_file(JPATH_SITE . '/' . $plugin->path . '/' . $plugin->name . '.xml')) :
                                $icon   = '';
                                $class  = '';
                                if ($plugin->icon) :
                                    $icon = $this->model->getIcon($plugin);
                                endif;

                                $class = in_array($plugin->name, explode(',', $this->profile->plugins)) ? '' : 'tab-disabled';

                                echo '<li class="defaultSkin ' . $class . '" data-name="' . $plugin->name . '"><a href="#tabs-plugin-' . $plugin->name . '">' . $icon . '<span class="tabs-label">' . WFText::_($plugin->title) . '</span></a></li>';
                            endif;
                        endforeach;
                        ?>
                    </ul>
                    <div class="tab-content">
                        <?php echo $this->loadTemplate('plugin'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="option" value="com_jce" />
    <input type="hidden" name="id" value="<?php echo $this->profile->id; ?>" />
    <input type="hidden" name="cid[]" value="<?php echo $this->profile->id; ?>" />
    <input type="hidden" name="view" value="profiles" />
    <input type="hidden" name="task" value="" />
<?php echo JHTML::_('form.token'); ?>
</form>