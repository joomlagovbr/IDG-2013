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

define('FLV_TAG_AUDIO_CODEC_UNCOMPRESSED', 0x00);
define('FLV_TAG_AUDIO_CODEC_ADPCM', 0x01);
define('FLV_TAG_AUDIO_CODEC_MP3', 0x02);
define('FLV_TAG_AUDIO_CODEC_NELLYMOSER_8K', 0x05);
define('FLV_TAG_AUDIO_CODEC_NELLYMOSER', 0x06);

define('FLV_TAG_AUDIO_FREQ_5KHZ', 0x00);
define('FLV_TAG_AUDIO_FREQ_11KHZ', 0x01);
define('FLV_TAG_AUDIO_FREQ_22KHZ', 0x02);
define('FLV_TAG_AUDIO_FREQ_44KHZ', 0x03);

define('FLV_TAG_AUDIO_DEPTH_8BITS', 0x00);
define('FLV_TAG_AUDIO_DEPTH_16BITS', 0x01);

define('FLV_TAG_AUDIO_MODE_MONO', 0x00);
define('FLV_TAG_AUDIO_MODE_STEREO', 0x01);

class FLV_Tag_Audio extends FLV_Tag_Generic {
          
    var $CODEC_UNCOMPRESSED = FLV_TAG_AUDIO_CODEC_UNCOMPRESSED;
    var $CODEC_ADPCM = FLV_TAG_AUDIO_CODEC_ADPCM;
    var $CODEC_MP3 = FLV_TAG_AUDIO_CODEC_MP3;
    var $CODEC_NELLYMOSER_8K = FLV_TAG_AUDIO_CODEC_NELLYMOSER_8K;
    var $CODEC_NELLYMOSER = FLV_TAG_AUDIO_CODEC_NELLYMOSER;

    var $FREQ_5KHZ = FLV_TAG_AUDIO_FREQ_5KHZ;
    var $FREQ_11KHZ = FLV_TAG_AUDIO_FREQ_11KHZ;
    var $FREQ_22KHZ = FLV_TAG_AUDIO_FREQ_22KHZ;
    var $FREQ_44KHZ = FLV_TAG_AUDIO_FREQ_44KHZ;

    var $DEPTH_8BITS = FLV_TAG_AUDIO_DEPTH_8BITS;
    var $DEPTH_16BITS = FLV_TAG_AUDIO_DEPTH_16BITS;

    var $MODE_MONO = FLV_TAG_AUDIO_MODE_MONO;
    var $MODE_STEREO = FLV_TAG_AUDIO_MODE_STEREO;
        
    var $codec;
    var $codec_name;
    var $frequency;
    var $frequency_rate;	
    var $depth;
    var $depth_bit;	
    var $mode;
    var $mode_name;	
    
    function analyze()        
    {
		$bits = new FLV_Util_BitStreamReader( $this->body );

		$this->codec = $bits->getInt(4);
		$this->codec_name = $this->audioFormat($this->codec);
		
		$this->frequency = $bits->getInt(2);
		$this->frequency_rate = $this->audioFormat($this->frequency);
		
		$this->depth = $bits->getInt(1);
		$this->depth_bit = $this->audioBitDepth($this->depth);
				
		$this->mode = $bits->getInt(1);
		$this->mode_name = $this->audioMode($this->mode);
    }

	function audioFormat($id) {
		$audioFormat = array(
			$this->CODEC_UNCOMPRESSED => 'uncompressed',
			$this->CODEC_ADPCM => 'ADPCM',
			$this->CODEC_MP3 => 'mp3',
			$this->CODEC_NELLYMOSER_8K => 'Nellymoser 8kHz mono',
			$this->CODEC_NELLYMOSER => 'Nellymoser',
		);
		return (@$audioFormat[$id] ? @$audioFormat[$id] : false);
	}
	
	function audioRate($id) {
		$audioRate = array(
			$this->FREQ_5KHZ =>  5500,
			$this->FREQ_11KHZ  => 11025,
			$this->FREQ_22KHZ  => 22050,
			$this->FREQ_44KHZ  => 44100,
		);
		return (@$audioRate[$id] ? @$audioRate[$id] : false);
	}
	
	function audioBitDepth($id) {
		$audioBitDepth = array(
			$this->DEPTH_8BITS =>  8,
			$this->DEPTH_16BITS => 16,
		);
		return (@$audioBitDepth[$id] ? @$audioBitDepth[$id] : false);
	}

	function audioMode($id) {
		$audioMode = array(
			$this->MODE_MONO =>  'mono',
			$this->MODE_STEREO => 'stereo',
		);
		return (@$audioMode[$id] ? @$audioMode[$id] : false);
	}	
}
?>