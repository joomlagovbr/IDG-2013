<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

require JPATH_SITE . '/modules/mod_barradogoverno/helper.php';
$link_css_ie8  = $params->get("link_css_ie8_2014", "");
$correcoes_ie8 = $params->get("correcoes_ie8_2014", "");
$mensagem_ie_6 = $params->get("mensagem_ie6_2014", "");
$anexar_js     = $params->get("anexar_js_2014", "");
$endereco_js   = $params->get("endereco_js_2014", "");
$correcoes_css = $params->get("correcoes_css_2014", "");
$document      = JFactory::getDocument();
$app           = JFactory::getApplication();

if( $anexar_js == 2) {
	$script = '<script type="text/javascript" src="'.$endereco_js.'"></script><noscript>A barra do Governo Federal só poderá ser visualizada se o javascript estiver ativado.</noscript>';
	$document->addCustomTag($script);	
}

if ($correcoes_ie8 == 'hide') {
	$style = '<!--[if lt IE 9]><style type="text/css"> #barra-brasil { display:none !important } </style><![endif]-->';
	$document->addCustomTag($style);
}
else if($correcoes_ie8 == 'show_css' && $link_css_ie8 != '') {
	$link_css_ie8 = str_replace('{URL_SITE}/', JURI::root(), $link_css_ie8);	
	$style = '<!--[if lt IE 9]><link rel="stylesheet" href="'.$link_css_ie8.'" type="text/css" /><![endif]-->';
	$document->addCustomTag($style);
}

if( $params->get("script_default_cache", 0) == 1)
{
	$cache_folder = ModBarraDoGovernoHelper::recreateCacheFolder( $app->getCfg('caching', 0), $params );
	$nome_barrajs = ModBarraDoGovernoHelper::getCacheFileName( $params );
	
	if( !ModBarraDoGovernoHelper::existsCachedFile( $params ) )
	{
		$barrajs = ModBarraDoGovernoHelper::getExternalFileContent( $params, $endereco_js );
		if( !empty($barrajs) )
		{
			$writing = ModBarraDoGovernoHelper::putContentIntoFile($barrajs, $cache_folder .'/' .$nome_barrajs );
			
			if($writing)
			{
				$cache_folder_url = ModBarraDoGovernoHelper::getCacheURLfolder($cache_folder);
				$endereco_js = $cache_folder_url .'/'. $nome_barrajs;
			}
			else
			{
				echo '<!-- Barra do governo: Impossível escrever arquivo externo para cache. -->';
			}	
		}
		else
		{
			echo '<!-- Barra do governo: Impossível carregar arquivo externo para cache. -->';
		}		
	}
	else
	{
		$cache_folder_url = ModBarraDoGovernoHelper::getCacheURLfolder($cache_folder);
		$endereco_js = $cache_folder_url .'/'. $nome_barrajs;
	}
}

?>