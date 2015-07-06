<?php

defined('_JEXEC') or die;
/**
 * @package    redessociais
 * @subpackage C:
 * @author     Ricardo Morais {@link }
 * @author     Created on 22-Nov-2013
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('acesso restrito');

if($config['twitter_show'] || $config['facebook_show']):
?>
<div class="header tabs row-fluid">
<ul class="nav nav-tabs">
  <?php if($config['twitter_show'] && $config['first']=='twitter'): ?>
   <li class="active"><a href="#twitter-pane" data-toggle="tab">Twitter</a></li>
  <?php endif; ?>
   <?php if($config['facebook_show']): ?>
   <li><a href="#facebook-pane" data-toggle="tab">Facebook</a></li>
  <?php endif; ?>
  <?php if($config['twitter_show'] && $config['first']!='twitter'): ?>
   <li><a href="#twitter-pane" data-toggle="tab">Twitter</a></li>
  <?php endif; ?>
</ul>
  
</div>

<div class="tab-content">
  <?php if($config['twitter_show'] && $config['first']=='twitter'): ?>
  <div class="tab-pane active <?php echo $config['twitter_class']; ?> pane" id="twitter-pane">
    <h2 class="hide">Twitter</h2>
    <div class="twitter-content">
        <a height="<?php echo $config['twitter_height']; ?>" data-widget-id="<?php echo $config['twitter_widget_id']; ?>" href="https://twitter.com/<?php echo $config['twitter_user'] ?>" class="twitter-timeline"><br /><?php echo $config['twitter_text_link_error']; ?></a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script><noscript>
            <div class="error">
              <p>Javascript desativado.</p>
              <p><a href="https://twitter.com/<?php echo $config['twitter_user'] ?>"><?php echo $config['twitter_text_link_error']; ?></a></p>
            </div>
        </noscript>
    </div>                                     
  </div>
  <?php endif; ?>
  <?php if($config['facebook_show']): ?>
  <div class="tab-pane <?php echo $config['facebook_class']; ?> pane" id="facebook-pane">
    <h2 class="hide">Facebook</h2>
    <div class="facebook-content">
      <?php
        $html  = '<iframe ';
        $html .= 'src="//www.facebook.com/plugins/likebox.php?';
        $html .= 'href=http%3A%2F%2Fwww.facebook.com%2F'.$config['facebook_page_url'];
        $html .= '&amp;width='.$config['facebook_width'];
        $html .= '&amp;height='.$config['facebook_height'];
        $html .= '&amp;colorscheme='.$config['facebook_colorscheme'];
        $html .= '&amp;show_faces='.$config['facebook_show_faces'];
        $html .= '&amp;header='.$config['facebook_header'];
        $html .= '&amp;stream='.$config['facebook_stream'];
        $html .= '&amp;show_border='.$config['facebook_show_border'];
        $html .= '&amp;appId='.$config['facebook_appId'].'"';
        $html .= ' scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$config['facebook_width'].'px; height:'.$config['facebook_height'].'px;" allowTransparency="true">';
        $html .= '</iframe>';
        echo $html;

      ?>  
    </div>                                     
  </div>
  <?php endif; ?>
  <?php if($config['twitter_show'] && $config['first']!='twitter'): ?>
  <div class="tab-pane <?php echo $config['twitter_class']; ?> pane" id="twitter-pane">
    <h2 class="hide">Twitter</h2>
    <div class="twitter-content">
        <a height="<?php echo $config['twitter_height']; ?>" data-widget-id="<?php echo $config['twitter_widget_id']; ?>" href="https://twitter.com/<?php echo $config['twitter_user'] ?>" class="twitter-timeline"><br /><?php echo $config['twitter_text_link_error']; ?></a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script><noscript>
            <div class="error">
              <p>Javascript desativado.</p>
              <p><a href="https://twitter.com/<?php echo $config['twitter_user'] ?>"><?php echo $config['twitter_text_link_error']; ?></a></p>
            </div>
        </noscript>
    </div>                                     
  </div>
  <?php endif; ?>
</div>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function(){
    jQuery('#twitter-pane a').click(function (e) {
       e.preventDefault();
      jQuery(this).tab('show');
    })
    jQuery('#facebook-pane a').click(function (e) {
       e.preventDefault();
      jQuery(this).tab('show');
    })

    jQuery('.nav-tabs a[href="#<?php echo $config["active"] ?>-pane"]').tab('show');
});
 //]]>
</script><noscript>Este m&oacute;dulo depende de javascript para funcionar.</noscript>
<?php endif; ?>