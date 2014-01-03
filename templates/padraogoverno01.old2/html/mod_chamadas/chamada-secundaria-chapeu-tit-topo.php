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
<?php foreach ($lista_chamadas as $k => $lista): ?>
	<?php if ($lista_chamadas_counter > 1) {
		if($k==0 || $k % 3 == 0)
			echo '<div class="'.trim($class_container.' '.'no-margin').'">';
		else
			echo '<div class="'.trim($class_container).'">';

	} ?>

	<?php if($params->get('variacao_item'.($k+1), 0) != 0): ?>
	<div class="variacao-module-0<?php echo $params->get('variacao_item'.($k+1)) ?>">
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
	
	<?php if ($params->get('exibir_imagem') && !empty($lista->image_url)): ?>	
		<?php if(strpos($lista->image_url, 'www.youtube')!==false): ?>
			<object width="230" height="176"><param value="<?php echo 'http://'.$lista->image_url; ?>" name="movie"><param value="true" name="allowFullScreen"><param value="always" name="allowscriptaccess"><embed width="230" height="176" allowfullscreen="true" allowscriptaccess="always" type="application/x-shockwave-flash" src="<?php echo 'http://'.$lista->image_url; ?>"></object>
		<?php else: ?>				
			<a href="<?php echo $lista->link ?>" class="img-rounded">
				<img src="<?php echo $lista->image_url ?>" width="230" height="136" alt="<?php echo $lista->image_alt ?>" />
			</a>
		<?php endif; ?>				
	<?php endif; ?>

	<?php if ($params->get('exibir_introtext') && $lista->introtext): ?>
		<?php echo $lista->introtext; ?>
	<?php endif; ?>

	<?php if($params->get('variacao_item'.($k+1), 0) != 0): ?>
	</div>
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