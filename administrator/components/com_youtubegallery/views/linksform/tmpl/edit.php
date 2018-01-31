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

<script language="javascript">
        function SwithTabs(nameprefix, count, activeindex)
        {
                for(i=0;i<count;i++)
                {
                        var obj=document.getElementById(nameprefix+i);
                        obj.style.display="none";
                }
                
                var obj=document.getElementById(nameprefix+activeindex);
                obj.style.display="block";
        }
</script>

<?php /*
	   <form action="<?php echo JRoute::_('index.php?option=com_youtubegallery&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="youtubegallery-form" class="form-validate">
	  */
?>
<style>
#jform_videolist{
		width:420px;
}
</style>
<p style="text-align:left;">Upgrade to <a href="http://joomlaboat.com/youtube-gallery#pro-version" target="_blank">PRO version</a> to get more features</p>
<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_youtubegallery'); ?>" method="post" class="form-inline">

        <fieldset class="adminform">
                <?php echo $this->form->getInput('id'); ?>
                
                
                <legend><?php echo JText::_( 'COM_YOUTUBEGALLERY_FORM_DETAILS' ); ?> (PRO Version)</legend>
                
				<table style="border:none;">
                        <tbody>
                                <tr><td style="width:200px;"><?php echo $this->form->getLabel('catid'); ?></td><td>:</td><td><?php echo $this->form->getInput('catid'); ?></td></tr>
								<tr><td style="width:200px;"><?php echo $this->form->getLabel('listname'); ?></td><td>:</td><td><?php echo $this->form->getInput('listname'); ?></td></tr>
                        </tbody>
                </table>
                
				
				
                <p><br/>
                </p>
                <?php //-------------------------- ?>
                       
                        <table style="border:none;">
                                <tbody>
                                        <tr>
						<td style="vertical-align: top;" valign="top"><?php echo $this->form->getLabel('videolist'); ?>
												<br/>
												<?php echo $this->form->getInput('videolist'); ?>
						</td>
						<td style="vertical-align: bottom;" valign="bottom">

						<div style="border: none;padding-left:10px;margin:0px;">
                       
						<p><b>Video Link Examples:</b></p>
						<br/>
						<ul>
						<li>
							<b>Youtube Video</b><br/>
							http://www.youtube.com/watch?v=VSGMqfGmjG0<br/>
							http://www.youtube.com/watch?v=baLkXC_qWJY&feature=related
						</li>
							
						<li>
							<b>Youtube Video Playlist, Channel, Standard Feeds, and Search Results</b><br/>
							http://www.youtube.com/playlist?list=PL5298F5DAD70298FC&feature=mh_lolz<br/>
							http://www.youtube.com/user/ivankomlev/favorites<br/>
							http://www.youtube.com/user/designcompasscorp<br/>
							http://www.youtube.com/results?search_query=wins+compilation+2012<br/>
							youtubestandard:<i>video_feed</i><br/>				
							<a href="http://joomlaboat.com/youtube-gallery/youtube-gallery-standard-feeds" target="_blank">More about Standard Video Feeds</a>
						</li>
						
						
						<li>
							<b>Vimeo Video</b><br/>
							http://vimeo.com/8761657
						</li>
						
						<li>
							<b>Vimeo User Videos</b><br/>
							http://vimeo.com/user12346578
						</li>
							
						<li>
							<b>Vimeo Channel</b><br/>
							http://vimeo.com/channels/123456
						</li>
						
						<li>
							<b>Vimeo Album</b><br/>
							https://vimeo.com/album/2585295
						</li>
						
						<li>
							<b>Break.com</b><br/>
							http://www.break.com/pranks/biker-falls-off-dock-wall-2392751
						</li>
						
						<li>
							<b>Daily Motion</b><br/>
							http://www.dailymotion.com/video/xrcy5b
						</li>
						
						<li>
							<b>Daily Motion Playlist</b><br/>
							http://www.dailymotion.com/playlist/x2jcwc_f669221398_reality/1
						</li>

						<li>
							<b>College Humor Video</b><br/>
							http://www.collegehumor.com/video/6446891/what-pi-sounds-like
						</li>
						
						<li>
							<b>Own3D.tv Video (live and uploaded)</b><br/>
							http://own3d.tv/l/153518<br/>
							http://own3d.tv/v/816530
						</li>
						
						<li>
							<b>Present.me</b><br/>
							http://www.present.me/view/82240-video-cv-blog-tutorials
						</li>
						
						<li>
							<b>Ustream.tv</b><br/>
							http://www.ustream.tv/recorded/35745825
						</li>
						
						<li>
							<b>Local .FLV files</b><br/>
							images/videos/test.flv
						</li>
						
						<li>
							<b>Video Lists</b><br/>
							videolist:3 <i>Will insert all videos from Video List ID:3 to current video list.</i><br/>
							videolist:all <i>Will insert all videos of all Video Lists.</i><br/>
							videolist:catid:4<i>Will insert all videos of Video Lists of Category with ID #4.</i><br/>
							videolist:category:music<i>Will insert all videos of Video Lists of Category "music"</i><br/>
						</li>
						
						<hr/>
						<p></p>
							<b>Also you may have your own title, description and thumbnail for each video.</b>
							To do this type comma then "<span style="color:green;">title</span>","<span style="color:green;">description</span>",
							"<span style="color:green;">imageurl</span>","<span style="color:green;">special_parameters</span>",
							"<span style="color:green;">startsecond</span>","<span style="color:green;">endsecond</span>"<br/>
							Should look like: <b>http://www.youtube.com/watch?v=baLkXC_qWJY</b>,"<b>Video Title</b>","<b>Video description</b>","<b>images/customthumbnail.jpg</b>"<br/>
							or<br/>
							<b>http://www.youtube.com/watch?v=baLkXC_qWJY</b>,"<b>Video Title</b>",,"<b>images/customthumbnail.jpg</b>"
						</p>
						<p>
							<b><span style="color:green;">Special parameters:</span></b> max-results=<i>NUMBER</i>,start-index=<i>NUMBER</i>,orderby=<i>FIELD_NAME</i><br/>
							<a href="http://joomlaboat.com/youtube-gallery/youtube-gallery-special-parameters" target="_blank">More about Special Parameters</a>
						</p>

<p><b><span style="color:green;">startsecond</span></b> (supported players: AS3, AS2, HTML5)<br/>
Values: A positive integer. This parameter causes the player to begin playing the video at the given number of seconds from the start of the video. Note that similar to the seekTo function, the player will look for the closest keyframe to the time you specify. This means sometimes the play head may seek to just before the requested time, usually no more than ~2 seconds.</p>



<p><b><span style="color:green;">endsecond</span></b> (supported players: AS3)<br/>
 Values: A positive integer. This parameter specifies the time, measured in seconds from the start of the video, when the player should stop playing the video. Note that the time is measured from the beginning of the video and not from either the value of the start player parameter or the startSeconds parameter, which is used in YouTube Player API functions for loading or queueing a video.</p>

						</div>

						</td>
					</tr>
                                </tbody>
                        </table>
                

						<br/>
                
                        <table style="border:none;">
                                <tbody>

                                        <tr><td style="width:200px;"><?php echo $this->form->getLabel('updateperiod'); ?></td><td>:</td><td><?php echo $this->form->getInput('updateperiod'); ?></td></tr>

                                </tbody>
                        </table>
                


        </fieldset>
        <div>
                <input type="hidden" name="jform[id]" value="<?php echo (int)$this->item->id; ?>" />				
                <input type="hidden" name="task" value="linksform.edit" />
                <?php echo JHtml::_('form.token'); ?>
        </div>
</form>