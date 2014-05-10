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
	return JError::raise(E_ERROR, 500, 'PHP versions 4.x, 5.0, 5.1 and 5.2 are no longer supported by Akeeba Backup.<br/><br/>The version of PHP used on your site is obsolete and contains known security vulenrabilities. Moreover, it is missing features required by Akeeba Backup to work properly or at all. Please ask your host to upgrade your server to the latest PHP 5.3 release. Thank you!');
}

JLoader::import('joomla.application.component.model');

// Load F0F
include_once JPATH_SITE.'/libraries/f0f/include.php';
if (!defined('F0F_INCLUDED') || !class_exists('F0FForm', true))
{
	JError::raiseError ('500', 'Your Akeeba Backup installation is broken; please re-install. Alternatively, extract the installation archive and copy the fof directory inside your site\'s libraries directory.');
}

F0FDispatcher::getTmpInstance('com_akeeba')->dispatch();