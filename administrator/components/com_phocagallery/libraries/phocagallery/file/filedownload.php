<?php
/**
 * @package   Phoca Gallery
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.filesystem.folder' ); 
jimport( 'joomla.filesystem.file' );

class PhocaGalleryFileDownload
{
	public static function download($item, $backLink, $extLink = 0) {
			
			
		// If you comment or remove the following line, user will be able to download external images
		// but it can happen that such will be stored on your server in root (the example is picasa images)
		$extLink = 0;
			
		$app	= JFactory::getApplication();

		if (empty($item)) {
			$msg = JText::_('COM_PHOCAGALLERY_ERROR_DOWNLOADING_FILE');
			$app->redirect($backLink, $msg);
			return false;
		} else {
			if ($extLink == 0) {
				phocagalleryimport('phocagallery.file.file');
				$fileOriginal = PhocaGalleryFile::getFileOriginal($item->filenameno);
			
				if (!JFile::exists($fileOriginal)) {
					$msg = JText::_('COM_PHOCAGALLERY_ERROR_DOWNLOADING_FILE');
					$app->redirect($backLink, $msg);
					return false;
				}
				$fileToDownload 	= $item->filenameno;
				$fileNameToDownload	= $item->filename;
			} else {
				$fileToDownload 	= $item->exto;
				$fileNameToDownload	= $item->title;
				$fileOriginal		= $item->exto;
			}
			
			// Clears file status cache
			clearstatcache();
			$fileOriginal	= $fileOriginal;
			$fileSize 		= @filesize($fileOriginal);
			$mimeType 		= PhocaGalleryFile::getMimeType($fileToDownload);
			$fileName		= $fileNameToDownload;
			
			if ($extLink > 0) {
				$content = '';
				if (function_exists('curl_init')) {
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $fileOriginal);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
					$downloadedFile = fopen($fileName, 'w+');
					curl_setopt($ch, CURLOPT_FILE, $downloadedFile);
					$content = curl_exec ($ch);
					$fileSize= strlen($content);
					curl_close ($ch);
					fclose($downloadedFile);
				}
				if ($content != '') {
					// Clean the output buffer
					ob_end_clean();

					header("Cache-Control: public, must-revalidate");
					header('Cache-Control: pre-check=0, post-check=0, max-age=0');
					header("Pragma: no-cache");
					header("Expires: 0"); 
					header("Content-Description: File Transfer");
					header("Expires: Sat, 30 Dec 1990 07:07:07 GMT");
					header("Content-Type: " . (string)$mimeType);
					
					header("Content-Length: ". (string)$fileSize);
					
					header('Content-Disposition: attachment; filename="'.$fileName.'"');
					header("Content-Transfer-Encoding: binary\n");
					
					echo $content;
					exit;
				
				}
			} else {
			
				// Clean the output buffer
				ob_end_clean();

				header("Cache-Control: public, must-revalidate");
				header('Cache-Control: pre-check=0, post-check=0, max-age=0');
				header("Pragma: no-cache");
				header("Expires: 0"); 
				header("Content-Description: File Transfer");
				header("Expires: Sat, 30 Dec 1990 07:07:07 GMT");
				header("Content-Type: " . (string)$mimeType);
				
				// Problem with IE
				if ($extLink == 0) {
					header("Content-Length: ". (string)$fileSize);
				}
				
				header('Content-Disposition: attachment; filename="'.$fileName.'"');
				header("Content-Transfer-Encoding: binary\n");
				
				@readfile($fileOriginal);
				exit;
			}
		}	
		return false;
	
	}
}
?>