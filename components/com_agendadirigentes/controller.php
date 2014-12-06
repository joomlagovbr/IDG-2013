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

// importar library de controllers do Joomla
jimport('joomla.application.component.controller');
 
/**
 * Agenda de Dirigentes Component Controller
 *
 * @since   0.0.1
 */
class AgendaDirigentesController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		$cachable = true;
		$vName = $this->input->get('view', 'autoridades');
		$this->input->set('view', $vName);

		parent::display($cachable, $urlparams);

		return $this;
	}
}
?>