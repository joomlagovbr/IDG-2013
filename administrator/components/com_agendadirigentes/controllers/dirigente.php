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
class AgendaDirigentesControllerDirigente extends JControllerForm
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
    protected function allowEdit($data = array(), $key = 'cargo_id')
    {
        $cargo_id = isset( $data[ $key ] ) ? $data[ $key ] : 0;
        if( !empty( $cargo_id ) ){
            $user = JFactory::getUser();
            return $user->authorise( "dirigentes.edit", "com_agendadirigentes.cargo." . $cargo_id );
        }
    }
}