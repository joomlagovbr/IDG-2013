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

class FLV_Tag_Generic {
    var $type;
    var $size;
    var $timestamp;
    var $streamId;
    
    var $body;

    function FLV_Tag_Generic( $hdr )
    {
        $p = 0;
        $this->type = ord($hdr[$p++]);


        $this->size = 		(ord($hdr[$p++]) << 16) +
        					(ord($hdr[$p++]) << 8) +
        					(ord($hdr[$p++]));

        $this->timestamp =	(ord($hdr[$p++]) << 16) +
        					(ord($hdr[$p++]) << 8) +
	        				(ord($hdr[$p++])) +
    	    				(ord($hdr[$p++]) << 24);

		$this->streamId =	(ord($hdr[$p++]) << 16) +
	        				(ord($hdr[$p++]) << 8) +
    	    				(ord($hdr[$p++]));
    }
    
    function setBody( $body )
    {
	   	$this->body = $body;
    }

    function analyze()
    {
		// nothing to do for a generic tag
    }
}

?>