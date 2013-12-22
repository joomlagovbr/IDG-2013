<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 3.5.9
 * @author DesignCompass corp< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');


?>
<p style="text-align:left;">Upgrade to <a href="http://joomlaboat.com/youtube-gallery#pro-version" target="_blank">PRO version</a> to get more features

<span style="margin-left:20px;">|</span>
				<a href="http://joomlaboat.com/contact-us" target="_blank" style="margin-left:20px;">Help (Contact Tech-Support)</a>

</p>
<form action="<?php echo JRoute::_('index.php?option=com_youtubegallery&view=videolist'); ?>" method="post" name="adminForm" id="adminForm">
<?php
	$s=JRequest::getVar( 'search');
?>
	<h3>Videos of this page: <?php echo count($this->items); ?></h3>
	<?php

		if(count($this->items)==0)
			echo '<p><b>No videos found. Try to "Refresh" the gallery, or add videos. </b><br/>To refresh go to previous page, check the "checkbox" next to this gallery and click "Refresh" button in toolbar.</p>';
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
			
</div>
		</div>




        <table class="table table-striped">
                <thead><?php echo $this->loadTemplate('head');?></thead>
                <tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
                <tbody><?php echo $this->loadTemplate('body');?></tbody>
        </table>
        <div>
                <input type="hidden" id="task" name="task" value="" />
				
				
<?php 				/* <input type="hidden" name="view" value="videolist" /> */ ?>
				<input type="hidden" name="listid" value="<?php echo JRequest::getInt( 'listid'); ?>" />

                <?php echo JHtml::_('form.token'); ?>
        </div>
		
	<p>
		If status of the video is "-", it means that it's not checked yet.<br/>Go to front-end and find this video in your gallery, as soon as you see it's thumbnail, it's data will be written here.
	</p>
	
</form>


