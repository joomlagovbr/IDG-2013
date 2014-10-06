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
		$limit = (int) $params->get('limit_compromissos', 0);
		$order = $params->get('order_compromissos', 'NEXT_DESC');

		if(empty($params->get('dia', '')))
			$params->set('dia', self::getDia($params) );

		JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_agendadirigentes/models', 'AgendaDirigentesModel');
		$model = JModelLegacy::getInstance('Compromissos', 'AgendaDirigentesModel', array('ignore_request' => true));
		$model->setState('autoridade.id', $id);
		$model->setState('participantes.load', false);
		$model->setState('params', $params);

		$items = $model->getItems();
		for ($i=0, $limit=count($items); $i < $limit; $i++) { 
			$items[$i]->horario_inicio = str_replace(':', 'h', $items[$i]->horario_inicio);
			$items[$i]->horario_fim = str_replace(':', 'h', $items[$i]->horario_fim);
		}
		return $items;
	}

	public static function getDia( &$params )
	{
		if(empty($params->get('dia', '')))
		{		
			$params->set('dia', self::getDate());
		}

		return $params->get('dia');
	}

	public static function getFormatedDate( &$params )
	{
		if(empty($params->get('dia', '')))
			$dia = self::getDia($params);
		else
			$dia = $params->get('dia');

		@$dia_por_extenso = new JDate( $dia );
		
		if(is_null($dia_por_extenso))
			$dia_por_extenso = new JDate( self::getDate() );

		return strtolower( $dia_por_extenso->format( 'd \d\e F \d\e Y' ) );
	}

	public static function getDate()
	{
		$app = JFactory::getApplication();
		$date = new JDate();
		return $date->format('Y-m-d', $app->getCfg('offset'));	
	}
}
