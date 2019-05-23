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
    /**
     * Implement to allow edit or not
     * Overwrites: JControllerForm::allowEdit
     *
     * @param array $data
     * @param string $key
     * @return bool
     */
    
	protected function allowEdit($data = array(), $key = 'id')
	{
		$id = isset( $data[ $key ] ) ? $data[ $key ] : 0;

        if( !empty( $id ) )
        {
            $model = $this->getModel();
            $item = $model->getTable();
            $item->load( $id );
            
            list($canManage, $canChange) = AgendaDirigentesHelper::getGranularPermissions('dirigentes', $item, 'manage' );
            
            return $canManage;          
        }

        return true;
	}

}