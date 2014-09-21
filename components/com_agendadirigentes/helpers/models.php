<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agendadirigentes
 */

defined('_JEXEC') or die;

class AgendadirigentesModels
{
	public static function setParamBeforeSetState( $var = 'dia', $format = 'DataBanco', $emptyValue = NULL )
	{
		$app = JFactory::getApplication();
        $params = $app->getParams();
        $input = $app->input;

        if ( !empty($input->get( $var, '')) || !empty($params->get( $var, '')) )
        {
        	if(!is_file(JPATH_COMPONENT_ADMINISTRATOR .'/models/rules/' . strtolower($format) .'.php'))
        	{
        		JLog::add('Erro na helper do arquivo models.php. Arquivo de formato nao encontrado.', JLog::WARNING, 'jerror');
                return false;
        	}

            require_once( JPATH_COMPONENT_ADMINISTRATOR .'/models/rules/' . strtolower($format) .'.php' );
            $object = 'JFormRule' . $format;
            $rule = new $object();

            if ( !empty($input->get( $var, '')) )
            {
                if (preg_match($rule->getRegex(), $input->get( $var, '')))
                    $params->set( $var, $input->get( $var));
            }
            elseif(!empty($params->get( $var, '')) && !preg_match($rule->getRegex(), $params->get( $var, '')))
            {
                $params->set( $var, '');
            }
        }
        if ( empty($input->get( $var, '')) && empty($params->get( $var, '')) )
        {
            $params->set( $var, $emptyValue );
        }
	}
}
