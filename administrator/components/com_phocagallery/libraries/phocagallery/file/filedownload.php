<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.filesystem.folder' ); 
jimport( 'joomla.filesystem.file' );

class PhocaGalleryFileDownload
{
	function download($item, $backLink, $extLink = 0) {
			
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
				
		return false;
	
	}
}
?>