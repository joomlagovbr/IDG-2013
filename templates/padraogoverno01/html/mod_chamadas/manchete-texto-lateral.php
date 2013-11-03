<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_chamadas
 *
 * @copyright   Copyright (C) 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php foreach ($lista_chamadas as $lista): ?>
	<?php if ($params->get('exibir_imagem')): ?>	
	<div class="span4 <?php if ( $lista->image_align != 'right'): ?>no-margin<?php else: ?>pull-right<?php endif; ?>">
	<?php else: ?>
	<div class="span12 no-margin">	
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
	</div>
	<?php if ($params->get('exibir_imagem')): ?>
	<div class="span8<?php if ( $lista->image_align == 'right'): ?> no-margin<?php endif; ?>">				
		<?php if (!empty($lista->image_url)): ?>
			<?php if(strpos($lista->image_url, 'www.youtube')!==false): ?>
				<object width="490" height="368"><param value="<?php echo 'http://'.$lista->image_url; ?>" name="movie"><param value="true" name="allowFullScreen"><param value="always" name="allowscriptaccess"><embed width="490" height="368" allowfullscreen="true" allowscriptaccess="always" type="application/x-shockwave-flash" src="<?php echo 'http://'.$lista->image_url; ?>"></object>
			<?php else: ?>				
				<a href="<?php echo $lista->link ?>">
					<img src="<?php echo $lista->image_url ?>" width="490" height="auto" alt="<?php echo $lista->image_alt ?>" />
				</a>				
			<?php endif; ?>
		<?php endif; ?>	
	</div>
	<?php endif; ?>
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