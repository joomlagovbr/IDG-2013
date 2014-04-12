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
// load loader
require_once(dirname(__FILE__) . '/loader.php');

// load text
wfimport('admin.classes.text');
// load xml
wfimport('admin.classes.xml');
// load parameter
wfimport('admin.classes.parameter');
// load xml helper
wfimport('admin.helpers.xml');

?>
