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
<h4>General Theme Settings</h4>
								<table style="border:none;">
								<tbody>
										<tr><td style="width:270px;"><?php echo $this->form->getLabel('themename'); ?></td><td>:</td><td><?php echo $this->form->getInput('themename'); ?></td></tr>
										<tr><td colspan="3"></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('width'); ?></td><td>:</td><td><?php echo $this->form->getInput('width'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('height'); ?></td><td>:</td><td><?php echo $this->form->getInput('height'); ?></td></tr>
										
										<tr><td><?php echo $this->form->getLabel('pagination'); ?></td><td>:</td><td><?php echo $this->form->getInput('pagination'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('playvideo'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('playvideo'); ?></fieldset></td></tr>


								</tbody>
								</table>


                      <h4>Layout Settings</h4>

                        <table style="border:none;">
                                <tbody>
                                        <tr><td style="width:270px;"><?php echo $this->form->getLabel('showlistname'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('showlistname'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('listnamestyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('listnamestyle'); ?></td></tr>
										
										<tr><td colspan="3" style="height:20px;"><hr style="border:none;border-bottom:1px dotted #DDDDDD;" /></td></tr>
                                        
										
                                        <tr><td><?php echo $this->form->getLabel('showactivevideotitle'); ?></td><td>:</td><td>
										<fieldset id="jform_attribs_link_titles" class="radio btn-group">
										<?php echo $this->form->getInput('showactivevideotitle'); ?>
										</fieldset>
										</td></tr>
										<tr><td><?php echo $this->form->getLabel('activevideotitlestyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('activevideotitlestyle'); ?></td></tr>
										<tr><td colspan="3" style="height:20px;"><hr style="border:none;border-bottom:1px dotted #DDDDDD;" /></td></tr>
										<tr><td><?php echo $this->form->getLabel('description'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('description'); ?></fieldset></td></tr>
										<tr><td><?php echo $this->form->getLabel('descr_style'); ?></td><td>:</td><td><?php echo $this->form->getInput('descr_style'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('descr_position'); ?></td><td>:</td><td><?php echo $this->form->getInput('descr_position'); ?></td></tr>
										<tr><td colspan="3" style="height:20px;"><hr style="border:none;border-bottom:1px dotted #DDDDDD;" /></td></tr>
										
                                        
                                        
                                        <tr><td><?php echo $this->form->getLabel('cssstyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('cssstyle'); ?></td></tr>
										<tr><td colspan="3" style="height:20px;"><hr style="border:none;border-bottom:1px dotted #DDDDDD;" /></td></tr>
                                </tbody>
                        </table>
						
						
						
						
						
						
<h4>Navigation Bar</h4>
   
<table style="border:none;">
	<tbody>

		<tr><td style="width:270px;"><?php echo $this->form->getLabel('showtitle'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('showtitle'); ?></fieldset></td></tr><!-- depricated - part of layout -->
		
		<tr><td><?php echo $this->form->getLabel('cols'); ?></td><td>:</td><td><?php echo $this->form->getInput('cols'); ?></td></tr>
	
		<tr><td><?php echo $this->form->getLabel('orderby'); ?></td><td>:</td><td><?php echo $this->form->getInput('orderby'); ?></td></tr>
		<tr><td><?php echo $this->form->getLabel('customlimit'); ?></td><td>:</td><td><?php echo $this->form->getInput('customlimit'); ?></td></tr>
		
		<tr><td colspan="3" style="height:20px;"><hr style="border:none;border-bottom:1px dotted #DDDDDD;" /></td></tr>
		<tr><td><?php echo $this->form->getLabel('navbarstyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('navbarstyle'); ?></td></tr>
		<tr><td><?php echo $this->form->getLabel('bgcolor'); ?></td><td>:</td><td><?php echo $this->form->getInput('bgcolor'); ?></td></tr><!-- depricated - part of layout -->
		<tr><td><?php echo $this->form->getLabel('thumbnailstyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('thumbnailstyle'); ?></td></tr><!-- depricated - part of layout  -->
		
		<tr><td><?php echo $this->form->getLabel('linestyle'); ?></td><td>:</td><td><?php echo $this->form->getInput('linestyle'); ?></td></tr><!-- depricated - can be done with navbarstyle -->
                      
		
		
	</tbody>
</table>