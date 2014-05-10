<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 1.3
 */
defined('_JEXEC') or die();

$data = $this->item->getData();
if(substr($data['configuration'], 0, 12) == '###AES128###') {
	// Load the server key file if necessary
	JLoader::import('joomla.filesystem.file');
	if(!defined('AKEEBA_SERVERKEY')) {
		$filename = JPATH_COMPONENT_ADMINISTRATOR.'/akeeba/serverkey.php';
		include_once $filename;
	}
	$key = AEUtilSecuresettings::getKey();

	$data['configuration'] = AEUtilSecuresettings::decryptSettings($data['configuration'], $key);
}

$defaultName = $this->input->get('view', 'joomla', 'cmd');
$filename = $this->input->get('basename', $defaultName, 'cmd');
$document = JFactory::getDocument();
$document->setName($filename);

echo json_encode($data);