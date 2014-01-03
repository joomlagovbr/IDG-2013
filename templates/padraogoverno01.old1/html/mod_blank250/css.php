<?php
/**
 *
 *
 * @package   mod_blank250
 * copyright Blackdale/Bob Galway
 * @license GPL3
 */

// no direct access
defined('_JEXEC') or die;
$css='
#inner' . $modno_bm . '{
padding:5px;
background:'.$colour1.' url('.$url.'modules/mod_blank250/tmpl/images/backgrounds/'.$bgpattern.'.png);
border-radius:5px;
border:'.$bordersz.'px solid '.$bordercol.';
box-shadow:0 0 '.$shadsz.'px '.$shadcol.';
}

#holder' . $modno_bm . ' {
padding:'.$shadsz.'px;
margin:0;
border:0;
}';

$css.='
#blank'.$modno_bm.'{';
if (!empty($margintop)){$css.='margin-top:'.$margintop.'px;';}
if (!empty($marginbottom)){$css.='margin-bottom:'.$marginbottom.'px;';}
if (!empty($marginleftmodule)){$css.='margin-left:'.$marginleftmodule.'px;';}
$css.='overflow:hidden;';
if (!empty($paddingleft)){$css.='padding-left:'.$paddingleft.'px;';}
if (!empty($paddingright)){$css.='padding-right:'.$paddingright.'px;';}
if (!empty($paddingtop)){$css.='padding-top:'.$paddingtop.'px;';}
if (!empty($paddingbottom)){$css.='padding-bottom:'.$paddingbottom.'px;';}
$css.='width:'.$width.$widthunit.';
background:'.$colour2;
$css .= '}';

$doc->addStyleDeclaration($css,'text/css');

