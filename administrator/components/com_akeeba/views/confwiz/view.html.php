<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * Akeeba Backup Configuration Wizard view class
 *
 */
class AkeebaViewConfwiz extends F0FViewHtml
{
	public function onAdd($tpl = null)
	{
		$aeconfig = AEFactory::getConfiguration();

		// Load the Configuration Wizard Javascript file
		AkeebaStrapper::addJSfile('media://com_akeeba/js/confwiz.js');

		// Add live help
		AkeebaHelperIncludes::addHelp('confwiz');

		$this->setLayout('wizard');

		return true;
	}
}