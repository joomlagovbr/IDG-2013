<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

?>
<h4>Advanced</h4>
	<table style="border:none;">
		<tbody>
                                        

										<tr><td><?php echo $this->form->getLabel('mediafolder'); ?></td><td>:</td><td>image/<?php echo $this->form->getInput('mediafolder'); ?></td></tr>
										<tr><td style="vertical-align: top;"><?php echo $this->form->getLabel('headscript'); ?></td><td>:</td><td>
												<div style="float:left;"><?php echo $this->form->getInput('headscript'); ?></div>
												<div style="float:left;">
												<p style="font-weight: bold;">Tags you can use with it:</p>
												<table>
														<tbody>
																<tr><td valign="top">[width]</td><td>:</td><td>Video Area Width</td></tr>
																<tr><td valign="top">[height]</td><td>:</td><td>Video Area Height</td></tr>
                                                                <tr><td valign="top">[instanceid]</td><td>:</td><td>Instance ID</td></tr>
																<tr><td valign="top">[mediafolder]</td><td>:</td><td>Media Folder</td></tr>
	
									                    </tbody>
                                                </table>
												<br/>
												<p style="font-weight: bold;">Tags to get values directly from Theme Settings:</p>
												<p>[bgcolor], [cols], [cssstyle], [navbarstyle], [thumbnailstyle], [linestyle], [activevideotitlestyle],
																		[listnamestyle], [color1], [color2], [descr_style], [rel], [hrefaddon], [mediafolder]</p>
												</div>
										</td></tr>
										<tr><td><?php echo $this->form->getLabel('themedescription'); ?></td><td>:</td><td><?php echo $this->form->getInput('themedescription'); ?></td></tr>
										

		</tbody>
	</table>

