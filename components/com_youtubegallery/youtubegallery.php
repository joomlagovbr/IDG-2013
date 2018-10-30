<?php
/**
 * YoutubeGallery Joomla! Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by Youtube Gallery

$controller = JControllerLegacy::getInstance('YoutubeGallery');

 
// Perform the Request task
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();

?>


