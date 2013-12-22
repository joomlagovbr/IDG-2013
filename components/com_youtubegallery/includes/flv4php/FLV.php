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

define('FLV_INCLUDE_PATH', dirname(__FILE__) . '/');

define('FLV_SECRET_KEY', 'flv_key');

define('FLV_VERSION', 'V0.21');

require_once(FLV_INCLUDE_PATH . 'Tag.php');
require_once(FLV_INCLUDE_PATH . 'Util/AMFSerialize.php');
require_once(FLV_INCLUDE_PATH . 'Util/AMFUnserialize.php');

require_once(FLV_INCLUDE_PATH . 'getid3/getid3.php');


define('FLV_HEADER_SIGNATURE', 'FLV');
define('FLV_HEADER_SIZE', 9);

define('FLV_ERROR_BLOCKED', 'ERROR CODE: 1');
define('FLV_ERROR_INVALID', 'ERROR CODE: 2');

/**
* Parse a .flv file to extract all the 'tag' information
*/
class FLV {
	/** The FLV header signature */
	var $FLV_HEADER_SIGNATURE = FLV_HEADER_SIGNATURE;

    /** The FLV main header size */
	var $FLV_HEADER_SIZE = FLV_HEADER_SIZE;

    /** The FLV tag header size */
    var $TAG_HEADER_SIZE = FLV_TAG_HEADER_SIZE;

    /**
		Maximun number of bytes to process as tag body. This is a safety meassure against
		corrupted FLV files.
	*/
    var $MAX_TAG_BODY_SIZE = FLV_TAG_MAX_BODY_SIZE;

  	var $filename;
  	var $_tempfilename;
  	var $metadata;
  	var $metadataend;

	var $config;
  	var $_db_link_id;
  	var $lock_key;
//  	var $lock_id;

  	var $_fh;
    var $_lastTagSize = 0;

	var $_getID3 = NULL;
	var $FileInfo = NULL;

	var $error = NULL;

	var $_nocashe = false;

    function FLV( $fname = false, $timeout = 30 )
    {
		if($fname) return $this->open($fname,$timeout);
		return false;
    }

	/*
	*
	* @param string $fname			Locatsion of file
	* @param int $timeout			Script Time out default 30, when using external ling for downloading..
	*
	* @return true/false			if success/failed.
	*/
    function open( $fname = false, $timeout = 30 )
    {
		$this->error = array();
		if($fname) {
			$this->filename = $fname;
			$this->setKey(basename($fname, ".flv"));
			$this->_nocashe = false;

			$url = parse_url($fname);

			if(!empty($url['scheme'])) {
				if( !is_readable(FLV_INCLUDE_PATH) || !is_writable(FLV_INCLUDE_PATH) ) {
					$this->_error_die('To Use External files you need to have: '. FLV_INCLUDE_PATH . ' chmod:777 ');
				} elseif( $this->_tempfilename = tempnam(FLV_INCLUDE_PATH, "FLV") ) {
					$this->_fh = @fopen($this->_tempfilename, "r+");
					if (!$this->_fh) $this->_error_die('Unable to Open temporary file');
					$tempcontent = file_get_contents($fname);

					fwrite($this->_fh, $tempcontent);
					$fname = $this->_tempfilename;

					rewind($this->_fh);
				} else $this->_error_die('Unable to Make temporary file');
			} else {
				$this->_fh = @fopen( $fname, 'r' );
				if (!$this->_fh) $this->_error_die('Unable to open the file');
			}

			$this->_getID3 = new getID3();
			$this->_getID3->setOption(array('encoding' => 'UTF-8'));

			$AutoGetHashes = (bool) (filesize($fname) < 52428800); // auto-get md5_data, md5_file, sha1_data, sha1_file if filesize < 50MB

			$this->_getID3->setOption(array('option_md5_data'  => $AutoGetHashes,'option_sha1_data' => $AutoGetHashes));

			$this->FileInfo = $this->_getID3->analyze($fname);

			$hdr = fread( $this->_fh, $this->FLV_HEADER_SIZE );
			//check file header signature
			if ( substr($hdr, 0, 3) !== $this->FLV_HEADER_SIGNATURE ) $this->_error_die('The header signature does not match');

			$this->version = ord($hdr[3]);
			$this->hasVideo = (bool)(ord($hdr[4]) & 0x01);
			$this->hasAudio = (bool)(ord($hdr[4]) & 0x04);

			$this->bodyOfs =	(ord($hdr[5]) << 24) +
								(ord($hdr[6]) << 16) +
								(ord($hdr[7]) << 8) +
								(ord($hdr[8]));

			$this->eof = false;

			$this->getMetaData();
			return true;
		}
		return false;
    }

