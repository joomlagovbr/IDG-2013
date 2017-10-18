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
TmplPadraoGoverno01Helper::init( $this ); //inicializacao de funcoes do template, como configuracao de cor, se alterada via get, limpeza do head padrao do joomla e outras providencias.
$active_item = TmplPadraoGoverno01Helper::getActiveItemid();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="head" />
	<?php if(TmplPadraoGoverno01Helper::isProtostarCssNeededForComponent()): ?>
	    <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/protostar/css/template.css" type='text/css'/>
	<?php endif; ?>
</head>
<body class="contentpane">
	<div id="all">
		<div id="main">
			<jdoc:include type="message" />
			<jdoc:include type="component" />
		</div>
	</div>
</body>
</html>