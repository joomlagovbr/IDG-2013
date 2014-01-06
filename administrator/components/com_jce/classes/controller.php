<?php

jimport('joomla.application.component.controller');

if (!class_exists('WFControllerBase')) {
    if (interface_exists('JController')) {

        abstract class WFControllerBase extends JControllerLegacy {
            
        }

    } else {

        abstract class WFControllerBase extends JController {
            
        }

    }
}
?>