<?php

/**
 * @GNU General Public License
 **/


// No direct access to this file
defined('_JEXEC') or die('Restricted access');


/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at http://getid3.sourceforge.net                 //
//            or http://www.getid3.org                         //
//                                                             //
//  FLV module by Seth Kaufman <seth@whirl-i-gig.com>          //
//                                                             //
//  * version 0.1 (26 June 2005)                               //
//                                                             //
//  minor modifications by James Heinrich <info@getid3.org>    //
//  * version 0.1.1 (15 December 2005)                             //
//                                                             //
//  Support for On2 VP6 codec and meta information by          //
//  Steve Webster <steve.webster@featurecreep.com>             //
//  * version 0.2 (22 March 2006)                           //
//                                                             //
//  Modified to not read entire file into memory               //
//  by James Heinrich <info@getid3.org>                        //
//  * version 0.3 (15 June 2006)                               //
//                                                             //
//  Fixed to analize keyframes also in metadata                //
//  Added support for ON2 VP6                                  //
//  by Morten Hundevad <webmaster@fanno.dk>                    //
//  * version 0.4 (21 March 2007)                            //
//                                                             //
/////////////////////////////////////////////////////////////////
//                                                             //
// module.audio-video.flv.php                                  //
// module for analyzing Shockwave Flash Video files            //
// dependencies: NONE                                          //
//                                                            ///
/////////////////////////////////////////////////////////////////

define('GETID3_FLV_TAG_AUDIO', 8);
define('GETID3_FLV_TAG_VIDEO', 9);
define('GETID3_FLV_TAG_META', 18);

define('GETID3_FLV_VIDEO_H263',   2);
define('GETID3_FLV_VIDEO_SCREEN', 3);
define('GETID3_FLV_VIDEO_VP6',    4);
define('GETID3_FLV_VIDEO_VP6ALPHA',    5);
define('GETID3_FLV_VIDEO_SCREENVIDEO_2',    6);

class getid3_flv
{