	/**
	* Move to beginning of the File ( after first header )
	*/
    function start()
    {
		fseek( $this->_fh, $this->bodyOfs );
		$this->eof = false;
    }

    /**
	* Close a previously open FLV file
	*/
    function close()
    {
    	fclose( $this->_fh );
		if(!empty($this->_tempfilename)) unlink($this->_tempfilename);
    }

    /**
	* Close a previously open FLV file
	*/
    function _error_die($msg)
    {
		$this->close();
		$this->error[] = $msg;
		die($msg);
    }

	/**
	* Returns the MetaData Tag
	*
	* @return object				return metadata object
	*/
    function getMetaData()
    {
		$this->start();

        $hdr = fread( $this->_fh, $this->TAG_HEADER_SIZE );

		if (strlen($hdr) < $this->TAG_HEADER_SIZE) {
		    $this->eof = true;
		   	return NULL;
		}

		// Get the tag object by skiping the first 4 bytes which tell the previous tag size
		$tag = FLV_Tag::getTag( substr( $hdr, 4 ) );

		// Read at most MAX_TAG_BODY_SIZE bytes of the body
		$bytesToRead = min( $this->MAX_TAG_BODY_SIZE, $tag->size );
		$tag->setBody( fread( $this->_fh, $bytesToRead ) );

		// Check if the tag body has to be processed

		$tag->analyze();

		// If the tag was skipped or the body size was larger than MAX_TAG_BODY_SIZE
		if ($tag->size > $bytesToRead) fseek( $this->_fh, $tag->size-$bytesToRead, SEEK_CUR );

		$this->_lastTagSize = $tag->size + $this->TAG_HEADER_SIZE - 4;

		$this->metadata = $tag->data;

		$this->metadataend = ftell($this->_fh);
		return $tag;
    }

	/**
	* Returns the MetaData string
	*
    * @param array $newMetaData		Array with new metadata
	* @param string $merge			Merge original array with new one
	*
	* @return string				New Metadata + next tag's previous size
	*/
    function createMetaData($newMetaData = false,$merge = true)
    {
		//if the metadata is pressent in the file merge it with the generated one
		$amf = new FLV_Util_AMFSerialize();

		if (is_array($newMetaData)) {
			if($merge && is_array($this->metadata)) $newMetaData = array_merge( $this->metadata, $newMetaData );
			$metadata = $newMetaData;
		} else $metadata = $this->metadata;

		$metadata['metadatacreator'] = 'FLV Editor for PHP '.FLV_VERSION.' (Project: Flv4php)';

		$serMeta = $amf->serialize('onMetaData') . $amf->serialize($metadata);

		$out = pack('N', 0);									// PreviousTagSize
		$out.= pack('C', FLV_TAG_TYPE_DATA);					// Type
		$out.= pack('Cn', "\x00", strlen($serMeta));			// BodyLength assumes it's shorter than 64Kb
		$out.= pack('N', 0);									// Time stamp (not used)
		$out.= pack('Cn', 0, 0);								// Stream ID (not used) <---- WHERE IS THIS comming from
		$out.= $serMeta;										// Metadata Body
		$out.= pack('N', strlen($serMeta) + 1 + 3 + 4 + 3); 	// PreviousTagSize
		return $out;
    }

