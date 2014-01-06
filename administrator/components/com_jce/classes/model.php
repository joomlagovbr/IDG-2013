<?php

jimport('joomla.application.component.model');

if (!class_exists('WFModelBase')) {
    if (interface_exists('JModel')) {

        abstract class WFModelBase extends JModelLegacy {
            
        }

    } else {

        abstract class WFModelBase extends JModel {
            
        }

    }
}
?>