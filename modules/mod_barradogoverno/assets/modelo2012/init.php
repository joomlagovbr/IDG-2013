<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

$opcoes_barra             = $params->get("opcoes_barra_2012", "");
$cor                      = $params->get("cor_2012", "");
$acesso_a_informacao      = $params->get("acesso_a_informacao_2012", "");
$largura_barra            = $params->get("largura_barra_2012", "");
$alinhamento_barra        = $params->get("alinhamento_barra_2012", "");
$link_acesso_a_informacao = $params->get("link_acesso_a_informacao_2012", "");
$link_portal_brasil       = $params->get("link_portal_brasil_2012", "");
$target_links             = $params->get("target_links_2012", "");
$anexar_css               = $params->get("anexar_css_2012", "");

if($target_links != "none")
	$target = 'target="'.$target_links.'"';
else
	$target = "";

$document =& JFactory::getDocument();
if( $anexar_css == 1)
{
	$document->addStyleSheet( JURI::root() . 'modules/mod_barradogoverno/assets/modelo2012/css/barradogoverno.css' );
	if( $largura_barra != 970 )
	{
		$style = '#barra-brasil-container { width:'.$largura_barra.'px }';
		$document =& JFactory::getDocument();
		$document->addStyleDeclaration($style);
	}
}
?>