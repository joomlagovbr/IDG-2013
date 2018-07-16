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
 
JHtml::_('behavior.tooltip');

jimport('joomla.version');
$version = new JVersion();
$JoomlaVersionRelease=$version->RELEASE;

$document = JFactory::getDocument();
$document->addCustomTag('<script src="http://code.jquery.com/jquery-1.10.2.js"></script>');

?>

				<a href="http://joomlaboat.com/contact-us" target="_blank" style="margin-left:20px;">Help (Contact Tech-Support)</a>

</p>


<script>
	function getAnswerValue(p,s)
	{
		var ps="*"+p+"_start*="
		var pe="*"+p+"_end*"
		
		var 	i1=s.indexOf(ps);
		if(i1==-1)
			return "";
		
		var 	i2=s.indexOf(pe,i1+ps.length);
		if(i2==-1)
			return "";
		
		return s.substring(i1+ps.length,i2);
		
	}
	
	function YGgetURlContent(theUrl,videoid,itemid)
	{
		var xmlHttp = null;

		xmlHttp = new XMLHttpRequest();
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				YGpostURlContent(videoid,xmlHttp.responseText,itemid);
			}
		}
		
		<?php
		
		if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on")
		{
			echo '
		theUrl=theUrl.replace("http://","https://");
		';
		}
			
		?>
		xmlHttp.open( "GET", theUrl, true );
		xmlHttp.send( null );

	}
	
	
		
	
	function YGpostURlContent(videoid,ygvdata,itemid)
	{
		var url = "index.php";
		$.post( url, { option: "com_youtubegallery", view: "updatedata", tmpl: "component", videoid: videoid, ygvdata: ygvdata })
		.done(function( data ) {
		  UpdateFormData(data,itemid)
		});
		
  

	}
	
	function UpdateVideoData(link,videoid,itemid)
	{
		var progressImage='<img src="../components/com_youtubegallery/images/progress_circle.gif" style="border:none !important;" />';
		document.getElementById("video_"+itemid+"_status").innerHTML=progressImage;
		YGgetURlContent(link,videoid,itemid);
	
	}
	
	function UpdateFormData(answer,itemid)
	{
		var video_title=getAnswerValue("title",answer);
		var video_description=getAnswerValue("description",answer);
		var video_lastupdate=getAnswerValue("lastupdate",answer);
			
		document.getElementById("video_"+itemid+"_title").innerHTML=video_title;
		document.getElementById("video_"+itemid+"_description").innerHTML=video_description;
		document.getElementById("video_"+itemid+"_lastupdate").innerHTML=video_lastupdate;
			
		if(video_lastupdate!="")
			document.getElementById("video_"+itemid+"_status").innerHTML='<span style="color:green;">Ok</span>';
		else
			document.getElementById("video_"+itemid+"_status").innerHTML='<span style="color:red;font-weight:bold;">No data</span>';
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_youtubegallery&view=videolist'); ?>" method="post" name="adminForm" id="adminForm">
<?php
	$s=JFactory::getApplication()->input->getVar( 'search');
?>
	<h3>Items on this page: <?php echo count($this->items); ?></h3>
	<?php

		if(count($this->items)==0)
			echo '<p><b>No videos found. Try to "Refresh" the gallery, or add videos. </b><br/>To refresh go to previous page, check the "checkbox" next to this gallery and click "Refresh" button in toolbar.</p>';
	?>
	


<?php if($JoomlaVersionRelease>=3.0): ?>

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

<?php else: ?>
	<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_( 'COM_YOUTUBEGALLERY_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
				<a href="http://joomlaboat.com/contact-us" target="_blank" style="font-weight: bold;margin-left:20px;">Help (Contact Tech-Support)</a>
			</td>

		</tr>
	</table>

        <table class="adminlist">
                <thead><?php echo $this->loadTemplate('head');?></thead>
                <tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
                <tbody><?php echo $this->loadTemplate('body');?></tbody>
        </table>
<?php endif; ?>


                <input type="hidden" id="task" name="task" value="" />
		<input type="hidden" name="listid" value="<?php echo JFactory::getApplication()->input->getInt( 'listid'); ?>" />
                <?php echo JHtml::_('form.token'); ?>

		
	<p>
		If status of the video is "-", it means that it's not checked yet.<br/>Go to front-end and find this video in your gallery, as soon as you see it's thumbnail, it's data will be written here.
	</p>
	
</form>


