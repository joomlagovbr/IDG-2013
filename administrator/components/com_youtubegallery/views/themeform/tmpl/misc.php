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
<h4>Misc</h4>
	<table style="border:none;">
		<tbody>
                                        
                                        <tr><td><?php echo $this->form->getLabel('openinnewwindow'); ?></td><td>:</td><td><?php echo $this->form->getInput('openinnewwindow'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('rel'); ?></td><td>:</td><td><?php echo $this->form->getInput('rel'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('hrefaddon'); ?></td><td>:</td><td><?php echo $this->form->getInput('hrefaddon'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('useglass'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('useglass'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('logocover'); ?></td><td>:</td><td><?php echo $this->form->getInput('logocover'); ?></td></tr>
                                        
                                        <tr><td><?php echo $this->form->getLabel('prepareheadtags'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('prepareheadtags'); ?></fieldset></td></tr>
					<tr><td><?php echo $this->form->getLabel('changepagetitle'); ?></td><td>:</td><td><?php echo $this->form->getInput('changepagetitle'); ?></td></tr>
										
										
					<tr><td><?php echo $this->form->getLabel('responsive'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('responsive'); ?></fieldset></td></tr>
					<tr><td><?php echo $this->form->getLabel('nocookie'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('nocookie'); ?></fieldset></td></tr>

		</tbody>
	</table>

