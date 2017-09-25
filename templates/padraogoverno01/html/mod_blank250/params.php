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

//Collect Parameters

$url = JURI::base();

$mode=$params->get('mode');
$codeeditor=$params->get('codeeditor');
$phpcode=$params->get('phpcode');
$phpuse=$params->get('phpuse');
$script=$params->get('script');
$scriptuse=$params->get('scriptuse');
$content1=$params->get('content1');
$content2=$params->get('content2');
$content3=$params->get('content3');
$graphics=$params->get('graphics');
$paddingleft=$params->get('paddingleft');
$paddingright=$params->get('paddingright');
$paddingtop=$params->get('paddingtop');
$paddingbottom=$params->get('paddingbottom');
$margintop=$params->get('margin-top');
$marginbottom=$params->get('margin-bottom');
$marginleftmodule=$params->get('margin-leftmodule');
$colour1='#'.$params->get('colour1');
$colour2='#'.$params->get('colour2');
$trans1=$params->get('trans1');
$trans2=$params->get('trans2');
$bordercol='#'.$params->get('bordercol');
$bordersz=$params->get('bordersz');
$shadcol='#'.$params->get('shadcol');
$shadsz=$params->get('shadsz');
if($trans1==2){$colour1="transparent";}
if($trans2==2){$colour2="transparent";}
$width=$params->get('width');
$widthunit=$params->get('widthunit');
$itemid=$params->get('itemid');
$contenttitleuse=$params->get('contenttitleuse');
$contentuse=$params->get('contentuse');
$textareause=$params->get('textareause');
$reverse=$params->get('reverse');
$bgpattern = $params->get('bgpattern');
$modno_bm = $params->get('modno_bm');
if ($modno_bm==0){$modno_bm="BM".($module->id);}



$css="";

$fileroot=str_replace('index.php','',$_SERVER["SCRIPT_FILENAME"]);
$tmp_file =$fileroot.'modules/mod_blank250/tmpl/temp'.$modno_bm.'.php';