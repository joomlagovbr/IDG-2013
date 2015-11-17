<?php
/**
 * @package     Joomlagovbr
 * @subpackage  mod_barradogoverno
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;

class ModBarraDoGovernoHelper
{
	public static function getExternalFileContent( $params = NULL, $endereco = '')
	{
		if (is_null($params)) {
			return false;
		}
		$ch = curl_init();

		if(is_null($ch))
			return false;

		$opts = array();
		if($params->get('script_default_curl_proxy','')!='')
		{
			$opts[CURLOPT_PROXY] = $params->get('script_default_curl_proxy',0);			
		}
		
		if($endereco=='')
		{
			$endereco =	$params->get('endereco_js_2014', '');	
		}

		if($endereco=='')
		{
			return false;
		}
		else if(substr($endereco, 0, 1)=='/')
		{
			$endereco = 'http:' . $endereco;
		}

		$opts[CURLOPT_CONNECTTIMEOUT] = 10;
		$opts[CURLOPT_RETURNTRANSFER] = true;
		$opts[CURLOPT_TIMEOUT] = 60;
		$opts[CURLOPT_USERAGENT] = 'CACHE_BARRA_GOVERNO_PORTAL_PADRAO_JOOMLA';
		$opts[CURLOPT_URL] = $endereco;

		curl_setopt_array($ch, $opts);
		$result = curl_exec($ch);

		return $result;
	}

	public static function recreateCacheFolder( $joomla_caching = 0, $params )
	{
		if(!class_exists('JFile')) jimport('joomla.filesystem.file');
		if(!class_exists('JFolder')) jimport('joomla.filesystem.folder');
		if(!is_object($params)) return false;

		$app = JFactory::getApplication();
		$cache_folder = self::getCacheFolder($params);

		if(!JFolder::exists($cache_folder))
			JFolder::create($cache_folder);
		
		if($joomla_caching)
			return $cache_folder;
		
		//else
		$cached_time = intval($params->get('script_default_cached_time', 0));
		$time = time();
		$cached_time_limit = intval($time) + intval($params->get('cache_time', 0));
		if($cached_time==0 || $time > $cached_time )
		{
			$nome_barrajs = self::getCacheFileName( $params );
			@unlink($cache_folder .'/' .$nome_barrajs);
			self::setCachedTime( $cached_time_limit, $cached_time );
		}
		
		return $cache_folder;
	}

	public static function getCacheFolder( $params )
	{
		$app = JFactory::getApplication();
		$cache_folder = $app->get('cache_path', JPATH_ROOT .'/' .'cache');
		$cache_folder .= '/mod_barradogoverno';
		return $cache_folder;
	}

	public static function getCacheFileName( $params )
	{
		$endereco_js  = $params->get("endereco_js_2014", "");
		$nome_barrajs = substr($endereco_js, strrpos($endereco_js, '/')+1);
		return $nome_barrajs;
	}

	public static function existsCachedFile( $params )
	{
		if(!class_exists('JFile')) jimport('joomla.filesystem.file');		
		$file = self::getCacheFolder( $params ) .'/'. self::getCacheFileName( $params );
		return JFile::exists($file);
	} 

	public static function getCacheURLfolder()
	{
		$app = JFactory::getApplication();
		$cache_folder = $app->get('cache_path', JPATH_ROOT .'/' .'cache');
		$cache_url = str_replace($cache_folder, '', $cache_folder);
		$cache_url = JURI::root() .'cache/mod_barradogoverno'. $cache_url;
		return $cache_url;
	}

	public static function putContentIntoFile($content, $file)
	{
		if (empty($content)) {
			return false;
		}

		if (empty($file)) {
			return false;
		}

		@$handle = fopen($file, 'w');
		@$return = fwrite($handle, $content);
		@fclose($handle);

		return $return;
	}

	public static function setCachedTime( $new_cached_time = 0, $old_cached_time = 0 )
	{
		if($new_cached_time==0) return false;

		$db = JFactory::getDBO();
		$query = $db->getQuery(true); 
		$query->select('id');
		$query->from('#__modules');
		$query->where('module = '.$db->Quote('mod_barradogoverno'));
		$db->setQuery($query);
		$ids = $db->loadColumn();

		$query = $db->getQuery(true);
		$old = '"script_default_cached_time":"'.$old_cached_time.'"';
		$new = '"script_default_cached_time":"'.$new_cached_time.'"';
		$ids = implode(',', $ids);

		$fields = array(
			$db->quoteName('params') . ' = REPLACE( ' . $db->quoteName('params') .', ' .$db->Quote($old) .', '. $db->Quote($new) .')'
		);
		$conditions = array(
			 $db->quoteName('id') . ' IN (' . $ids . ')'
		);
		$query->update($db->quoteName('#__modules'))->set($fields)->where($conditions);
		
		$db->setQuery($query); 
		return $db->query();
	}
}
