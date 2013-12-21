<?php
defined('_JEXEC') or die;
//*
class TmplAllvideosHelper
{
	public static function removeCss($wanted = array())
	{
		if (count($wanted)==0) {
			return;
		}
		$doc = JFactory::getDocument();
		foreach($doc->_styleSheets as $k => $sheet)
		{
			foreach ($wanted as $word) {
				if(strpos($k, $word)!==false)
				{
					unset($doc->_styleSheets[$k]);					
				}
			}
		}
	}

	public static function addCss($path)
	{
		$doc = JFactory::getDocument();
		$doc->_styleSheets[$path] = array('mime'=>'text/css','media'=> NULL, 'attribs'=>array());		
	}

	public static function addJs($path)
	{
		$doc = JFactory::getDocument();
		$script = '<script type="text/javascript" src="'.$path.'"></script><noscript>Essa página depende do carramento de javascript.</noscript>';
		$doc->addCustomTag($script);	
	}

	public static function removeJs($wanted = array())
	{
		if (count($wanted)==0) {
			return;
		}
		$doc = JFactory::getDocument();
		foreach($doc->_scripts as $k => $script)
		{
			foreach ($wanted as $word) {
				if(strpos($k, $word)!==false)
				{
					unset($doc->_scripts[$k]);					
				}
			}
		}
		reset($wanted);
		foreach ($doc->_script as $k => $script) {
			foreach ($wanted as $word) {
				if(strpos($script, $word)!==false)
				{
					unset($doc->_script[$k]);
				}
			}
		}
	}
	public static function removeCustom($wanted = array())
	{
		if (count($wanted)==0) {
			return;
		}
		$doc = JFactory::getDocument();
		foreach($doc->_custom as $k => $v)
		{
			foreach ($wanted as $word) {
				if(strpos($v, $word)!==false)
				{
					unset($doc->_custom[$k]);					
				}
			}
		}
	}
}
?>