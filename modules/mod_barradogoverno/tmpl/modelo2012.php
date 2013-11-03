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
<div id="barra-brasil" <?php if($cor != "" || $alinhamento_barra != ""): ?>class="<?php echo $cor; ?> <?php echo $alinhamento_barra; ?>"<?php endif; ?>>
	<div id="barra-brasil-container">
	 	<a id="barra-brasil-marca-link" title="<?php echo $link_portal_brasil; ?>" <?php echo $target ?> href="<?php echo $link_portal_brasil; ?>">Brasil &ndash; Governo Federal</a>
		<?php if($acesso_a_informacao == 1): ?>
			<span id="marca-brasil-separador">|</span>
			<a id="barra-brasil-acesso-a-informacao-link" title="<?php echo $link_acesso_a_informacao; ?>" <?php echo $target ?> href="<?php echo $link_acesso_a_informacao; ?>">Acesso &agrave; informa&ccedil;&atilde;o</a>		
		<?php endif; ?>
	</div>
</div>