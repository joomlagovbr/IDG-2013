<?php
/**
 * @package     Joomlagovbr
 * @subpackage  mod_videosdestaque
 *
 * @copyright   Copyright (C) 2015 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;

class ModVideosDestaqueHelper
{
	public static function getItems( &$params )
	{
		return json_decode($params->get('list_videos'));
	}

	public static function showPlayer( $url, $qtd_itens )
	{
		$medias = array();
		$medias[0] = array();
		$medias[0]['regex'] = '/youtube\.com\//';
		$medias[0]['type'] = 'youtube';

		$medias[1] = array();
		$medias[1]['regex'] = '/http\:\/\/centraldemidia\.mec\.gov\.br\//';
		$medias[1]['type'] = 'centraldemidia';

		for ($i=0, $medias_limit = count($medias); $i < $medias_limit; $i++) { 
			preg_match($medias[$i]['regex'], $url, $matches);
			if(count($matches))
			{
				$function = $medias[$i]['type'] . '_player';
				self::$function( $url, $qtd_itens );
				break;
			}
		}
	}

	protected static function youtube_player( $url, $qtd_itens )
	{
		$posVideoId = strpos($url, '?v=');
		$videoId = substr($url, $posVideoId + strlen('?v='));

		if(strpos($videoId, '&') !== false)
		{
			$posEndVideoId = strpos($videoId, '&');	
			$videoId = substr($videoId, $posEndVideoId);		
		}

		$html = '<iframe width="100%" height="auto" frameborder="0" allowfullscreen="" src="//www.youtube.com/embed/'. $videoId .'?showinfo=0"></iframe>';
		echo $html;
	}

	protected static function centraldemidia_player( $url, $qtd_itens )
	{
		$posVideoId = strpos($url, '&id=');
		if($posVideoId===false)
			$posVideoId = strpos($url, '&amp;id=');

		$videoId = substr($url, $posVideoId + strlen('&id='));
		if(strpos($videoId, ':') !== false)
		{
			$posEndVideoId = strpos($videoId, ':');	
			$videoId = substr($videoId, 0, $posEndVideoId);		
		}

		if($qtd_itens>=3)
		{
			$width = '235';
			$height = '150';
		}
		else if ($qtd_itens==2) {
			$width = '235';
			$height = '150';
		}
		else if ($qtd_itens==1) {
			$width = '470';
			$height = '300';
		}

		$url = 'http://centraldemidia.mec.gov.br/index.php?option=com_hwdmediashare&amp;task=get.embed&amp;id='.$videoId.'&amp;width='.$width.'&amp;height='.$height.'&amp;Itemid=444';
		$html = '<iframe width="100%" height="auto" src="'.$url.'" frameborder="0" scrolling="no"></iframe>';
		echo $html;
	}

	public static function getLink($params, $fields = array('simple', 'menu', 'article'), $content_item = false, $isJoomlaArticle = true )
	{
		$simple  = $fields[0];
		$menu    = $fields[1];
		$article = $fields[2];
		$link    = '';

		if( $params->get($simple, '' ) != '' )
		{
			$link = str_ireplace('{SITE}/', JURI::root(), $params->get($simple ) );
		}
		elseif( $params->get($menu, '' ) != '' )
		{
			$application = JFactory::getApplication();
			$cms_menu = $application->getMenu();
			$menu_item = $cms_menu->getItem( $params->get($menu) );
			$link = JRoute::_($menu_item->link.'&Itemid='.$menu_item->id);
		}
		elseif( $params->get($article, '' ) != ''  )
		{
			if(self::getjVersion() > 2)
			{
				$link = JRoute::_(  'index.php?option=com_content&view=article&id='. $params->get($article, '') );
			}
			else
			{
				$link = JRoute::_(ContentHelperRoute::getArticleRoute( $params->get($article, '')));
			}
		}
		elseif($content_item && $isJoomlaArticle )
		{
			if(self::getjVersion() > 2)
			{
				$link = JRoute::_(  'index.php?option=com_content&view=article&id='.$content_item->id );
			}
			else
			{
				$link = JRoute::_(ContentHelperRoute::getArticleRoute( $content_item->id, $content_item->catid ));
			}

		}
		elseif($content_item)
		{
			if(@isset($content_item->link))
				$link = $content_item->link;
		}

		return $link;
	}

	public static function getjVersion()
	{
		$versao = new JVersion;
		$versaoint = intval($versao->RELEASE);
		return $versaoint;
	}
}
