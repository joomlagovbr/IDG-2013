<?php
/**
 * @package     
 * @subpackage  
 *
 * @copyright   
 * @license     
 */

defined('_JEXEC') or die;

class TmplK2Helper
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
	public static function getSearchTagLink( $tag, $link = '' )
	{
		if(empty($link))
			$link = 'index.php?ordering=newest&searchphrase=all&limit=20&areas[0]=contenttags&areas[1]=k2&Itemid=181&option=com_search&searchword=';
		
		$lang = JFactory::getLanguage();	
		$search_formated = urlencode(substr(trim($tag),0, $lang->getUpperLimitSearchWord()));
		return JRoute::_($link . $search_formated);		
	}
}
