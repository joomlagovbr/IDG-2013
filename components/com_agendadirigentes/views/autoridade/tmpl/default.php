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
<div class="item-page<?php echo $this->pageclass_sfx; ?> row-fluid autoridade-page">
	<div class="span8">
		<h1 class="secondaryHeading autoridade-title">
			<?php echo $this->page_heading; ?>
		</h1>
		<?php if (!empty($this->sharing) && $this->templatevar != 'system'): ?>
		<div class="autoridade-sharing-options">
			<?php echo $this->sharing; ?>
		</div>			
		<?php endif ?>

		<div class="autoridade-header">
			<img class="brasao-republica" src="<?php echo JURI::root() ?>media/com_agendadirigentes/images/brasao.png" alt="<?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_BRASAO_ALT'); ?>" width="64" height="63" />
			<?php if (!empty($this->nome_orgao)): ?>
			<strong class="autoridade-orgao">
				<?php echo $this->nome_orgao; ?>
			</strong>
			<?php endif; ?>				
			<strong class="autoridade-nome-e-cargo">
				<?php if ($this->autoridade->sexo == 'M'): ?>
				<?php echo sprintf(
							JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_AGENDA_CARGO_M')
							, $this->autoridade->car_name );
				 ?>
				<?php else: ?>
				<?php echo sprintf(
							JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_AGENDA_CARGO_F')
							, $this->autoridade->car_name );
				 ?>
				<?php endif ?>
				<br>
				<?php echo $this->autoridade->dir_name; ?>
			</strong>
			<?php if (!empty($this->dia_por_extenso)): ?>
			<h2><?php echo $this->dia_por_extenso; ?></h2>
			<?php endif; ?>				
			<?php if (! is_null($this->autoridade->qtd_alteracoes_agenda) ): ?>
				<span class="autoridade-agenda-alterada">
					<?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_AGENDA_ALTERADA'); ?>
				</span>
			<?php endif ?>
		</div>
		<div class="autoridade-compromissos tile-list-1">
			<?php for ($i=0, $count_compromissos = count($this->compromissos); $i < $count_compromissos; $i++): ?> 
				<?php
				$compromisso =& $this->compromissos[$i];
				$compromisso = $this->prepararCompromisso( $compromisso );
				?>
				<div class="tileItem autoridade-compromisso">
					<div class="span9 tileContent">										
						<h3 class="tileHeadline autoridade-compromisso-titulo">
		              		<?php echo $compromisso->title ?>
		          		</h3>
		          		<div class="description autoridade-compromisso-participantes">		          			
		          			<ul>
		          				<?php foreach ($compromisso->participantes as $participante): ?>
		          					<?php if (!empty($participante->cargo_name) && $compromisso->params->get('exibir_participantes_locais', 1) == 1):
		          						//participantes locais (cadastrados no sistema) possuem sempre cargo
		          						?>
			          					<?php $title = 'title="' . $participante->cargo_name . '"'; ?>
			          					<li <?php echo $title; ?>><?php echo $participante->dirigente_name ?></li>
		          					<?php elseif($compromisso->params->get('exibir_participantes_externos', 1) == 1):
		          						//participantes externos (cadastrados no compromisso mas não como dirigente) não possuem cargo
		          						?>
		          						<li><?php echo $participante->dirigente_name ?></li>
		          					<?php endif; ?>
		          				<?php endforeach; ?>
		          			</ul>
						</div>

						<?php if (!empty($compromisso->description) && $compromisso->params->get('exibir_pauta', 1) == 1): ?>
						<div class="keywords autoridade-compromisso-pauta">
							<?php echo $compromisso->description; ?>
						</div> 							
						<?php endif ?>

						<?php if (!empty($compromisso->local) && $compromisso->params->get('exibir_local', 1) == 1): ?>
						<div class="keywords autoridade-compromisso-local">
							<b>Local:</b> <?php echo $compromisso->local; ?>
						</div>
						<?php endif ?>
						<?php if($this->templatevar != 'system' && $compromisso->params->get('permitir_vcard', 1) == 1): ?>
						<div class="autoridade-compromisso-rodape">
							<a class="autoridade-compromisso-link-calendario" href="<?php echo $compromisso->link_vcalendar; ?>"><i class="icon-fixed-width icon-calendar"></i> <?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_ADD_CALENDAR'); ?></a>
						</div>
						<?php endif; ?>
		          	</div>
					<div class="span3 tileInfo autoridade-compromisso-horario">
						<ul>
							<?php if ($compromisso->dia_todo && $compromisso->params->get('exibir_horario_inicio', 1) == 1 ): ?>
							<li class="autoridade-compromisso-inicio">
								<i class="icon-fixed-width icon-time"><span class="hide"></span></i> <?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_ALL_DAY'); ?>
							</li>
							<?php else: ?>
								<?php if ( $compromisso->params->get('exibir_horario_inicio', 1) == 1 ): ?>
								<li class="autoridade-compromisso-inicio">
									<i class="icon-fixed-width icon-time"><span class="hide"><?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_BEGINNING') ?>:</span></i> <?php echo $compromisso->horario_inicio; ?>
								</li>								
								<?php endif ?>
								<?php if ( $compromisso->params->get('exibir_horario_fim', 1) == 1 ): ?>
								<li class="autoridade-compromisso-fim">
									<i class="icon-fixed-width icon-time"><span class="hide"><?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_END') ?>:</span></i> <?php echo $compromisso->horario_fim; ?>
								</li>							
								<?php endif; ?>
							<?php endif; ?>
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
						<p><?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_EMPTY_DAY'); ?></p>
					</div>
					</div>
				</div>
			</div>
			<?php endif ?>	
		</div>
		<?php if($this->templatevar != 'system'): ?>
		<div class="below-content">
			<ul class="autoridade-list-actions">
				<li><span><a href="<?php echo JURI::getInstance()->toString()."&template=system"; ?>" target="_blank" class="link-categoria"><i class="icon-fixed-width icon-print"></i> <?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_PRINT'); ?></a></span></li>
				<?php if(!empty($this->link_reportar_erro)): ?>				
				<li><span><a href="<?php echo $this->link_reportar_erro; ?>" class="link-categoria"><i class="icon-fixed-width icon-warning-sign"></i> <?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_REPORT_ERROR'); ?></a></span></li>
				<?php endif; ?>
			</ul>			
		</div>
		<?php endif; ?>
	</div>
	<?php if($this->templatevar != 'system'): ?>
	<div class="span4">
		<div class="module-box-01 module variacao-module-00 autoridade-calendario-container">
			<div class="header">
				<h2 class="title"><?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_CALENDAR_TITLE'); ?></h2>	        					
			</div>
			<div id="autoridade-calendario">
				<div class="footer hide">
					<button class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right" type="button"><?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_GOTO_MONTH'); ?></button>
				</div>
			</div>
		</div>
		<?php if(! empty($this->allow_search_field)): ?>
		<div class="module-box-01 module variacao-module-00 autoridade-busca-container">
			<div class="header">
				<h2 class="title"><?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_SEARCH_TITLE'); ?></h2>
			</div>
			<div class="formated-description autoridade-busca">
				<p><?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_SEARCH_DESC'); ?></p>
				<form action="index.php" method="get" role="search">
					<div class="search">						
						<input type="hidden" name="view" value="search">
						<input type="hidden" name="option" value="com_search">
						<input type="hidden" name="ordering" value="newest">
						<input type="hidden" name="searchprase" value="all">
						<input type="hidden" name="limit" value="80">
						<input type="hidden" name="areas[]" value="agendadirigentes">
						<input type="hidden" name="Itemid" value="<?php echo $this->params->get('busca_itemid', "") ?>">
						<input type="hidden" name="filtro_autoridade" value="<?php echo $this->autoridade->id; ?>">
						<div class="input-append">
        					<label class="hide" for="portal-searchbox-field"><?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_BTNSEARCH_LABEL'); ?> </label>
        					<input type="text" name="searchword" title="<?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_INPUTSEARCH_TEXT'); ?>" placeholder="<?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_INPUTSEARCH_TEXT'); ?>" class="inputbox searchField" maxlength="20" >       
            				<button class="btn searchButton" type="submit"><span class="hide"><?php echo JText::_('COM_AGENDADIRIGENTES_TMPL_AUTORIDADE_BTNSEARCH_TEXT'); ?></span><i class="icon-search"></i></button>
						</div>
					</div>
				</form>
			</div>			
		</div>
		<?php endif; ?>
		<?php if (!empty($this->sidebar_custom_modules) && $this->templatevar != 'system'): ?>
		<div class="autoridade-custom-modules">
			<?php echo $this->sidebar_custom_modules; ?>
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>
</div>