<?php
/**
 * @package     Joomlagovbr
 * @subpackage  mod_barradogoverno
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;

class ModAgendaDirigentesHelper
{
	public static function getItems( &$params )
	{
		$id = (int) $params->get('autoridade', 0);
		$modo = $params->get('modo_agenda', '');
		$limit = (int) $params->get('limit_compromissos', -1);
		$order = $params->get('order_compromissos', 'NEXT_DESC');
		$params_dia = $params->get('dia', '');
		$featured = $params->get('featured_compromissos', '');

		if(!empty($featured) && !is_numeric($featured))
			$featured = '';

		if( empty($params_dia) )
			$params->set('dia', self::getDia($params) );

		JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_agendadirigentes/models', 'AgendaDirigentesModel');
		$model = JModelLegacy::getInstance('Compromissos', 'AgendaDirigentesModel', array('ignore_request' => true));
		$model->setState('autoridade.id', $id);
		$model->setState('participantes.load', false);
		$model->setState('filter.featured', $featured);
		$model->setState('params', $params);

		if($limit > -1)
			$model->setState('list.limit', $limit);

		$items = $model->getItems();
		for ($i=0, $limit=count($items); $i < $limit; $i++) { 
			$items[$i]->horario_inicio = str_replace(':', 'h', $items[$i]->horario_inicio);
			$items[$i]->horario_fim = str_replace(':', 'h', $items[$i]->horario_fim);
		}
		return $items;
	}

	public static function getDia( &$params )
	{
		$params_dia = $params->get('dia', '');

		if( empty($params_dia) )
		{		
			$params->set('dia', self::getDate());
		}

		return $params->get('dia');
	}

	public static function getFormatedDate( &$params )
	{
		$params_dia = $params->get('dia', '');

		if( empty($params_dia) )
			$dia = self::getDia($params);
		else
			$dia = $params->get('dia');

		@$dia_por_extenso = new JDate( $dia );
		
		if(is_null($dia_por_extenso))
			$dia_por_extenso = new JDate( self::getDate() );

		return strtolower( $dia_por_extenso->format( JText::_('MOD_AGENDADIRIGENTES_HELPER_DATE_FORMAT') ) );
	}

	public static function getDate()
	{
		$app = JFactory::getApplication();
		$date = new JDate('now', $app->getCfg('offset'));
		return $date->format('Y-m-d', $app->getCfg('offset'));	
	}

	public static function getAlturaLista( $params )
	{
		$altura_lista = $params->get('altura_lista', '');
		if(empty($altura_lista))
			return '';

		$altura_lista = preg_replace('/[^0-9]/', '', $altura_lista);
		$altura_lista = (int) $altura_lista;

		if($altura_lista == 0)
			return '';

		return $altura_lista;
	}
}
