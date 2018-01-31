<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_chamadas
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport('cms.version');
require_once( JPATH_SITE . '/components/com_content/helpers/route.php' );
/**
 * Helper para mod_chamadas
 *
 * @package     Joomla.Site
 * @subpackage  mod_chamadas
 */
class ModChamadasHelper
{
	public function getChamadas($params)
	{
		//Carrega Modelo
		$modelo = $params->get('modelo');

		//Chama modelo que deverá ser executado
		require_once __DIR__ . '/modelos/'.$modelo.'/'.$modelo.'.php';
		$nomeclass = 'Modelo'.ucfirst($modelo);

		/**
			#Os campos das tabelas deverão ser nomeados de forma padrão.
			#Esses parametros deverão ser utilizados na tmpl.
			#O resultado deverá retornar objeto (loadObjectList() - para consulta bd).

			#Os dois primeiros campos são fundamentais para a criação do link,
			#para o padrão com_content.
				id 			-> id da tabela #__content
				catid 		-> catid da tabela #__content

			#Campos que farão parte das chamadas do tmpl.
				image		-> Imagem do artigo (matéria)
				title		-> Título do artigo (matéria)
				alias		-> Apelido que será utilizado na URL
				titlecat	-> Título da categoria (#__categories)
				introtext	-> Texto introdutório do artigo (matéria)
				chapeu		-> Texto complementar, subtítulo

		**/

		$listamodelo = new $nomeclass;

 		//Executa a função getListaModelo (padrão).
		$lista = $listamodelo->getListaModelo($params);

		return $lista;
	}

	public static function getIntroLimiteCaracteres($intro, $params)
	{
 		if ($params->get('limitar_caractere')):

			$tam_texto = strlen($intro);

			if($tam_texto > $params->get('limite_caractere')){
				//Busca o total de caractere até a última palavra antes do limite.
				$limite_palavra = strrpos(substr(strip_tags($intro), 0, $params->get('limite_caractere')), " ");
				$intro = trim(substr(strip_tags($intro), 0, $limite_palavra)).'...';
			}

			return $intro;

		else:

			return strip_tags($intro, '<b><i><strong><u><b>');

		endif;
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
			if(ModChamadasHelper::getjVersion() > 2)
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
			if(ModChamadasHelper::getjVersion() > 2)
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