<?php
/**
 * YoutubeGallery Joomla! 2.5 Native Component
 * @version 4.4.5
 * @author Ivan Komlev <support@joomlaboat.com>
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

<form action="<?php echo JRoute::_('index.php?option=com_youtubegallery&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="youtubegallery-form" class="form-validate">
        <fieldset class="adminform">
                <?php echo $this->form->getInput('id'); ?>
                
                
                <legend><?php echo JText::_( 'COM_YOUTUBEGALLERY_FORM_DETAILS' ); ?> (PRO Version)</legend>
                
             
                
                
				
				<table style="border:none;">
                        <tbody>
										<tr><td><?php echo $this->form->getLabel('themename'); ?></td><td>:</td><td><?php echo $this->form->getInput('themename'); ?></td></tr>
										<tr><td colspan="3"></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('width'); ?></td><td>:</td><td><?php echo $this->form->getInput('width'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('height'); ?></td><td>:</td><td><?php echo $this->form->getInput('height'); ?></td></tr>

						</tbody>
                </table>
               <p><br /><br /></p>
                <?php $d=($this->form->getvalue('customlayout')!='' ? 'none' : 'block' ); ?>
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;display: <?php echo $d; ?>;" id="layouttab_0" class="layouttab_content">
                        <div style="margin-top:-50px;">
                                 <?php //Layout Wizard ?> <h4>Layout Wizard | <a href="javascript: SwithTabs('layouttab_',2,1)">Custom Layout</a></h4>
                        </div>
                        <table style="border:none;">
                                <tbody>
                                        <tr><td><?php echo $this->form->getLabel('showlistname'); ?></td><td>:</td><td><?php echo $this->form->getInput('showlistname'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('listnamestyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('listnamestyle'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('pagination'); ?></td><td>:</td><td><?php echo $this->form->getInput('pagination'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('showactivevideotitle'); ?></td><td>:</td><td><?php echo $this->form->getInput('showactivevideotitle'); ?></td></tr>
										<tr><td><?php echo $this->form->getLabel('activevideotitlestyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('activevideotitlestyle'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('playvideo'); ?></td><td>:</td><td><?php echo $this->form->getInput('playvideo'); ?></td></tr>

                                        <tr><td><?php echo $this->form->getLabel('descr_style'); ?></td><td>:</td><td><?php echo $this->form->getInput('descr_style'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('descr_position'); ?></td><td>:</td><td><?php echo $this->form->getInput('descr_position'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('description'); ?></td><td>:</td><td><?php echo $this->form->getInput('description'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('cssstyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('cssstyle'); ?></td></tr>
                                </tbody>
                        </table>
                </div>
                
                <?php $d=($this->form->getvalue('customlayout')!='' ? 'block' : 'none' ); ?>
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;display: <?php echo $d; ?>;" id="layouttab_1" class="layouttab_content">
                        <div style="margin-top:-50px;">
                                 <?php //Layout Wizard ?> <h4><a href="javascript: SwithTabs('layouttab_',2,0)">Layout Wizard</a> | Custom Layout</h4>
                        </div>
                        <table style="border:none;">
                                <tbody>
                                        <tr>
                                                <td valign="top"><b><?php echo $this->form->getLabel('customlayout'); ?><b/><br/>
														<?php echo $this->form->getInput('customlayout'); ?></td>
                                                <td valign="top">
                                                        
<?php
require_once('customlayoutdoc.php');
?>

                                                </td>                                
                                                      
                                        
                                        </tr>
                                                                                
                                </tbody>
                        </table>
                </div>
                
                

                <?php //Navigation Bar ?><h4>Navigation Bar</h4>
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;">
                        <table style="border:none;">
                                <tbody>
										
										
                                        <tr><td><?php echo $this->form->getLabel('navbarstyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('navbarstyle'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('cols'); ?></td><td>:</td><td><?php echo $this->form->getInput('cols'); ?></td></tr>
										
					<tr><td><?php echo $this->form->getLabel('linestyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('linestyle'); ?></td></tr><!-- depricated - can be done with navbarstyle -->
										
					<tr><td><?php echo $this->form->getLabel('orderby'); ?></td><td>:</td><td><?php echo $this->form->getInput('orderby'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('customlimit'); ?></td><td>:</td><td><?php echo $this->form->getInput('customlimit'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('bgcolor'); ?></td><td>:</td><td><?php echo $this->form->getInput('bgcolor'); ?></td></tr><!-- depricated - part of layout -->
					<tr><td><?php echo $this->form->getLabel('thumbnailstyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('thumbnailstyle'); ?></td></tr><!-- depricated - part of layout  -->
					<tr><td><?php echo $this->form->getLabel('showtitle'); ?></td><td>:</td><td><?php echo $this->form->getInput('showtitle'); ?></td></tr><!-- depricated - part of layout -->
                                </tbody>
                        </table>
						
			<br/><br/>
										
								
			<?php $d=($this->form->getvalue('customnavlayout')!='' ? 'none' : 'block' ); ?>
			<div style="border: 1px dotted #000000;padding:10px;margin:0px;display: <?php echo $d; ?>;" id="navlayouttab_0" class="layouttab_content">
				<div style="margin-top:-50px;">
					<?php //Nav Layout Wizard ?> <h4><a href="javascript: SwithTabs('navlayouttab_',2,1)">Navigation Bar Layout - Show</a></h4>
				</div>
			</div>
						
			<?php $d=($this->form->getvalue('customnavlayout')!='' ? 'block' : 'none' ); ?>
			<div style="border: 1px dotted #000000;padding:10px;margin:0px;display: <?php echo $d; ?>;" id="navlayouttab_1" class="layouttab_content">
				<div style="margin-top:-50px;">
					<?php //Nav Layout Wizard ?> <h4><a href="javascript: SwithTabs('navlayouttab_',2,0)">Navigation Bar Layout - Hide</a></h4>
				</div>
				<table style="border:none;">
				<tbody>
                                        <tr>
                                                <td valign="top">
							<b><?php echo $this->form->getLabel('customnavlayout'); ?></b><br/>
							<?php echo $this->form->getInput('customnavlayout'); ?>
						</td>
												
						<td valign="top" width="350">
<?php
require_once('navbarcustomlayoutdoc.php');
?>
                                                </td>                                
                                        </tr>
                                </tbody>
				</table>
			</div>
		</div>
		
			
			
                
                <?php //Player Settings ?><h4>Player Settings</h4>
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;">
                        <table style="border:none;">
                                <tbody>
                                        <tr><td><?php echo $this->form->getLabel('border'); ?></td><td>:</td><td><?php echo $this->form->getInput('border'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('color1'); ?></td><td>:</td><td><?php echo $this->form->getInput('color1'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('color2'); ?></td><td>:</td><td><?php echo $this->form->getInput('color2'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('autoplay'); ?></td><td>:</td><td><?php echo $this->form->getInput('autoplay'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('repeat'); ?></td><td>:</td><td><?php echo $this->form->getInput('repeat'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('allowplaylist'); ?></td><td>:</td><td><?php echo $this->form->getInput('allowplaylist'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('fullscreen'); ?></td><td>:</td><td><?php echo $this->form->getInput('fullscreen'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('related'); ?></td><td>:</td><td><?php echo $this->form->getInput('related'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('showinfo'); ?></td><td>:</td><td><?php echo $this->form->getInput('showinfo'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('controls'); ?></td><td>:</td><td><?php echo $this->form->getInput('controls'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('muteonplay'); ?></td><td>:</td><td><?php echo $this->form->getInput('muteonplay'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('volume'); ?></td><td>:</td><td><?php echo $this->form->getInput('volume'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('playertype'); ?></td><td>:</td><td><?php echo $this->form->getInput('playertype'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('youtubeparams'); ?></td><td>:</td><td><?php echo $this->form->getInput('youtubeparams'); ?></td></tr>
                                        
                                </tbody>
                        </table>
                </div>
			
			
                <?php //Misc ?><h4>Misc</h4>
                <div style="border: 1px dotted #000000;padding:10px;margin:0px;">
                        <table style="border:none;">
                                <tbody>
                                        
                                        <tr><td><?php echo $this->form->getLabel('openinnewwindow'); ?></td><td>:</td><td><?php echo $this->form->getInput('openinnewwindow'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('rel'); ?></td><td>:</td><td><?php echo $this->form->getInput('rel'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('hrefaddon'); ?></td><td>:</td><td><?php echo $this->form->getInput('hrefaddon'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('useglass'); ?></td><td>:</td><td><?php echo $this->form->getInput('useglass'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('logocover'); ?></td><td>:</td><td><?php echo $this->form->getInput('logocover'); ?></td></tr>
                                        
                                        <tr><td><?php echo $this->form->getLabel('prepareheadtags'); ?></td><td>:</td><td><?php echo $this->form->getInput('prepareheadtags'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('changepagetitle'); ?></td><td>:</td><td><?php echo $this->form->getInput('changepagetitle'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('responsive'); ?></td><td>:</td><td><?php echo $this->form->getInput('responsive'); ?></td></tr>
					<tr><td><?php echo $this->form->getLabel('nocookie'); ?></td><td>:</td><td><?php echo $this->form->getInput('nocookie'); ?></td></tr>
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
                </div>

        </fieldset>
        <div>
				<p><a href="http://www.joomlaboat.com/youtube-gallery/youtube-gallery-themes?view=catalog&layout=custom" target="_blank" style="font-weight: bold;margin-left:20px;">Get more Themes</a></p>
                <input type="hidden" name="task" value="themeform.edit" />
                <?php echo JHtml::_('form.token'); ?>
        </div>
</form>