	function getid3_flv(&$fd, &$ThisFileInfo, $ReturnAllTagData=false) {
		fseek($fd, $ThisFileInfo['avdataoffset'], SEEK_SET);

		$FLVdataLength = $ThisFileInfo['avdataend'] - $ThisFileInfo['avdataoffset'];
		$FLVheader = fread($fd, 5);

		$ThisFileInfo['fileformat'] = 'flv';
		$ThisFileInfo['flv']['header']['signature'] =                           substr($FLVheader, 0, 3);
		$ThisFileInfo['flv']['header']['version']   = getid3_lib::BigEndian2Int(substr($FLVheader, 3, 1));
		$TypeFlags                                  = getid3_lib::BigEndian2Int(substr($FLVheader, 4, 1));

		if ($ThisFileInfo['flv']['header']['signature'] != 'FLV') {
			$ThisFileInfo['error'][] = 'Expecting "FLV" at offset '.$ThisFileInfo['avdataoffset'].', found "'.$ThisFileInfo['flv']['header']['signature'].'"';
			unset($ThisFileInfo['flv']);
			unset($ThisFileInfo['fileformat']);
			return false;
		}

		$ThisFileInfo['flv']['header']['hasAudio'] = (bool) ($TypeFlags & 0x04);
		$ThisFileInfo['flv']['header']['hasVideo'] = (bool) ($TypeFlags & 0x01);

		$FrameSizeDataLength = getid3_lib::BigEndian2Int(fread($fd, 4));
		$FLVheaderFrameLength = 9;
		if ($FrameSizeDataLength > $FLVheaderFrameLength) {
			fseek($fd, $FrameSizeDataLength - $FLVheaderFrameLength, SEEK_CUR);
		}

		$Duration = 0;
		while ((ftell($fd) + 1) < $ThisFileInfo['avdataend']) {
			//if (!$ThisFileInfo['flv']['header']['hasAudio'] || isset($ThisFileInfo['flv']['audio']['audioFormat'])) {
			//	if (!$ThisFileInfo['flv']['header']['hasVideo'] || isset($ThisFileInfo['flv']['video']['videoCodec'])) {
			//		break;
			//	}
			//}
			$ThisTagHeader = fread($fd, 16);

			$PreviousTagLength = getid3_lib::BigEndian2Int(substr($ThisTagHeader,  0, 4));
			$TagType           = getid3_lib::BigEndian2Int(substr($ThisTagHeader,  4, 1));
			$DataLength        = getid3_lib::BigEndian2Int(substr($ThisTagHeader,  5, 3));
			$Timestamp         = getid3_lib::BigEndian2Int(substr($ThisTagHeader,  8, 3));
			$LastHeaderByte    = getid3_lib::BigEndian2Int(substr($ThisTagHeader, 15, 1));
			$NextOffset = ftell($fd) - 1 + $DataLength;

			switch ($TagType) {
				case GETID3_FLV_TAG_AUDIO:
					if (!isset($ThisFileInfo['flv']['audio']['audioFormat'])) {
						$ThisFileInfo['flv']['audio']['audioFormat']     =  $LastHeaderByte & 0x07;
						$ThisFileInfo['flv']['audio']['audioRate']       = ($LastHeaderByte & 0x30) / 0x10;
						$ThisFileInfo['flv']['audio']['audioSampleSize'] = ($LastHeaderByte & 0x40) / 0x40;
						$ThisFileInfo['flv']['audio']['audioType']       = ($LastHeaderByte & 0x80) / 0x80;
					}
					break;

				case GETID3_FLV_TAG_VIDEO:
					if (!isset($ThisFileInfo['flv']['video']['videoCodec'])) {					
						$ThisFileInfo['flv']['video']['videoCodec'] = $LastHeaderByte & 0x07;

						switch ($ThisFileInfo['flv']['video']['videoCodec'])
						{
							case GETID3_FLV_VIDEO_H263 :
								$FLVvideoHeader = fread($fd, 11);
							
								$PictureSizeType = (getid3_lib::BigEndian2Int(substr($FLVvideoHeader, 3, 2))) >> 7;
								$PictureSizeType = $PictureSizeType & 0x0007;
								$ThisFileInfo['flv']['header']['videoSizeType'] = $PictureSizeType;
								switch ($PictureSizeType) {
									case 0:
										$PictureSizeEnc = getid3_lib::BigEndian2Int(substr($FLVvideoHeader, 5, 2));
										$PictureSizeEnc <<= 1;
										$ThisFileInfo['video']['resolution_x'] = ($PictureSizeEnc & 0xFF00) >> 8;
										$PictureSizeEnc = getid3_lib::BigEndian2Int(substr($FLVvideoHeader, 6, 2));
										$PictureSizeEnc <<= 1;
										$ThisFileInfo['video']['resolution_y'] = ($PictureSizeEnc & 0xFF00) >> 8;
										break;
				
									case 1:
										$PictureSizeEnc = getid3_lib::BigEndian2Int(substr($FLVvideoHeader, 5, 4));
										$PictureSizeEnc <<= 1;
										$ThisFileInfo['video']['resolution_x'] = ($PictureSizeEnc & 0xFFFF0000) >> 16;
				
										$PictureSizeEnc = getid3_lib::BigEndian2Int(substr($FLVvideoHeader, 7, 4));
										$PictureSizeEnc <<= 1;
										$ThisFileInfo['video']['resolution_y'] = ($PictureSizeEnc & 0xFFFF0000) >> 16;
										break;
				
									case 2:
										$ThisFileInfo['video']['resolution_x'] = 352;
										$ThisFileInfo['video']['resolution_y'] = 288;
										break;
				
									case 3:
										$ThisFileInfo['video']['resolution_x'] = 176;
										$ThisFileInfo['video']['resolution_y'] = 144;
										break;
				
									case 4:
										$ThisFileInfo['video']['resolution_x'] = 128;
										$ThisFileInfo['video']['resolution_y'] = 96;
										break;
				
									case 5:
										$ThisFileInfo['video']['resolution_x'] = 320;
										$ThisFileInfo['video']['resolution_y'] = 240;
										break;
				
									case 6:
										$ThisFileInfo['video']['resolution_x'] = 160;
										$ThisFileInfo['video']['resolution_y'] = 120;
										break;
				
									default:
										$ThisFileInfo['video']['resolution_x'] = 0;
										$ThisFileInfo['video']['resolution_y'] = 0;
										break;
								}
								break;
							case GETID3_FLV_VIDEO_SCREEN :
								$bits = new BitStreamReader(fread($fd, $DataLength));
									$bits->seek(4, SEEK_CUR);
									$ThisFileInfo['video']['resolution_x'] = $bits->getInt(12);
									$bits->seek(4, SEEK_CUR);
									$ThisFileInfo['video']['resolution_y'] = $bits->getInt(12);								
								break;
							case GETID3_FLV_VIDEO_VP6:
									$bits = new BitStreamReader(fread($fd, $DataLength));
									$adjW = $bits->getInt(4);
									$adjH = $bits->getInt(4);
									$mode = $bits->getInt(1);
									if ($mode === 0) {
										$bits->seek(15, SEEK_CUR);
										$ThisFileInfo['video']['resolution_y'] = $bits->getInt(8) * 16 - $adjH;
										$ThisFileInfo['video']['resolution_x'] = $bits->getInt(8) * 16 - $adjW;
									}
								break;
							case GETID3_FLV_VIDEO_VP6ALPHA:
									$bits = new BitStreamReader(fread($fd, $DataLength));
									$adjW = $bits->getInt(4);
									$adjH = $bits->getInt(4);
									$mode = $bits->getInt(1);
									// mode is for ? unknown ?
									if ($mode === 0) {
										$bits->seek(39, SEEK_CUR);
										$ThisFileInfo['video']['resolution_y'] = $bits->getInt(8) * 16 - $adjH;
										$ThisFileInfo['video']['resolution_x'] = $bits->getInt(8) * 16 - $adjW;
									}
								break;
							case GETID3_FLV_VIDEO_SCREENVIDEO_2:
	   	    					$ThisFileInfo['video']['resolution_x'] = $bits->getInt(12);
	   	    					$ThisFileInfo['video']['resolution_y'] = $bits->getInt(12);
								break;
						}
					}
					break;

				// Meta tag
				case GETID3_FLV_TAG_META:
					fseek($fd, -1, SEEK_CUR);
					
				    $reader = new AMFReader( fread($fd, $DataLength) );
					$eventName = $reader->getItem();
					if($eventName == 'onMetaData') $ThisFileInfo['meta']['onMetaData'] = $reader->getItem();					
					unset($reader);

					$ThisFileInfo['video']['frame_rate']   = @$ThisFileInfo['meta']['onMetaData']['framerate'];
					$ThisFileInfo['video']['resolution_x'] = @$ThisFileInfo['meta']['onMetaData']['width'];
					$ThisFileInfo['video']['resolution_y'] = @$ThisFileInfo['meta']['onMetaData']['height'];
					break;

				default:
					// noop
					break;
			}

			if ($Timestamp > $Duration) {
				$Duration = $Timestamp;
			}

			fseek($fd, $NextOffset, SEEK_SET);
		}

		if ($ThisFileInfo['playtime_seconds'] = $Duration / 1000) {
		    $ThisFileInfo['bitrate'] = ($ThisFileInfo['avdataend'] - $ThisFileInfo['avdataoffset']) / $ThisFileInfo['playtime_seconds'];
		}

		if ($ThisFileInfo['flv']['header']['hasAudio']) {
			$ThisFileInfo['audio']['codec']           =   $this->FLVaudioFormat($ThisFileInfo['flv']['audio']['audioFormat']);
			$ThisFileInfo['audio']['sample_rate']     =     $this->FLVaudioRate($ThisFileInfo['flv']['audio']['audioRate']);
			$ThisFileInfo['audio']['bits_per_sample'] = $this->FLVaudioBitDepth($ThisFileInfo['flv']['audio']['audioSampleSize']);

			$ThisFileInfo['audio']['channels']   = $ThisFileInfo['flv']['audio']['audioType'] + 1; // 0=mono,1=stereo
			$ThisFileInfo['audio']['lossless']   = ($ThisFileInfo['flv']['audio']['audioFormat'] ? false : true); // 0=uncompressed
			$ThisFileInfo['audio']['dataformat'] = 'flv';
		}
		if (@$ThisFileInfo['flv']['header']['hasVideo']) {
			$ThisFileInfo['video']['codec']      = $this->FLVvideoCodec($ThisFileInfo['flv']['video']['videoCodec']);
			$ThisFileInfo['video']['dataformat'] = 'flv';
			$ThisFileInfo['video']['lossless']   = false;
		}

		return true;
	}


