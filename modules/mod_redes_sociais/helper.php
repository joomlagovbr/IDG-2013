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
defined('_JEXEC') || die('=;)');


class modRedesSociaisHelper{

public static function getConfigs(&$params)
  {
    $config['first'] = $params->get('first_item', 'twitter');
    $config['active'] = $config['first'];


    //configuracoes twitter
    $config['twitter_user']            = $params->get('twitter_user', '');
    $config['twitter_height']          = $params->get('twitter_height','350');
    $config['twitter_widget_id']       = $params->get('twitter_widget_id','');
    $config['twitter_text_link_error'] = $params->get('twitter_text_link_error','Microblog');
    $config['twitter_class']           = $params->get('twitter_class');
    if($config['twitter_user']=='' || $config['twitter_widget_id']=='')
        $config['twitter_show'] = false;
    else
    {
        $config['twitter_show'] = true;       
    }
    
    //configuracoes Facebook
    $config['facebook_page_url']        = str_replace( array('http://','www.facebook.com','/'), '', $params->get('facebook_page_url', ''));
    $config['facebook_width']           = $params->get('facebook_width','292');
    $config['facebook_height']          = $params->get('facebook_height','590');
    $config['facebook_colorscheme']     = $params->get('facebook_colorscheme','light');         
    $config['facebook_show_faces']      = $params->get('facebook_show_faces','false');
    $config['facebook_header']          = $params->get('facebook_header','true');
    $config['facebook_stream']          = $params->get('facebook_stream','true');
    $config['facebook_show_border']     = $params->get('facebook_show_border','true');
    $config['facebook_appId']           = $params->get('facebook_appId');
    $config['facebook_class']           = $params->get('facebook_class');
    if($config['facebook_page_url']=='')
        $config['facebook_show'] = false;
    else
    {
        $config['facebook_show'] = true;
    }

    return $config;
  }
}


?>
