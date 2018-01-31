<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 3.5.9
 * @author DesignCompass corp< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
?>

<p style="text-align:left;">Upgrade to <a href="http://joomlaboat.com/youtube-gallery#pro-version" target="_blank">PRO version</a> to get more features</p>
<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_youtubegallery'); ?>" method="post" class="form-inline">


        <fieldset class="adminform">
               <legend><?php echo JText::_( 'COM_YOUTUBEGALLERY_CATEGORY_FORM_DETAILS' ); ?></legend>
               
               <div>
               <div style="float:left;width:100px;"><?php echo $this->form->getLabel('categoryname'); ?></div>: <?php echo $this->form->getInput('categoryname'); ?>
               </div>
               
               
               <div>
               <div style="float:left;width:100px;"><?php echo $this->form->getLabel('parentid'); ?></div>: <?php echo $this->form->getInput('parentid'); ?>
               </div>
               
               

        </fieldset>
        <div>
                <input type="hidden" name="jform[id]" value="<?php echo (int)$this->item->id; ?>" />
                <input type="hidden" name="task" value="categoryform.edit" />
                <?php echo JHtml::_('form.token'); ?>
        </div>
</form>
