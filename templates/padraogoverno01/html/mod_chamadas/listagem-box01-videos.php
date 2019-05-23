<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_chamadas
 *
 * @copyright   Copyright (C) 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if($params->get('modelo') != 'youtubegallery'):
  echo '<h3>Atenção</h3><p>Somente a fonte de dados youtubegallery encontra-se preparada para exibir o módulo de galeria de vídeos. Acesse a área administrativa e mude a fonte de dados do módulo.</p>';
else:
	//preencher link quando categoria for unica
	if (empty($link_saiba_mais) && count($params->get('catid'))==1 && $params->get('buscar_cat_tag')==1) {
		$catid = $params->get('catid');
		$link_saiba_mais = JRoute::_('index.php?option=com_content&view=category&id='.$catid[0]);
	}
	?>
	<div class="gallery-pane">
		<div id="gallery-carousel-<?php echo $module->id ?>" class="carousel slide">
			<div class="carousel-inner">
				<?php foreach ($lista_chamadas as $k => $lista): ?>
				<div class="item<?php if($k==0): ?> active<?php endif; ?>">
					<?php 
					$playlist = array();
					for ($i=1; $i < count($lista_chamadas); $i++) { 
						$playlist[] = $lista_chamadas[$i]->alias;
					}
					if(count($playlist))
						@$playlist = '&amp;playlist='.implode(',', $playlist).','.$lista->alias;
					?>
					<iframe width="364" height="264" frameborder="0" id="videoplayer_<?php echo $module->id; ?>" src="http://www.youtube.com/embed/<?php echo $lista->alias ?>?autoplay=0&amp;hl=en&amp;fs=0&amp;showinfo=1&amp;iv_load_policy=3&amp;rel=1&amp;loop=0&amp;border=0&amp;controls=1&amp;start=0&amp;end=0<?php echo $playlist ?>"></iframe>
					<div class="galleria-info">
						<div class="galleria-info-text">
						    <div class="galleria-info-title">
						    	<<?php echo $params->get('header_tag')?>><a href="<?php echo $lista->link ?>"><?php echo $lista->title ?></a></<?php echo $params->get('header_tag')?>>
						    </div>
						    <div class="galleria-info-description"><?php echo $lista->introtext; ?></div>
						    <div data-index="<?php echo $k ?>" class="rights"><?php echo $lista->image_caption; ?></div>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<a data-slide="prev" href="#gallery-carousel-<?php echo $module->id ?>" class="left carousel-control"><i class="icon-angle-left"></i><span class="hide">Mover foto esquerda</span></a>
			<!-- separador para fins de acessibilidade <--><span class="hide">&nbsp;</span></--><!-- fim separador para fins de acessibilidade -->
			<a data-slide="next" href="#gallery-carousel-<?php echo $module->id ?>" class="right carousel-control"><i class="icon-angle-right"></i><span class="hide">Mover foto esquerda</span></a>
		</div>
		<div class="galeria-thumbs hide">
			<ul>
				<?php reset($lista_chamadas); ?>
				<?php foreach ($lista_chamadas as $k => $lista): ?>
				<li class="galeria-image">
					<a href="#0<?php echo $k ?>"><img src="<?php echo $lista->image_url; ?>" alt="Img navegação."></a>
				</li>
				<?php endforeach; ?>			
			</ul>
		</div>
	</div>
	<?php if (! empty($link_saiba_mais) ): ?>
		<div class="footer">
			<a href="<?php echo $link_saiba_mais; ?>" class="link">
				<?php echo $params->get('texto_saiba_mais', 'saiba mais')?>			
			</a>	
		</div>
	<?php endif; ?>
<?php endif; ?>