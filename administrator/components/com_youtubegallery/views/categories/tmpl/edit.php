<?php
/**
 * YoutubeGallery
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/


// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
?>
<p style="text-align:left;">Upgrade to <a href="http://joomlaboat.com/youtube-gallery#pro-version" target="_blank">PRO version</a> to get more features</p>
<form action="<?php echo JRoute::_('index.php?option=com_youtubegallery&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="youtubegallery-form" class="form-validate">
        <fieldset class="adminform">
               <legend><?php echo JText::_( 'COM_YOUTUBEGALLERY_CATEGORY_FORM_DETAILS' ); ?></legend>
               
                <ul class="adminformlist">
<?php foreach($this->form->getFieldset() as $field): ?>
                        <li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
                </ul>
        </fieldset>
        <div>
                <input type="hidden" name="task" value="categoryform.edit" />
                <?php echo JHtml::_('form.token'); ?>
        </div>
</form>
