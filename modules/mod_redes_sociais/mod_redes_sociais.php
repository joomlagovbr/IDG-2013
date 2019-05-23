<?php
/**
 * @package    redessociais
 * @subpackage C:
 * @author     Ricardo Morais {@link }
 * @author     Created on 22-Nov-2013
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');


// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

$config = modRedesSociaisHelper::getConfigs($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_redes_sociais', $params->get('layout', 'default'));


?>

