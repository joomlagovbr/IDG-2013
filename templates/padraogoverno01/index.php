<?php
/**
 * @package               
 * @subpackage	
 * @copyright        
 * @license          
 */

// No direct access.
defined('_JEXEC') or die;
require JPATH_SITE .'/templates/'.$this->template.'/helper.php';
TmplPadraoGoverno01Helper::clearDefaultScripts( $this );
$active_item = TmplPadraoGoverno01Helper::getActiveItemid();
/*TESTE DE MENSAGENS*/
// JFactory::getApplication()->enqueueMessage('Message');
// JError::raiseNotice( 100, 'Notice' );
// JError::raiseWarning( 100, 'Warning' );
/*FIM TESTE DE MENSAGENS*/
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="pt-br" dir="ltr"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="pt-br" dir="ltr"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="pt-br" dir="ltr"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="pt-br" dir="ltr"> <!--<![endif]-->
<head>    
    <!--[if lt IE 9]>
    <script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/html5shiv.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/bootstrap/css/bootstrap.min.css" type='text/css'/>
    <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/template-<?php echo $this->params->get('cor', 'verde'); ?>.css" type='text/css'/>      
    <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/font-awesome/css/font-awesome.min.css" type='text/css'/>
    <!--[if lt IE 10]>
    <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/ie.css" />
    <![endif]-->
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/ie8.css" />
    <![endif]-->
    <!--[if lt IE 8]>
    <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/ie7.css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome-ie7.min.css" />
    <![endif]-->        
    <?php if(TmplPadraoGoverno01Helper::beforeHead('local_jquery', $this)) TmplPadraoGoverno01Helper::getJqueryScripts( $this ); ?>
    <?php if(TmplPadraoGoverno01Helper::beforeHead('local_mainscript', $this)) TmplPadraoGoverno01Helper::getTemplateMainScripts( $this ); ?>
    <jdoc:include type="head" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport">    
    <?php if(TmplPadraoGoverno01Helper::afterHead('local_jquery', $this)) TmplPadraoGoverno01Helper::getJqueryScripts( $this ); ?>
    <?php if(TmplPadraoGoverno01Helper::afterHead('local_mainscript', $this)) TmplPadraoGoverno01Helper::getTemplateMainScripts( $this ); ?>
    <?php TmplPadraoGoverno01Helper::getFontStyle( $this ); ?>
