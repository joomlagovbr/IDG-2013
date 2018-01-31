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


require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');
?>

<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_youtubegallery'); ?>" method="post" class="form-inline">



				<h2 style="text-align:left;">Settings</h2>
				<h4>Vimeo Specific</h4>
				<p>In order to allow Youtube Gallery to fetch metadata (title,description etc) of the Vimeo Video you have to register your own instance of Youtube Gallery.</p>
				<p><a href="https://developer.vimeo.com/apps/new" target="_blank">https://developer.vimeo.com/apps/new</a></p>
				<p>Type "YoutubeGallery Your Site/Name" into "App Name" field during registration.</p>
				
				
				<hr/>
				<p>When you finish you get Client ID and Secret information. Paste it into fields below:<br/></p>
				<p>
				Client ID (Also known as Consumer Key or API Key):<br/>
				<input name="vimeo_api_client_id" style="width:400px;" value="<?php echo YouTubeGalleryMisc::getSettingValue('vimeo_api_client_id'); ?>" />
				</p>
				
				<p>
				Client Secret (Also known as Consumer Secret or API Secret):<br/>
				<input name="vimeo_api_client_secret" style="width:400px;" value="<?php echo YouTubeGalleryMisc::getSettingValue('vimeo_api_client_secret'); ?>" />
				</p>
				
				<p>
				Youtube Public API:<br/>
				<input name="youtube_public_api" style="width:400px;" value="<?php echo YouTubeGalleryMisc::getSettingValue('youtube_public_api'); ?>" />
				</p>
				
				
                <input type="hidden" name="task" value="" />

				
                <?php echo JHtml::_('form.token'); ?>

</form>