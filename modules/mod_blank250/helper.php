<?php
/**
 *
 *
 * @package   mod_blank250
 * copyright Blackdale.com/Bob Galway
 * @license GPL3
 */

// no direct access
defined('_JEXEC') or die;
class modblank250Helper
{
function phpprocessbm($pcode,$modno,$fname){
		$fcontent="<?php defined('_JEXEC') or die; ".$pcode." ?>";
		if(file_get_contents($fname)!==$fcontent){
		  file_put_contents($fname,$fcontent );
        }
    return $fname;}
}
