<?php
/**
 * @package Akeeba
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 3.6.0
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

JHtml::_('behavior.framework');
?>
    <ul id="runCheckTabs" class="nav nav-tabs">
        <li>
            <a href="#absTabRunBackups" data-toggle="tab">
                <?php echo JText::_('COM_AKEEBA_SCHEDULE_LBL_RUN_BACKUPS'); ?>
            </a>
        </li>
        <li>
            <a href="#absTabCheckBackups" data-toggle="tab">
                <?php echo JText::_('COM_AKEEBA_SCHEDULE_LBL_CHECK_BACKUPS'); ?>
            </a>
        </li>
    </ul>

    <div id="runCheckTabsContent" class="tab-content">
<?php
    echo $this->loadTemplate('runbackups');
    echo $this->loadTemplate('checkbackups');
?>
    </div>
<?php
JFactory::getDocument()->addScriptDeclaration( <<<ENDJS
    (function($) {
        $(document).ready(function(){
            $('#runCheckTabs a:first').tab('show');
        });
    })(akeeba.jQuery);
ENDJS
);
?>