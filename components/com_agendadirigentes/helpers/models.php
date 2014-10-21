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
        $input_var = $input->get( $var, '');
        $params_var = $params->get( $var, '');

        if ( !empty($input_var) || !empty($params_var) )
        {
        	if(!is_file(JPATH_COMPONENT_ADMINISTRATOR .'/models/rules/' . strtolower($format) .'.php'))
        	{
        		JLog::add('Erro na helper do arquivo models.php. Arquivo de formato nao encontrado.', JLog::WARNING, 'jerror');
                return false;
        	}

            require_once( JPATH_COMPONENT_ADMINISTRATOR .'/models/rules/' . strtolower($format) .'.php' );
            $object = 'JFormRule' . $format;
            $rule = new $object();

            if ( !empty($input_var) )
            {
                if (preg_match($rule->getRegex(), $input_var))
                    $params->set( $var, $input->get( $var));
            }
            elseif(!empty($params_var) && !preg_match($rule->getRegex(), $params_var))
            {
                $params->set( $var, '');
            }
        }
        if ( empty($input_var) && empty($params_var) )
        {
            $params->set( $var, $emptyValue );
        }
	}
}
