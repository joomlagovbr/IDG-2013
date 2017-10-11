<?php
/**
 * YoutubeGallery Joomla! Native Component
 * @version 4.4.0
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');

jimport('joomla.version');
$version = new JVersion();
$JoomlaVersionRelease=$version->RELEASE;

require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');
?>
<p style="text-align:left;">Upgrade to <a href="http://joomlaboat.com/youtube-gallery#pro-version" target="_blank">PRO version</a> to get more features
<span style="margin-left:20px;">|</span>
				<a href="http://joomlaboat.com/contact-us" target="_blank" style="margin-left:20px;">Help (Contact Tech-Support)</a>

</p>
<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_youtubegallery'); ?>" method="post" class="form-inline">
				<h2 style="text-align:left;">Settings</h2>
				<h4>General</h4>
				<br/>
				
				<?php
								$allowsef=YouTubeGalleryMisc::getSettingValue('allowsef');
								if($allowsef!=1)
												$allowsef=0;
								
				?>
				<table style="border:none;">
                                <tbody>
                                        <tr><td><?php echo JText::_( 'Allow SEF Links' ); ?></td><td>:</td>
								
						<td>
								<?php if($JoomlaVersionRelease>=3.0): ?>
								
								<fieldset id="jform_attribs_link_titles" class="radio btn-group">
								<fieldset id="jform_allowsef" class="radio inputbox">
								<input type="radio" id="jform_allowsef1" name="jform[allowsef]" value="1"<?php echo ($allowsef=='1' ? 'checked="checked"' : ''); ?> />
								<label for="jform_allowsef1">Yes</label>
								<input type="radio" id="jform_allowsef0" name="jform[allowsef]" value="0"<?php echo ($allowsef=='0' ? 'checked="checked"' : ''); ?> />
								<label for="jform_allowsef0">No</label>
								</fieldset>
								</fieldset>
								
								<?php else: ?>
								
								<input type="radio" name="allowsef"  value="1" <?php echo ($allowsef==1 ? 'checked="checked"' : '' ) ?> />
								<?php echo JText::_( 'Yes' ); ?>
								<input type="radio" name="allowsef"  value="0" <?php echo ($allowsef==0 ? 'checked="checked"' : '' ) ?> />
								<?php echo JText::_( 'No' ); ?>
						
								<?php endif; ?>		
						
						</td>
								
                                        
					</tr>
				</tbody>
				</table>
				
				
				
				<hr/>
				<p><br/></p>
				<p>How to get Video information:</p>
				<?php
								$getinfomethod=YouTubeGalleryMisc::getSettingValue('getinfomethod');
								
				?>
				<table style="border:none;">
                                <tbody>
                                        <tr><td><?php echo JText::_( 'Use' ); ?></td><td>:</td>
								
						<td>
								<?php if($JoomlaVersionRelease>=3.0): ?>
								
								<fieldset id="jform_attribs_link_titles" class="radio btn-group">
								<fieldset id="jform_getinfomethod" class="radio inputbox">
								
								<input type="radio" id="jform_getinfomethodjsmanual" name="jform[getinfomethod]" value="jsmanual"<?php echo ($getinfomethod=='jsmanual' ? 'checked="checked"' : ''); ?> />
								<label for="jform_getinfomethodjsmanual">Javascript (Back-end/Manual)</label>
								
								<input type="radio" id="jform_getinfomethodphp" name="jform[getinfomethod]" value="php"<?php echo (($getinfomethod=='php' or $getinfomethod=='') ? 'checked="checked"' : ''); ?> />
								<label for="jform_getinfomethodphp">cURL/file_get_contents</label>
								</fieldset>
								</fieldset>
								
								<?php else: ?>
								
								<input type="radio" name="getinfomethod"  value="jsmanual" <?php echo ($getinfomethod=='jsmanual' ? 'checked="checked"' : '' ) ?> />
								<?php echo JText::_( 'Javascript (Back-end/Manual)' ); ?>
								
								<input type="radio" name="getinfomethod"  value="php" <?php echo (($getinfomethod=='php' or $getinfomethod=='') ? 'checked="checked"' : '' ) ?> />
								<?php echo JText::_( 'cURL/Get File Content' ); ?>
						
								<?php endif; ?>

								</td>
								
                                        
					</tr>
				</tbody>
				</table>
				
				
				<hr/>
				<p><br/></p>
				<h4>Youtube API v3</h4>
				<p>In order to allow Youtube Gallery to fetch metadata (video title, view count etc.) of the Youtube video you have to register your own instance of Youtube Gallery.</p>
				<p>Visit this page: <a href="https://console.developers.google.com/project" target="_blank">https://console.developers.google.com/project</a> click on <span style="background-color:#ff7777;padding:3px;">Create Project</span> button.</p>
				<p style="padding:2px;">
				Project name: <b>"YoutubeGallery for YOUR SITE"</b> (or whatever you like)<br/>
				Project ID: <b>"youtubegaller-YOUR SITE"</b> (or whatever you like)<br/>
				<br/>
				Then click on <span style="background-color:#77ff77;padding:3px;">Enable API</span> button.<br/>
				<br/>
				You will find "YouTube Data API v3" on the buttom of the page, there will be <span style="background-color:#aaaaaa;padding:3px;">OFF</span> button next to it. Click on that button to enable the API.<br/>
				After that click on "Credentials" link in the left column.<br/>
				Then click on <span style="background-color:#ff7777;padding:3px;">Create New Key</span> button and <span style="background-color:#bbbbbb;padding:3px;">Server Key</span>.</p>
				<p>Put your server IP (<span style="font-weight:bold;font-size:16px;"><?php echo $_SERVER['SERVER_ADDR']; ?></span>) to "Accept requests from these server IP addresses" box.</p>
				<p>And click <span style="background-color:#7777ff;padding:3px;">Create</span> button. There you will find the <b>API Key</b>.
				</p>
				
				
				<?php /* 
				<!--<p>When you finish click on new project in the list and "APIs and auth" in left column. Change "YouTube Data API v3" status to "On":<br/></p>-->
				<p>
				Client ID:<br/>
				<input name="youtube_api_client_id" style="width:400px;" value="<?php echo YouTubeGalleryMisc::getSettingValue('youtube_api_client_id'); ?>" />
				</p>
				
				
				<p>
				Client Secret:<br/>
				<input name="youtube_api_client_secret" style="width:400px;" value="<?php echo YouTubeGalleryMisc::getSettingValue('youtube_api_client_secret'); ?>" />
				</p>
				*/
				?>
				
				<p>
				API Key:<br/>
				<input name="youtube_api_key" style="width:400px;" value="<?php echo YouTubeGalleryMisc::getSettingValue('youtube_api_key'); ?>" />
				</p>
				
				<hr/>
				<p><br/></p>
				
				
				<h4>SoundCloud API</h4>
				<p>In order to allow Youtube Gallery to fetch metadata (title,description etc) of the SoundCloud track you have to register your own instance of Youtube Gallery.</p>
				<p><a href="http://soundcloud.com/you/apps/new" target="_blank">http://soundcloud.com/you/apps/new</a></p>
				<p>Type "YoutubeGallery Your Site/Name" into "Name of your app" field during registration.</p>
				
				
				<hr/>
				<p>When you finish you will get Client ID and Client Secret. Paste it into fields below:<br/></p>
				<p>
				Client ID:<br/>
				<input name="soundcloud_api_client_id" style="width:400px;" value="<?php echo YouTubeGalleryMisc::getSettingValue('soundcloud_api_client_id'); ?>" />
				</p>
				
				<p>
				Client Secret:<br/>
				<input name="soundcloud_api_client_secret" style="width:400px;" value="<?php echo YouTubeGalleryMisc::getSettingValue('soundcloud_api_client_secret'); ?>" />
				</p>
				
				
				<hr/>
				<p><br/></p>
				
				
				
				<h4>Vimeo API</h4>
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
				
				<hr/>
				<p><br/></p>
				
				
				<?php
								$errorreporting=YouTubeGalleryMisc::getSettingValue('errorreporting');
								if($errorreporting!=1)
												$errorreporting=0;
								
				?>
				<?php if($JoomlaVersionRelease>=3.0): ?>
				<table style="border:none;">
                                <tbody>
                                        <tr><td><?php echo JText::_( 'Error Reporting' ); ?></td><td>:</td>
								
						<td>
								
								
								<fieldset id="jform_attribs_link_titles" class="radio btn-group">
								<fieldset id="jform_errorreporting" class="radio inputbox">
								<input type="radio" id="jform_errorreporting1" name="jform[errorreporting]" value="1"<?php echo ($errorreporting=='1' ? 'checked="checked"' : ''); ?> />
								<label for="jform_errorreporting1">Yes</label>
								<input type="radio" id="jform_errorreporting0" name="jform[errorreporting]" value="0"<?php echo ($errorreporting=='0' ? 'checked="checked"' : ''); ?> />
								<label for="jform_errorreporting0">No</label>
								</fieldset>
								</fieldset>
								
								</td>
								
                                        
					</tr>
				</tbody>
				</table>
				<?php endif; ?>
				
                <input type="hidden" name="task" value="" />

				
                <?php echo JHtml::_('form.token'); ?>

</form>