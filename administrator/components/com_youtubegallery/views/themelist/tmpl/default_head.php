<?php
/**
 * YoutubeGallery Joomla! Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.version');
$version = new JVersion();
$JoomlaVersionRelease=$version->RELEASE;

?>
<tr>
        <th width="5">
                <?php echo JText::_('COM_YOUTUBEGALLERY_ID'); ?>
        </th>
        <th width="20">
                <?php if($JoomlaVersionRelease>=3.0): ?>
                <input type="checkbox" name="checkall-toggle" value="" title="Check All" onclick="Joomla.checkAll(this)" />
                <?php endif; ?>
        </th>                     
        <th align="left" style="text-align:left;">
                <?php echo JText::_('COM_YOUTUBEGALLERY_THEMENAME'); ?>
        </th>
        
        <th align="left" style="text-align:left;">
                Media Folder
        </th>
        
        <th align="left" style="text-align:left;">
                Export
        </th>
</tr>