	/**
	* Play the flv and close file after
	*
    * @param int $limitSpeed		Limit speed of downloading, calculated off videorate + audiorate + $limitSpeed
	* @param int $seekat			Start playback at..
	* @param array $newMetaData		Array with new metadata
	* @param bool $merge			Merge original array with new one
	*/
    function playFlv($limitSpeed = 0,$seekat = 0,$newMetaData = false,$merge = true)
    {
		session_write_close();

		if ($seekat != 0) {
	      	fseek($this->_fh, $seekat);
		} else {
			if (!is_array($newMetaData)) $newMetaData = $this->defaultMetaData();
			$metadata = $this->createMetaData($newMetaData,$merge);
			rewind($this->_fh);												// Rewind the movie
			fseek($this->_fh, $this->metadataend+4);							// Skip the Original metadata
		}

		if ($limitSpeed) {
			if ($this->FileInfo['bitrate']) $limitSpeed = ceil(($this->FileInfo['bitrate']/100)/8)+$limitSpeed-1;
			else $limitSpeed = false;
		}
		if ($metadata) $size = strlen($metadata) + ( filesize($this->filename) - ftell($this->_fh) ) + 9;
		else $size = filesize($this->filename) - ftell($this->_fh) + 9;

		$this->setHeader($size);

		header("Content-Disposition: filename=".basename($this->filename));

		print("FLV");
		print(pack('C', 1 ));
		print(pack('C', 5 ));
		print(pack('N', 9 ));

		if ($seekat) {
			print(pack('N', 9 ));
		} else {
			print($metadata);
		}

		set_time_limit(0);
//		print(fread($this->_fh, 50000));
		print(fread($this->_fh, 5000));
		while(!feof($this->_fh)) {
			if ($limitSpeed) {
				print(fread($this->_fh, round($limitSpeed*(1024/32))));
				flush();
				usleep(31250);
			 } else {
				print(fread($this->_fh, 1024));
			 }
		}
		$this->close();
    }

	/**
	* Play the flv  with lock and close file after
	*
	* @param int $limitSpeed		Limit speed off downloading, calculated off videorate + audiorate + $limitSpeed
	* @param int $seekat			Start playback at..
	* @param array $newMetaData		Array off new metadata
	* @param bool $merge			Merge original array with new one
	* @param bool $usedb			use database for key veryfying
	*/
    function playFlvLock($limitSpeed = 0,$seekat = 0,$newMetaData = false,$merge = true,$usedb = false)
    {
		$this->_nocashe = true;
		if ( $this->validLock($usedb) ) {
			$this->playFlv($limitSpeed,$seekat,$newMetaData,$merge);
		} else {
			$this->error[] = FLV_ERROR_INVALID;
			$this->close();
		}
	}

	/**
	* Get Flv Thumb output's a thumb clip from offset point, locate a key frame and from there output's duration
	* if no key frame is found it use the first key frame.
	*
	* @param int $offset			Offset in ms
	* @param bool $usemetadata		Use metadata to attempt to find first keyframe
	*
	* @return string				Single Flv keyframe
	*/
    function getFlvThumb($offset=2000,$usemetadata=true) {
		session_write_close();

		$this->start();
		$skipTagTypes = array();
		$skipTagTypes[FLV_TAG_TYPE_AUDIO] = FLV_TAG_TYPE_AUDIO;

		if ($usemetadata && $offset && $this->metadata['keyframes']['times']) {
			foreach ( $this->metadata['keyframes']['times'] as $key => $value){
				if ( $value >= ($offset/1000) ) {
					$offset = $value*1000;
					fseek($this->_fh,$this->metadata['keyframes']['filepositions'][$key]-4);
					break;
				}
			}
		}

		while ($tag = $this->getTag($skipTagTypes)) {
			if ( $tag->type == FLV_TAG_TYPE_VIDEO ) {
				if ($tag->timestamp >= $offset && $tag->frametype == 1 ) {
					rewind($this->_fh);
					fseek($this->_fh, $tag->start );
					$dataOut = fread($this->_fh, ( ( $tag->end + 4 ) - $tag->start ) );
					$this->replaceTimestamp($dataOut,0);
					break;
				}
			}
			//Does it actually help with memory allocation?
			unset($tag);
		}

		if(!$dataOut) {
			$offset = 0;
			$this->start();
			while ($tag = $this->getTag($skipTagTypes)) {
				if ( $tag->type == FLV_TAG_TYPE_VIDEO ) {
					if ($tag->timestamp >= $offset && $tag->frametype == 1 ) {
						rewind($this->_fh);
						fseek($this->_fh, $tag->start);
						$dataOut = fread($this->_fh, ( ( $tag->end + 4 ) - $tag->start ) );
						$this->replaceTimestamp($dataOut,0);
						break;
					}
				}
				//Does it actually help with memory allocation?
				unset($tag);
			}
		}

		$newMetaData = $this->defaultMetaData(false);
		unset($newMetaData['keyframes']['times']);
		$newMetaData['keyframes']['times'][] = 0;
		//
		unset($newMetaData['keyframes']['filepositions']);
		$newMetaData['keyframes']['filepositions'][] = 0;
		//
		$newMetaData['duration'] = $newMetaData['datasize'] = $newMetaData['audiosize'] = $newMetaData['videosize'] = $newMetaData['filesize'] = $newMetaData['audiodatarate'] = $newMetaData['audiocodecid'] = $newMetaData['lastkeyframetimestamp'] = $newMetaData['lasttimestamp'] = $newMetaData['framerate'] = 0;

		$metadata = $this->createMetaData($newMetaData,true);

		$sizeMetaData = strlen($metadata);

		$newMetaData = array();
		$newMetaData = $this->defaultMetaData(false);
		unset($newMetaData['keyframes']['times']);
		$newMetaData['keyframes']['times'][] = 0;
		//
		unset($newMetaData['keyframes']['filepositions']);
		$newMetaData['keyframes']['filepositions'][] = 0;
		//
		$newMetaData['lastkeyframetimestamp'] = $newMetaData['lasttimestamp'] = $newMetaData['framerate'] = $newMetaData['duration'] = $newMetaData['audiosize'] = $newMetaData['audiodatarate'] = $newMetaData['audiocodecid'] = 0;

		$newMetaData['datasize'] = $sizeMetaData;
		$newMetaData['videosize'] = strlen($dataOut);
		$newMetaData['filesize'] = $sizeMetaData + strlen($dataOut) + FLV_HEADER_SIZE;

		$metadata = $this->createMetaData($newMetaData,true);

		$this->setHeader(strlen($metadata) + strlen($dataOut) + FLV_HEADER_SIZE);

		header("Content-Disposition: filename=".basename($this->filename));

		$this->close();

		return "FLV".pack('C', 1 ).pack('C', 1 ).pack('N', 9 ).$metadata.$dataOut;
	}

