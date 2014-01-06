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
<div id="search-browser">
    <span id="searchbox"><input type="text" id="search-input" placeholder="<?php echo WFText::_('WF_LABEL_SEARCH'); ?>..." /><span class="search-icon"></span></span>
    <button class="button" id="search-button" role="button"><?php echo WFText::_('WF_LABEL_SEARCH'); ?></button>
    <span id="search-options-button" role="button" title="<?php echo WFText::_('WF_LABEL_SEARCH_OPTIONS'); ?>"><span class="icon"></span></span>
    <div id="search-options">
        <fieldset class="phrases">
            <legend><?php echo JText::_('WF_SEARCH_FOR'); ?>
            </legend>
            <div class="phrases-box">
                <?php echo $this->lists['searchphrase']; ?>
            </div>
            <div class="ordering-box">
                <label for="ordering" class="ordering">
                    <?php echo JText::_('WF_SEARCH_ORDERING'); ?>
                </label>
                <?php echo $this->lists['ordering']; ?>
            </div>
        </fieldset>
        <fieldset class="only">
            <legend><?php echo JText::_('WF_SEARCH_SEARCH_ONLY'); ?></legend>
            <ul>
            <?php
            foreach ($this->searchareas as $val => $txt) :
                ?>
                <li>
                    <input type="checkbox" name="areas[]" value="<?php echo $val; ?>" id="area-<?php echo $val; ?>" />
                <label for="area-<?php echo $val; ?>">
                    <?php echo JText::_($txt); ?>
                </label>
                </li>
            <?php endforeach; ?>
            </ul>
        </fieldset>
    </div>
    <div id="search-result"></div>
</div>