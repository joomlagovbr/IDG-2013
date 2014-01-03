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
	static function getScripts( &$tmpl )
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

	static function clearDefaultScripts( &$tmpl, $return = false )
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
	static function writeScripts($javascript, &$tmpl )
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

	static function getJqueryScripts( &$tmpl )
	{
		?>
		<script src="<?php echo $tmpl->baseurl; ?>/templates/<?php echo $tmpl->template; ?>/js/jquery.min.js" type="text/javascript"></script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
    	<script src="<?php echo $tmpl->baseurl; ?>/templates/<?php echo $tmpl->template; ?>/js/jquery-noconflict.js" type="text/javascript"></script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
		<?php
	}

	static function getTemplateMainScripts( &$tmpl )
	{
		?>
		<script src="<?php echo $tmpl->baseurl; ?>/templates/<?php echo $tmpl->template; ?>/bootstrap/js/bootstrap.min.js" type="text/javascript"></script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
	    <script src="<?php echo $tmpl->baseurl; ?>/templates/<?php echo $tmpl->template; ?>/js/jquery.cookie.js" type="text/javascript"></script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
	    <script src="<?php echo $tmpl->baseurl; ?>/templates/<?php echo $tmpl->template; ?>/js/template.js" type="text/javascript"></script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
		<?php
	}

	static function beforeHead( $param='', &$tmpl )
	{
		if ($tmpl->params->get( $param, 'footer') == 'before_head') {
			return true;
		}
		return false;
	}

	static function afterHead( $param='', &$tmpl )
	{
		if ($tmpl->params->get( $param, 'footer') == 'after_head') {
			return true;
		}
		return false;
	}

	static function inFooter( $param='', &$tmpl )
	{
		if ($tmpl->params->get( $param, 'footer') == 'footer') {
			return true;
		}
		return false;
	}

	static function getBarra2014Script( &$tmpl )
	{
		?>
		<script src="<?php echo $tmpl->params->get('endereco_js_barra2014'); ?>" type="text/javascript"></script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
		<?php
	}

	static function getFontStyle( &$tmpl )
	{
		if($tmpl->params->get('font_style_url', '') != 'NENHUM'):
		?>
		<link href='<?php echo str_replace(array('{SITE}', '{LOCALFONT}'), array(substr(JURI::root(),0,-1), JURI::root().'templates/'.$tmpl->template.'/css/fontes.css'), $tmpl->params->get('font_style_url', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,800,700') ); ?>'  rel='stylesheet' type='text/css'>
		<?php
		endif;
	}

	static function getActiveItemid()
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$itemid = $jinput->get('Itemid', 0, 'integer');
		$menu = $app->getMenu();
		return $menu->getItem($itemid);		
	}

	static function getItemidParam( $activeItemid, $param )
	{
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		
		if(!$activeItemid)
			return '';
		
		$params = $menu->getParams( $activeItemid->id );
		return $params->get( $param );
	}

	static function getPageClass( $activeItemid, $only_class = false, $pageclass = false )
	{
		$class = TmplPadraoGoverno01Helper::getItemidParam($activeItemid, 'pageclass_sfx');

		if($only_class)
			return $class;

		if(!empty($class) && $pageclass)
			$class = 'pagina-'.$class;

		if(! empty($class))
			$class = 'class="'.$class.'"';
		else
			$class = '';

		return $class;
	}

	static function getPagePositionPreffix($activeItemid)
	{
		$pos_preffix = TmplPadraoGoverno01Helper::getPageClass($activeItemid, true);		
		if(empty($pos_preffix))
		{
			$jinput = JFactory::getApplication()->input;
			$option = $jinput->get('option', '', 'string');
			$view   = $jinput->get('view', '', 'string');
			$pos_preffix = $option . '-' . $view;
		}
		else
		{
			$pos_preffix = explode(' ',$pos_preffix);
			$pos_preffix = $pos_preffix[0];
		}
		return $pos_preffix;
	}

	static function isOnlyModulesPage()
	{
		$jinput = JFactory::getApplication()->input;
		$option = $jinput->get('option', '', 'string');
		
		//informar aqui componentes que desejar utilizar para páginas internas de capa, que exibirão somente modulos:
		$onlyModules = array('com_blankcomponent','NOME_OUTRO_COMPONENTE');

		if(in_array($option, $onlyModules))
			return true;

		return false;
	}

	static function loadModuleByPosition($position = NULL, $attribs = array(), $modules = NULL) //TmplPadraoGoverno01Helper::loadModuleByPosition('')
	{
		if(is_null($modules))
			$modules = JModuleHelper::getModules( $position );
		else if(is_null($position))
			return;

		foreach ($modules as $k => $mod):
			if(count($attribs) > 0)
			{
				//correcoes utilizadas para menu de redes sociais, no rodape, por exemplo
				if(@$attribs['replaceHTMLentities']=='1')
				{
					$mod = JModuleHelper::renderModule($mod, $attribs);
					$mod = str_replace(array('&lt;', '&gt;','<i', 'i>'), array('<','>', '<span', 'span>'), $mod);
					echo $mod;
				}
				else
					echo JModuleHelper::renderModule($mod, $attribs);							
			}
			else
				echo JModuleHelper::renderModule($mod);

		endforeach;
	}

	static function getModules($position = NULL)
	{
		if(is_null($position))
			return array();

		$modules = JModuleHelper::getModules( $position );
		return $modules;
	}

	static function hasMessage()
	{
        if (count(JFactory::getApplication()->getMessageQueue()) > 0)
        	return true;

        return false;
	}

	static function debug( $preffix = '', $active_item = 0 )
	{
		if(JApplication::getCfg('debug')==1)
		{
			// var_dump($active_item);
			echo '<strong>Debug de template</strong><br />';
			echo '<strong>Prefixo de posicoes de modulo:</strong> '.$preffix.'<br />';
			echo '<strong>ID Item de menu ativo:</strong> '.$active_item->id.'<br />';
			echo '<strong>LINK Item de menu ativo:</strong> '.$active_item->link.'<br />';
		}
	}
}
