<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 2.1
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

?>
<html>
<head>
<title><?php echo JText::_('LIGHT_HEADER');?></title>
</head>
<body>
<h1><?php echo JText::_('LIGHT_HEADER');?></h1>
<div
	style="margin: 2px; border: thin solid red; background-color: #ffffee;">
<p><b><?php echo JText::_('LIGHT_TEXT_ERROR');?></b></p>
<p style="color: red;"><?php echo $this->errormessage ?></p>
<p><b><?php echo JText::_('LIGHT_TEXT_ERRORPOST');?></b></p>
</div>