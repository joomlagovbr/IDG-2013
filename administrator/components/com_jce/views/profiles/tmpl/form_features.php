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

$position = 'mce' . ucfirst($this->profile->layout_params->get('toolbar_align', 'center'));

// width and height
$width  = $this->profile->layout_params->get('editor_width', 600);
$height = $this->profile->layout_params->get('editor_height', 'auto');

if (is_numeric($width) || strpos('%', $width) === false) {
    $width .= 'px';
}
if (is_numeric($height) || strpos('%', $height) === false) {
    $height .= 'px';
}

if (strpos('%', $width) !== false) {
    $height = '600px';
}

if (strpos('%', $height) !== false) {
    $height = 'auto';
}

$theme = $this->profile->layout_params->get('toolbar_theme', 'default');
if (strpos($theme, '.') === false) {
    $theme = $theme.'Skin';
} else {
    $theme = str_replace(array('o2k7.silver', 'o2k7.black'), array('o2k7.Silver', 'o2k7.Black'), $theme);   
    $theme = preg_replace('#([\w]+)\.([\w]+)#', '$1Skin $1Skin$2', $theme);
}
?>
<fieldset class="first">
    <legend><?php echo WFText::_('WF_PROFILES_FEATURES_LAYOUT'); ?></legend>
    <!-- Layout Params -->
    <div id="layout_params">
        <?php foreach ($this->profile->layout_groups as $group) : ?>
            <div id="tabs-editor-<?php echo $group ?>">
            <?php echo $this->profile->layout_params->render('params[editor]', $group); ?>
            </div>
        <?php endforeach; ?>
    </div>

    <ul class="adminformlist" id="profileLayoutTable">
        <!-- Active Editor Layout -->
        <li>
            <label class="wf-tooltip" title="<?php echo WFText::_('WF_PROFILES_FEATURES_LAYOUT_EDITOR') . '::' . WFText::_('WF_PROFILES_FEATURES_LAYOUT_EDITOR_DESC'); ?>"><?php echo WFText::_('WF_PROFILES_FEATURES_LAYOUT_EDITOR'); ?></label>
            <span class="profileLayoutContainer profileLayoutContainerCurrent <?php echo $theme; ?>">
                <span id="editor_toggle"><?php echo $this->profile->layout_params->get('toggle_label', '[Toggle Editor]');?></span>
                <span class="widthMarker" style="width:<?php echo $width; ?>;"><span><?php echo $width; ?></span></span>
                <!-- Toolbar -->
                <span id="toolbar_container" class="profileLayoutContainerToolbar">
                    <ul class="sortableList <?php echo $position; ?>">
                        <?php for ($i = 1; $i <= count($this->rows); $i++) : ?>
                            <li class="sortableListItem">
                                <span class="sortableRow mceToolbar">
                                    <?php for ($x = 1; $x <= count($this->rows); $x++) : ?>
                                        <?php if ($i == $x) : ?>
                                            <?php foreach (explode(',', $this->rows[$x]) as $icon) : ?>
                                                <?php if ($icon == 'spacer') : ?>
                                                    <span class="sortableRowItem spacer" data-name="spacer"><span class="mceSeparator"></span></span>
                                                <?php endif; ?>
                                                <?php foreach ($this->plugins as $plugin) : ?>
                                                    <?php if ($plugin->icon && $plugin->name == $icon) : ?>
                                                        <span data-name="<?php echo $plugin->name; ?>" class="sortableRowItem <?php echo $plugin->type; ?> wf-tooltip wf-tooltip-cancel-ondrag" title="<?php echo WFText::_($plugin->title);?>::<?php echo WFText::_($plugin->description);?>"><?php echo $this->model->getIcon($plugin); ?></span>
                                                    <?php
                                                    endif;
                                                endforeach;
                                            endforeach;
                                        endif;
                                    endfor;
                                    ?>
                                </span>
                                <span class="sortableRowHandle"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></span>
                                <span class="sortableOption"></span>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </span>
                <!--  Editor -->
                <span id="editor_container" class="profileLayoutContainerEditor mceIframeContainer" style="width:<?php echo $width; ?>;height:<?php echo $height; ?>"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></span>
                <!--  Statusbar -->
                <span id="statusbar_container" class="profileLayoutContainerStatusBar"><span class="mceStatusbar"><span><span class="mcePathLabel">Path: </span></span><a href="#" class="mceResize"></a></span></span>
            </span>
        </li>
        <!-- Available Buttons -->
        <li>
            <label class="wf-tooltip" title="<?php echo WFText::_('WF_PROFILES_FEATURES_LAYOUT_AVAILABLE') . '::' . WFText::_('WF_PROFILES_FEATURES_LAYOUT_AVAILABLE_DESC'); ?>"><?php echo WFText::_('WF_PROFILES_FEATURES_LAYOUT_AVAILABLE'); ?></label>
            <span class="profileLayoutContainer <?php echo $theme; ?>">
                <span class="profileLayoutContainerToolbar">
                <ul class="sortableList">
                    <?php for ($i = 1; $i <= 5; $i++) :?>
                        <li class="sortableListItem">
                            <span class="sortableRow mceToolbar">
                                <?php if ($i == 5) :
                                    for ($x = 1; $x <= 10; $x++) :
                                ?>
                                    <span class="sortableRowItem spacer" data-name="spacer"><span class="mceSeparator"></span></span>
                                <?php
                                    endfor;
                                endif;

                                foreach ($this->plugins as $plugin) :
                                    if (!in_array($plugin->name, explode(',', implode(',', $this->rows)))) :
                                        if ($plugin->icon && (int)$plugin->row == $i) :
                                            echo '<span class="sortableRowItem ' . $plugin->type . ' wf-tooltip wf-tooltip-cancel-ondrag" data-name="' . $plugin->name . '" title="' . WFText::_($plugin->title) . '::' . WFText::_($plugin->description) . '">' . $this->model->getIcon($plugin) . '</span>';
                                        endif;
                                    endif;
                                endforeach;
                                ?>
                            </span>
                            <span class="sortableRowHandle"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></span>
                            <span class="sortableOption"></span>
                        </li>
                    <?php endfor; ?>
                </ul>
                </span>
            </span>	
        </li>
    </ul>
    <input type="hidden" name="rows" value="<?php echo $this->profile->rows; ?>" />
    <input type="hidden" name="plugins" value="<?php echo $this->profile->plugins; ?>" />
