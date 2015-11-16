<?php
/**
 * @package    Joomla.Installation
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$doc = JFactory::getDocument();

// Add Stylesheets
JHtml::_('bootstrap.loadCss', true, $this->direction);
JHtml::_('stylesheet', 'installation/template/css/template.css');

// Load the JavaScript behaviors
JHtml::_('bootstrap.framework');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.framework', true);
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
JHtml::_('script', 'installation/template/js/installation.js');

// Load the JavaScript translated messages
JText::script('INSTL_PROCESS_BUSY');
JText::script('INSTL_FTP_SETTINGS_CORRECT');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
		<jdoc:include type="head" />
		<!--[if lt IE 9]>
			<script src="../media/jui/js/html5.js"></script>
		<![endif]-->
		<script type="text/javascript">
			jQuery(function()
			{	// Delay instantiation after document.formvalidation and other dependencies loaded
				window.setTimeout(function(){
					window.Install = new Installation('container-installation', '<?php echo JUri::current(); ?>');
			   	}, 500);

			});
		</script>
		<!-- portalpadrao: adicao de css do template padrao -->
		<link rel="stylesheet" href="<?php echo JURI::root(); ?>templates/padraogoverno01/css/template-verde.css" type='text/css'/>
    	<link rel="stylesheet" href="<?php echo JURI::root(); ?>templates/padraogoverno01/css/fontes.css" type='text/css'/>
		<!-- portalpadrao: fim adicao de css do template padrao -->
		<!-- portalpadrao: adicao barra do governo no head (vide topo e final do documento) -->
		<style>
		#barra-brasil li {line-height: inherit} /*ajuste para nao haver conflito com css do bootstrap*/
		</style>
		<!-- portalpadrao: fim adicao barra do governo no head (vide topo e final do documento) -->
	</head>
	<body>
		<!-- portalpadrao: adicao barra do governo no topo (vide final do documento) -->
		<div id="barra-brasil" style="background:#7F7F7F; height: 20px; padding:0 0 0 10px;display:block;"> 
			<ul id="menu-barra-temp" style="list-style:none;">
				<li style="display:inline; float:left;padding-right:10px; margin-right:10px; border-right:1px solid #EDEDED"><a href="http://brasil.gov.br" style="font-family:sans,sans-serif; text-decoration:none; color:white;">Portal do Governo Brasileiro</a></li> 
				<li><a style="font-family:sans,sans-serif; text-decoration:none; color:white;" href="http://epwg.governoeletronico.gov.br/barra/atualize.html">Atualize sua Barra de Governo</a></li>
			</ul>
		</div>
		<!-- portalpadrao: fim adicao barra do governo no topo (vide final do documento) -->
		<!-- portalpadrao: adicao de cabecalho especifico -->
		<header>
            <div class="container">
                <div class="row-fluid">
                    <div class="span12 small" id="logo" align="center">
                        <a title="Nome principal" href="http://github.com/joomlagovbr" target="_blank">
                            <h1 class="portal-title corto">Portal Institucional Padr&atilde;o do Governo Federal</h1>
                            <span class="portal-description">instalador para o CMS Joomla!</span>
                        </a>
                    </div>
                    <!-- fim .span8 -->

                </div>
                <!-- fim .row-fluid -->
            </div>
            <!-- fim div.container -->
        </header>
		<!-- portalpadrao: fim adicao de cabecalho especifico -->
		<!-- Header -->
		<div class="header">
			<!-- portalpadrao: trecho do instalador original comentado -->
			<!-- <img src="<?php echo $this->baseurl ?>/template/images/joomla.png" alt="Joomla" />
			<hr /> -->
			<!-- portalpadrao: fim trecho do instalador original comentado -->
			<h5>
				<?php
				// Fix wrong display of Joomla!® in RTL language
				if (JFactory::getLanguage()->isRtl())
				{
					$joomla = '<a href="http://www.joomla.org" target="_blank">Joomla!</a><sup>&#174;&#x200E;</sup>';
				}
				else
				{
					$joomla = '<a href="http://www.joomla.org" target="_blank">Joomla!</a><sup>&#174;</sup>';
				}
				$license = '<a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html" target="_blank">' . JText::_('INSTL_GNU_GPL_LICENSE') . '</a>';
				echo JText::sprintf('JGLOBAL_ISFREESOFTWARE', $joomla, $license);
				?>
			</h5>
		</div>
		<!-- Container -->
		<div class="container">
			<jdoc:include type="message" />
			<div id="javascript-warning">
				<noscript>
					<div class="alert alert-error">
						<?php echo JText::_('INSTL_WARNJAVASCRIPT'); ?>
					</div>
				</noscript>
			</div>
			<div id="container-installation">
				<jdoc:include type="component" />
			</div>
			<hr />
		</div>
		<script>
			function initElements()
			{
				(function($){
					$('.hasTooltip').tooltip()

					// Chosen select boxes
					$("select").chosen({
						disable_search_threshold : 10,
						allow_single_deselect : true
					});

					// Turn radios into btn-group
				    $('.radio.btn-group label').addClass('btn');
				    $(".btn-group label:not(.active)").click(function()
					{
				        var label = $(this);
				        var input = $('#' + label.attr('for'));

				        if (!input.prop('checked'))
						{
				            label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
				            if(input.val()== '')
							{
				                    label.addClass('active btn-primary');
				             } else if(input.val()==0 || input.val()=='remove')
							{
				                    label.addClass('active btn-danger');
				             } else {
				            label.addClass('active btn-success');
				             }
				            input.prop('checked', true);
				        }
				    });
				    $(".btn-group input[checked=checked]").each(function()
					{
						if ($(this).val()== '')
						{
				           $("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
				        } else if($(this).val()==0 || $(this).val()=='remove')
						{
				           $("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
				        } else {
				            $("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
				        }
				    });
				})(jQuery);
			}
			initElements();
		</script>
		<!-- portalpadrao: adicao barra do governo no final do documento -->
		<script src="//barra.brasil.gov.br/barra.js" type="text/javascript"></script><noscript>Barra do governo federal depende de javascript habilitado.</noscript>
		<!-- portalpadrao: fim adicao barra do governo no final do documento -->
	</body>
</html>