	function FLVaudioFormat($id) {
		$FLVaudioFormat = array(
			0 => 'uncompressed',
			1 => 'ADPCM',
			2 => 'mp3',
			5 => 'Nellymoser 8kHz mono',
			6 => 'Nellymoser',
		);
		return (@$FLVaudioFormat[$id] ? @$FLVaudioFormat[$id] : false);
	}

	function FLVaudioRate($id) {
		$FLVaudioRate = array(
			0 =>  5500,
			1 => 11025,
			2 => 22050,
			3 => 44100,
		);
		return (@$FLVaudioRate[$id] ? @$FLVaudioRate[$id] : false);
	}

	function FLVaudioBitDepth($id) {
		$FLVaudioBitDepth = array(
			0 =>  8,
			1 => 16,
		);
		return (@$FLVaudioBitDepth[$id] ? @$FLVaudioBitDepth[$id] : false);
	}

	function FLVvideoCodec($id) {
		$FLVvideoCodec = array(
			GETID3_FLV_VIDEO_H263   => 'Sorenson H.263',
			GETID3_FLV_VIDEO_SCREEN => 'Screen video',
			GETID3_FLV_VIDEO_VP6    => 'On2 VP6',
			GETID3_FLV_VIDEO_VP6ALPHA    => 'On2 VP6 Alpha',
			GETID3_FLV_VIDEO_SCREENVIDEO_2    => 'Screen Video 2',
		);
		return (@$FLVvideoCodec[$id] ? @$FLVvideoCodec[$id] : false);
	}
}

