<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 3.5.9
 * @author DesignCompass corp< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


?>
<style>
#jform_customnavlayout
{
		width:100%;
}
</style>

<h4>Navigation Bar - Custom Layout</h4>



<?php echo $this->form->getInput('customnavlayout'); ?>



							<b>Layout tags:</b><br/>
                                                        
					                <table>
						                <tbody>
									<tr><td valign="top">[image]</td><td>:</td><td>Returns Thumnail Image as a Tag</td></tr>
									<tr><td valign="top">[image:<i>number</i>]</td><td>:</td><td>Returns Specific Thumnail Image (1 of 6) as a Tag</td></tr>
									<tr><td valign="top">[imageurl]</td><td>:</td><td>Only URL to thumbnail image</td></tr>
                                    <tr><td valign="top">[title]</td><td>:</td><td>Video Title</td></tr>
									<tr><td valign="top">[description]</td><td>:</td><td>Video Description</td></tr>
									<tr><td valign="top">[description:<i>words</i>]</td><td>:</td><td>Show First <i>number</i> of <i>words</i> of Video Description</td></tr>
									<tr><td valign="top">[description:0,<i>chars</i>]</td><td>:</td><td>Show First <i>number</i> of <i>chars</i> of Video Description (number of words ignored)</td></tr>
									
									<tr><td valign="top">[link]</td><td>:</td><td>Link to the Video (on this website)</td></tr>
									<tr><td valign="top">[link:<i>full</i>]</td><td>:</td><td>Full Link to the Video (to share on FB for example)</td></tr>
									<tr><td valign="top">[a] and [/a]</td><td>:</td><td>Complete anchor tag with the link and title</td></tr>
									<tr><td valign="top">[publisheddate]</td><td>:</td><td>Publish date</td></tr>
									<tr><td valign="top">[publisheddate:<i>date format</i>]</td><td>:</td><td>Publish date with custom <i>date format</i>. Example: [publisheddate:F j, Y]. <a href="http://php.net/manual/en/function.date.php" target="_blank">More here.</a></td></tr>
									<tr><td valign="top">[duration]</td><td>:</td><td>Duration in Seconds</td></tr>
									<tr><td valign="top">[duration:<i>time format</i>]</td><td>:</td><td>Duration with custom <i>time format</i>. Example: [duration:H:i:s]. <a href="http://php.net/manual/en/function.date.php" target="_blank">More here.</a></td></tr>
									<tr><td valign="top">[viewcount]</td><td>:</td><td>View Count</td></tr>
									<tr><td valign="top">[viewcount:<i>Thousands Separator</i>]</td><td>:</td><td>View Count with <i>Thousands Separator</i>. Example: [viewcount:,] number example: 2,345</td></tr>
									
									
									<tr><td valign="top">[favcount]</td><td>:</td><td>Favorites Count</td></tr>
									<tr><td valign="top">[rating_average]</td><td>:</td><td>Average Rating</td></tr>
									<tr><td valign="top">[rating_max]</td><td>:</td><td>Max. Rating</td></tr>
									<tr><td valign="top">[rating_min]</td><td>:</td><td>Min. Rating</td></tr>
									<tr><td valign="top">[rating_numRaters]</td><td>:</td><td>Number of Raters</td></tr>
									
									<tr><td valign="top">[likes]</td><td>:</td><td>Likes</td></tr>
									<tr><td valign="top">[likes:<i>Thousands Separator</i>]</td><td>:</td><td>Likes with <i>Thousands Separator</i>. Example: [likes:,] number example: 2,345</td></tr>
									
									<tr><td valign="top">[dislikes]</td><td>:</td><td>Dislikes</td></tr>
									<tr><td valign="top">[dislikes:<i>Thousands Separator</i>]</td><td>:</td><td>Dislikes with <i>Thousands Separator</i>. Example: [dislikes:,] number example: 1,234</td></tr>
									
									<tr><td valign="top">[commentcount]</td><td>:</td><td>Number of comments</td></tr>
									<tr><td valign="top">[commentcount:<i>Thousands Separator</i>]</td><td>:</td><td>Number of Comments with <i>Thousands Separator</i>. Example: [commentcount:,] number example: 1,056</td></tr>
									
									<tr><td valign="top">[keywords]</td><td>:</td><td>Keywords</td></tr>
									<tr><td valign="top">[videosource]</td><td>:</td><td>Video Source</td></tr>
									<tr><td valign="top">[videoid]</td><td>:</td><td>Video ID</td></tr>
									
									<tr><td valign="top">[channel:<i>parameter</i>,<i>thousands separator (optional)</i>]</td><td>:</td><td>Returns Channel details.
									<br/>Parameters: <i>username,title,subscribers,subscribed,location,commentcount,viewcount,videocount,description</i>
									 <br/>Note: to enable it add "moredetails=true" into "Special Parameter" of Youtube Channel link.
									</td></tr>

									<tr><td valign="top">[social:<i>button</i>,<i>parameter</i>,<i>parameter2</i>]</td><td>:</td><td>Social Button:
									
									<ul>
										<li>
												<i>facebook_share</i>
												<ul>
														<li>parameter (optional): Button Label text</li>
														<li>parameter2 (optional): width of the button</li>
												</ul>
										</li>
										
										<li>
												<i>facebook_like</i>
												<ul>
														<li>parameter (optional): Language</li>
														<li>parameter2 (optional): width of the area</li>
												</ul>
										</li>
										
										<li>
												<i>twitter</i>
												<ul>
														<li>parameter (required): Twitter Account</li>
														<li>parameter2 (optional): width of the area</li>
												</ul>
										</li>
									</ul>

									</td></tr>
									
									<tr><td valign="top" colspan="3">[if:TAGNAME] and [endif:TAGNAME] - Conditional tags</td></tr>
									<tr><td valign="top" colspan="3">[ifnot:TAGNAME] and [endifnot:TAGNAME] - Conditional tags</td></tr>
									<tr><td valign="top" colspan="3">[if:isactive] and [endif:isactive] - Show this part if video is active</td></tr>
									<tr><td valign="top" colspan="3">[ifnot:isactive] and [endifnot:isactive] - Show this part if video is NOT active</td></tr>
									
									
                                                                </tbody>
                                                        </table>
<br/>
							<b><label id="jform_customnavlayout-lbl" for="jform_customnavlayout" class="hasTip" title="Custom Layout::Example">Example:</label></b><br/>
                                                        
                                                        
                                                        <textarea cols="15" rows="12" readonly="readonly" style="color: #00ff00;background: black;width:500px;">

[image]<br/>
[title]<br/>

[if:viewcount]<!-- if number of views more than 0 (not empty/nothing) -->
		Views: [viewcount]
[endif:viewcount]
														
</textarea>
                                                
                                  