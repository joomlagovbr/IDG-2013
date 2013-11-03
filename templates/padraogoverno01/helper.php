<?php
/**
 * @package     Joomlagovbr
 * @subpackage  tmpl_padraogoverno01
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;

class TmplPadraoGoverno01Helper
{
	function getScripts( &$tmpl )
	{
		$javascript_on_footer     = $tmpl->params->get('javascript_on_footer', 0);
		$clear_default_javascript = $tmpl->params->get('clear_default_javascript', 0);

		if ( $javascript_on_footer==1 )
		{
			return TmplPadraoGoverno01Helper::clearDefaultScripts( $tmpl, true);
		}
		else if($clear_default_javascript==1)
		{
			TmplPadraoGoverno01Helper::clearDefaultScripts( $tmpl );
		}
		
		return array('scripts'=> array(), 'script'=> array());
	}

	function clearDefaultScripts( &$tmpl, $return = false )
	{
		$clear_default_javascript = $tmpl->params->get('clear_default_javascript', 0);
		$new_scripts = $scripts = $tmpl->_scripts; 		
		$new_script  = $script = $tmpl->_script;

		if ($clear_default_javascript == 1) {
	 		unset($new_scripts[$tmpl->baseurl.'/media/system/js/mootools-core.js']);
			unset($new_scripts[$tmpl->baseurl.'/media/system/js/core.js']);
			unset($new_scripts[$tmpl->baseurl.'/media/system/js/caption.js']);        

	 		$limit_new_script = count($new_script);
	 		foreach ($new_script as $k => $v) {
	 			if(strpos($v, "new JCaption('img.caption');") !== false){
	 				unset($new_script[$k]);
					break; 				
	 			}
	 		}
			$tmpl->_scripts = $new_scripts;
			$tmpl->_script  = $new_script;
		}

		if($return)
		{
			$return_array = array();
			$return_array['scripts'] = $scripts;
			$return_array['script']  = $script;
			return $return_array;			
		}		
	}

	/*
	* coloca scripts no rodape. Codigo base original do joomla que renderiza o head está em /libraries/joomla/document/html/renderer/head.php
	*/
	function writeScripts($javascript, &$tmpl )
	{
		$document =& JFactory::getDocument();
		$lnEnd    = $document->_getLineEnd();
		$tab      = $document->_getTab();
		$buffer   = '';

		foreach ($javascript['scripts'] as $strSrc => $strAttr)
		{
			$buffer .= $tab . '<script src="' . $strSrc . '"';
			if (!is_null($strAttr['mime']))
			{
				$buffer .= ' type="' . $strAttr['mime'] . '"';
			}
			if ($strAttr['defer'])
			{
				$buffer .= ' defer="defer"';
			}
			if ($strAttr['async'])
			{
				$buffer .= ' async="async"';
			}
			$buffer .= '></script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>' . $lnEnd;
		}
		
		foreach ($javascript['script'] as $type => $content)
		{
			$buffer .= $tab . '<script type="' . $type . '">' . $lnEnd;

			$buffer .= $content . $lnEnd;

			$buffer .= $tab . '</script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>' . $lnEnd;
		}

		echo $buffer;
	}

	function getJqueryScripts( &$tmpl )
	{
		?>
		<script src="<?php echo $tmpl->baseurl; ?>/templates/<?php echo $tmpl->template; ?>/js/jquery.min.js" type="text/javascript"></script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
    	<script src="<?php echo $tmpl->baseurl; ?>/templates/<?php echo $tmpl->template; ?>/js/jquery-noconflict.js" type="text/javascript"></script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
		<?php
	}

	function jqueryBeforeHead( &$tmpl )
	{
		if ($tmpl->params->get('local_jquery', 'footer') == 'before_head') {
			return true;
		}
		return false;
	}

	function jqueryAfterHead( &$tmpl )
	{
		if ($tmpl->params->get('local_jquery', 'footer') == 'after_head') {
			return true;
		}
		return false;
	}

	function jqueryInFooter( &$tmpl )
	{
		if ($tmpl->params->get('local_jquery', 'footer') == 'footer') {
			return true;
		}
		return false;
	}

	function getBarra2014Script( &$tmpl )
	{
		?>
		<script src="<?php echo $tmpl->params->get('endereco_js_barra2014'); ?>" type="text/javascript"></script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
		<?php
	}

	function getFontStyle( &$tmpl )
	{
		if($tmpl->params->get('font_style_url', '') != 'NENHUM'):
		?>
		<link href='<?php echo $tmpl->params->get('font_style_url', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,800,700'); ?>'  rel='stylesheet' type='text/css'>
		<?php
		endif;
	}

	function getActiveItemid()
	{
		$jinput = JFactory::getApplication()->input;
		$itemid = $jinput->get('Itemid', 0, 'integer');
		$menu =& JSite::getMenu();
		return $menu->getItem($itemid);		
		//$params = $menu->getParams( $active->id );
		//$pageclass = $params->get( 'pageclass_sfx' );
	}

	function getItemidParam( $activeItemid, $param )
	{
		$menu   =& JSite::getMenu();
		
		if(!$activeItemid)
			return '';
		
		$params = $menu->getParams( $activeItemid->id );
		return $params->get( $param );
	}

	function getPageClass( $activeItemid, $only_class = false )
	{
		$class = TmplPadraoGoverno01Helper::getItemidParam($activeItemid, 'pageclass_sfx');

		if($only_class)
			return $class;

		if(! empty($class))
			$class = 'class="'.$class.'"';
		else
			$class = '';

		return $class;
	}

	function getPagePositionPreffix($activeItemid)
	{
		$pos_preffix = TmplPadraoGoverno01Helper::getPageClass($activeItemid, true);
		if(empty($pos_preffix))
		{
			$jinput = JFactory::getApplication()->input;
			$option = $jinput->get('option', '', 'string');
			$view   = $jinput->get('view', '', 'string');
			$pos_preffix = $option . '-' . $view;
		}
		return $pos_preffix;
	}

	function hasMessage()
	{
        if (count(JFactory::getApplication()->getMessageQueue()) > 0)
        	return true;

        return false;
	}
}
