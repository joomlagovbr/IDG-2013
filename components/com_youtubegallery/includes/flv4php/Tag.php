<?php
/**
 * @Copyright 2006 Iván Montes
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


/*
 Copyright 2006 Iván Montes, Morten Hundevad

 This file is part of FLV tools for PHP (FLV4PHP from now on).

 FLV4PHP is free software; you can redistribute it and/or modify it under the 
 terms of the GNU General var License as published by the Free Software 
 Foundation; either version 2 of the License, or (at your option) any later 
 version.

 FLV4PHP is distributed in the hope that it will be useful, but WITHOUT ANY 
 WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
 A PARTICULAR PURPOSE. See the GNU General var License for more details.

 You should have received a copy of the GNU General var License along with 
 FLV4PHP; if not, write to the Free Software Foundation, Inc., 51 Franklin 
 Street, Fifth Floor, Boston, MA 02110-1301, USA
*/

require_once(FLV_INCLUDE_PATH . 'Tag/Generic.php');
require_once(FLV_INCLUDE_PATH . 'Tag/Video.php');
require_once(FLV_INCLUDE_PATH . 'Tag/Audio.php');
require_once(FLV_INCLUDE_PATH . 'Tag/Data.php');

define('FLV_TAG_HEADER_SIZE', 15);
define('FLV_TAG_MAX_BODY_SIZE', 16386);

define('FLV_TAG_TYPE_AUDIO', 8);
define('FLV_TAG_TYPE_VIDEO', 9);
define('FLV_TAG_TYPE_DATA', 18);

class FLV_Tag {
    var $TYPE_AUDIO = FLV_TAG_TYPE_AUDIO;
    var $TYPE_VIDEO = FLV_TAG_TYPE_VIDEO;
    var $TYPE_DATA = FLV_TAG_TYPE_DATA;
    
    /**
     * Static Factory method to return the correct tag object
     *
     * @param string $hdr	The tag header
     * @return FLV_Tag		A FLV_Tag object or a descendant of it
     */
    function getTag( $hdr )
    {
   	switch ( ord($hdr[0]) )
    	{
    		case FLV_TAG_TYPE_AUDIO:
    			return new FLV_Tag_Audio( $hdr );
    		case FLV_TAG_TYPE_VIDEO:
    			return new FLV_Tag_Video( $hdr );
    		case FLV_TAG_TYPE_DATA:
    			return new FLV_Tag_Data( $hdr );
    		default:
    			return new FLV_Tag_Generic( $hdr );
    	}
    	return NULL;
    }

}
?>