<?php
/**
 * @package
 * @subpackage
 * @copyright
 * @license
 */

// No direct access.
defined('_JEXEC') or die;
require_once JPATH_SITE .'/templates/'.$this->template.'/helper.php';
header('Content-Type: text/html; charset=utf-8');
if(!@isset($this->params))
{
	$app = JFactory::getApplication();
	$this->params = $app->getTemplate(true)->params;
}
TmplPadraoGoverno01Helper::init( $this );
$active_item = TmplPadraoGoverno01Helper::getActiveItemid();
TmplPadraoGoverno01Helper::clearDefaultScripts( $this );
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="pt-br" dir="ltr"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="pt-br" dir="ltr"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="pt-br" dir="ltr"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="pt-br" dir="ltr"> <!--<![endif]-->
<head>
    <!--[if lt IE 9]><script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/bootstrap/css/bootstrap.min.css" type='text/css'/>
    <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/template-<?php echo $this->params->get('cor', 'verde'); ?>.css" type='text/css'/>
    <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/font-awesome/css/font-awesome.min.css" type='text/css'/>
    <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/custom.css" type='text/css'/>
    <!--[if lt IE 10]><link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/ie.css" /><![endif]-->
    <!--[if lt IE 9]><link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/ie8.css" /><![endif]-->
    <!--[if lt IE 8]><link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/ie7.css" /><link rel="stylesheet" href="font-awesome/css/font-awesome-ie7.min.css" /><![endif]-->
    <?php if(TmplPadraoGoverno01Helper::beforeHead('local_jquery', $this)) TmplPadraoGoverno01Helper::getJqueryScripts( $this ); ?>
    <?php if(TmplPadraoGoverno01Helper::beforeHead('local_mainscript', $this)) TmplPadraoGoverno01Helper::getTemplateMainScripts( $this ); ?>
    <title>Erro <?php echo $this->error->getCode(); ?> - <?php echo $this->error->getMessage(); ?></title>
    <link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <?php if(TmplPadraoGoverno01Helper::afterHead('local_jquery', $this)) TmplPadraoGoverno01Helper::getJqueryScripts( $this ); ?>
    <?php if(TmplPadraoGoverno01Helper::afterHead('local_mainscript', $this)) TmplPadraoGoverno01Helper::getTemplateMainScripts( $this ); ?>
    <?php TmplPadraoGoverno01Helper::getFontStyle( $this ); ?>
