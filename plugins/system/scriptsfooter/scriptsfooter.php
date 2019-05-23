<?php


// no direct access
defined('_JEXEC') or die;


class plgSystemScriptsfooter extends JPlugin
{


	function onAfterInitialise()
	{
		
	}

	function onBeforeRender()
	{
		$document =& JFactory::getDocument();
		// echo "<pre>";
		 // var_dump();
		// $document->_scripts = null;
	}
}