class AMFReader {
	var $data;
	var $pos;
	var $isLittleEndian;

	function AMFReader( $data = '' )
	{
	    $this->__construct( $data );
	}
	function __construct( $data = '' )
	{
		//calculate endianness of the CPU
		$this->isLittleEndian = ( pack('s', 1) == pack('v', 1) );
		
		$this->setPayload($data);
	}
  
	function setPayload( $payload )
	{
	  $this->data = $payload;
	  $this->pos = 0;
	}

	function seek( $offset, $whence = SEEK_SET)
	{
	  switch ($whence) {
	    case SEEK_SET:
	      if ($offset < strlen($this->data) && $offset >= 0) 
	      {
	        $this->pos = $offset;
	        return $this->pos;
	      }
	    break;
	      
	    case SEEK_CUR:
	      if ($offset >= 0 && $this->pos+$offset < strlen($this->data)) 
	      {
	        $this->pos += $offset;
	        return $this->pos;
	      }
	    break;
	      
	    case SEEK_END:
	      if ($offset <= 0 && strlen($this->data) + $offset >= 0) {
	        $this->pos = strlen($this->data) + $offset;
	        return $this->pos;
	      }
	    break;
	  }
	  
	  return false;   
	}

	function getBoolean()
	{
		return $this->data[$this->pos++] > 0;
	}

	function getSizedString( $size )
	{
		if ($size > 0)
		{
			$val = substr( $this->data, $this->pos, $size );
			$this->pos += $size;
			return $val;
		} else {
		    return '';
		}
	}
	
	function getString()
	{
		//get string length
		$size = (ord($this->data[$this->pos++]) << 8) +
				ord($this->data[$this->pos++]);
		return $this->getSizedString( $size );
	}
  
	function getLongString()
	{
		$size = (ord($this->data[$this->pos++]) << 24) + 
				(ord($this->data[$this->pos++]) << 16) + 
				(ord($this->data[$this->pos++]) << 8) +
				ord($this->data[$this->pos++]);
				
		return $this->getSizedString($size);
	}

	function getNumber()
	{
	    //read the number
		$number = substr( $this->data, $this->pos, 8 );
		$this->pos += 8;
		
		//reverse bytes if we are in little-endian harware
		if ($this->isLittleEndian)
		{
		  $number = strrev( $number );
		}
		
		$tmp = unpack('dnum', $number);
		
		return $tmp['num'];
	}

	function getArray()
	{
		// item count
		$cnt  = (ord($this->data[$this->pos++]) << 24) + 
				(ord($this->data[$this->pos++]) << 16) + 
				(ord($this->data[$this->pos++]) << 8) + 
				ord($this->data[$this->pos++]);
		
		$arr = array();
		for ($i=0; $i<$cnt; $i++)
		{
		  $arr[] = $this->getItem();
		}
		
		return $arr;
	}
	
	function getEcmaArray()
	{
		// skip the item count, we'll use the terminator
		$this->pos += 4;
		
		return $this->getObject();
	}

	function getObject()
	{
		$arr = array();
		do {
			//fetch the key and cast it to a number if it's numeric
			$key = $this->getString();
			if (is_numeric($key))
				$key = (float)$key;
		
			//check for the end of sequence mark
			if ( ord($this->data[$this->pos]) == 0x09 )
			{
				$this->pos++;
				break;
			}
		  
			$arr[$key] = $this->getItem();
			
		} while ( $this->pos < strlen($this->data) );
		
		return $arr;
	}
  
