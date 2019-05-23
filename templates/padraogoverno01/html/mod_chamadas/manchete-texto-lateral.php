<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_chamadas
 *
 * @copyright   Copyright (C) 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; ?>
<div class="manchete-texto-lateral">
<?php
$lista_chamadas_counter = count($lista_chamadas);
if( $lista_chamadas_counter == 3 )
	$class_container = 'span6';
elseif( $lista_chamadas_counter > 3 )
	$class_container = 'span4';
else
	$class_container = '';

$subheader = 'h'.(intval(substr($params->get('header_tag'), 1))+1);
?>
<?php for ($i=0; $i < $lista_chamadas_counter; $i++):
	$lista = $lista_chamadas[$i];
?>
	<?php if($i==0): ?>
	<div class="row-fluid">
		<?php if ($lista->image_url != ''): ?>
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
						<?php if (!empty($lista->title)): ?>
							<?php echo $lista->title ?>
						<?php endif; ?>
					</a>
				</<?php echo $params->get('header_tag')?>>
		<?php endif; ?>

		<?php if ($params->get('exibir_introtext') && $lista->introtext): ?>
			<?php echo $lista->introtext; ?>
		<?php endif; ?>
		</div>
		<?php if ($lista->image_url != ''): ?>
		<div class="span8<?php if ( $lista->image_align == 'right'): ?> no-margin<?php endif; ?> img-manchete-lateral">
			<?php if (!empty($lista->image_url)): ?>
				<?php if(strpos($lista->image_url, 'www.youtube')!==false): ?>
					<iframe width="490" height="285" src="<?php echo $lista->image_url; ?>" frameborder="0" allowfullscreen></iframe>
				<?php else: ?>
					<a href="<?php echo $lista->link ?>">
						<img src="<?php echo $lista->image_url ?>" class="img-rounded" width="490" height="auto" alt="<?php echo $lista->image_alt ?>" />
					</a>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
	<?php else: ?>
		<?php if($i==1): ?>
			<div class="chamadas-secundarias row-fluid">
			<div class="<?php echo trim($class_container.' '.'no-margin'); ?>">
		<?php else: ?>
			<div class="<?php echo trim($class_container); ?>">
		<?php endif; ?>

			<?php if($lista_chamadas_counter <= 4 || ($lista_chamadas_counter>4 && $i<3)): ?>
				<?php if (!empty($lista->image_url)): ?>
					<?php if(strpos($lista->image_url, 'www.youtube')!==false): ?>
                    	<iframe width="490" height="285" src="<?php echo $lista->image_url; ?>" frameborder="0" allowfullscreen></iframe>
                    	<?php else: ?>
						<a href="<?php echo $lista->link ?>" class="img-rounded">
							<img src="<?php echo $lista->image_url ?>" width="230" height="136" alt="<?php echo $lista->image_alt ?>" />
						</a>
					<?php endif; ?>
				<?php endif; ?>

				<?php if (@$lista->chapeu): ?>
				<p class="subtitle">
					<?php echo $lista->chapeu ?>
				</p>
				<?php endif; ?>

				<?php if ($params->get('exibir_title') && !empty($lista->title)): ?>
						<<?php echo $subheader; ?> <?php if ($params->get('header_class')): echo 'class="'.$params->get('header_class').'"'; endif; ?>>
							<a href="<?php echo $lista->link ?>" <?php if ($params->get('header_class')): echo 'class="'.$params->get('header_class').'"'; endif; ?>>
								<?php if (!empty($lista->metadesc)): ?>
									<?php echo $lista->metadesc ?>
								<?php else: ?>
									<?php echo $lista->title ?>
								<?php endif; ?>
							</a>
						</<?php echo $subheader; ?>>
				<?php endif; ?>

				<?php if ($params->get('exibir_introtext') && $lista->introtext): ?>
					<?php echo $lista->introtext; ?>
				<?php endif; ?>
			<?php else: ?>
				<div class="item-lista-chamada-secundaria<?php if($i==3): ?> first-item-lista-chamada-secundaria<?php endif; ?><?php if($i==($lista_chamadas_counter-1)): ?> last-item-lista-chamada-secundaria<?php endif; ?>">
					<?php if (@$lista->chapeu && $i==3): ?>
					<p class="subtitle">
						<?php echo $lista->chapeu ?>
					</p>
					<?php endif; ?>

					<?php if ($params->get('exibir_title') && !empty($lista->title)): ?>
							<<?php echo $subheader; ?> <?php if ($params->get('header_class')): echo 'class="'.$params->get('header_class').'"'; endif; ?>>
								<a href="<?php echo $lista->link ?>" <?php if ($params->get('header_class')): echo 'class="'.$params->get('header_class').'"'; endif; ?>>
									<?php if (!empty($lista->title)): ?>
										<?php echo $lista->title ?>
									<?php endif; ?>
								</a>
							</<?php echo $subheader; ?>>
					<?php endif; ?>

					<?php if ($params->get('exibir_introtext') && $lista->introtext && $i==3): ?>
						<?php echo $lista->introtext; ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			</div>
		<?php if($i==($lista_chamadas_counter-1)): ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
<?php endfor; ?>
<?php if (! empty($link_saiba_mais) ): ?>
	<div class="outstanding-footer<?php if($lista_chamadas_counter>1): ?> no-bkg<?php endif; ?>">
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