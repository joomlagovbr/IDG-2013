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
	<?php if (count($items)): ?>
	<ul class="compromissos-lista" <?php echo $style_altura_lista; ?>>
		<?php foreach ($items as $item): ?>
		<li>
			<?php if ($item->dia_todo == 1): ?>
			<div class="compromisso-horario-inicio">
				<i class="icon-fixed-width icon-time"><span class="hide"></span></i><span class="timestamp">Dia todo</span>
			</div>
			<?php else: ?>
			<div class="compromisso-horario-inicio">
				<i class="icon-fixed-width icon-time"><span class="hide">A partir de:</span></i><span class="timestamp"><?php echo $item->horario_inicio ?></span>
			</div>
			<?php endif ?>
			<a class="compromisso-link" href="<?php echo JURI::root() ?>index.php?option=com_agendadirigentes&view=autoridade&id=<?php echo $params->get('autoridade', 0); ?>&Itemid=<?php echo $params->get('itemid') ?>&dia=<?php echo $params->get('dia'); ?>">
				<?php echo $item->title ?>
			</a>
		</li>
		<?php endforeach ?>
	</ul>
	<?php else: ?>
	<ul class="compromissos-lista" <?php echo $style_altura_lista; ?>>
		<?php if($featured_compromissos==''): ?>
		<li class="sem-compromissos-oficiais">Sem compromissos oficiais.</li>
		<?php elseif($featured_compromissos==1): ?>
		<li class="sem-compromissos-oficiais">Sem compromissos em destaque.</li>
		<?php elseif($featured_compromissos==0): ?>
		<li class="sem-compromissos-oficiais">N&atilde;o h&aacute; itens dispon&iacute;veis de acordo com a consulta.</li>
		<?php endif; ?>
	</ul>		
	<?php endif ?>

</div>
<div class="footer">
<a class="link" href="<?php echo JURI::root() ?>index.php?option=com_agendadirigentes&view=autoridades&Itemid=<?php echo $params->get('itemid') ?>">Acesse todas as agendas</a>
</div>