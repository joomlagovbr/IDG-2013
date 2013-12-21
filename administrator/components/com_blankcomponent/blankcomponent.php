<?php
/**
* Author:	Omar Muhammad
* Email:	admin@omar84.com
* Website:	http://omar84.com
* Component:Blank Component
* Version:	3.0.0
* Date:		03/11/2012
* copyright	Copyright (C) 2012 http://omar84.com. All Rights Reserved.
* @license	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

$app = JFactory::getApplication();
$admin = $app->isAdmin();
if($admin==1)
	{
?>
<div>
	This Component was made to make it possible to create a menu item that has only modules and no component.<br />
	You can use it by adding a new menu item, select "Blank Component" from the "Menu Item Type" list, and save.<br />
	then, go to the module manager, and assign the modules you want to use with this menu item, and you're done!<br />
</div>
<?php
	}
else
	{

	jimport('joomla.application.component.controller');

	// Create the controller
	$controller = JControllerLegacy::getInstance('BlankComponent');

	// Perform the Request task
	$controller->execute(JRequest::getCmd('task'));

	// Redirect if set by the controller
	$controller->redirect();
	}

 ?>