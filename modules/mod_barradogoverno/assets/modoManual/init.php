<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

$head     = $params->get("head_manual", "");
$html     = $params->get("html_manual", "");
$document = JFactory::getDocument();

if($head != '')
	$document->addCustomTag($head);
?>