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
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<p style="text-align:left;"><a href="http://joomlaboat.com/contact-us" target="_blank" style="margin-left:20px;">Help (Contact Tech-Support)</a></p>
<?php

jimport('joomla.version');
$version = new JVersion();
$JoomlaVersionRelease=$version->RELEASE;

if($JoomlaVersionRelease>=3.0):
?>

<form action="<?php echo JRoute::_('index.php?option=com_youtubegallery&view=categories'); ?>" method="post" name="adminForm" id="adminForm">
        <table class="table table-striped" id="articleList">
                <thead><?php echo $this->loadTemplate('head3x');?></thead>
                <tfoot><?php echo $this->loadTemplate('foot3x');?></tfoot>
                <tbody><?php echo $this->loadTemplate('body3x');?></tbody>
        </table>
        <div>
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <?php echo JHtml::_('form.token'); ?>
        </div>
</form>

<?php else: ?>

<form action="<?php echo JRoute::_('index.php?option=com_youtubegallery'); ?>" method="post" name="adminForm">
        <table class="adminlist">
                <thead><?php echo $this->loadTemplate('head25');?></thead>
                <tfoot><?php echo $this->loadTemplate('foot25');?></tfoot>
                <tbody><?php echo $this->loadTemplate('body25');?></tbody>
        </table>
        <div>
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <?php echo JHtml::_('form.token'); ?>
        </div>
</form>

<?php endif; ?>

