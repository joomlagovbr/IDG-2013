<?php

/**
 * @package   	JCE
 * @copyright 	Copyright (c) 2009-2013 Ryan Demmer. All rights reserved.
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

defined('_JEXEC') or die('RESTRICTED');

// load constants

require_once(dirname(__FILE__) . '/constants.php');

// low level error handler

//require_once(WF_ADMINISTRATOR . '/classes/error.php');

// load loader

require_once(dirname(__FILE__) . '/loader.php');
// load text
require_once(WF_ADMINISTRATOR. '/classes/text.php');
// load xml
require_once(WF_ADMINISTRATOR . '/classes/xml.php');
// load parameter
require_once(WF_ADMINISTRATOR . '/classes/parameter.php');

// load xml helper
require_once(WF_ADMINISTRATOR . '/helpers/xml.php');

?>
