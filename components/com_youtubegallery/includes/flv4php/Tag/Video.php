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
require_once(FLV_INCLUDE_PATH . 'Util/BitStreamReader.php');

class FLV_Tag_Video extends FLV_Tag_Generic {

    var $CODEC_SORENSON_H263 = 0x02;
    var $CODEC_SORENSON = 0x03;
    var $CODEC_ON2_VP6 = 0x04;
    var $CODEC_ON2_VP6ALPHA = 0x05;
    var $CODEC_SCREENVIDEO_2 = 0x06;
        
    var $FRAME_KEYFRAME = 0x01;
    var $FRAME_INTERFRAME = 0x02;
    var $FRAME_DISPENSABLE = 0x03;

    var $codec;
    var $codec_name;
    var $frametype;
    var $width;
    var $height;
	
    function analyze()
    {
		$bits = new FLV_Util_BitStreamReader($this->body);

		$this->frametype = $bits->getInt(4);
		
		$this->codec = $bits->getInt(4);
		$this->codec_name = $this->videoCodec($this->codec);

		switch ($this->codec)
		{
		    case $this->CODEC_SORENSON_H263 :
		    
		    	//skip video packet header
		    	$bits->seek(17+5+8, SEEK_CUR);
				
				$type = $bits->getInt(3);

		    	switch ($type)
		    	{
		    	    case 0x00:
		    	        $this->width = $bits->getInt(8);
		    	        $this->height = $bits->getInt(8);
		    	    break;
		    	    case 0x01:
		    	        $this->width = $bits->getInt(16);
		    	        $this->height = $bits->getInt(16);
		    	    break;
		    	    case 0x02: //CIF
		    			$this->width = 352;
		    			$this->height = 288;
		    		break;
		    	    case 0x03: //QCIF
		    			$this->width = 176;
		    			$this->height = 144;
		    		break;
		    	    case 0x04: //SQCIF
		    			$this->width = 128;
		    			$this->height = 96;
		    		break;
		    	    case 0x05: 
		    			$this->width = 320;
		    			$this->height = 240;
		    		break;
		    	    case 0x06: 
		    			$this->width = 160;
		    			$this->height = 120;
		    		break;
		    	}
		    break;
		    
	   	    case $this->CODEC_SORENSON :
				$bits->seek(4, SEEK_CUR);
				$this->width = $bits->getInt(12);
				$bits->seek(4, SEEK_CUR);
				$this->height = $bits->getInt(12);
	   	    break;
			
            // format layout taken from libavcodec project (http://ffmpeg.mplayerhq.hu/)
            case $this->CODEC_ON2_VP6 :
				if($this->frametype == 1) {			
					$adjW = $bits->getInt(4);
					$adjH = $bits->getInt(4);
					$mode = $bits->getInt(1);
					if ($mode === 0) {
						$bits->seek(15, SEEK_CUR);
						$this->height = $bits->getInt(8) * 16 - $adjH;
						$this->width = $bits->getInt(8) * 16 - $adjW;
					}
				}
	   	    break;

            case $this->CODEC_ON2_VP6ALPHA :
				if($this->frametype == 1) {
					$adjW = $bits->getInt(4);
					$adjH = $bits->getInt(4);
					$mode = $bits->getInt(1);
					if ($mode === 0) {
						$bits->seek(39, SEEK_CUR);
						$this->height = $bits->getInt(8) * 16 - $adjH;
						$this->width = $bits->getInt(8) * 16 - $adjW;
					}
				}
	   	    break;
						
			/* TODO: not tested */
	   	    case $this->CODEC_SCREENVIDEO_2 :	   	    
	   	    	$this->width = $bits->getInt(12);
	   	    	$this->height = $bits->getInt(12);
	   	    break;
			
			default :
			break;
		}
    }

	function videoCodec($id) {
		$videoCodec = array(
			$this->CODEC_SORENSON_H263   => 'Sorenson H.263',
			$this->CODEC_SORENSON => 'Screen video',
			$this->CODEC_ON2_VP6    => 'On2 VP6',
			$this->CODEC_ON2_VP6ALPHA    => 'On2 VP6 Alpha',
			$this->CODEC_SCREENVIDEO_2    => 'Screen Video 2',
		);
		return (@$videoCodec[$id] ? @$videoCodec[$id] : false);
	}	
}

?>