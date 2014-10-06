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
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 
/**
 * AgendaDirigentes Controller
 */
class AgendaDirigentesControllerCompromisso extends JControllerForm
{

	protected function allowEdit($data = array(), $key = 'id')
	{
		@$id = (int) $data['id'];

		if( ! $id )
			return false;

		$model = $this->getModel();
		$item = $model->getTable();
		$item->load( $id );
		
		if( !isset($item->catid) )
			return false;

		$user = JFactory::getUser();
		$coreEdit = $user->authorise( "core.edit", $this->option );
		$coreEditOwn = $user->authorise( "core.edit.own", $this->option );
		$categoryEdit = $user->authorise( "core.edit", $this->option . ".category." . $item->catid );
		$categoryEditOwn = $user->authorise( "core.edit.own", $this->option . ".category." . $item->catid );

		$params = JComponentHelper::getParams( $this->option );
        $permissionType = $params->get('permissionsType', 'implicit');

        if($permissionType == 'implicit')
        {
			if($coreEdit && $categoryEdit !== false)
			{
				return true;
			}

			if($coreEditOwn && $categoryEditOwn !== false && $item->created_by == $user->id)
			{
				return true;
			}			

        }
		elseif( $permissionType == 'explicit' )
		{
			if($coreEdit && $categoryEdit)
			{
				return true;
			}

			if($coreEditOwn && $categoryEditOwn && $item->created_by == $user->id)
			{
				return true;
			}	
		}

		return false;
	}

	

}