</head>
<body <?php echo TmplPadraoGoverno01Helper::getPageClass( $active_item, false, true ); ?>>
    <a class="hide" id="topo" href="#accessibility">Ir direto para menu de acessibilidade.</a>
    <noscript>
      <div class="error minor-font">
        Seu navegador de internet está sem suporte à JavaScript. Por esse motivo algumas funcionalidades do site podem não estar acessíveis.
      </div>
    </noscript>
    <!--[if lt IE 7]><center><strong>Atenção, a versão de seu navegador não é compatível com este sítio. Atualize seu navegador.</strong></center><![endif]-->
    <jdoc:include type="modules" name="barra-do-governo" />
    <div class="layout">
        <header>
            <div class="container"> 
                <div class="row-fluid accessibility-language-actions-container">                         
                    <div class="span6 accessibility-container">
                        <ul id="accessibility">
                            <li>
                                <a accesskey="1" href="#content" id="link-conteudo">
                                    Ir para o conte&uacute;do
                                    <span>1</span>
                                </a>
                            </li>
                            <li>
                                <a accesskey="2" href="#navigation" id="link-navegacao">
                                    Ir para o menu
                                    <span>2</span>
                                </a>
                            </li>
                            <li>
                                <a accesskey="3" href="#portal-searchbox" id="link-buscar">
                                    Ir para a busca
                                    <span>3</span>
                                </a>
                            </li>
                            <li>
                                <a accesskey="4" href="#footer" id="link-rodape">
                                    Ir para o rodap&eacute;
                                    <span>4</span>
                                </a>
                            </li>
                        </ul>                       
                    </div>
                    <!-- fim div.span6 -->
                    <div class="span6 language-and-actions-container">
                        <jdoc:include type="modules" name="header-topo-direita" style="hidden_titles" headerLevel="2" />
                    </div>
                    <!-- fim div.span6 -->    
                </div>
                <!-- fim .row-fluid -->
                <div class="row-fluid">
                    <div id="logo" class="span8<?php if($this->params->get('classe_nome_principal', '') != '') echo ' '.$this->params->get('classe_nome_principal'); ?>">
                        <a href="<?php echo JURI::root(); ?>" title="<?php echo $this->params->get('nome_principal', 'Nome principal'); ?>">
                            <span class="portal-title-1"><?php echo $this->params->get('denominacao', ''); ?></span>
                            <h1 class="portal-title corto"><?php echo $this->params->get('nome_principal', 'Nome principal'); ?></h1>
                            <span class="portal-description"><?php echo $this->params->get('subordinacao', ''); ?></span>
                        </a>
                    </div>
                    <!-- fim .span8 -->
                    <div class="span4">                                         
                        <jdoc:include type="modules" name="header-meio-direita" style="row01" headerLevel="2" />
                    </div>
                    <!-- fim .span4 -->
                </div>
                <!-- fim .row-fluid -->
            </div>
            <!-- fim div.container -->
            <div class="sobre">
                <div class="container">
                    <jdoc:include type="modules" name="menu-sobre" style="menu_sobre" headerLevel="2" />
                </div>
                <!-- .container -->
            </div>
            <!-- fim .sobre -->         
        </header>
        <main>            
            <div class="container">
                <jdoc:include type="modules" name="topo-main" style="rowfluid_section01" headerLevel="2" />  
                <div class="row-fluid">
                    <?php if($this->countModules("menu-principal")): ?>
                    <div id="navigation" class="span3">
                        <a href="#" class="visible-phone visible-tablet mainmenu-toggle btn"><i class="icon-list"></i>&nbsp;Menu</a>
                        <section id="navigation-section">                           
                            <span class="hide">Início do menu principal</span>
                            <jdoc:include type="modules" name="menu-principal" style="nav_span" headerLevel="2" />
                            <span class="hide">Fim do menu principal</span>
                        </section>                  
                    </div>
                    <!-- fim #navigation.span3 --> 
                    <?php endif; ?>                   
                    <div id="content" class="<?php if($this->countModules("menu-principal")): ?>span9<?php else: ?>span12 full<?php endif; ?><?php if( !TmplPadraoGoverno01Helper::isOnlyModulesPage() || @$active_item->home != 1 ): ?> internas<?php endif; ?>">
                        <section id="content-section">                          
                            <span class="hide">Início do conteúdo da página</span> 
                            
                            <?php if(TmplPadraoGoverno01Helper::hasMessage()):  ?>
                            <div class="row-fluid">
                                <jdoc:include type="message" />        
                            </div>
                            <?php endif; ?>

                            <?php if(@$active_item->home == 1 ): //pagina inicial ?>
                            
                                <jdoc:include type="modules" name="pagina-inicial" style="container" headerLevel="2" />                            
                           
                            <?php else:
                                
                                $preffix = TmplPadraoGoverno01Helper::getPagePositionPreffix($active_item);
                                $posicao_topo = $preffix. '-topo';
                                $posicao_rodape = $preffix. '-rodape';
                                $posicao_direita = $preffix. '-direita';                                
                                ?>

                                <?php if($this->countModules($posicao_topo) || $this->countModules("internas-topo")): ?>
                                <div class="row-fluid">
                                    <jdoc:include type="modules" name="internas-topo" headerLevel="2" style="container" />  
                                    <jdoc:include type="modules" name="<?php echo $posicao_topo ?>" headerLevel="2" style="container" />                              
                                </div>
                                <?php endif; ?>
                                
                                <?php if($this->countModules($posicao_direita) || $this->countModules("internas-direita")): ?>
                                <div class="row-fluid">
                                    <div class="span9">
                                        <?php if(  TmplPadraoGoverno01Helper::isOnlyModulesPage() ): ?>
                                             <jdoc:include type="modules" name="pagina-interna-capa" style="container" headerLevel="2" />  
                                             <jdoc:include type="modules" name="pagina-interna-capa-<?php echo $preffix ?>" style="container" headerLevel="2" />
                                        <?php else: ?>
                                            <jdoc:include type="component" />
                                        <?php endif; ?>
                                    </div>
                                    <div class="span3">
                                        <jdoc:include type="modules" name="internas-direita" headerLevel="2" style="container" />                                     
                                        <jdoc:include type="modules" name="<?php echo $posicao_direita ?>" headerLevel="2" style="container" />
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="row-fluid">
                                    <?php if(  TmplPadraoGoverno01Helper::isOnlyModulesPage() ): ?>
                                         <jdoc:include type="modules" name="pagina-interna-capa" style="container" headerLevel="2" />  
                                         <jdoc:include type="modules" name="pagina-interna-capa-<?php echo $preffix ?>" style="container" headerLevel="2" />
                                    <?php else: ?>
                                        <jdoc:include type="component" />
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>

                                <?php if($this->countModules($posicao_rodape) || $this->countModules("internas-rodape")): ?>
                                <div class="row-fluid">                                    
                                    <jdoc:include type="modules" name="<?php echo $posicao_rodape ?>" headerLevel="2" style="container" />
                                    <jdoc:include type="modules" name="internas-rodape" headerLevel="2" style="container" />  
                                </div>
                                <?php endif; ?>
                               
                            <?php endif; ?>
                            
                            <span class="hide">Fim do conteúdo da página</span>                         
                        </section>
                    </div>
                    <!-- fim #content.span9 -->
                </div>
                <!-- fim .row-fluid -->
            </div>
            <!-- fim .container -->
        </main>
        <footer>
            <div class="footer-atalhos">
                <div class="container">
                    <div class="pull-right voltar-ao-topo"><a href="#portal-siteactions"><i class="icon-chevron-up"></i>&nbsp;Voltar para o topo</a></div>
                </div>
            </div>
            <div class="container container-menus">
                <div id="footer" class="row footer-menus">
                    <span class="hide">Início da navegação de rodapé</span>
                    <jdoc:include type="modules" name="menus-rodape" style="div_nav_rodape" headerLevel="2" />
                    <span class="hide">Fim da navegação de rodapé</span>                    
                </div>
                <!-- fim .row -->
            </div>
            <!-- fim .container -->
            <div class="footer-logos">
                <div class="container">
                    <?php if( $this->params->get('rodape_acesso_informacao', 1) == 1 ): ?>
                        <a href="http://www.acessoainformacao.gov.br/" class="logo-acesso pull-left"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/acesso-a-informacao.png" alt="Acesso a Informação"></a>
                    <?php endif; ?>
                    <?php if( $this->params->get('rodape_logo_brasil', 1) == 1 ): ?>
                        <!-- separador para fins de acessibilidade --><span class="hide">&nbsp;</span><!-- fim separador para fins de acessibilidade -->                    
                        <a href="http://www.brasil.gov.br/" class="brasil pull-right"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/brasil.png" alt="Brasil - Governo Federal"></a>
                    <?php endif; ?>
                </div>              
            </div>
            <div class="footer-ferramenta">
                <div class="container">
                    <?php echo $this->params->get('mensagem_final_ferramenta', '<p>Interface preparada para desenvolvimento com o CMS <a href="http://www.joomla.org">Joomla</a></p>'); ?>                            
                </div>              
            </div>
            <div class="footer-atalhos visible-phone">
                <div class="container">
                    <span class="hide">Fim do conteúdo da página</span>
                    <div class="pull-right voltar-ao-topo"><a href="#portal-siteactions"><i class="icon-chevron-up"></i>&nbsp;Voltar para o topo</a></div>
                </div>
            </div>
        </footer>       
    </div>
    <!-- fim div#wrapper -->    
    <!-- scripts principais do template --> 
    <?php if(TmplPadraoGoverno01Helper::inFooter('local_jquery', $this)) TmplPadraoGoverno01Helper::getJqueryScripts( $this ); ?>
    <?php if(TmplPadraoGoverno01Helper::inFooter('local_mainscript', $this)) TmplPadraoGoverno01Helper::getTemplateMainScripts( $this ); ?>
    <?php if($this->countModules('barra-do-governo') && $this->params->get('anexar_js_barra2014')) TmplPadraoGoverno01Helper::getBarra2014Script( $this ); ?>
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
    <jdoc:include type="modules" name="debug" />
    <?php TmplPadraoGoverno01Helper::debug( @$preffix, @$active_item); ?>
</body>
</html>