</head>
<body <?php TmplPadraoGoverno01Helper::getPageClass( $active_item ); ?> >
    <a class="hide" id="topo" href="#accessibility"><?php echo JText::_('TPL_PADRAOGOVERNO01_IR_MENU_ACESSIBILIDADE'); ?></a>
    <noscript>
      <div class="error minor-font">
        <?php echo JText::_('TPL_PADRAOGOVERNO01_NO_SCRIPT'); ?>
      </div>
    </noscript>
    <!--[if lt IE 7]><center><strong><?php echo JText::_('TPL_PADRAOGOVERNO01_BROWSER_NAO_COMPATIVEL'); ?></strong></center><![endif]-->
    <?php $module_barra_do_governo = TmplPadraoGoverno01Helper::getModules('barra-do-governo') ?>
    <?php TmplPadraoGoverno01Helper::loadModuleByPosition('barra-do-governo', NULL, $module_barra_do_governo); ?>
    <div class="layout">
    	<header>
           <div class="container"> 
                <div class="row-fluid accessibility-language-actions-container">                         
                    <div class="span6 accessibility-container">
                        <ul id="accessibility">
                            <li>
                                <a accesskey="1" href="#content" id="link-conteudo">
                                    <?php echo JText::_('TPL_PADRAOGOVERNO01_LINK_CONTEUDO'); ?>
                                    <span>1</span>
                                </a>
                            </li>
                            <li>
                                <a accesskey="2" href="#navigation" id="link-navegacao">
                                    <?php echo JText::_('TPL_PADRAOGOVERNO01_LINK_NAVEGACAO'); ?>
                                    <span>2</span>
                                </a>
                            </li>
                            <li>
                                <a accesskey="3" href="#portal-searchbox" id="link-buscar">
                                    <?php echo JText::_('TPL_PADRAOGOVERNO01_LINK_BUSCAR'); ?>
                                    <span>3</span>
                                </a>
                            </li>
                            <li>
                                <a accesskey="4" href="#footer" id="link-rodape">
                                    <?php echo JText::_('TPL_PADRAOGOVERNO01_LINK_RODAPE'); ?>
                                    <span>4</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- fim div.span6 -->
                    <div class="span6 language-and-actions-container">
    					<?php TmplPadraoGoverno01Helper::loadModuleByPosition('header-topo-direita', array('style'=>'hidden_titles','headerLevel'=>'2')); ?>
                    </div>
                    <!-- fim div.span6 -->    
                </div>
                <!-- fim .row-fluid -->
                <div class="row-fluid">
                    <div id="logo" class="span8<?php if($this->params->get('classe_nome_principal', '') != '') echo ' '.$this->params->get('classe_nome_principal'); ?>">
                        <a href="<?php echo JURI::root(); ?>" title="<?php echo $this->params->get('nome_principal', '???'); ?>">
                            <?php if( $this->params->get('emblema', '') != '' ): ?>
                            <img src="<?php echo JURI::root(); ?><?php echo $this->params->get('emblema', ''); ?>" alt="<?php echo $this->params->get('nome_principal', '???'); ?>" />
                            <?php endif; ?>
                            <span class="portal-title-1"><?php echo $this->params->get('denominacao', ''); ?></span>
                            <h1 class="portal-title corto"><?php echo $this->params->get('nome_principal', '???'); ?></h1>
                            <span class="portal-description"><?php echo $this->params->get('subordinacao', ''); ?></span>
                        </a>
                    </div>
                    <!-- fim .span8 -->
                    <div class="span4">
                    	<?php TmplPadraoGoverno01Helper::loadModuleByPosition('header-meio-direita', array('style'=>'row01','headerLevel'=>'2')); ?>                                         
                    </div>
                    <!-- fim .span4 -->
                </div>
                <!-- fim .row-fluid -->
            </div>
            <!-- fim div.container -->
            <div class="sobre">
                <div class="container">
                	<?php TmplPadraoGoverno01Helper::loadModuleByPosition('menu-sobre', array('style'=>'menu_sobre','headerLevel'=>'2')); ?>
                </div>
                <!-- .container -->
            </div>
            <!-- fim .sobre -->   
    	</header>
    	<main>
    		<div class="container">
               	<?php TmplPadraoGoverno01Helper::loadModuleByPosition('topo-main', array('style'=>'rowfluid_section01','headerLevel'=>'2')); ?>
                <?php TmplPadraoGoverno01Helper::loadModuleByPosition('topo-main-pagina-erro', array('style'=>'rowfluid_section01','headerLevel'=>'2')); ?>
               	<div class="row-fluid">
               		<?php
               			$modules_menu_principal = TmplPadraoGoverno01Helper::getModules("menu-principal");
               		?>
                    <?php if(count($modules_menu_principal) > 0): ?>
                    <div id="navigation" class="span3">
                        <a href="#" class="visible-phone visible-tablet mainmenu-toggle btn"><i class="icon-list"></i>&nbsp;<?php echo JText::_('TPL_PADRAOGOVERNO01_MENU'); ?></a>
                        <section id="navigation-section">
                            <span class="hide"><?php echo JText::_('TPL_PADRAOGOVERNO01_INI_MENU'); ?></span>
                            <jdoc:include type="modules" name="menu-principal" style="nav_span" headerLevel="2" />
                            <span class="hide"><?php echo JText::_('TPL_PADRAOGOVERNO01_FIM_MENU'); ?></span>
                        </section>
                    </div>
                    <!-- fim #navigation.span3 --> 
                    <?php endif; ?>
                    <div id="content" class="<?php if(count($modules_menu_principal)): ?>span9<?php else: ?>span12 full<?php endif; ?> internas">
                    	<section id="content-section">                          
                            <span class="hide"><?php echo JText::_('TPL_PADRAOGOVERNO01_INI_CONTEUDO'); ?></span>
                            <div class="row-fluid">
                            	<h1 class="documentFirstHeading">
                            		Erro <?php echo $this->error->getCode(); ?>
                            	</h1>
								<div class="subtitle">
									<h2><?php echo $this->error->getMessage(); ?></h2>
                                    <p><?php echo JText::_('TPL_PADRAOGOVERNO01_ERRO_DESCULPAS'); ?></p>
                                    <p><?php echo JText::_('TPL_PADRAOGOVERNO01_ERRO_MSG_CONTATO'); ?></p>
								</div>
								<br />
								<p><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></p>									
								<ol>
									<li><?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
									<li><?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
									<li><?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
									<li><?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
									<li><?php echo JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'); ?></li>
									<li><?php echo JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></li>
								</ol>
								<p><strong><?php echo JText::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES'); ?></strong></p>

								<ul>
									<li><a href="<?php echo $this->baseurl; ?>/index.php" title="<?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?>"><?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></li>
                                    <li><a href="<?php echo $this->baseurl; ?>/index.php/mapa-do-site" title="<?php JText::_('TPL_PADRAOGOVERNO01_MAPA_SITE'); ?>"><?php JText::_('TPL_PADRAOGOVERNO01_MAPA_SITE'); ?></a></li>
                                    <li><a href="<?php echo $this->baseurl; ?>/index.php/busca" title="<?php echo JText::_('TPL_PADRAOGOVERNO01_BUSCA'); ?>"><?php echo JText::_('TPL_PADRAOGOVERNO01_BUSCA'); ?></a></li>
								</ul>
								<br />
								<div class="subtitle">
									<p><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>
								</div>
								<br />
								<h3><?php ECHO JText::_('TPL_PADRAOGOVERNO01_INFORMACOES_TECNICAS'); ?></h3>
								<div id="techinfo" class="error">
									<p><?php echo htmlspecialchars($this->error->getMessage()); ?></p>
									<p>
										<?php if ($this->debug) :
											echo $this->renderBacktrace();
										endif; ?>
									</p>
								</div>
                            </div>
                        </section>
					</div>
               	</div>    			
    		</div>
    	</main>
        <footer>
            <div class="footer-atalhos">
                <div class="container">
                    <div class="pull-right voltar-ao-topo"><a href="#portal-siteactions"><i class="icon-chevron-up"></i>&nbsp;<?php echo JText::_('TPL_PADRAOGOVERNO01_VOLTAR_TOPO'); ?></a></div>
                </div>
            </div>
            <div class="container container-menus">
                <div id="footer" class="row footer-menus">
                    <span class="hide"><?php echo JText::_('TPL_PADRAOGOVERNO01_INI_RODAPE'); ?></span>
                    <jdoc:include type="modules" name="menus-rodape" style="div_nav_rodape" headerLevel="2" />
                    <span class="hide"><?php echo JText::_('TPL_PADRAOGOVERNO01_FIM_RODAPE'); ?></span>
                </div>
                <!-- fim .row -->
            </div>
            <!-- fim .container -->
            <div class="footer-logos">
                <div class="container">
                    <?php if( $this->params->get('rodape_acesso_informacao', 1) == 1 ): ?>
                        <a href="http://www.acessoainformacao.gov.br/" class="logo-acesso pull-left"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/acesso-a-informacao.png" alt="<?php JText::_('TPL_PADRAOGOVERNO01_ACESSO_INFORMACAO'); ?>"></a>
                    <?php endif; ?>
                    <?php if( $this->params->get('rodape_logo_brasil', 1) == 1 ): ?>
                        <!-- separador para fins de acessibilidade --><span class="hide">&nbsp;</span><!-- fim separador para fins de acessibilidade -->
                        <a href="http://www.brasil.gov.br/" class="brasil pull-right"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/brasil.png" alt="<?php JText::_('TPL_PADRAOGOVERNO01_BRASIL_GOV'); ?>"></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="footer-ferramenta">
                <div class="container">
                    <?php echo $this->params->get('mensagem_final_ferramenta', '<p>'.JText::_('TPL_PADRAOGOVERNO01_DES_CODIGO_ABERTO').' <a href="http://www.joomla.org">Joomla</a></p>'); ?>
                </div>
            </div>
            <div class="footer-atalhos visible-phone">
                <div class="container">
                    <span class="hide"><?php echo JText::_('TPL_PADRAOGOVERNO01_FIM_CONTEUDO'); ?></span>
                    <div class="pull-right voltar-ao-topo"><a href="#portal-siteactions"><i class="icon-chevron-up"></i>&nbsp;<?php echo JText::_('TPL_PADRAOGOVERNO01_VOLTAR_TOPO'); ?></a></div>
                </div>
            </div>
        </footer> 
    </div>
    <!-- scripts principais do template --> 
    <?php if(TmplPadraoGoverno01Helper::inFooter('local_jquery', $this)) TmplPadraoGoverno01Helper::getJqueryScripts( $this ); ?>
    <?php if(TmplPadraoGoverno01Helper::inFooter('local_mainscript', $this)) TmplPadraoGoverno01Helper::getTemplateMainScripts( $this ); ?>
    <?php if(count($module_barra_do_governo) && $this->params->get('anexar_js_barra2014')) TmplPadraoGoverno01Helper::getBarra2014Script( $this ); ?>
    <?php if($this->params->get('google_analytics_id', '') != ''): ?>
        <script type="text/javascript">
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', '<?php echo $this->params->get('google_analytics_id', ''); ?>']);
          _gaq.push(['_trackPageview']);
          <?php if($this->params->get('google_analytics_domain_name', '') != ''): ?>
          _gaq.push(['_setDomainName', '<?php echo $this->params->get('google_analytics_domain_name', ''); ?>']);
          <?php endif; ?>
          <?php if($this->params->get('google_analytics_allow_linker', '') == 1): ?>
          _gaq.push(['_setAllowLinker', true]);
          <?php endif; ?>
          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();
        </script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
    <?php endif; ?>
    <!-- debug -->
    <?php TmplPadraoGoverno01Helper::loadModuleByPosition('debug'); ?>
    <?php TmplPadraoGoverno01Helper::debug( @$preffix, @$active_item); ?>    
</body>
</html>