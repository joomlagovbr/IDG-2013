<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

$link_css_ie8  = $params->get("link_css_ie8_2014", "");
$correcoes_ie8 = $params->get("correcoes_ie8_2014", "");
$mensagem_ie_6 = $params->get("mensagem_ie6_2014", "");
$anexar_js     = $params->get("anexar_js_2014", "");
$endereco_js   = $params->get("endereco_js_2014", "");
$document      = JFactory::getDocument();

if( $anexar_js == 2) {
	$script = '<script type="text/javascript" src="'.$endereco_js.'"></script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>';
	$document->addCustomTag($script);	
}

if ($correcoes_ie8 == 'hide') {
	$style = '<!--[if lt IE 9]><style> #barra-brasil { display:none !important } </style><![endif]-->';
	$document->addCustomTag($style);
}
else if($correcoes_ie8 == 'show_css' && $link_css_ie8 != '') {
	$link_css_ie8 = str_replace('{URL_SITE}/', JURI::root(), $link_css_ie8);	
	$style = '<!--[if lt IE 9]><link rel="stylesheet" href="'.$link_css_ie8.'" /><![endif]-->';
	$document->addCustomTag($style);
}

?>