	/**
	* Get Flv Preview output's a Preview clip from offset point, locate a key frame and from there output's duration
	* if no key frame is found it use the first key frame.
	*
	* @param int $offset			Offset in ms
	* @param int $duration			Duration in ms
	* @param bool $usemetadata		Use metadata to attempt to find first keyframe
	* @param int $speedModifyer		Playback Speed modifier
	*
	* @return string				Flv File preview
	*/
    function getFlvPreview($offset=2000,$duration=2000,$usemetadata=true,$speedModifyer=100) {
    	session_write_close();
		$speedModifier = (int) $speedModifier;
		if ($speedModifier < 1) $speedModifier = 1;

		$this->dataArray = array();
		$this->start();

		$skipTagTypes = array();
		$skipTagTypes[FLV_TAG_TYPE_AUDIO] = FLV_TAG_TYPE_AUDIO;


        if ( !is_array($offset) || !is_array($duration) ) {
               $currentOffset[0] = $offset;
               $currentDuration[0] = $duration;
        } else {
               $currentOffset = $offset;
               $currentDuration = $duration;
        }
        $totalDuration = $lastKeyframe = 0;

      	if($usemetadata && $currentOffset[0] && $this->metadata['keyframes']['times']) {
  			foreach($this->metadata['keyframes']['times'] as $metakey => $metavalue){
  				if($metavalue >= ($currentOffset[0]/1000)) {
  					$offset = $metavalue*1000;
  					fseek($this->_fh,$this->metadata['keyframes']['filepositions'][$metakey]-4);
  					break;
  				}
  			}
  		}
        foreach($currentOffset as $offsetKey => $offset) {
            if ( !$currentDuration[$offsetKey] ) $currentDuration[$offsetKey] = $currentDuration[0];
    		$endFouned = false;
       		$index = $startTimestamp = 0;
            $endTimestamp = $totalDuration;
    		while($tag = $this->getTag($skipTagTypes)) {
    			if($tag->type == FLV_TAG_TYPE_VIDEO) {
    				if(!$this->dataArray[$offsetKey] && $tag->timestamp >= $offset && $tag->frametype == 1 ) {
    					$startTimestamp = $tag->timestamp;

           				rewind($this->_fh);
    					fseek($this->_fh, $tag->start);

    					$this->dataArray[$offsetKey][$index++] = fread($this->_fh, ($tag->end - $tag->start));
                        if ( $totalDuration ) {
        					$this->replaceTimestamp($this->dataArray[$offsetKey][$index-1],$totalDuration+10);

                        } else {
        					$this->replaceTimestamp($this->dataArray[$offsetKey][$index-1],0);
                        }

    					rewind($this->_fh);
    					fseek($this->_fh, $tag->end);
    				} elseif($this->dataArray[$offsetKey]) {
    					$endTimestamp = $totalDuration + $this->modifyTimestamp(($tag->timestamp-$startTimestamp),$speedModifyer);

    					if($tag->frametype == 1) $lastKeyframe = $endTimestamp;
    					rewind($this->_fh);
    					fseek($this->_fh, $tag->start);

    					$this->dataArray[$offsetKey][$index++] = fread($this->_fh, ($tag->end - $tag->start));
    					$this->replaceTimestamp($this->dataArray[$offsetKey][$index-1], $endTimestamp);

    					rewind($this->_fh);
    					fseek($this->_fh, $tag->end);
    					if($tag->timestamp >= ($currentDuration[$offsetKey]+$startTimestamp)) {
    						$endFouned = true;
    						break;
    					}
    				}
    			}
    			//Does it actually help with memory allocation?
    			unset($tag);

    		}

    		if(!$endFouned && !$this->dataArray[1]) {
    			unset($this->dataArray[$offsetKey]);
    			$index = $offset = $lastKeyframe = $startTimestamp = $endTimestamp = 0;

    			$this->start();
    			while($tag = $this->getTag($skipTagTypes)) {
    				if($tag->type == FLV_TAG_TYPE_VIDEO) {
    					if(!$this->dataArray[$offsetKey] && $tag->timestamp >= $offset && $tag->frametype == 1 ) {
    						$startTimestamp = $tag->timestamp;
    						rewind($this->_fh);
    						fseek($this->_fh, $tag->start);

    						$this->dataArray[$offsetKey][$index++] = fread($this->_fh, ($tag->end - $tag->start));
    						$this->replaceTimestamp($this->dataArray[$offsetKey][$index-1],0);

    						rewind( $this->_fh );
    						fseek( $this->_fh, $tag->end );
    					} elseif($this->dataArray[$offsetKey]) {
    						$endTimestamp = $this->modifyTimestamp(($tag->timestamp-$startTimestamp),$speedModifyer);
    						if($tag->frametype == 1) $lastKeyframe = $endTimestamp;
    						rewind($this->_fh);
    						fseek($this->_fh,$tag->start);

    						$this->dataArray[$offsetKey][$index++] = fread($this->_fh, ($tag->end - $tag->start));
    						$this->replaceTimestamp($this->dataArray[$offsetKey][$index-1],$endTimestamp);

    						rewind($this->_fh);
    						fseek($this->_fh, $tag->end);
    					}
    				}
					if ($tag->timestamp >= ($currentDuration[$offsetKey]+$startTimestamp)) break;
    				//Does it actually help with memory allocation?
    				unset($tag);
    			}
    		}
            $totalDuration = $endTimestamp;
            if(!$endFouned) break;
        }
		$dataOut = '';
		$lastSize = 0;
		foreach($this->dataArray as $key => $value) {
            foreach($this->dataArray[$key] as $key2 => $value2) {
		    	if( $key || $key2 ) $dataOut = $dataOut.pack('N', $lastSize ).$value2;
	    		else $dataOut = $value2;
    			$lastSize = strlen($value2);
            }
		}

		$newMetaData = array();
		$newMetaData = $this->defaultMetaData(true);
		unset($newMetaData['keyframes']['times']);
		$newMetaData['keyframes']['times'][] = 0;
		//
		unset($newMetaData['keyframes']['filepositions']);
		$newMetaData['keyframes']['filepositions'][] = 0;

		$newMetaData['duration'] = ( $totalDuration / 1000 );

		$newMetaData['datasize'] = $newMetaData['audiosize'] = $newMetaData['videosize'] = $newMetaData['filesize'] = $newMetaData['audiodatarate'] = $newMetaData['audiocodecid'] = 0;

		$newMetaData['hasmetadata'] = true;
		$newMetaData['haskeyframes'] = true;

		$newMetaData['lastkeyframetimestamp'] = $lastKeyframe;
		$newMetaData['lasttimestamp'] = $totalDuration;

		$metadata = $this->createMetaData($newMetaData,true);

		$sizeMetaData = strlen($metadata);

		$newMetaData = array();
		$newMetaData = $this->defaultMetaData(true);
		unset($newMetaData['keyframes']['times']);
		$newMetaData['keyframes']['times'][] = 0;
		//
		unset($newMetaData['keyframes']['filepositions']);
		$newMetaData['keyframes']['filepositions'][] = $sizeMetaData + FLV_HEADER_SIZE;
		//
		$newMetaData['duration'] =  ( $totalDuration / 1000 );
		$newMetaData['datasize'] = $sizeMetaData;

		$newMetaData['audiosize'] = $newMetaData['audiodatarate'] = $newMetaData['audiocodecid'] = 0;
		$newMetaData['videosize'] = strlen($dataOut);
		$newMetaData['filesize'] = $sizeMetaData + strlen($dataOut) + FLV_HEADER_SIZE;

		$newMetaData['hasmetadata'] = true;
		$newMetaData['haskeyframes'] = true;

		$newMetaData['lastkeyframetimestamp'] = $lastKeyframe;
		$newMetaData['lasttimestamp'] = $totalDuration;

		$metadata = $this->createMetaData($newMetaData,true);

		$this->setHeader(strlen($metadata) +  strlen($dataOut) + FLV_HEADER_SIZE);

		header("Content-Disposition: filename=".basename($this->filename));

		$this->close();
		return "FLV".pack('C', 1).pack('C', 1).pack('N', 9).$metadata.$dataOut;
	}

