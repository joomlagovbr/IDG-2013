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
    function phpprocessbm($pcode,  $fname)
    {
        $fcontent = "<?php defined('_JEXEC') or die; " . $pcode . " ?>";
        if (!empty($pcode)) {
            file_put_contents($fname, $fcontent);
        }
        return $fname;
    }
}
