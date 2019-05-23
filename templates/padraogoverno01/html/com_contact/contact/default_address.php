<?php

/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */
defined('_JEXEC') or die;

/* marker_class: Class based on the selection of text, none, or icons
 * jicon-text, jicon-none, jicon-icon
 */
?>
<?php if (($this->params->get('address_check') > 0) &&  ($this->contact->address || $this->contact->suburb  || $this->contact->state || $this->contact->country || $this->contact->postcode)) : ?>
	<div class="contact-address">
	<?php if ($this->params->get('address_check') > 0) : ?>
		<h3>
			Endereço
		</h3>
		<address>
	<?php endif; ?>
	<?php if ($this->contact->address && $this->params->get('show_street_address')) : ?>
		<span class="contact-street">
			<?php echo nl2br($this->contact->address); ?>
		</span>
	<?php endif; ?>
	<?php if ($this->contact->suburb && $this->params->get('show_suburb')) : ?>
		<span class="contact-suburb">
		|	<?php echo $this->contact->suburb; ?>
		</span>
	<?php endif; ?>
	<?php if ($this->contact->state && $this->params->get('show_state')) : ?>
		<span class="contact-state">
		-	<?php echo $this->contact->state; ?>
		</span>
	<?php endif; ?>
	<?php if ($this->contact->postcode && $this->params->get('show_postcode')) : ?>
		<span class="contact-postcode">
		|	CEP: <?php echo $this->contact->postcode; ?>
		</span>
	<?php endif; ?>
	<?php if ($this->contact->country && $this->params->get('show_country')) : ?>
		<span class="contact-country">
		-	<?php echo $this->contact->country; ?>
		</span>
	<?php endif; ?>
<?php endif; ?>

<?php if ($this->params->get('address_check') > 0) : ?>
	</address>
	</div>
<?php endif; ?>

<?php if($this->params->get('show_email') || $this->params->get('show_telephone')||$this->params->get('show_fax')||$this->params->get('show_mobile')|| $this->params->get('show_webpage') ) : ?>
	<div class="contact-contactinfo">
<?php endif; ?>
<?php if ($this->contact->email_to && $this->params->get('show_email')) : ?>	
		<h3>
			E-mail
		</h3>
	<p>
		<span class="contact-emailto">
			<?php echo $this->contact->email_to; ?>
		</span>
	</p>
<?php endif; ?>

<?php if ($this->contact->telephone && $this->params->get('show_telephone')) : ?>
		<h3>
			Telefone
		</h3>
	<p>
	
		<span class="contact-telephone">
			<?php echo nl2br($this->contact->telephone); ?>
		</span>
	</p>
<?php endif; ?>
<?php if ($this->contact->fax && $this->params->get('show_fax')) : ?>
	
		<h3>
			Fax
		</h3>
	<p>
		<span class="contact-fax">
		<?php echo nl2br($this->contact->fax); ?>
		</span>
	</p>
<?php endif; ?>
<?php if ($this->contact->mobile && $this->params->get('show_mobile')) :?>
		<h3>
			Celular
		</h3>
	<p>
		<span class="contact-mobile">
			<?php echo nl2br($this->contact->mobile); ?>
		</span>
	</p>
<?php endif; ?>
<?php if ($this->contact->webpage && $this->params->get('show_webpage')) : ?>
	<h3>
		Site
	</h3>
	<p>
		<span class="contact-webpage">
			<a href="<?php echo $this->contact->webpage; ?>" target="_blank">
			<?php echo $this->contact->webpage; ?></a>
		</span>
	</p>
<?php endif; ?>
<?php if($this->params->get('show_email') || $this->params->get('show_telephone')||$this->params->get('show_fax')||$this->params->get('show_mobile')|| $this->params->get('show_webpage') ) : ?>
	</div>
<?php endif; ?>