	/**
	* Download Flv
	*
	* @param int $limitSpeed		Limit speed off downloading, calculated off videorate + audiorate + $limitSpeed
	* @param array $newMetaData		Array off new metadata
	* @param bool $merge			Merge original array with new one
	*/
    function downloadFlv($limitSpeed = 0,$newMetaData = false,$merge = true)
    {
		session_write_close();
		$this->setHeader(filesize($this->filename));

		header("Content-Disposition: attachment; filename=".basename($this->filename));

		print("FLV");
		print(pack('C', 1 ));
		print(pack('C', 5 ));
		print(pack('N', 9 ));

		if(!is_array($newMetaData)) $newMetaData = $this->defaultMetaData();

		print($this->createMetaData($newMetaData,$merge));
		rewindcs($this->_fh);												// Rewind the movie
		fseek($this->_fh, $this->metadataend+4);							// Skip the Original metadata

		if($limitSpeed) {
			if ($this->FileInfo['bitrate']) $limitSpeed = ceil(($this->FileInfo['bitrate']/100)/8)+$limitSpeed-1;
//			if ($this->metadata["videodatarate"] || $this->metadata["audiodatarate"]) $limitSpeed = ceil(($this->metadata["videodatarate"]+$this->metadata["audiodatarate"])/8)+$limitSpeed-1;
			else $limitSpeed = false;
		}

		set_time_limit(0);
		while(!feof($this->_fh)) {
			if($limitSpeed) {
				print(fread($this->_fh, round($limitSpeed*(1024/32))));
				flush();
				usleep(31250);
			 } else {
				print(fread($this->_fh, 1024));
			 }
		}
		$this->close();
	}

