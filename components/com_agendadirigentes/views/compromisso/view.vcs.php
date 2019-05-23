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

// importar library de views do Joomla
jimport('joomla.application.component.view');
 
/**
* HTML View class para AgendaDirigentes Component
*
* @since 0.0.1
*/
class AgendaDirigentesViewCompromisso extends JViewLegacy
{

        /**
         * Apresenta a view Autoridade
         *
         * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
         *
         * @return  void
         */
        public function display($tpl = null) 
        {
            $this->state = $this->get('State');
            $this->params = $this->state->get('params');
            $this->compromisso = $this->get('Item');            
            $this->_prepareDocument();

//NAO EDENTAR AS LINHAS ABAIXO
            header("Content-Type: text/x-vCalendar");
            header("Content-Disposition: inline; filename=compromisso-da-autoridade-"
                    . $this->compromisso->id . ".vcs"); ?>
BEGIN:VCALENDAR
PRODID:-//<?php echo $this->nome_orgao ?>//<?php echo JText::_('COM_AGENDADIRIGENTES_VIEW_COMPROMISSO_DEFAULT_TITLE') ?>//<?php echo JText::_('COM_AGENDADIRIGENTES_VIEW_COMPROMISSO_LANG_PREFIX') . "\n"; ?>
VERSION:1.0
BEGIN:VEVENT
DTSTART:<?php echo $this->dtstart . "\n"; ?>
DTEND:<?php echo $this->dtend . "\n"; ?>
DCREATED:<?php echo $this->dcreated . "\n"; ?>
UID:Compromisso-<?php echo $this->compromisso->id . "\n"; ?>
STATUS:<?php echo $this->status . "\n"; ?>
SEQUENCE:0
LAST-MODIFIED:<?php echo $this->dmodified . "\n"; ?>
SUMMARY:<?php echo $this->compromisso->title . "\n"; ?>
DESCRIPTION;ENCODING=QUOTED-PRINTABLE:<?php echo $this->description . "\n"; ?>
LOCATION:<?php echo $this->compromisso->local . "\n"; ?>
PRIORITY:3
TRANSP:0
END:VEVENT
END:VCALENDAR
            <?php
            exit();
        }

        protected function _prepareDocument()
        {
            $app            = JFactory::getApplication();
            $template   = $app->getTemplate(true);
            
            //nome_orgao
            $this->nome_orgao = '';
            if ($this->params->get('fonte_nome_orgao')=='custom')
            {
                $this->nome_orgao = $this->params->get('custom_nome_orgao', '');                    
            }
            elseif($this->params->get('fonte_nome_orgao')=='site_name')
            {
                $this->nome_orgao = $app->getCfg('sitename');
            }
            elseif($this->params->get('fonte_nome_orgao')=='tmpl_padraogoverno01')
            {
                $this->nome_orgao = $template->params->get('denominacao', '')
                                    . ' '. $template->params->get('nome_principal', '');
            }

            $this->dtstart = $this->_formatDate( $this->compromisso->data_inicial
                                                 . ' ' . $this->compromisso->horario_inicio );

            $this->dtend = $this->_formatDate( $this->compromisso->data_final
                                                 . ' ' . $this->compromisso->horario_fim );

            $this->dcreated = $this->_formatDate( $this->compromisso->created );
            $this->dmodified = $this->_formatDate( $this->compromisso->modified );

            $search = array('</p>','<br />', '<br>');
            $replace = "=0D=0A";
            $this->compromisso->description = str_ireplace($search, $replace, $this->compromisso->description);
            $this->description = strip_tags( $this->compromisso->description );


            if ($this->compromisso->state > 0)
            {
                $this->status = JText::_('COM_AGENDADIRIGENTES_VIEW_COMPROMISSO_STATUS_CONFIRMED');
            }
            else
            {
                $this->status = JText::_('COM_AGENDADIRIGENTES_VIEW_COMPROMISSO_STATUS_CANCELLED');
            }
           
        }

        protected function _formatDate( $date )
        {
            list($date, $time) = explode(' ', $date);
            $date = explode('-', $date);
            $time = explode(':', $time);
            if(count($time)==2)
                $time[] = '00';

            return $date[0].$date[1].$date[2].'T'.$time[0].$time[1].$time[2]; //Z
        }

}

?>