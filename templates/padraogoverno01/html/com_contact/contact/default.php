<?php
 /**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

defined('_JEXEC') or die;

$cparams = JComponentHelper::getParams ('com_media');
?>
<div class="contact<?php echo $this->pageclass_sfx?>">
<?php if ($this->params->get('show_page_heading')) : ?>
<h1 class="documentFirstHeading">
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>
	
	<?php if (($this->contact->name && $this->params->get('show_name') && (!$this->params->get('show_contact_list')) || ($this->params->get('show_contact_list') && count($this->contacts) < 1))) : ?>
	<div class="subtitle">
		<span class="contact-name">
			<?php echo $this->contact->name; ?>
		</span>
	</div>
	<?php elseif ($this->params->get('show_contact_list') && count($this->contacts) > 1):  ?>
	<div class="subtitle">
		<form action="#" method="get" id="selectForm" class="pull-left">
			<label for="id" class="hide"><?php echo JText::_('COM_CONTACT_SELECT_CONTACT'); ?></label>
			<?php echo JHtml::_('select.genericlist',  $this->contacts, 'id', 'class="inputbox" onchange="document.location.href = this.value"', 'link', 'name', $this->contact->link);?>
			
		</form>
		<br>
	</div>
	<br>
	<?php endif;  ?>

	<div class="row-fluid description">
		<p>
		<?php if ($this->params->get('show_contact_category') == 'show_no_link') : ?>
			<span class="contact-category">Categoria: <?php echo $this->contact->category_title; ?>.</span>
		<?php endif; ?>
		<?php if ($this->params->get('show_contact_category') == 'show_with_link') : ?>
			<?php $contactLink = ContactHelperRoute::getCategoryRoute($this->contact->catid);?>
			<span class="contact-category">Categoria: <a href="<?php echo $contactLink; ?>">
				<?php echo $this->escape($this->contact->category_title); ?></a>.
			</span>
		<?php endif; ?>
		<?php if ($this->contact->con_position && $this->params->get('show_position')) : ?>
			<span class="contact-position"> | Cargo: <?php echo $this->contact->con_position; ?></span>
		<?php endif; ?>
		</p>			
	</div>
	
	<div class="row-fluid">
		<div class="span8">
			<?php if ($this->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id)) : ?>
			<div class="module">
				<div class="outstanding-header">
					<h2 class="outstanding-title"><span>Envie um e-mail</span></h2>
			 	</div>
			
			
				<?php  echo $this->loadTemplate('form');  ?>
			</div>
			<?php endif; ?>

			<?php if ($this->params->get('show_links')) : ?>
				<?php echo $this->loadTemplate('links'); ?>
			<?php endif; ?>
		</div>
		<div class="span4">
			<div class="module">
				<div class="outstanding-header">
					<h2 class="outstanding-title"><span>Contatos</span></h2>
			 	</div>
			
				<?php if ($this->contact->image && $this->params->get('show_image')) : ?>
					<div class="contact-image row-fluid">
						<?php echo JHtml::_('image', $this->contact->image, JText::_('COM_CONTACT_IMAGE_DETAILS'), array('align' => 'middle', 'class' => 'img-rounded')); ?>
					</div>
				<?php endif; ?>

				<div class="address row-fluid">
				<?php echo $this->loadTemplate('address'); ?>
				</div>
			</div>


			<?php if ($this->params->get('allow_vcard')) :	?>
			<div class="vcard row-fluid">			
				<?php echo JText::_('COM_CONTACT_DOWNLOAD_INFORMATION_AS');?>
					<a href="<?php echo JRoute::_('index.php?option=com_contact&amp;view=contact&amp;id='.$this->contact->id . '&amp;format=vcf'); ?>">
					<?php echo JText::_('COM_CONTACT_VCARD');?></a>
			</div>
			<?php endif; ?>
				
		</div>
	</div>
	<?php //comentado, pois provavelmente nao sera utilizado: ?>
	<?php /*if ($this->params->get('show_articles') && $this->contact->user_id && $this->contact->articles) : ?>
		<?php if ($this->params->get('presentation_style')!='plain'):?>
			<?php echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('JGLOBAL_ARTICLES'), 'display-articles'); ?>
			<?php endif; ?>
			<?php if  ($this->params->get('presentation_style')=='plain'):?>
			<?php echo '<h3>'. JText::_('JGLOBAL_ARTICLES').'</h3>'; ?>
			<?php endif; ?>
			<?php echo $this->loadTemplate('articles'); ?>
	<?php endif; ?>
	<?php if ($this->params->get('show_profile') && $this->contact->user_id && JPluginHelper::isEnabled('user', 'profile')) : ?>
		<?php if ($this->params->get('presentation_style')!='plain'):?>
			<?php echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('COM_CONTACT_PROFILE'), 'display-profile'); ?>
		<?php endif; ?>
		<?php if ($this->params->get('presentation_style')=='plain'):?>
			<?php echo '<h3>'. JText::_('COM_CONTACT_PROFILE').'</h3>'; ?>
		<?php endif; ?>
		<?php echo $this->loadTemplate('profile'); ?>
	<?php endif;*/ ?>

	<?php /*if ($this->contact->misc && $this->params->get('show_misc')) : ?>
		<?php if ($this->params->get('presentation_style')!='plain'){?>
			<?php echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('COM_CONTACT_OTHER_INFORMATION'), 'display-misc');} ?>
		<?php if ($this->params->get('presentation_style')=='plain'):?>
			<?php echo '<h3>'. JText::_('COM_CONTACT_OTHER_INFORMATION').'</h3>'; ?>
		<?php endif; ?>
				<div class="contact-miscinfo">
					<div class="<?php echo $this->params->get('marker_class'); ?>">
						<?php echo $this->params->get('marker_misc'); ?>
					</div>
					<div class="contact-misc">
						<?php echo $this->contact->misc; ?>
					</div>
				</div>
	<?php endif;*/ ?>
	<?php //fim comentado, pois provavelmente nao sera utilizado ?>

</div>
