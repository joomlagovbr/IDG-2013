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

abstract class WFToken {

    /**
     * Create a token-string
     * From JSession::_createToken
     * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
     * @license   GNU/GPL, see LICENSE.php
     * @access protected
     * @param int $length lenght of string
     * @return string $id generated token
     */
    private static function _createToken($length = 32) {
        static $chars = '0123456789abcdef';
        $max = strlen($chars) - 1;
        $token = '';
        $name = session_name();
        for ($i = 0; $i < $length; ++$i) {
            $token .= $chars[(rand(0, $max))];
        }

        return md5($token . $name);
    }

    public static function getToken() {
        $session = JFactory::getSession();
        $user = JFactory::getUser();
        $token = $session->get('session.token', null, 'wf');

        //create a token
        if ($token === null) {
            $token = self::_createToken(12);
            $session->set('session.token', $token, 'wf');
        }

        if (method_exists('JApplication', 'getHash')) {
            return 'wf' . JApplication::getHash($user->get('id', 0) . $token);
        } else {
            return 'wf' . JUtility::getHash($user->get('id', 0) . $token);
        }
    }

    /**
     * Check the received token
     */
    public static function checkToken($method = 'POST') {
        $token = self::getToken();
        // check POST and GET for token		
        return JRequest::getVar($token, JRequest::getVar($token, '', 'GET', 'alnum'), 'POST', 'alnum');
    }

}