</fieldset>
<fieldset>
    <legend><?php echo WFText::_('WF_PROFILES_FEATURES_ADDITIONAL'); ?></legend>
    <ul id="profileAdditionalFeatures" class="adminformlist">
        <?php
        $i = 0;
        foreach ($this->plugins as $plugin) :
            if (!$plugin->icon) :
                if ($plugin->editable) :
                    ?>
                    <li class="editable">
                        <label valign="top" class="key"><?php echo WFText::_($plugin->title); ?></label>
                        <input type="checkbox" value="<?php echo $plugin->name; ?>" <?php echo in_array($plugin->name, explode(',', $this->profile->plugins)) ? 'checked="checked"' : ''; ?>/>
            <?php echo WFText::_('WF_' . strtoupper($plugin->name) . '_DESC'); ?>
                    </li>
                    <?php else : ?>
                    <li>
                        <label><?php echo WFText::_($plugin->title); ?></label>
                        <input type="checkbox" value="<?php echo $plugin->name; ?>" <?php echo in_array($plugin->name, explode(',', $this->profile->plugins)) ? 'checked="checked"' : ''; ?>/>
                    <?php echo WFText::_('WF_' . strtoupper($plugin->name) . '_DESC'); ?>
                    </li>
                <?php
                endif;
            endif;
            $i++;
        endforeach;
        ?>
    </ul>
</fieldset>