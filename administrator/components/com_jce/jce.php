<?php

/**
 * @package   	JCE
 * @copyright 	Copyright (c) 2009-2013 Ryan Demmer. All rights reserved.
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
defined('_JEXEC') or die('RESTRICTED');

// load base classes
require_once(dirname(__FILE__) . '/includes/base.php');

// get the view
$view = JRequest::getCmd('view');
// get task
$task = JRequest::getCmd('task');

// legacy conversion
if ($task == 'popup') {
    $view = 'popup';
    JRequest::setVar('task', null);
}

// import library dependencies
jimport('joomla.application.component.helper');
jimport('joomla.application.component.controller');

// Require the base controller
require_once (WF_ADMINISTRATOR . '/controller.php');

// Load controller
$controllerPath = WF_ADMINISTRATOR . '/controller/' . $view . '.php';

if (file_exists($controllerPath)) {
    require_once ($controllerPath);

    $controllerClass = 'WFController' . ucfirst($view);
    $controller = new $controllerClass(array(
                'base_path' => WF_ADMINISTRATOR
            ));
// load default controller
} else {
    $controller = new WFController(array(
                'base_path' => WF_ADMINISTRATOR
            ));
}

// check Authorisations
switch ($view) {
    case 'editor':
    case 'help':
    case 'popup':
        break;
    default:
        $app = JFactory::getApplication();

        if ($app->isAdmin() === false) {
            $app->redirect('index.php', WFText::_('ALERTNOTAUTH'), 'error');
        }

        // Authorise
        $controller->authorize($view);
        // check state of extension
        $controller->check();
        break;
}

// Perform the Request task
$controller->execute($task);
$controller->redirect();
?>
