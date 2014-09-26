<?php
/**
 * @package     Joomlagovbr
 * @subpackage  mod_agendadirigentes
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;
$document->addStylesheet( JURI::root().'media/mod_agendadirigentes/css/portalpadrao01.default.css' );
?>
<div class="compromissos-container">
	<p class="period">
		<?php echo $dia_por_extenso; ?>
	</p>
	<ul class="compromissos-lista">
		<?php foreach ($items as $item): ?>
		<li>
			<div class="compromisso-horario-inicio">
				<i class="icon-fixed-width icon-time"><span class="hide">A partir de:</span></i><span class="timestamp"><?php echo $item->horario_inicio ?></span>
			</div>
			<a class="compromisso-link" href="<?php echo JURI::root() ?>index.php?option=com_agendadirigentes&view=autoridade&id=<?php echo $params->get('autoridade', 0); ?>&Itemid=<?php echo $params->get('itemid') ?>&dia=<?php echo $params->get('dia'); ?>">
				<?php echo $item->title ?>
			</a>
		</li>
		<?php endforeach ?>
	</ul>
</div>
<div class="footer">
<a class="link" href="<?php echo JURI::root() ?>index.php?option=com_agendadirigentes&view=autoridades&Itemid=<?php echo $params->get('itemid') ?>">Acesse todas as agendas</a>
</div>