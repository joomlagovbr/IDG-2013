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

//Code to retrieve article
$article="";

// 1.  title retrieval
$db=& JFactory::getDBO();
if (($contenttitleuse==1)&&($itemid !="a")){
    $db->setQuery('SELECT * FROM `#__content` WHERE `id`= '.$itemid.' ORDER BY `id`');
    $contents = $db->loadObjectList();
    if(isset($contents[0]) ){$article ='<h4 id="title_'.$modno_bm.'" style="overflow:hidden">'.($contents[0]->title).'</h4>';}
}
//2 . content retrieval
if (($contentuse==1)&&($itemid !="a")) {
    $db->setQuery('SELECT * FROM `#__content` WHERE `id`= '.$itemid.' ORDER BY `id`');
    $contents = $db->loadObjectList();
    if(isset($contents[0]) ){$article.='<div>'.($contents[0]->introtext ).($contents[0]->fulltext ).'</div>';}
}
