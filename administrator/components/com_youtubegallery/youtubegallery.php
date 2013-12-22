<?php
/**
 * Youtube Gallery Joomla! 3.0 Native Component
 * @version 3.5.9
 * @author DesignCompass corp <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/


// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import joomla controller library
jimport('joomla.application.component.controller');
 
if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);

if(isset($_POST['task']))
	$task=$_POST['task'];
else
	$task='';

$view=JRequest::getCmd( 'view');
$t='categories.delete';

if($view=='' and $task==$t)
{
	$controllerName = 'categories';
	JRequest::setVar('view', 'categories');
}
else
	$controllerName = JRequest::getCmd( 'view', 'linkslist' );
 


switch($controllerName)
{
	case 'linkslist':
	
		JSubMenuHelper::addEntry(JText::_('Video Lists'), 'index.php?option=com_youtubegallery&view=linkslist', true);
		JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_youtubegallery&view=themelist', false);
		JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_youtubegallery&view=categories', false);
		JSubMenuHelper::addEntry(JText::_('Settings'), 'index.php?option=com_youtubegallery&view=settings&layout=edit', false);
	break;

	case 'themelist':
		
		JSubMenuHelper::addEntry(JText::_('Video Lists'), 'index.php?option=com_youtubegallery&view=linkslist', false);
		JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_youtubegallery&view=themelist', true);
		JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_youtubegallery&view=categories', false);
		JSubMenuHelper::addEntry(JText::_('Settings'), 'index.php?option=com_youtubegallery&view=settings&layout=edit', false);
	break;

	case 'categories':
		
		JSubMenuHelper::addEntry(JText::_('Video Lists'), 'index.php?option=com_youtubegallery&view=linkslist', false);
		JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_youtubegallery&view=themelist', false);
		JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_youtubegallery&view=categories', true);
		JSubMenuHelper::addEntry(JText::_('Settings'), 'index.php?option=com_youtubegallery&view=settings&layout=edit', false);
	break;

	case 'settings':
		
		JSubMenuHelper::addEntry(JText::_('Video Lists'), 'index.php?option=com_youtubegallery&view=linkslist', false);
		JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_youtubegallery&view=themelist', false);
		JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_youtubegallery&view=categories', false);
		JSubMenuHelper::addEntry(JText::_('Settings'), 'index.php?option=com_youtubegallery&view=settings&layout=edit', true);
	break;

}
 

$controller = JControllerLegacy::getInstance('youtubeGallery');

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();

?>