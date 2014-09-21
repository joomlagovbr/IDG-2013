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
if($this->templatevar != 'system'):
	$this->document->addStylesheet( JURI::root().'media/com_agendadirigentes/fullcalendar/fullcalendar.css' );
	$this->document->addStylesheet( JURI::root().'media/com_agendadirigentes/css/frontend.css' );
	$this->document->addScript( JURI::root().'media/com_agendadirigentes/fullcalendar/lib/jquery-ui.custom.min.js' );
	$this->document->addScript( JURI::root().'media/com_agendadirigentes/fullcalendar/lib/moment.min.js' );
	$this->document->addScript( JURI::root().'media/com_agendadirigentes/fullcalendar/fullcalendar.min.js' );
	$this->document->addScript( JURI::root().'media/com_agendadirigentes/fullcalendar/lang/pt-br.js' );
	$this->document->addScript( JURI::root().'media/com_agendadirigentes/js/frontend.js' );
	$script = "<script type=\"text/javascript\">\n"
				."jQuery(document).ready(function() {\n"
					."setCalendar('#autoridade-calendario', '".$this->params->get("dia")."', '".JURI::root()."index.php?option=com_agendadirigentes&view=autoridade&dia={DATA}&id=".$this->autoridade->id."&Itemid=".$this->Itemid."');\n"
				."});\n"
			  ."</script>\n";
	$this->document->addCustomTag($script);
else:
	$style = "<link href='".JURI::root()."media/com_agendadirigentes/css/frontend.print.css' rel='stylesheet' media='all' />";
	$this->document->addCustomTag( $style );
	$script = "<script type=\"text/javascript\">\n"
				."window.print();\n"
			  ."</script>\n";
	$this->document->addCustomTag($script);
endif;
?>
<div class="item-page<?php //echo $this->pageclass_sfx?> row-fluid autoridade-page">
	<div class="span8">
		<h1 class="secondaryHeading autoridade-title">
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
						<?php if($this->templatevar != 'system'): ?>
						<div class="autoridade-compromisso-rodape">
							<a class="autoridade-compromisso-link-calendario" href="<?php echo $link_vcalendar ?>"><i class="icon-fixed-width icon-calendar"></i> Adicionar ao meu calend&aacute;rio</a>
						</div>
						<?php endif; ?>
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
			<?php if ($count_compromissos==0): ?>
			<div id="system-message-container">
				<div id="system-message">
					<div class="alert alert-warning">
					<div>
						<p>Sem compromissos oficiais.</p>
					</div>
					</div>
				</div>
			</div>
			<?php endif ?>	
		</div>
		<?php if($this->templatevar != 'system'): ?>
		<div class="below-content">
			<ul class="autoridade-list-actions">
				<li><span><a href="<?php echo JURI::getInstance()->toString()."&template=system"; ?>" target="_blank" class="link-categoria"><i class="icon-fixed-width icon-print"></i> Imprimir</a></span></li>
				<?php if(!empty($this->link_reportar_erro)): ?>				
				<li><span><a href="<?php echo $this->link_reportar_erro; ?>" class="link-categoria"><i class="icon-fixed-width icon-warning-sign"></i> Reportar erro</a></span></li>
				<?php endif; ?>
			</ul>			
		</div>
		<?php endif; ?>
	</div>
	<?php if($this->templatevar != 'system'): ?>
	<div class="span4">
		<div class="module-box-01 module variacao-module-00 autoridade-calendario-container">
			<div class="header">
				<h2 class="title">Agenda</h2>	        					
			</div>
			<div id="autoridade-calendario">
				<div class="footer hide">
					<button class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right" type="button">Ver m&ecirc;s atual</button>
				</div>
			</div>
		</div>

		<div class="module-box-01 module variacao-module-00 autoridade-busca-container">
			<div class="header">
				<h2 class="title">Busca de agenda</h2>	        					
			</div>
			<div class="formated-description autoridade-busca">
				<p>Faça buscas de agenda por palavras-chave</p>
				<form action="index.php" method="get" role="search">
					<div class="search">						
						<input type="hidden" name="view" value="search">
						<input type="hidden" name="option" value="com_search">
						<input type="hidden" name="ordering" value="newest">
						<input type="hidden" name="searchprase" value="all">
						<input type="hidden" name="limit" value="80">
						<input type="hidden" name="areas[]" value="agendadirigentes">
						<input type="hidden" name="Itemid" value="181">
						<input type="hidden" name="filtro_autoridade" value="<?php echo $this->autoridade->id; ?>">
						<div class="input-append">
        					<label class="hide" for="portal-searchbox-field">Busca: </label>
        					<input type="text" name="searchword" title="Buscar na agenda" placeholder="Buscar na agenda" class="inputbox searchField" maxlength="20" >       
            				<button class="btn searchButton" type="submit"><span class="hide">Buscar</span><i class="icon-search"></i></button>
						</div>
					</div>
				</form>
			</div>			
		</div>
		
	</div>
	<?php endif; ?>
</div>