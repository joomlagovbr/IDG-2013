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
<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_youtubegallery'); ?>" method="post" class="form-inline" enctype="multipart/form-data">

				<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
				
				<div style="width:600px;margin:0 auto;">
								
				<div style="width: 290px;margin:50px auto;font-size:18px;position: relative;">
					<?php echo JText::_('COM_YOUTUBEGALLERY_THEME_UPLOADFILE'); ?>: <input name="themefile" id="themefile" type="file" style="font-size:18px;" />
				</div>

				
				<h2 style="text-align:center;">Or insert Theme code here<span style="font-size:12px;"><br/>(You can find it in "theme.txt" file.)</span></h2>
				<textarea filter="raw" cols="40" rows="5" name="themecode" style="width:600px;"></textarea>
				
								<div style="width: 105px;margin:20px auto;font-size:18px;position: relative;">
												<input type="submit" value="<?php echo JText::_('COM_YOUTUBEGALLERY_THEME_UPLOADFILE_BUTTON'); ?>" />
								</div>
				</div>
				
				
				
                <input type="hidden" name="task" value="themeimport.upload" />
				
				
				
				
                <?php echo JHtml::_('form.token'); ?>

</form>