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
defined('JPATH_BASE') or die('RESTRICTED');

class WFTableProfiles extends JTable {

    /**
     * Primary Key
     *
     *  @var int
     */
    var $id = null;

    /**
     *
     *
     * @var varchar
     */
    var $name = null;

    /**
     *
     *
     * @var varchar
     */
    var $description = null;

    /**
     *
     *
     * @var varchar
     */
    var $components = null;

    /**
     *
     *
     * @var int
     */
    var $area = null;

    /**
     *
     *
     * @var varchar
     */
    var $device = null;

    /**
     *
     *
     * @var varchar
     */
    var $users = null;

    /**
     *
     *
     * @var varchar
     */
    var $types = null;

    /**
     *
     *
     * @var varchar
     */
    var $rows = null;

    /**
     *
     *
     * @var varchar
     */
    var $plugins = null;

    /**
     *
     *
     * @var tinyint
     */
    var $published = 0;

    /**
     *
     *
     * @var tinyint
     */
    var $ordering = 1;

    /**
     *
     *
     * @var int unsigned
     */
    var $checked_out = 0;

    /**
     *
     *
     * @var datetime
     */
    var $checked_out_time = "";

    /**
     *
     *
     * @var text
     */
    var $params = null;

    public function __construct(& $db) {
        parent::__construct('#__wf_profiles', 'id', $db);
    }
}
?>