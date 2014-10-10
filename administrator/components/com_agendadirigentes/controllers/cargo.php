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
 * AgendaCargos Controller
 */
class AgendaDirigentesControllerCargo extends JControllerForm
{
	 /**
     * Implement to allowAdd or not
     *
     * Not used at this time (but you can look at how other components use it....)
     * Overwrites: JControllerForm::allowAdd
     *
     * @param array $data
     * @return bool
     */
    protected function allowAdd($data = array())
    {
        return parent::allowAdd($data);
    }

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
      
            $user = JFactory::getUser();
            $coreEdit = $user->authorise( "core.edit", $this->option );
            $categoryEdit = $user->authorise( "cargos.edit", $this->option . ".category." . $item->catid );

            $params = JComponentHelper::getParams( $this->option );
            $permissionType = $params->get('permissionsType', 'implicit');
            
            if($permissionType == 'implicit')
            {
                if($coreEdit && $categoryEdit !== false)
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
            }
            return false;
        }

        return true;
    }
}