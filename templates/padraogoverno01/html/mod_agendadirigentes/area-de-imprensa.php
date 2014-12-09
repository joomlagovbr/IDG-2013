<?php
/**
 * @package     Joomlagovbr
 * @subpackage  mod_agendadirigentes
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;
$document->addStylesheet( JURI::root().'templates/padraogoverno01/html/mod_agendadirigentes/css/area-de-imprensa.css' );

$dia = ModAgendaDirigentesHelper::getDia($params);
$dia_semana = new JDate( $dia );
$dia_semana = $dia_semana->format( JText::_('l') );

?>
<img class="area-de-imprensa-brasao-republica" src="images/conteudo/imagem-brasao-da-republica.jpg" alt="Brasão da República" width="91" height="97" border="0" />
<p class="area-de-imprensa-cabecalho">
	<strong>nome do órgão</strong>
	<br />
	<strong class="area-de-imprensa-cabecalho-title"><?php echo $module->title; ?></strong>
</p>
<p class="area-de-imprensa-cabecalho">
	<em><?php echo $dia_semana ?></em>
	<br />
	<em><?php echo $dia_por_extenso; ?></em>
</p>
<div class="area-de-imprensa-compromissos-container">
	<?php if (count($items)): ?>
	<ul class="compromissos-lista" <?php echo $style_altura_lista; ?>>
		<?php foreach ($items as $item): ?>
		<li>
			<?php if ($item->dia_todo == 1): ?>
			<div class="compromisso-horario-inicio">
				<i class="icon-fixed-width icon-time"><span class="hide"></span></i><span class="timestamp"><?php echo JText::_('MOD_AGENDADIRIGENTES_ALL_DAY'); ?></span>
			</div>
			<?php else: ?>
			<div class="compromisso-horario-inicio">
				<i class="icon-fixed-width icon-time"><span class="hide"><?php echo JText::_('MOD_AGENDADIRIGENTES_FROM'); ?></span></i><span class="timestamp"><?php echo $item->horario_inicio ?></span>
			</div>
			<?php endif ?>
			<a class="compromisso-link" href="<?php echo JRoute::_("index.php?option=com_agendadirigentes&view=autoridade&id=".$params->get('autoridade', 0)."&Itemid=".$params->get('itemid')."&dia=".$params->get('dia') ); ?>">
				<?php echo $item->title ?>
			</a>
		</li>
		<?php endforeach ?>
	</ul>
	<?php else: ?>
	<ul class="compromissos-lista" <?php echo $style_altura_lista; ?>>
		<?php if($featured_compromissos==''): ?>
		<li class="sem-compromissos-oficiais"><?php echo JText::_('MOD_AGENDADIRIGENTES_NO_ITEMS'); ?></li>
		<?php elseif($featured_compromissos==1): ?>
		<li class="sem-compromissos-oficiais"><?php echo JText::_('MOD_AGENDADIRIGENTES_NO_FEATURED_ITEMS'); ?></li>
		<?php elseif($featured_compromissos==0): ?>
		<li class="sem-compromissos-oficiais"><?php echo JText::_('MOD_AGENDADIRIGENTES_NO_UNFEATURED_ITEMS'); ?></li>
		<?php endif; ?>
	</ul>		
	<?php endif ?>

</div>
