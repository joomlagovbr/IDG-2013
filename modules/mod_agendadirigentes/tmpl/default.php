<?php
/**
 * @package     Joomlagovbr
 * @subpackage  mod_agendadirigentes
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;
?>
<p class="period">
	<?php echo $dia_por_extenso; ?>
</p>
<ul class="compromissos-list">
	<?php foreach ($items as $item): ?>
	<li>
		<div class="timestamp-cell">
			<span class="timestamp"><?php echo $item->horario_inicio ?></span>
		</div>
		<a class="title-item" href="#">
			<?php echo $item->title ?>
		</a>
	</li>
	<?php endforeach ?>
</ul>