	function getDate()
	{
		//64bit unsigned int with ms since 1/Jan/1970
		$ms = $this->getNumber();
		
		//16bit signed int with local time offset in minuttes from UTC
		$ofs = (ord($this->data[$this->pos++]) << 8) + ord($this->data[$this->pos++]);
		if ($ofs > 720)
		  $ofs = -(65536 - $ofs);
		$ofs = -$ofs;
	
		$date = date( 'Y-m-d\TH:i:s', floor($ms/1000) ) . '.' . str_pad( $ms % 1000, 3, '0', STR_PAD_RIGHT);
		if ($ofs > 0)
			return $date . '+' . str_pad( floor($ofs/60), 2, '0', STR_PAD_LEFT ) . ':' . str_pad( $ofs % 60, 2, '0', STR_PAD_LEFT );
		else if ($ofs < 0)
			return $date . '-' . str_pad( floor($ofs/60), 2, '0', STR_PAD_LEFT ) . ':' . str_pad( $ofs % 60, 2, '0', STR_PAD_LEFT );
		else
			return $date . 'Z';
	}
  
	function getItem()
	{
		switch (ord($this->data[$this->pos++]))
		{
			case 0x00: 
		    	return $this->getNumber();
		  	break;
		  	case 0x01:
		    	return $this->getBoolean();
		  	break;
		  	case 0x02:
		    	return $this->getString();
		  	break;
		  	case 0x03:
		    	return $this->getObject();
		  	break;
		  	case 0x05:
		    	return NULL;
		  	break;
		  	case 0x08:
		    	return $this->getEcmaArray();
		  	break;
		  	case 0x0A:
		    	return $this->getArray();
		  	break;
		  	case 0x0B: //11 Date
		    	return $this->getDate();
		  	break;
		  	case 0x0C: //12
		    	return $this->getLongString();
		  	default:
				die('Unknown AMF datatype ' . ord($this->data[$this->pos-1]) );
		}
	}
}

class BitStreamReader {

	var $data;
	var $bits;
	var $pos;
	var $ofs;

	/**
	 * Class constructor which can initilaze the stream
	 *
	 * @param string $data	An optional binary string
	 */
	function BitStreamReader( $data = '' )
	{
	    $this->__construct( $data );
	}
	function __construct( $data = '' )
	{
	    $this->setPayload( $data );
	}

	/**
	 * Sets the binary stream to use
	 *
	 * @param string $data	The binary string
	 */
	function setPayload( $data )
	{
		$this->data = $data;
		$this->pos = 0;
		$this->bits = '';
		$this->ofs = 0;
	}
  
	/**
	 * Makes sure we have the requested number of bits in the working buffer
	 * 
	 * @access private
 	 * @param int $cnt	The number of bits needed
	 */
	function fetch( $cnt )
	{
		/*
		// Either we already have the needed bits in the buffer or we rebuild it
		if ($this->pos < ($this->ofs << 3) ||
			$this->pos + $cnt > ($this->ofs << 3) + strlen($this->bits) )
		{		
			$this->bits = '';
			$this->ofs = $this->pos >> 3;
			for ($i = $this->ofs; $i <= $this->ofs + ($cnt >> 3); $i++ )
			{
				$this->bits .= str_pad( decbin(ord($this->data[$i])), 8, '0', STR_PAD_LEFT );
			}
		}
		*/
       // Either we already have the needed bits in the buffer or we rebuild it
        if ($this->pos < $this->ofs*8 ||
            $this->pos + $cnt > $this->ofs*8 + strlen($this->bits) )
        {       
            $this->bits = '';
            $this->ofs = FLOOR($this->pos/8);
            for ($i = $this->ofs; $i <= $this->ofs + CEIL($cnt/8); $i++ )
            {
                $this->bits .= str_pad( decbin(ord($this->data[$i])), 8, '0', STR_PAD_LEFT );
            }
        }		
	}
	
	/**
	 * Consume an integer from an arbitrary number of bits in the stream
	 * 
	 * @param int $cnt	Length in bits of the integer
	 */
	function getInt( $cnt )
	{
		$this->fetch( $cnt );
		
		$ret = bindec( substr($this->bits, $this->pos-($this->ofs << 3), $cnt) );
		$this->pos += $cnt;
		return $ret;
	}

	/**
	 * Seeks into the bit stream in a similar way to fseek()
	 * 
	 * @param int $cnt	Number of bits to seek
	 * @param int $whence Either SEEK_SET (default), SEEK_CUR or SEEK_END
	 */	
	function seek( $ofs, $whence = SEEK_SET )
	{
		switch ($whence)
		{
			case SEEK_SET:
				$this->pos = $ofs;
			break;
			case SEEK_CUR:
				$this->pos += $ofs;
			break;
			case SEEK_END:
				$this->pos -= strlen($this->data)*8 - $ofs;
			break;
		}
		
		if ($this->pos < 0)
			$this->pos;
		elseif ($this->pos > strlen($this->data)*8)
			$this->pos = $this->data*8;
	}
}

?>