<?php
/**
 * @version	
 * @package	
 * @author  
 * @copyright
 * @license	
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
// require_once __DIR__.'/_helper.php';
require_once __DIR__.'/_helper.php';
TmplAllvideosHelper::removeJs(array('behaviour.js','jwplayer.min.js','silverlight.js','wmvplayer.js','AC_QuickTime.js'));
TmplAllvideosHelper::removeCss(array('jw_allvideos/Classic/css/template.css'));
TmplAllvideosHelper::addCss(JURI::root().'templates/padraogoverno01/jplayer/skin/portalpadrao01/jplayer.css');
TmplAllvideosHelper::addJs(JURI::root().'templates/padraogoverno01/jplayer/js/jquery.jplayer.min.js');

$extension = substr($output->source, strrpos($output->source, '.')+1);
$namefile = substr($output->source, strrpos($output->source, '/')+1);
$output->newsource = JURI::root().$output->folder.'/'.$namefile;
?>
 <!-- inicio jplayer -->
  <div class="jp-audio">    
    <div id="jplayer_<?php echo $output->playerID; ?>" class="jp-jplayer"></div>
    <div id="jp_container_jplayer_<?php echo $output->playerID; ?>" class="jp-audio">
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
  <?php //echo $output->newsource; die(); ?>
  <script type="text/javascript">
  //<![CDATA[
  jQuery(document).ready(function(){
      urls = {mp3:"<?php echo $output->newsource; ?>"};   
      playAudio("jplayer_<?php echo $output->playerID; ?>", urls, "<?php echo $extension ?>", "<?php echo JURI::root().'templates/padraogoverno01/jplayer/'; ?>");
  });
  //]]>
  </script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
  <!-- fim jplayer -->