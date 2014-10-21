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
class AgendaDirigentesViewAutoridade extends JViewLegacy
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
            $this->autoridade = $this->get('Item');
            $this->compromissos = $this->get('Compromissos');
            $this->params = $this->state->get('params');
            
            if(@$this->autoridade->state < 1 || empty($this->autoridade))
            {
                    JLog::add('Autoridade n&atilde;o encontrada', JLog::WARNING, 'jerror');
                    return false;                        
            }


            // Check for errors.
            if (count($errors = $this->get('Errors'))) 
            {
                    JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
                    return false;
            }
            $this->_prepareDocument();

            // Display the view
            parent::display($tpl);
        }

        protected function _prepareDocument()
        {
            $app            = JFactory::getApplication();
            $menus          = $app->getMenu();
            $pathway        = $app->getPathway();
            $title          = null;
            $activeMenu = $menus->getActive();
            $template   = $app->getTemplate(true);
            $this->templatevar = $app->input->getCmd('template');

            $this->Itemid = $app->input->getInt('Itemid', 0);
            
            //page_heading
            @$params_page_title = $this->params->get('page_title', '');
            @$activeMenu_title = $activeMenu->title;

            if( empty($params_page_title) || $params_page_title==$activeMenu_title )
            {
                    if(@isset($this->autoridade->car_name)===false || @isset($this->autoridade->dir_name)===false)
                            $this->page_heading = 'Agenda de Autoridade';
                    else
                    {
                        if($this->autoridade->sexo == 'M')
                        {
                            $this->page_heading = 'Agenda do ' . $this->autoridade->car_name
                                                    . ' ' . $this->autoridade->dir_name;
                        }
                        else
                        {
                            $this->page_heading = 'Agenda da ' . $this->autoridade->car_name_f
                                                    . ' ' . $this->autoridade->dir_name;
                        }                            
                    }
            }
            else
            {
                    $this->page_heading = $this->params->get('page_title', '');
            }
            
            $this->page_heading = $this->escape($this->page_heading);
            $pathway->addItem($this->page_heading);

            if( $this->templatevar =='system')
                $this->document->setTitle( $this->page_heading );

            //sharing
            $this->sharing = '';
            if ( $this->params->get('sharing_type')=='html' )
            {
                $this->sharing = $this->params->get('sharing_code', '');
            }
            elseif ( $this->params->get('sharing_type')=='module' )
            {
                @$modulesSocial = JModuleHelper::getModules( $this->params->get('sharing_mod_position') );
                $countModulesSocial = count($modulesSocial);
                if ($countModulesSocial)
                {
                    $moduleSocialTmpl = $this->loadTemplate('sharingmodule');
                    for ($i=0; $i < $countModulesSocial; $i++)
                    { 
                        $module = JModuleHelper::renderModule( $modulesSocial[$i] );
                        $module = str_replace('{SITE}', JURI::root(), $module);
                        $module = str_replace('{MODULE}', $module, $moduleSocialTmpl);
                        $this->sharing .= $module . "\n";
                    }                    
                }
            }

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

            //link reporte erro
            $this->link_reportar_erro = $this->params->get('link_reportar_erro', '');

            if( !empty($this->link_reportar_erro) )
            {
                $this->link_reportar_erro = str_replace('{SITE}/', JURI::root(), $this->link_reportar_erro );

                if(strpos($this->link_reportar_erro, '{SITE}')!==false)
                    $this->link_reportar_erro = str_replace('{SITE}', JURI::root(), $this->link_reportar_erro );
            }
            else
            {
                $this->link_reportar_erro = '';
            }

            //dia por extenso
            $this->dia_por_extenso = '';
            @$dia_por_extenso = new JDate( $this->params->get('dia') );
            if ( !empty($dia_por_extenso) )
            {
                $this->dia_por_extenso = $dia_por_extenso->format( 'l, ' )
                                        . strtolower( $dia_por_extenso->format( 'd \d\e F \d\e Y' ) );
            
            }

        }

        public function mergeParams($app_params, $item_params)
        {
           
            if(!is_object($app_params))
            {
                $params = new JRegistry;
                $params->loadString( $app_params );
                $app_params = $params;
            }

            if(!is_object($item_params))
            {
                $params = new JRegistry;
                $params->loadString( $item_params );
                $item_params = $params;
            }

            $app_params->merge( $item_params );
            return $app_params;
        }

        public function prepararCompromisso( $compromisso )
        {
            $compromisso->horario_inicio = str_replace(':', 'h', $compromisso->horario_inicio);
            $compromisso->horario_fim = str_replace(':', 'h', $compromisso->horario_fim);
            $compromisso->params = $this->mergeParams($this->params, $compromisso->params);             
            $compromisso->link_vcalendar = JURI::root() . 'index.php?option=com_agendadirigentes&view=compromisso&format=vcs&id=' . $compromisso->id;
            return $compromisso;
        }

}

?>