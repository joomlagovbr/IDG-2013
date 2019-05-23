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
?>
	<?php if(count($lista_chamadas)>0): ?>
	<div class="videos-container">
		<div class="span8 video-main player-metadata">
			<div id="YoutubeGallerySecondaryContainer1" class="player-container">
				<?php 
					$playlist = array();
					for ($i=1; $i < count($lista_chamadas); $i++) { 
						$playlist[] = $lista_chamadas[$i]->alias;
					}
					if(count($playlist))
						$playlist = '&amp;playlist='.implode(',', $playlist).','.$lista_chamadas[0]->alias;
				?>
				<iframe width="459" height="344" frameborder="0" id="videoplayer_<?php echo $module->id; ?>" src="http://www.youtube.com/embed/<?php echo $lista_chamadas[0]->alias ?>?autoplay=0&amp;hl=en&amp;fs=0&amp;showinfo=1&amp;iv_load_policy=3&amp;rel=1&amp;loop=0&amp;border=0&amp;controls=1&amp;start=0&amp;end=0<?php echo $playlist ?>"></iframe>
			</div>

				<h3><span id="videoplayer_title_<?php echo $module->id; ?>" class="title"><?php echo $lista_chamadas[0]->title ?></span></h3>
				<p id="videoplayer_description_<?php echo $module->id; ?>" class="description">
					<?php echo $lista_chamadas[0]->introtext; ?>
				</p>
		</div>
		<div class="span4 video-list">
			<?php
			foreach ($lista_chamadas as $key => $lista):
			?>
			<div class="video-item row-fluid">
				<a href="<?php echo $lista->link ?>" class="link-video-item"><img src="<?php echo $lista->images->image_intro ?>" alt="Imagem do vídeo" width="120" height="90"></a>
				<h3><a href="<?php echo $lista->link ?>" class="link-video-item-title"><?php echo $lista->title ?></a></h3>
				<div class="hide info-description"><?php echo $lista->introtext ?></div>
				<?php 
					$playlist_tmp = str_replace(array($lista->alias,','.$lista->alias), '', $playlist);
				?>
				<div class="hide info-link">http://www.youtube.com/embed/<?php echo $lista->alias ?>?autoplay=0&amp;hl=en&amp;fs=0&amp;showinfo=1&amp;iv_load_policy=3&amp;rel=1&amp;loop=0&amp;border=0&amp;controls=1&amp;start=0&amp;end=0<?php echo $playlist_tmp; ?></div>		
			</div>
			<?php
			endforeach;
			?>
		</div>
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
	<script type="text/javascript">
		jQuery(document).ready(function(){
			setModuleBox02clicks();
		});
	</script>
	<?php endif; ?>
<?php endif; ?>