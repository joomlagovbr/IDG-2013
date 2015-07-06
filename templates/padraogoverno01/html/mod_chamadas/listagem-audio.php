<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_chamadas
 *
 * @copyright   Copyright (C) 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if($params->get('modelo') != 'manual' && $params->get('modelo') != 'article_k2'):
  echo '<h3>Atenção</h3><p>Somente as fontes de dados manual e article_k2 encontram-se preparadas para adquirir dados do módulo de áudios. Acesse a área administrativa e mude a fonte de dados do módulo.</p>';
else:
  // //preencher link quando categoria for unica
  // if (empty($link_saiba_mais) && count($params->get('catid'))==1 && $params->get('buscar_cat_tag')==1) {
  // 	$catid = $params->get('catid');
  // 	$link_saiba_mais = JRoute::_('index.php?option=com_content&view=category&id='.$catid[0]);
  // }
  $document = JFactory::getDocument();
  $document->addStyleSheet(JURI::root().'templates/padraogoverno01/jplayer/skin/portalpadrao01/jplayer.css');
  $script = '<script type="text/javascript" src="'.JURI::root().'templates/padraogoverno01/jplayer/js/jquery.jplayer.min.js"></script><noscript>A exibição do player de áudio desta página depende de javascript.</noscript>';
  $document->addCustomTag($script);
  foreach ($lista_chamadas as $k => $lista):

    $media_url = $lista->image_url;
    $extension = substr($media_url, strrpos($media_url, '.')+1);
  ?>
    <?php if ($params->get('exibir_title') && !empty($lista->title)): ?>
    <h3><?php echo $lista->title; ?></h3>
    <?php endif; ?>
  <!-- inicio jplayer -->
  <div class="jp-audio-slim">    
    <div id="jplayer_<?php echo $module->id.'_'.$k; ?>" class="jp-jplayer"></div>
    <div id="jp_container_jplayer_<?php echo $module->id.'_'.$k; ?>" class="jp-audio">
      <div class="jp-type-single">
        <div class="jp-gui jp-interface">
          <ul class="jp-controls">
            <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
            <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
            <li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
            <li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
            <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
            <li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
          </ul>
          <div class="jp-progress">
            <div class="jp-seek-bar">
              <div class="jp-play-bar"></div>
            </div>
          </div>
          <div class="jp-volume-bar">
            <div class="jp-volume-bar-value"></div>
          </div>
          <div class="jp-time-holder">
            <div class="jp-current-time"></div>
            <div class="jp-duration"></div>            
          </div>
        </div>
        <div class="jp-no-solution">
          <span>Necessário plugin</span>
          Para habilitar o áudio, é necessário que você tenha instalado em seu computador o <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
  //<![CDATA[
  jQuery(document).ready(function(){
      urls = {<?php echo $extension ?>:"<?php echo $media_url; ?>"};   
      playAudio("jplayer_<?php echo $module->id.'_'.$k; ?>", urls, "<?php echo $extension ?>", "<?php echo JURI::root().'templates/padraogoverno01/jplayer/'; ?>");
  });
  //]]>
  </script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
    <?php if ($params->get('exibir_introtext') && $lista->introtext): ?>
      <div class="formated-description">
      <?php echo $lista->introtext; ?>
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

<?php endif; ?>