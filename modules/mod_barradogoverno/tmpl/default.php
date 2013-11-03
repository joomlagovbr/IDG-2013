<?php
/**
 * @package     Joomlagovbr
 * @subpackage  mod_barradogoverno
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;
?>
<!-- barra do governo -->
<div id="barra-brasil">
    <a href="http://brasil.gov.br" style="background:#7F7F7F; height: 20px; padding:4px 0 4px 10px; display: block; font-family:sans,sans-serif; text-decoration:none; color:white; ">Portal do Governo Brasileiro</a>
</div>
<?php if( $anexar_js == 1 && $endereco_js != ""): ?>
<script src="<?php echo $endereco_js; ?>" type="text/javascript"></script><noscript>A barra do Governo Federal só poderá ser visualizada se o javascript estiver ativado.</noscript>
<?php endif; ?>	
<?php if( $mensagem_ie_6 != ""): ?>
<!--[if lt IE 7]><br /><strong><?php echo $mensagem_ie_6; ?></strong><br /><![endif]-->
<?php endif; ?>
<!-- fim barra do governo -->