<?php
/**
 * @package     Joomlagovbr
 * @subpackage  mod_barradogoverno
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;
echo $teste;
if ($correcoes_css == 1 && $endereco_js != "") {
	$style = '<style type="text/css">'."\n";
	$style .= '#barra-brasil li { line-height:inherit; }'."\n";
	$style .= '</style>'."\n";
	$document->addCustomTag($style);
}
?>
<!-- barra do governo -->
<div id="barra-brasil">
	<div id="barra-brasil" style="background:#7F7F7F; height: 20px; padding:0 0 0 10px;display:block;"> 
	<ul id="menu-barra-temp" style="list-style:none;">
		<li style="display:inline; float:left;padding-right:10px; margin-right:10px; border-right:1px solid #EDEDED"><a href="http://brasil.gov.br" style="font-family:sans,sans-serif; text-decoration:none; color:white;">Portal do Governo Brasileiro</a></li> 
		<li><a style="font-family:sans,sans-serif; text-decoration:none; color:white;" href="http://epwg.governoeletronico.gov.br/barra/atualize.html">Atualize sua Barra de Governo</a></li>
	</ul>
	</div>
</div>
<?php if( $anexar_js == 1 && $endereco_js != ""): ?>
<script type="text/javascript">
document.onreadystatechange = function () {
     if (document.readyState == "complete") {
		scr = document.createElement('script');
		scr.type="text/javascript";
		scr.src="<?php echo $endereco_js; ?>";  
		document.body.appendChild(scr);
   }
 }
</script><noscript>A barra do Governo Federal só poderá ser visualizada se o javascript estiver ativado.</noscript>
<?php elseif( $anexar_js == 3 && $endereco_js != ""): ?>
	<script type="text/javascript" src="<?php echo $endereco_js; ?>"></script><noscript>A barra do Governo Federal só poderá ser visualizada se o javascript estiver ativado.</noscript>
<?php endif; ?>	
<?php if( $mensagem_ie_6 != ""): ?>
<!--[if lt IE 7]><br /><strong><?php echo $mensagem_ie_6; ?></strong><br /><![endif]-->
<?php endif; ?>
<!-- fim barra do governo -->