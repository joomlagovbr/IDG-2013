<?php
/**
* @package PortalPadrao
* @subpackage com_agendadirigentes
*
* @copyright Copyright (C) 2005 - 2014 Joomla Calango. All rights reserved.
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/
 
// impedir acesso direto ao arquivo
defined('_JEXEC') or die;
$this->document->addStylesheet( JURI::root().'media/com_agendadirigentes/css/frontend.css' );

?>
<div class="item-page<?php //echo $this->pageclass_sfx?> row-fluid autoridade-page">
	<div class="span8">
		<h1 class="secondaryHeading">
			<?php echo $this->page_heading; ?>
		</h1>
		<?php if (!empty($this->sharing)): ?>
		<div class="autoridade-sharing-options">
			<?php echo $this->sharing; ?>
		</div>			
		<?php endif ?>

		<div class="autoridade-header">
			<img class="brasao-republica" src="<?php echo JURI::root() ?>media/com_agendadirigentes/images/brasao.png" alt="Brasão da República" width="64" height="63" />
			<?php if (!empty($this->nome_orgao)): ?>
			<strong class="autoridade-orgao">
				<?php echo $this->nome_orgao; ?>
			</strong>
			<?php endif; ?>				
			<strong class="autoridade-nome-e-cargo">
				Agenda do(a) <?php echo $this->autoridade->car_name; ?>
				<br>
				<?php echo $this->autoridade->dir_name; ?>
			</strong>
			<?php if (!empty($this->dia_por_extenso)): ?>
			<h2><?php echo $this->dia_por_extenso; ?></h2>
			<?php endif; ?>				
		</div>

		<div class="autoridade-compromissos tile-list-1">
			<?php for ($i=0, $count_compromissos = count($this->compromissos); $i < $count_compromissos; $i++): ?> 
				<?php
				$compromisso =& $this->compromissos[$i];
				$compromisso->horario_inicio = str_replace(':', 'h', $compromisso->horario_inicio);
				$compromisso->horario_fim = str_replace(':', 'h', $compromisso->horario_fim);
				$link_vcalendar = JURI::root() . 'index.php?option=com_agendadirigentes&view=compromisso&format=vcs&id=' . $compromisso->id;
				?>
				<div class="tileItem autoridade-compromisso">
					<div class="span9 tileContent">										
						<h3 class="tileHeadline autoridade-compromisso-titulo">
		              		<?php echo $compromisso->title ?>
		          		</h3>
		          		<div class="description autoridade-compromisso-participantes">
		          			<ul>
		          				<?php foreach ($compromisso->participantes as $participante): ?>
		          					<?php
		          					$title = (!empty($participante->cargo_name))? 'title="' . $participante->cargo_name . '"' : '';
		          					?>
		          					<li <?php echo $title; ?>><?php echo $participante->dirigente_name ?></li>
		          				<?php endforeach; ?>
		          			</ul>
						</div>

						<?php if (!empty($compromisso->description)): ?>
						<div class="keywords autoridade-compromisso-pauta">
							<?php echo $compromisso->description; ?>
						</div> 							
						<?php endif ?>

						<?php if (!empty($compromisso->local)): ?>
						<div class="keywords autoridade-compromisso-local">
							<b>Local:</b> <?php echo $compromisso->local; ?>
						</div>
						<?php endif ?>

						<div class="autoridade-compromisso-rodape">
							<a class="autoridade-compromisso-link-calendario" href="<?php echo $link_vcalendar ?>"><i class="icon-fixed-width icon-calendar"></i> Adicionar ao meu calend&aacute;rio</a>
						</div>
		          	</div>
					<div class="span3 tileInfo autoridade-compromisso-horario">
						<ul>		
							<li class="autoridade-compromisso-inicio">
								<i class="icon-fixed-width icon-time"><span class="hide">Início:</span></i> <?php echo $compromisso->horario_inicio; ?>
							</li>						
							<li class="autoridade-compromisso-fim">
								<i class="icon-fixed-width icon-time"><span class="hide">Fim:</span></i> <?php echo $compromisso->horario_fim; ?>
							</li>						
						</ul>							            								
					</div>			
				</div>
				<!-- fim .autoridade-compromisso -->
			<?php endfor; ?>			
		</div>
		<div class="below-content">
			<ul class="autoridade-list-actions">
				<li><span><a href="/joomla-3.x.dev/index.php/ultimas-noticias" class="link-categoria"><i class="icon-fixed-width icon-print"></i> Imprimir</a></span></li>
				<li><span><a href="/joomla-3.x.dev/index.php/ultimas-noticias" class="link-categoria"><i class="icon-fixed-width icon-bar-chart"></i> Reportar erro</a></span></li>
			</ul>			
		</div>
	</div>
	<div class="span4" style="background:grey">
		teste
	</div>
</div>