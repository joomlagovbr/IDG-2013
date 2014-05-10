<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

JDEBUG ? define('AKEEBADEBUG', 1) : null;

// Check for PHP4
if(defined('PHP_VERSION')) {
	$version = PHP_VERSION;
} elseif(function_exists('phpversion')) {
	$version = phpversion();
} else {
	// No version info. I'll lie and hope for the best.
	$version = '5.0.0';
}

// Old PHP version detected. EJECT! EJECT! EJECT!
if(!version_compare($version, '5.3.0', '>='))
{
	return JError::raise(E_ERROR, 500, 'This version of PHP is not compatible with Akeeba Backup');
}

JLoader::import('joomla.application.component.model');

// Load F0F
include_once JPATH_SITE.'/libraries/f0f/include.php';
if (!defined('F0F_INCLUDED') || !class_exists('F0FForm', true))
{
	JError::raiseError ('500', 'Your Akeeba Backup installation is broken; please re-install. Alternatively, extract the installation archive and copy the fof directory inside your site\'s libraries directory.');
}

F0FDispatcher::getTmpInstance('com_akeeba')->dispatch();