	/*
	* Flv php header
	*
	* @param int $size				Size of file.
	*/
    function setHeader($size=0)
    {
		header("Content-Type: video/x-flv");
//		header("Content-Type: text/plain");
		header("Content-Length: " .(string)$size);

		if($this->_nocashe) {
			// Date in the past
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			// always modified
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			// HTTP/1.1
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
		} else {
			// calc an offset of 24 hours
			$offset = 3600 * 24;
			// calc the string in GMT not localtime and add the offset
			$expire = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
			//output the HTTP header
			header($expire);
		}
	}

	/**
	* Open a lock used when playing with playFlvLock.
	*
	* @param string $key			Key Name
	* @param int $validtime			Defines how long time a key is valid
	* @param bool $usedb			Use Database key storage?
	*
	* @return true/false			if sucess/failed.
	*/
    function openLock($key=false,$validtime=30,$usedb=false)
    {
		if(!$key) return false;
		if(!session_id()) session_start();
		if($usedb) {
			$this->loadConfig();
			$sid = session_id();
			$expire = time()+$validtime;
			$ip = $_SERVER['REMOTE_ADDR'];

			$this->dbConnect();

			$result = mysql_list_tables ( $this->config['db_name'] ,$this->_db_link_id);

			$tableKey = $this->config['db_table_prefix']."key";
			$tableBlock = $this->config['db_table_prefix']."block";

			$maketableKey = true;
			$maketableBlock = true;
			while($row = mysql_fetch_row($result)) {
			   if($row[0] == $tableKey) $maketableKey = false;
			   elseif($row[0] == $tableBlock) $maketableBlock = false;
			}

			if($maketableKey) {
				$query="CREATE TABLE `".$tableKey."` ( `uid` SMALLINT( 5 ) AUTO_INCREMENT , `sid` VARCHAR( 35 ) NOT NULL , `key` VARCHAR( 50 ) NOT NULL , `timeout` INT( 20 ) NOT NULL , `ip` VARCHAR( 15 ) NOT NULL , PRIMARY KEY ( `uid` ) ) TYPE = MYISAM";
				if (!mysql_query($query)) $this->_error_die(mysql_error());
			}
			if($maketableBlock) {
				$query="CREATE TABLE `".$tableBlock."` (`uid` SMALLINT( 5 ) AUTO_INCREMENT ,`ip` VARCHAR( 15 ) NOT NULL ,`count` SMALLINT( 5 ) DEFAULT '0',PRIMARY KEY ( `uid` ) ) TYPE = MYISAM";
				if (!mysql_query($query)) $this->_error_die(mysql_error());
			}

			$query = "SELECT uid FROM ".$tableKey." WHERE `sid` LIKE '$sid' AND `key` LIKE '$key' LIMIT 1";
			$result = mysql_query($query);
			$rowKey = mysql_fetch_assoc($result);

			if($rowKey['uid']) $query = "UPDATE ".$tableKey." SET timeout = $expire , ip = '$ip' WHERE uid = ".$rowKey['uid'];
			else $query = "INSERT INTO ".$tableKey." VALUES ('','$sid','$key','$expire','$ip')";
			@mysql_query($query);
			mysql_close($this->_db_link_id);
		} else {
			$_SESSION[FLV_SECRET_KEY][$key] = time()+$validtime;
		}
		return true;
	}

