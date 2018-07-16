<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
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
<p>
<a href="http://joomlaboat.com/contact-us" target="_blank" style="margin-left:20px;">Help (Contact Tech-Support)</a>
</p>
<?php
//----------------------- Check PHP requirememts
		if( !ini_get('allow_url_fopen') or !ini_get('allow_url_include') ) {
			echo '<div style="margin-bottom:10px;padding:3px;border: 1px solid red; "><p style="color:red;font-weight:bold;">PHP configuration: "allow_url_fopen" and "allow_url_include" should be "On" </p>';
			echo '
			<p>In order to let Youtube Gallery to request list of videos and/or information about videos from Youtube servers "allow_url_fopen" and "allow_url_include" should be enabled.
			<br/>
			To do this modify your main php.ini file or create a new  file named "php.ini" with lines below:<br/><br/>
<span style="color:green;">allow_url_fopen=on<br/>
allow_url_include=on</span><br/>
<br/>
Upload it to your website root and administrator folders. Or contact your hosting provider to enable this functionality.
<br/>
<i>These settings maybe disabled for security reasons. If you are concern about it, enabled them temporary to get videos and disable again.</i>
			</p></div>';
		}
		require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
		$api_key = YouTubeGalleryMisc::getSettingValue('youtube_api_key');
		if($api_key=="")
		{
			echo '<div style="margin-bottom:10px;padding:3px;border: 1px solid red; "><p style="color:red;font-weight:bold;">Since April 2016 Youtube requires that you have you own API Key.</p>';
			echo '
			<p>Please click <a href="http://www.joomlaboat.com/youtube-gallery/f-a-q/why-i-see-allow-url-fopen-message?cbprofile=2" target="_blank">here</a> for step by step guide.</p>
			<p>When you get your own Youtube API Key add it to Youtube Gallery <a href="/administrator/index.php?option=com_youtubegallery&view=settings&layout=edit">Settings</a> page<br/>
			<i><span style="color:green;">Refresh video list and clear Joomla cache after that.</span></i>
			</p>
			</div>';	
		}

		if(!_is_curl_installed())
		{
				echo '<div style="margin-bottom:10px;padding:3px;border: 1px solid red; ">
				<p style="font-weight:bold;">cURL is NOT <span style="color:red">installed</span> on this server.</p>
				</div>';
				
		}
		
		
		if(!class_exists("DOMDocument"))
		{
				echo '<div style="margin-bottom:10px;padding:3px;border: 1px solid red; ">
				<p style="font-weight:bold;">DOM is NOT <span style="color:red">installed</span> on this server.</p>
				</div>';
				
		}
//------------------ end PHP check		
?>

<form action="<?php echo JRoute::_('index.php?option=com_youtubegallery'); ?>" method="post" name="adminForm" id="adminForm">

<?php 
	//for joomla 3.0
	$s=JFactory::getApplication()->input->getVar( 'search');
?>
<div id="j-main-container" class="span10">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="search" class="element-invisible">Search title.</label>
				<input type="text" name="search" placeholder="Search title." id="search" value="<?php echo $s; ?>" title="Search title." />
			</div>
			<div class="btn-group pull-left hidden-phone">
				<button class="btn tip hasTooltip" type="submit" title="Search"><i class="icon-search"></i></button>
				<button class="btn tip hasTooltip" type="button" onclick="document.id('search').value='';this.form.submit();" title="Clear"><i class="icon-remove"></i></button>
			</div>
			
			<div class="filter-select hidden-phone" style="float:right;"><?php echo $this->lists['categories'].'&nbsp;'; ?></div>
</div></div>

		<div class="clearfix"> </div>
		

        <table class="table table-striped">
                <thead><?php echo $this->loadTemplate('head');?></thead>
                <tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
                <tbody><?php echo $this->loadTemplate('body');?></tbody>
        </table>


		<input type="hidden" id="task" name="task" value="" />
                <input type="hidden" id="boxchecked" name="boxchecked" value="0" />
                <?php echo JHtml::_('form.token'); ?>
</form>

