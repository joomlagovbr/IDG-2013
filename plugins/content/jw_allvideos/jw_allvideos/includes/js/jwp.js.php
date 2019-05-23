<?php
/**
 * @version    4.8.0
 * @package    AllVideos (plugin)
 * @author     JoomlaWorks - http://www.joomlaworks.net
 * @copyright  Copyright (c) 2006 - 2017 JoomlaWorks Ltd. All rights reserved.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

$expires = 24; // Time in hours to cache this file

ob_start("ob_gzhandler");

// Includes
echo "/* behaviour.js */\n";
include(dirname(__FILE__)."/behaviour.js");
echo "/* silverlight.js */\n";
include(dirname(__FILE__)."/wmvplayer/silverlight.js");
echo "\n\n";
echo "/* wmvplayer.js */\n";
include(dirname(__FILE__)."/wmvplayer/wmvplayer.js");
echo "\n\n";
echo "/* ac_quicktime.js */\n";
include(dirname(__FILE__)."/quicktimeplayer/ac_quicktime.js");
echo "\n\n";

$bufferSize = ob_get_length(); // Required to close the connection

header("Content-type: text/javascript; charset=utf-8");
header("Cache-Control: max-age=".($expires*3600));
header("Expires: ".gmdate("D, d M Y H:i:s", time() + ($expires*3600))." GMT");
header("Content-Length: $bufferSize");
header("Connection: close");

ob_end_flush();
ob_flush();
flush();