	/**
	* Close a Lock used when playing with playFlvLock.
	*
	* @param int $lock_id			uid in Database
	*/
    function closeLock($lock_id = 0)
    {
		if($lock_id) {
			$this->loadConfig();
			$this->dbConnect();

			$sid = session_id();
			$timeout = time();

			$tableKey = $this->config['db_table_prefix']."key";
			$tableBlock = $this->config['db_table_prefix']."block";

			$query = "DELETE FROM `".$tableKey."` WHERE `uid` = '$lock_id' AND `sid` LIKE '$sid' AND `key` LIKE '$this->lock_key'";

			if (!mysql_query($query)) $this->_error_die(mysql_error());
			mysql_close($this->_db_link_id);
		}
		unset($_SESSION[FLV_SECRET_KEY][$this->lock_key]);
	}

	/**
	* Play the flv  with lock
	*
	* @param bool $usedb			Check in database
	*
	* @return true/false			if sucess/failed.
	*/
    function validLock($usedb=false)
    {
		if(!session_id()) session_start();
		$return = false;
		if($usedb) {
			$this->loadConfig();
			$this->dbConnect();
			$ip = $_SERVER['REMOTE_ADDR'];

			$tableKey = $this->config['db_table_prefix']."key";
			$tableBlock = $this->config['db_table_prefix']."block";

			$query = "SELECT count FROM ".$tableBlock." WHERE `ip` LIKE '$ip' LIMIT 1";
			$result = mysql_query($query);
			$rowBlock = mysql_fetch_assoc($result);

			$sid = session_id();
			$timeout = time();

			$query = "SELECT uid FROM ".$tableKey." WHERE `sid` LIKE '$sid' AND `key` LIKE '$this->lock_key' AND `timeout` >= $timeout LIMIT 1";
			$result = mysql_query($query);
			$rowKey = mysql_fetch_assoc($result);

			if($rowKey['uid'] && ($this->config['block_counter'] > $rowBlock['count'] || !$rowBlock['count'])) $return = true;
			elseif($this->config['block_counter']) {
				$query = "SELECT uid FROM ".$tableBlock." WHERE `ip` LIKE '$ip' LIMIT 1";
				$result = mysql_query($query);
				$rowBlock = mysql_fetch_assoc($result);

				if($rowBlock['uid']) $query = "UPDATE ".$tableBlock." SET count = count+1 WHERE uid = ".$rowBlock['uid'];
				else $query = "INSERT INTO ".$tableBlock." VALUES ('','$ip','1')";
				@mysql_query($query);
				$this->error[] = FLV_ERROR_BLOCKED;
			}
			mysql_close($this->_db_link_id);
		} elseif($_SESSION[FLV_SECRET_KEY][$this->lock_key] >= time()) {
			$return = true;
		}
		$this->closeLock($rowKey['uid']);
		return $return;
	}

	/**
	* Play the flv  with lock
	*
	* @param string $key			Set Key
	*/
    function setKey($key)
    {
		$this->lock_key = $key;
	}

