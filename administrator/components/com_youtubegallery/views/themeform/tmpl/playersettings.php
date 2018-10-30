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
<h4>Player Settings</h4>
<table style="border:none;">
	<tbody>
                                        <tr><td><?php echo $this->form->getLabel('border'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('border'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('color1'); ?></td><td>:</td><td><?php echo $this->form->getInput('color1'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('color2'); ?></td><td>:</td><td><?php echo $this->form->getInput('color2'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('autoplay'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('autoplay'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('repeat'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('repeat'); ?></fieldset></td></tr>
					<tr><td><?php echo $this->form->getLabel('allowplaylist'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('allowplaylist'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('fullscreen'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('fullscreen'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('related'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('related'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('showinfo'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('showinfo'); ?></fieldset></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('controls'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('controls'); ?></fieldset></td></tr>
					<tr><td><?php echo $this->form->getLabel('muteonplay'); ?></td><td>:</td><td><fieldset id="jform_attribs_link_titles" class="radio btn-group"><?php echo $this->form->getInput('muteonplay'); ?></fieldset></td></tr>
					<tr><td><?php echo $this->form->getLabel('volume'); ?></td><td>:</td><td><?php echo $this->form->getInput('volume'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('playertype'); ?></td><td>:</td><td><?php echo $this->form->getInput('playertype'); ?></td></tr>
                                        <tr><td><?php echo $this->form->getLabel('youtubeparams'); ?></td><td>:</td><td><?php echo $this->form->getInput('youtubeparams'); ?></td></tr>
	</tbody>
</table>
