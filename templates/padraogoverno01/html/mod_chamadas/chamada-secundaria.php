<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_chamadas
 *
 * @copyright   Copyright (C) 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$lista_chamadas_counter = count($lista_chamadas);
if( $lista_chamadas_counter == 2 )
	$class_container = 'module span6';
elseif( $lista_chamadas_counter > 2 )
	$class_container = 'module span4';
else
	$class_container = '';
?>
<div class="chamadas-secundarias">
<?php foreach ($lista_chamadas as $k => $lista): ?>
	<?php if ($lista_chamadas_counter > 1) {
		if($k==0 || $k % 3 == 0)
			echo '<div class="'.trim($class_container.' '.'no-margin').'">';
		else
			echo '<div class="'.trim($class_container).'">';

	} ?>
	<?php if ($params->get('exibir_imagem') && !empty($lista->image_url)): ?>	
		<?php if(strpos($lista->image_url, 'www.youtube')!==false): ?>
             <iframe width="230" height="135" src="<?php echo $lista->image_url; ?>" frameborder="0" allowfullscreen></iframe>
			
		<?php else: ?>				
			<a href="<?php echo $lista->link ?>" class="img-rounded">
				<img src="<?php echo $lista->image_url ?>" alt="<?php echo $lista->image_alt ?>" />
			</a>
		<?php endif; ?>				
	<?php endif; ?>
	
	<?php if (@$lista->chapeu): ?>
	<p class="subtitle">
      <?php echo $lista->chapeu ?>
	</p>
	<?php endif; ?>

	<?php if ($params->get('exibir_title') && !empty($lista->title)): ?>			
			<<?php echo $params->get('header_tag')?> <?php if ($params->get('header_class')): echo 'class="'.$params->get('header_class').'"'; endif; ?>>
				<a href="<?php echo $lista->link ?>" <?php if ($params->get('header_class')): echo 'class="'.$params->get('header_class').'"'; endif; ?>>
					<?php echo $lista->title ?>
				</a>
			</<?php echo $params->get('header_tag')?>>
	<?php endif; ?>

	<?php if ($params->get('exibir_introtext') && $lista->introtext): ?>
		<?php echo $lista->introtext; ?>
	<?php endif; ?>
	<?php if ($lista_chamadas_counter > 1) {
		echo '</div>';
	} ?>
<?php endforeach; ?>

<?php if (! empty($link_saiba_mais) ): ?>
	<div class="outstanding-footer">
		<a href="<?php echo $link_saiba_mais; ?>" class="outstanding-link">
			<?php if ($params->get('texto_saiba_mais')): ?>
				<span class="text"><?php echo $params->get('texto_saiba_mais')?></span>
			<?php else: ?>
				<span class="text">saiba mais</span>
			<?php endif;?>
			<span class="icon-box">                                          
		      <i class="icon-angle-right icon-light"><span class="hide">&nbsp;</span></i>
		    </span>
		</a>	
	</div>
<?php endif; ?>
</div>