    /**
	* Returns the next tag from the open file
	*
	* @param array $skipTagTypes	The tag types contained in this array won't be examined
	*
	* @return object				FLV_Tag_Generic or one of its descendants
	*/
    function getTag( $skipTagTypes = false )
    {
        if ($this->eof) return NULL;

        $hdr = fread( $this->_fh, $this->TAG_HEADER_SIZE );

		if (strlen($hdr) < $this->TAG_HEADER_SIZE) {
		    $this->eof = true;
		   	return NULL;
		}

		// check against corrupted files
		$prevTagSize = unpack( 'Nprev', $hdr );

//		if ($prevTagSize['prev'] != $this->_lastTagSize) die("<br>Previous tag size check failed. Actual size is ".$this->_lastTagSize." but defined size is ".$prevTagSize['prev']);

		// Get the tag object by skiping the first 4 bytes which tell the previous tag size
		$tag = FLV_Tag::getTag( substr( $hdr, 4 ) );

		// Read at most MAX_TAG_BODY_SIZE bytes of the body
		$bytesToRead = min( $this->MAX_TAG_BODY_SIZE, $tag->size );
		$tag->setBody( fread( $this->_fh, $bytesToRead ) );

		// Check if the tag body has to be processed
		if ((is_array($skipTagTypes) && !in_array($tag->type, $skipTagTypes)) || !$skipTagTypes) $tag->analyze();

		// If the tag was skipped or the body size was larger than MAX_TAG_BODY_SIZE
		if ($tag->size > $bytesToRead) fseek($this->_fh, $tag->size-$bytesToRead, SEEK_CUR);

		$this->_lastTagSize = $tag->size + $this->TAG_HEADER_SIZE - 4;

		$tag->start = $this->getTagOffset();

		$tag->end = ftell($this->_fh);

		return $tag;
    }

	/**
	* Returns the offset from the start of the file of the last processed tag
	*
	* @return int 					get offset
	*/
    function getTagOffset()
    {
    	return ftell($this->_fh) - $this->_lastTagSize;
    }

	/**
	* Connect to Database
	*/
    function dbConnect()
    {
		$this->loadConfig();
		// connect to the mysql database server.
		$this->_db_link_id = mysql_connect ($this->config['db_host'], $this->config['db_username'], $this->config['db_password']);
		// sellect db
		if (!mysql_select_db($this->config['db_name'])) $this->_error_die(mysql_error());
    }

	/**
	* Load Config
	*/
    function loadConfig()
    {
		if( empty($this->config) ) {
			$config = array();
			require(FLV_INCLUDE_PATH.'config.php');
			$this->config = $config;
		}
    }

	/**
	* defaultMetaData
	*
	* @return array 				default Metadata
	*/
    function defaultMetaData($supressGetId3=false)
    {
		$buffMetaData = array();

		if(!$supressGetId3) {
//			$buffMetaData = $this->FileInfo;
			$buffMetaData = $this->FileInfo['meta']['onMetaData'];
			$buffMetaData['flv'] = $this->FileInfo['flv'];
			$buffMetaData['video'] = $this->FileInfo['video'];
			$buffMetaData['audio'] = $this->FileInfo['audio'];
			$buffMetaData['bitrate'] = $this->FileInfo['bitrate'];
		}
		$buffMetaData['width'] = $this->FileInfo['video']['resolution_x'];
		$buffMetaData['height'] = $this->FileInfo['video']['resolution_y'];

		$buffMetaData['metadatacreator'] = 'FLV Editor for PHP '.FLV_VERSION.' (Project: Flv4php)';
		$buffMetaData['creator'] = 'FLV Editor for PHP '.FLV_VERSION." (Project: Flv4php)";
		$buffMetaData['metadatadate'] = gmdate('Y-m-d\TH:i:s') . '.000Z';

		return (array) $buffMetaData;
    }

	/**
	* defaultMetaData replace timestamp in frame with new one
	*/
    function replaceTimestamp(&$frame,$newTimestamp)
    {
		$frame[4] = chr($newTimestamp >> 16);
		$frame[5] = chr($newTimestamp >> 8);
		$frame[6] = chr($newTimestamp);
		$frame[7] = chr($newTimestamp >> 24);
    }

	/**
	* defaultMetaData replace timestamp in frame with new one
	*
	* @return int 					Modifyed timestamp
	*/
    function modifyTimestamp($timestamp,$modifyer=100)
	{
		if ($modifyer < 1) $modifyer = 1;
		return (int) ceil((100*$timestamp)/$modifyer);
    }
}

?>