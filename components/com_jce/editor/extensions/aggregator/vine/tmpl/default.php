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
defined('_WF_EXT') or die('RESTRICTED');
?>
<table border="0" cellpadding="4" cellspacing="0" width="100%">
    <tr>
        <td>
            <label title="<?php echo WFText::_('WF_AGGREGATOR_VINE_TYPE_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_AGGREGATOR_VINE_TYPE') ?></label>
            <select id="vine_type">
                <option value="simple"><?php echo WFText::_('WF_AGGREGATOR_VINE_SIMPLE') ?></option>
                <option value="postcard"><?php echo WFText::_('WF_AGGREGATOR_VINE_POSTCARD') ?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td>
            <label title="<?php echo WFText::_('WF_AGGREGATOR_VINE_SIZE_DESC') ?>" class="tooltip"><?php echo WFText::_('WF_AGGREGATOR_VINE_SIZE') ?></label>
            <select id="vine_size">
                <option value="600">600px</option>
                <option value="480">480px</option>
                <option value="300">300px</option>
            </select>
        </td>
    </tr>
</table>