<?php

?>

<b>Layout tags:</b>
                                                        
                                                         <table>
                                                                <tbody>
										<tr><td valign="top">[videolist:<i>option</i>]</td><td>:</td><td>Video List info. Options: <i>title</i>, <i>description</i>, <i>author</i>, <i>authorurl</i>, <i>image</i>, <i>note</i>, <i>playlist</i>, <i>watchgroup (joomla 2.5,3.x)</i></td></tr>
										<tr><td valign="top">[videotitle]</td><td>:</td><td>Show Active Video Title</td></tr>
										<tr><td valign="top">[videotitle:<i>words</i>]</td><td>:</td><td>Show First <i>number</i> of <i>words</i> of Active Video Title</td></tr>
										<tr><td valign="top">[videotitle:0,<i>chars</i>]</td><td>:</td><td>Show First <i>number</i> of <i>chars</i> of Active Video Title</td></tr>
										<tr><td valign="top">[videodescription]</td><td>:</td><td>Show Active Video Description</td></tr>
										<tr><td valign="top">[videodescription:<i>words</i>]</td><td>:</td><td>Show First <i>number</i> of <i>words</i> of Active Video Description</td></tr>
										<tr><td valign="top">[videodescription:0,<i>chars</i>]</td><td>:</td><td>Show First <i>number</i> of <i>chars</i> of Active Video Description (number of words ignored)</td></tr>
										<tr><td valign="top">[videoplayer]</td><td>:</td><td>Player</td></tr>
										<tr><td valign="top">[videoplayer:<i>width</i>,<i>height</i>]</td><td>:</td><td>Player with custom <i>width</i> and/or <i>height</i></td></tr>
										<tr><td valign="top">[navigationbar]</td><td>:</td><td>Navigation Bar (a table of thumbnails)</td></tr>
										<tr><td valign="top">[navigationbar:<i>columns</i>,<i>bar width</i>]</td><td>:</td><td>
																		
												Navigation Bar (a table of thumbnails)
												with parameters to overwrite <i>number of columns</i> and bar <i>width</i>
												<br/>

										</td></tr>
																		<tr><td valign="top">[thumbnails]</td><td>:</td><td>List of thumbnails (no formating except Navigation Bar Custom Layout)</td></tr>
                                                                        <tr><td valign="top">[count]</td><td>:</td><td>Number of videos (thumbnails)</td></tr>
									<tr><td valign="top">[count:all]</td><td>:</td><td>Total number of videos</td></tr>
                                                                        <tr><td valign="top">[pagination]</td><td>:</td><td>Pagination</td></tr>
																		<tr><td valign="top">[width]</td><td>:</td><td>Video Area Width</td></tr>
																		<tr><td valign="top">[height]</td><td>:</td><td>Video Area Height</td></tr>
																		<tr><td valign="top">[mediafolder]</td><td>:</td><td>Media Folder</td></tr>
		<tr><td valign="top">[instanceid]</td><td>:</td><td>Instance ID</td></tr>
                <tr><td valign="top">[if:TAGNAME] and [endif:TAGNAME]</td><td>:</td><td>Conditional tags</td></tr>
		<tr><td valign="top">[notif:TAGNAME] and [endif:TAGNAME]</td><td>:</td><td>Conditional tags</td></tr>
		<tr><td valign="top" colspan="3" ><b>Tags to get values directly from Theme Settings:</b><br/>
		[bgcolor], [cols], [cssstyle], [navbarstyle], [thumbnailstyle], [linestyle], [activevideotitlestyle],
		[listnamestyle], [color1], [color2], [descr_style], [rel], [hrefaddon], [mediafolder], [videoid]
		</td></tr>
		<tr><td valign="top">[video:<i>Parameter</i>]</td><td>:</td><td>This allows to read any value from thumbnail. See Navigation Bar Custom Layout.</td></tr>
										
										<tr><td valign="top">[social:<i>button</i>,<i>parameter</i>,<i>parameter2</i>,<i>parameter3</i>]</td><td>:</td><td>Social Button:
									
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
												<i>facebook_comments</i>
												<ul>
														<li>parameter (optional): Number of Posts</li>
														<li>parameter2 (optional): Width</li>
														<li>parameter3 (optional): Color Theme (light by default)</li>
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
                                                                </tbody>
                                                        </table>
                                                        
                                                        <br />
                                                        <b>Example:</b><br/>
                                                        
                                                        
                                                        <textarea cols="30" rows="12" readonly="readonly" style="width:500px;color: #00ff00;background: black;" class="customlayoutexample"><h3>[listname]</h3>
[if:videodescription]<!-- If there is a description for video -->
		<h4>[videodescription:50]</h4><!-- Show first 50 words of the description -->
[endif:videodescription]

<!-- Video Player -->
[videoplayer]

[if:videotitle]
		<h3>[videotitle]</h3>
[endif:videotitle]

[if:count]<!-- if number of videos more than 0 -->
		<hr style="border-color:#E7E7E9;border-style:solid;border-width:1px;" />
		[navigationbar:3,500]<!-- Show navigation bar with 3 columns and width should be 500px. -->
[endif:count]
														</textarea>