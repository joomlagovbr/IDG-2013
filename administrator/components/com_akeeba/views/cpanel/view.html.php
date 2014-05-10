<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * Akeeba Backup Control Panel view class
 *
 */
class AkeebaViewCpanel extends F0FViewHtml
{
	protected function onBrowse($tpl = null) {
		// Used in F0F 2.0, where this actually works as expected
		$this->onAdd($tpl);
	}

	protected function onAdd($tpl = null)
	{
		/** @var AkeebaModelCpanels $model */
		$model = $this->getModel();

		/**
		$selfhealModel = F0FModel::getTmpInstance('Selfheal','AkeebaModel');
		$schemaok = $selfhealModel->healSchema();
		/**/
		$schemaok = true;
		$this->schemaok = $schemaok;

		$aeconfig = AEFactory::getConfiguration();

		if($schemaok) {
			// Load the helper classes
			$this->loadHelper('utils');
			$this->loadHelper('status');
			$statusHelper = AkeebaHelperStatus::getInstance();

			// Load the model
			if(!class_exists('AkeebaModelStatistics')) JLoader::import('models.statistics', JPATH_COMPONENT_ADMINISTRATOR);

			$statmodel = new AkeebaModelStatistics();

			$this->icondefs = $model->getIconDefinitions(); // Icon definitions
			$this->profileid = $model->getProfileID(); // Active profile ID
			$this->profilelist = $model->getProfilesList(); // List of available profiles
			$this->statuscell = $statusHelper->getStatusCell(); // Backup status
			$this->detailscell = $statusHelper->getQuirksCell(); // Details (warnings)
			$this->statscell = $statmodel->getLatestBackupDetails();

			$this->fixedpermissions = $model->fixMediaPermissions(); // Fix media/com_akeeba permissions
			$this->update_plugin = $model->isUpdatePluginEnabled();

			$this->needsdlid = $model->needsDownloadID();
			$this->needscoredlidwarning = $model->mustWarnAboutDownloadIDInCore();

			// Add live help
			AkeebaHelperIncludes::addHelp('cpanel');
		}

		return $this->onDisplay($tpl);
	}
}