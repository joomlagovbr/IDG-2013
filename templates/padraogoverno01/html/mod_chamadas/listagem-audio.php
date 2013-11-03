<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_chamadas
 *
 * @copyright   Copyright (C) 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
//preencher link quando categoria for unica
if (empty($link_saiba_mais) && count($params->get('catid'))==1 && $params->get('buscar_cat_tag')==1) {
	$catid = $params->get('catid');
	$link_saiba_mais = JRoute::_('index.php?option=com_content&view=category&id='.$catid[0]);
}
?>
<div id="daf6a33c36c54df8a2f8e6b91e619aff" data-tile="@@audio/daf6a33c36c54df8a2f8e6b91e619aff" class="tile tile-default">
  <div id="audio-daf6a33c36c54df8a2f8e6b91e619aff" class="audio-tile">
    <h3 class="title"></h3>
    <!-- Audio item player -->
    <div id="audio_jplayer_daf6a33c36c54df8a2f8e6b91e619aff" data-audio-description="Espaço para inserir a legenda do áudio 1" data-audio-url="http://tv1-lnx-04.grupotv1.com/portalmodelo/conteudos-de-marcacao/audio-1-titulo-do-audio-1/audio-1-nome-do-audio.mp3" class="jp-jplayer" style="width: 0px; height: 0px;"><img id="jp_poster_0" style="width: 0px; height: 0px; display: none;"><object width="1" height="1" id="jp_flash_0" data="/++resource++brasil.gov.tiles/Jplayer.swf" type="application/x-shockwave-flash" tabindex="-1" style="width: 0px; height: 0px;"><param name="flashvars" value="jQuery=jQuery&amp;id=audio_jplayer_daf6a33c36c54df8a2f8e6b91e619aff&amp;vol=0.8&amp;muted=false"><param name="allowscriptaccess" value="always"><param name="bgcolor" value="#000000"><param name="wmode" value="window"></object></div>
    <div id="audio_jpcontainer_daf6a33c36c54df8a2f8e6b91e619aff" class="jp-audio">
      <div class="jp-type-single">
        <div class="jp-gui jp-interface">
          <ul class="jp-controls"><li><a tabindex="1" class="jp-play" href="javascript:;" style="display: block;">play</a></li>
            <li><a tabindex="1" class="jp-pause" href="javascript:;" style="display: none;">pause</a></li>
            <li><a title="mute" tabindex="1" class="jp-mute" href="javascript:;">mute</a></li>
            <li><a title="unmute" tabindex="1" class="jp-unmute" href="javascript:;" style="display: none;">unmute</a></li>
            <li class="last-item"><a title="max volume" tabindex="1" class="jp-volume-max" href="javascript:;">max volume</a></li>
          </ul><div class="jp-progress-time-wrapper">
            <div class="jp-progress">
              <div class="jp-seek-bar" style="width: 100%;">
                <div class="jp-play-bar" style="width: 22.692%;"></div>
              </div>
            </div>
            <div class="jp-time-holder">
              <span class="jp-current-time">00:07</span>/
              <span class="jp-duration">00:32</span>
            </div>
          </div>
          <div class="jp-volume-bar">
            <div class="jp-volume-bar-value" style="width: 80%;"></div>
          </div>
        </div>
        <div class="jp-no-solution" style="display: none;">
          <span>Update Required</span>
          To play the media you will need to either update your browser to a recent version or update your <a target="_blank" href="http://get.adobe.com/flashplayer/">Flash plugin</a>.
        </div>
      </div>
    </div>
    <p class="description">Espaço para inserir a legenda do áudio 1</p>
    
  </div>
</div>