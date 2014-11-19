<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla rule library
jimport('joomla.form.rule');
 
/**
 * Form Rule class for the Joomla Framework.
 */
class JFormRuleTitleOrName extends JFormRule
{
        /**
         * The regular expression.
         *
         * @access      protected
         * @var         string
         * @since       2.5
         */
        protected $regex = '^[^0-9\s\t\r\n]+';
}
