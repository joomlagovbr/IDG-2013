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
phocagalleryimport( 'phocagallery.image.image');
phocagalleryimport( 'phocagallery.file.fileuploadfront' );
class PhocaGalleryFileUpload
{
	public static function realMultipleUpload( $frontEnd = 0) {

		$paramsC 		= JComponentHelper::getParams('com_phocagallery');
		$chunkMethod 	= $paramsC->get( 'multiple_upload_chunk', 0 );
		$uploadMethod 	= $paramsC->get( 'multiple_upload_method', 4 );

		$app 	= JFactory::getApplication();
		$app->allowCache(false);

		// Chunk Files
		header('Content-type: text/plain; charset=UTF-8');
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		// Invalid Token
		JSession::checkToken( 'request' ) or jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 100,
				'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
				'details' => JText::_('COM_PHOCAGALLERY_INVALID_TOKEN'))));

		// Set FTP credentials, if given
		$ftp = JClientHelper::setCredentialsFromRequest('ftp');

		$path			= PhocaGalleryPath::getPath();
		$file 			= JFactory::getApplication()->input->files->get( 'file', null );
		$chunk 			= JFactory::getApplication()->input->get( 'chunk', 0, '', 'int' );
		$chunks 		= JFactory::getApplication()->input->get( 'chunks', 0, '', 'int' );
		$folder			= JFactory::getApplication()->input->get( 'folder', '', '', 'path' );

		// Make the filename safe
		if (isset($file['name'])) {
			$file['name']	= JFile::makeSafe($file['name']);
		}
		if (isset($folder) && $folder != '') {
			$folder	= $folder . '/';
		}

		$chunkEnabled = 0;
		// Chunk only if is enabled and only if flash is enabled
		if (($chunkMethod == 1 && $uploadMethod == 1) || ($frontEnd == 0 && $chunkMethod == 0 && $uploadMethod == 1)) {
			$chunkEnabled = 1;
		}




		if (isset($file['name'])) {


			// - - - - - - - - - -
			// Chunk Method
			// - - - - - - - - - -
			// $chunkMethod = 1, for frontend and backend
			// $chunkMethod = 0, only for backend
			if ($chunkEnabled == 1) {

				// If chunk files are used, we need to upload parts to temp directory
				// and then we can run e.g. the condition to recognize if the file already exists
				// We must upload the parts to temp, in other case we get everytime the info
				// that the file exists (because the part has the same name as the file)
				// so after first part is uploaded, in fact the file already exists
				// Example: NOT USING CHUNK
				// If we upload abc.jpg file to server and there is the same file
				// we compare it and can recognize, there is one, don't upload it again.
				// Example: USING CHUNK
				// If we upload abc.jpg file to server and there is the same file
				// the part of current file will overwrite the same file
				// and then (after all parts will be uploaded) we can make the condition to compare the file
				// and we recognize there is one - ok don't upload it BUT the file will be damaged by
				// parts uploaded by the new file - so this is why we are using temp file in Chunk method
				$stream 				= JFactory::getStream();// Chunk Files
				$tempFolder				= 'pgpluploadtmpfolder/';
				$filepathImgFinal 		= JPath::clean($path->image_abs.$folder.strtolower($file['name']));
				$filepathImgTemp 		= JPath::clean($path->image_abs.$folder.$tempFolder.strtolower($file['name']));
				$filepathFolderFinal 	= JPath::clean($path->image_abs.$folder);
				$filepathFolderTemp 	= JPath::clean($path->image_abs.$folder.$tempFolder);
				$maxFileAge 			= 60 * 60; // Temp file age in seconds
				$lastChunk				= $chunk + 1;
				$realSize				= 0;

				// Get the real size - if chunk is uploaded, it is only a part size, so we must compute all size
				// If there is last chunk we can computhe the whole size
				if ($lastChunk == $chunks) {
					if (JFile::exists($filepathImgTemp) && JFile::exists($file['tmp_name'])) {
						$realSize = filesize($filepathImgTemp) + filesize($file['tmp_name']);
					}
				}

				// 5 minutes execution time
				@set_time_limit(5 * 60);// usleep(5000);

				// If the file already exists on the server:
				// - don't copy the temp file to final
				// - remove all parts in temp file
				// Because some parts are uploaded before we can run the condition
				// to recognize if the file already exists.
				if (JFile::exists($filepathImgFinal)) {
					if($lastChunk == $chunks){
					@JFolder::delete($filepathFolderTemp);
				}

				jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 108,
							'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
							'details' => JText::_('COM_PHOCAGALLERY_FILE_ALREADY_EXISTS'))));
				}

				if (!PhocaGalleryFileUpload::canUpload( $file, $errUploadMsg, $frontEnd, $chunkEnabled, $realSize )) {

					// If there is some error, remove the temp folder with temp files
					if($lastChunk == $chunks){
						@JFolder::delete($filepathFolderTemp);
					}
					jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 104,
								'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
								'details' => JText::_($errUploadMsg))));
				}

				// Ok create temp folder and add chunks
				if (!JFolder::exists($filepathFolderTemp)) {
					@JFolder::create($filepathFolderTemp);
				}

				// Remove old temp files
				if (JFolder::exists($filepathFolderTemp)) {
					$dirFiles = JFolder::files($filepathFolderTemp);
					if (!empty($dirFiles)) {
						foreach ($dirFiles as $fileS) {
							$filePathImgS = $filepathFolderTemp . $fileS;
							// Remove temp files if they are older than the max age
							if (preg_match('/\\.tmp$/', $fileS) && (filemtime($filepathImgTemp) < time() - $maxFileAge)) {
								@JFile::delete($filePathImgS);
							}
						}
					}
				} else {
					jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 100,
							'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
							'details' => JText::_('COM_PHOCAGALLERY_ERROR_FOLDER_UPLOAD_NOT_EXISTS'))));
				}

				// Look for the content type header
				if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
					$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

				if (isset($_SERVER["CONTENT_TYPE"]))
					$contentType = $_SERVER["CONTENT_TYPE"];

				if (strpos($contentType, "multipart") !== false) {
					if (isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {

						// Open temp file
						$out = $stream->open($filepathImgTemp, $chunk == 0 ? "wb" : "ab");
						//$out = fopen($filepathImgTemp, $chunk == 0 ? "wb" : "ab");
						if ($out) {
							// Read binary input stream and append it to temp file
							$in = fopen($file['tmp_name'], "rb");
							if ($in) {
								while ($buff = fread($in, 4096)) {
									$stream->write($buff);
									//fwrite($out, $buff);
								}
							} else {
								jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 101,
								'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
								'details' => JText::_('COM_PHOCAGALLERY_ERROR_OPEN_INPUT_STREAM'))));
							}
							$stream->close();
							//fclose($out);
							@JFile::delete($file['tmp_name']);
						} else {
							jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 102,
							'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
							'details' => JText::_('COM_PHOCAGALLERY_ERROR_OPEN_OUTPUT_STREAM'))));
						}
					} else {
						jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 103,
							'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
							'details' => JText::_('COM_PHOCAGALLERY_ERROR_MOVE_UPLOADED_FILE'))));
					}
				} else {
					// Open temp file
					$out = $stream->open($filepathImgTemp, $chunk == 0 ? "wb" : "ab");
					//$out = JFile::read($filepathImg);
					if ($out) {
						// Read binary input stream and append it to temp file
						$in = fopen("php://input", "rb");

						if ($in) {
							while ($buff = fread($in, 4096)) {
								$stream->write($buff);
							}
						} else {
							jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 101,
								'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
								'details' => JText::_('COM_PHOCAGALLERY_ERROR_OPEN_INPUT_STREAM'))));
						}
						$stream->close();
						//fclose($out);
					} else {
						jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 102,
						'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
						'details' => JText::_('COM_PHOCAGALLERY_ERROR_OPEN_OUTPUT_STREAM'))));
					}
				}


				// Rename the Temp File to Final File
				if($lastChunk == $chunks){

					if(($imginfo = getimagesize($filepathImgTemp)) === FALSE) {
						JFolder::delete($filepathFolderTemp);
						jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 110,
						'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
						'details' => JText::_('COM_PHOCAGALLERY_WARNING_INVALIDIMG'))));
					}


					if(!JFile::move($filepathImgTemp, $filepathImgFinal)) {

						JFolder::delete($filepathFolderTemp);

						jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 109,
						'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
						'details' => JText::_('COM_PHOCAGALLERY_ERROR_UNABLE_TO_MOVE_FILE') .'<br />'
						. JText::_('COM_PHOCAGALLERY_CHECK_PERMISSIONS_OWNERSHIP'))));
					}


					JFolder::delete($filepathFolderTemp);
				}

				if ((int)$frontEnd > 0) {
					return $file['name'];
				}

				jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'OK', 'code' => 200,
				'message' => JText::_('COM_PHOCAGALLERY_SUCCESS').': ',
				'details' => JText::_('COM_PHOCAGALLERY_IMAGES_UPLOADED'))));


			} else {
				// No Chunk Method

				$filepathImgFinal 		= JPath::clean($path->image_abs.$folder.strtolower($file['name']));
				$filepathFolderFinal 	= JPath::clean($path->image_abs.$folder);



				if (!PhocaGalleryFileUpload::canUpload( $file, $errUploadMsg, $frontEnd, $chunkMethod, 0 )) {
					jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 104,
					'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
					'details' => JText::_($errUploadMsg))));
				}

				if (JFile::exists($filepathImgFinal)) {
					jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 108,
					'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
					'details' => JText::_('COM_PHOCAGALLERY_FILE_ALREADY_EXISTS'))));
				}


				if(!JFile::upload($file['tmp_name'], $filepathImgFinal, false, true)) {
					jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 109,
					'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
					'details' => JText::_('COM_PHOCAGALLERY_ERROR_UNABLE_TO_UPLOAD_FILE') .'<br />'
					. JText::_('COM_PHOCAGALLERY_CHECK_PERMISSIONS_OWNERSHIP'))));
				}

				if ((int)$frontEnd > 0) {
					return $file['name'];
				}

				jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'OK', 'code' => 200,
				'message' => JText::_('COM_PHOCAGALLERY_SUCCESS').': ',
				'details' => JText::_('COM_PHOCAGALLERY_IMAGES_UPLOADED'))));


			}
		} else {
			// No isset $file['name']

			jexit(json_encode(array( 'jsonrpc' => '2.0', 'result' => 'error', 'code' => 104,
			'message' => JText::_('COM_PHOCAGALLERY_ERROR').': ',
			'details' => JText::_('COM_PHOCAGALLERY_ERROR_UNABLE_TO_UPLOAD_FILE'))));
		}

	}


	public static function realSingleUpload( $frontEnd = 0 ) {

	//	$paramsC 		= JComponentHelper::getParams('com_phocagallery');
	//	$chunkMethod 	= $paramsC->get( 'multiple_upload_chunk', 0 );
	//	$uploadMethod 	= $paramsC->get( 'multiple_upload_method', 4 );

		$app			= JFactory::getApplication();
		JSession::checkToken( 'request' ) or jexit( 'ERROR: '. JText::_('COM_PHOCAGALLERY_INVALID_TOKEN'));

		$app->allowCache(false);

		$path			= PhocaGalleryPath::getPath();
		$file 			= JFactory::getApplication()->input->files->get( 'Filedata', null );
		$folder			= JFactory::getApplication()->input->get( 'folder', '', '', 'path' );
		$format			= JFactory::getApplication()->input->get( 'format', 'html', '', 'cmd');
		$return			= JFactory::getApplication()->input->get( 'return-url', null, 'post', 'base64' );//includes field
		$viewBack		= JFactory::getApplication()->input->get( 'viewback', '', '', '' );
		$tab			= JFactory::getApplication()->input->get( 'tab', '', '', 'string' );
		$field			= JFactory::getApplication()->input->get( 'field' );
		$errUploadMsg	= '';
		$folderUrl 		= $folder;
		$tabUrl			= '';
		$component		= JFactory::getApplication()->input->get( 'option', '', '', 'string' );

		// In case no return value will be sent (should not happen)
		if ($component != '' && $frontEnd == 0) {
			$componentUrl 	= 'index.php?option='.$component;
		} else {
			$componentUrl	= 'index.php';
		}
		if ($tab != '') {
			$tabUrl = '&tab='.(string)$tab;
		}

		$ftp = JClientHelper::setCredentialsFromRequest('ftp');

		// Make the filename safe
		if (isset($file['name'])) {
			$file['name']	= JFile::makeSafe($file['name']);
		}


		if (isset($folder) && $folder != '') {
			$folder	= $folder . '/';
		}


		// All HTTP header will be overwritten with js message
		if (isset($file['name'])) {
			$filepath = JPath::clean($path->image_abs.$folder.strtolower($file['name']));

			if (!PhocaGalleryFileUpload::canUpload( $file, $errUploadMsg, $frontEnd )) {

				if ($errUploadMsg == 'COM_PHOCAGALLERY_WARNING_FILE_TOOLARGE') {
					$errUploadMsg 	= JText::_($errUploadMsg) . ' ('.PhocaGalleryFile::getFileSizeReadable($file['size']).')';
				} else if ($errUploadMsg == 'COM_PHOCAGALLERY_WARNING_FILE_TOOLARGE_RESOLUTION') {
					$imgSize		= PhocaGalleryImage::getImageSize($file['tmp_name']);
					$errUploadMsg 	= JText::_($errUploadMsg) . ' ('.(int)$imgSize[0].' x '.(int)$imgSize[1].' px)';
				} else {
					$errUploadMsg 	= JText::_($errUploadMsg);
				}


				/*if ($return) {
					$app->enqueueMessage( $errUploadMsg, 'error');
					$app->redirect(base64_decode($return).'&folder='.$folderUrl);
					exit;
				} else {
					$app->enqueueMessage( $errUploadMsg, 'error');
					$app->redirect($componentUrl, $errUploadMsg, 'error');
					exit;
				}*/


				if ($return) {
					$app->enqueueMessage( $errUploadMsg, 'error');
					if ($frontEnd > 0) {

						$app->redirect(base64_decode($return));
					} else {
						$app->redirect(base64_decode($return).'&folder='.$folderUrl);
					}
					exit;
				} else {
					$app->enqueueMessage( $errUploadMsg, 'error');
					$app->redirect($componentUrl, $errUploadMsg, 'error');
					exit;
				}
			}

			if (JFile::exists($filepath)) {
				if ($return) {
					$app->enqueueMessage( JText::_('COM_PHOCAGALLERY_FILE_ALREADY_EXISTS'), 'error');
					$app->redirect(base64_decode($return).'&folder='.$folderUrl);
					exit;
				} else {
					$app->enqueueMessage(JText::_('COM_PHOCAGALLERY_FILE_ALREADY_EXISTS'), 'error');
					$app->redirect($componentUrl);
					exit;
				}
			}

			if (!JFile::upload($file['tmp_name'], $filepath, false, true)) {
				if ($return) {
					$app->enqueueMessage( JText::_('COM_PHOCAGALLERY_ERROR_UNABLE_TO_UPLOAD_FILE'), 'error');
					$app->redirect(base64_decode($return).'&folder='.$folderUrl);
					exit;
				} else {
					$app->enqueueMessage( JText::_('COM_PHOCAGALLERY_ERROR_UNABLE_TO_UPLOAD_FILE'), 'error');
					$app->redirect($componentUrl);
					exit;
				}
			} else {

				if ((int)$frontEnd > 0) {
					return $file['name'];
				}

				if ($return) {
					$app->enqueueMessage( JText::_('COM_PHOCAGALLERY_SUCCESS_FILE_UPLOAD'));
					$app->redirect(base64_decode($return).'&folder='.$folderUrl);
					exit;
				} else {
					$app->enqueueMessage( JText::_('COM_PHOCAGALLERY_SUCCESS_FILE_UPLOAD'));
					$app->redirect($componentUrl, JText::_('COM_PHOCAGALLERY_SUCCESS_FILE_UPLOAD'));
					exit;
				}
			}
		} else {
			$msg = JText::_('COM_PHOCAGALLERY_ERROR_UNABLE_TO_UPLOAD_FILE');
			if ($return) {
				$app->enqueueMessage( $msg);
				$app->redirect(base64_decode($return).'&folder='.$folderUrl);
				exit;
			} else {
				switch ($viewBack) {
					case 'phocagalleryi':
						$app->enqueueMessage( $msg, 'error');
						$app->redirect('index.php?option=com_phocagallery&view=phocagalleryi&tmpl=component'.$tabUrl.'&folder='.$folder.'&field='.$field);
						exit;
					break;

					case 'phocagallerym':
						$app->enqueueMessage( $msg, 'error');
						$app->redirect('index.php?option=com_phocagallery&view=phocagallerym&layout=form&hidemainmenu=1'.$tabUrl.'&folder='.$folder);
						exit;
					break;

					default:
						$app->enqueueMessage( $msg, 'error');
						$app->redirect('index.php?option=com_phocagallery');
						exit;
					break;

				}
			}
		}

	}

	public static function realJavaUpload( $frontEnd = 0 ) {

		$app	= JFactory::getApplication();

		JSession::checkToken( 'request' ) or exit( 'ERROR: '. JText::_('COM_PHOCAGALLERY_INVALID_TOKEN'));

	//	$files 	= JFactory::getApplication()->input->get( 'Filedata', '', 'files', 'array' );

		$path		= PhocaGalleryPath::getPath();
		$folder		= JFactory::getApplication()->input->get( 'folder', '', '', 'path' );

		if (isset($folder) && $folder != '') {
			$folder	= $folder . '/';
		}
		$errUploadMsg	= '';
		$ftp 			= JClientHelper::setCredentialsFromRequest('ftp');

		foreach ($_FILES as $fileValue => $file) {
			echo('File key: '. $fileValue . "\n");
			foreach ($file as $item => $val) {
				echo(' Data received: ' . $item.'=>'.$val . "\n");
			}


			// Make the filename safe
			if (isset($file['name'])) {
				$file['name'] = JFile::makeSafe($file['name']);
			}

			if (isset($file['name'])) {
				$filepath = JPath::clean($path->image_abs.$folder.strtolower($file['name']));

				if (!PhocaGalleryFileUpload::canUpload( $file, $errUploadMsg, $frontEnd  )) {
					exit( 'ERROR: '.JText::_($errUploadMsg));
				}

				if (JFile::exists($filepath)) {
					exit( 'ERROR: '.JText::_('COM_PHOCAGALLERY_FILE_ALREADY_EXISTS'));
				}

				if (!JFile::upload($file['tmp_name'], $filepath, false, true)) {
					exit( 'ERROR: '.JText::_('COM_PHOCAGALLERY_ERROR_UNABLE_TO_UPLOAD_FILE'));
				}
				if ((int)$frontEnd > 0) {
					return $file['name'];
				}

				exit( 'SUCCESS');
			} else {
				exit( 'ERROR: '.JText::_('COM_PHOCAGALLERY_ERROR_UNABLE_TO_UPLOAD_FILE'));
			}
		}
		return true;
	}


	/**
	 * can Upload
	 *
	 * @param array $file
	 * @param string $errorUploadMsg
	 * @param int $frontEnd - if it is called from frontend or backend (1  - category view, 2 user control panel)
	 * @param boolean $chunkMethod - if chunk method is used (multiple upload) then there are special rules
	 * @param string $realSize - if chunk method is used we get info about real size of file (not only the part)
	 * @return boolean True on success
	 * @since 1.5
	 */


	public static function canUpload( $file, &$errUploadMsg, $frontEnd = 0, $chunkEnabled = 0, $realSize = 0 ) {

		$params 	= JComponentHelper::getParams( 'com_phocagallery' );
		$paramsL 	= array();
		$paramsL['upload_extensions'] 	= 'gif,jpg,png,jpeg,webp';
		$paramsL['image_extensions'] 	= 'gif,jpg,png,jpeg,webp';
		$paramsL['upload_mime']			= 'image/jpeg,image/gif,image/png,image/webp';
		$paramsL['upload_mime_illegal']	='application/x-shockwave-flash,application/msword,application/excel,application/pdf,application/powerpoint,text/plain,application/x-zip,text/html';

		// The file doesn't exist
		if(empty($file['name'])) {
			$errUploadMsg = 'COM_PHOCAGALLERY_ERROR_UNABLE_TO_UPLOAD_FILE';
			return false;
		}

		// Not safe file
		jimport('joomla.filesystem.file');
		if ($file['name'] !== JFile::makesafe($file['name'])) {
			$errUploadMsg = 'COM_PHOCAGALLERY_WARNING_FILENAME';
			return false;
		}

		$format = strtolower(JFile::getExt($file['name']));

		// Allowable extension
		$allowable = explode( ',', $paramsL['upload_extensions']);
		if ($format == '' || $format == false || (!in_array($format, $allowable))) {
		//if (!in_array($format, $allowable)) {
			$errUploadMsg = 'COM_PHOCAGALLERY_WARNING_FILETYPE';
			return false;
		}

		// 'COM_PHOCAGALLERY_MAX_RESOLUTION'
		$imgSize		= PhocaGalleryImage::getImageSize($file['tmp_name']);
		$maxResWidth 	= $params->get( 'upload_maxres_width', 3072 );
		$maxResHeight 	= $params->get( 'upload_maxres_height', 2304 );
		if (((int)$maxResWidth > 0 && (int)$maxResHeight > 0)
		&& ((int)$imgSize[0] > (int)$maxResWidth || (int)$imgSize[1] > (int)$maxResHeight)) {
			$errUploadMsg = 'COM_PHOCAGALLERY_WARNING_FILE_TOOLARGE_RESOLUTION';
			return false;
		}

		// User (only in ucp) - Check the size of all images by users
		if ($frontEnd == 2) {
			$user 				= JFactory::getUser();
			$maxUserImageSize 	= (int)$params->get( 'user_images_max_size', 20971520 );

			if ($chunkEnabled == 1) {
				$fileSize = $realSize;
			} else {
				$fileSize = $file['size'];
			}
			$allFileSize = PhocaGalleryFileUploadFront::getSizeAllOriginalImages($fileSize, $user->id);

			if ((int)$maxUserImageSize > 0 && (int) $allFileSize > $maxUserImageSize) {
				$errUploadMsg = JText::_('COM_PHOCAGALLERY_WARNING_USERIMAGES_TOOLARGE');
				return false;
			}
		}

		// Max size of image
		// If chunk method is used, we need to get computed size
		$maxSize = $params->get( 'upload_maxsize', 3145728 );
		if ($chunkEnabled == 1) {
			if ((int)$maxSize > 0 && (int)$realSize > (int)$maxSize) {
				$errUploadMsg = 'COM_PHOCAGALLERY_WARNING_FILE_TOOLARGE';
				return false;
			}
		} else {
			if ((int)$maxSize > 0 && (int)$file['size'] > (int)$maxSize) {
				$errUploadMsg = 'COM_PHOCAGALLERY_WARNING_FILE_TOOLARGE';
				return false;
			}
		}

		$user = JFactory::getUser();
		$imginfo = null;


		// Image check
		$images = explode( ',', $paramsL['image_extensions']);
		if(in_array($format, $images)) { // if its an image run it through getimagesize
			if ($chunkEnabled != 1) {
				if(($imginfo = getimagesize($file['tmp_name'])) === FALSE) {
					$errUploadMsg = 'COM_PHOCAGALLERY_WARNING_INVALIDIMG';
					return false;
				}
			}
		} else if(!in_array($format, $images)) {
			// if its not an image...and we're not ignoring it
			$allowed_mime = explode(',', $paramsL['upload_mime']);
			$illegal_mime = explode(',', $paramsL['upload_mime_illegal']);
			if(function_exists('finfo_open')) {
				// We have fileinfo
				$finfo = finfo_open(FILEINFO_MIME);
				$type = finfo_file($finfo, $file['tmp_name']);
				if(strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime)) {
					$errUploadMsg = 'COM_PHOCAGALLERY_WARNING_INVALIDMIME';
					return false;
				}
				finfo_close($finfo);
			} else if(function_exists('mime_content_type')) {
				// we have mime magic
				$type = mime_content_type($file['tmp_name']);
				if(strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime)) {
					$errUploadMsg = 'COM_PHOCAGALLERY_WARNING_INVALIDMIME';
					return false;
				}
			}/* else if(!$user->authorize( 'login', 'administrator' )) {
				$errUploadMsg =  = 'WARNNOTADMIN';
				return false;
			}*/
		}

		// XSS Check
		$xss_check =  JFile::read($file['tmp_name'],false,256);
		$html_tags = array('abbr','acronym','address','applet','area','audioscope','base','basefont','bdo','bgsound','big','blackface','blink','blockquote','body','bq','br','button','caption','center','cite','code','col','colgroup','comment','custom','dd','del','dfn','dir','div','dl','dt','em','embed','fieldset','fn','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','iframe','ilayer','img','input','ins','isindex','keygen','kbd','label','layer','legend','li','limittext','link','listing','map','marquee','menu','meta','multicol','nobr','noembed','noframes','noscript','nosmartquotes','object','ol','optgroup','option','param','plaintext','pre','rt','ruby','s','samp','script','select','server','shadow','sidebar','small','spacer','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','ul','var','wbr','xml','xmp','!DOCTYPE', '!--');
		foreach($html_tags as $tag) {
			// A tag is '<tagname ', so we need to add < and a space or '<tagname>'
			if(stristr($xss_check, '<'.$tag.' ') || stristr($xss_check, '<'.$tag.'>')) {
				$errUploadMsg = 'COM_PHOCAGALLERY_WARNING_IEXSS';
				return false;
			}
		}
		return true;
	}
	/*
	function uploader($id='file-upload', $params = array()) {

		$path = 'administrator/components/com_phocagallery/assets/upload/';
		JHTML::script('swf.js', $path, false ); // mootools are loaded yet
		JHTML::script('uploader.js', $path, false );// mootools are loaded yet

		static $uploaders;

		if (!isset($uploaders)) {
			$uploaders = array();
		}

		if (isset($uploaders[$id]) && ($uploaders[$id])) {
			return;
		}

		// Setup options object
		$opt['url']					= (isset($params['targetURL'])) ? $params['targetURL'] : null ;
		$opt['swf']					= (isset($params['swf'])) ? $params['swf'] : JURI::root(true).'/media/system/swf/uploader.swf';
		$opt['multiple']			= (isset($params['multiple']) && !($params['multiple'])) ? '\\false' : '\\true';
		$opt['queued']				= (isset($params['queued']) && !($params['queued'])) ? '\\false' : '\\true';
		$opt['queueList']			= (isset($params['queueList'])) ? $params['queueList'] : 'upload-queue';
		$opt['instantStart']		= (isset($params['instantStart']) && ($params['instantStart'])) ? '\\true' : '\\false';
		$opt['allowDuplicates']		= (isset($params['allowDuplicates']) && !($params['allowDuplicates'])) ? '\\false' : '\\true';
		$opt['limitSize']			= (isset($params['limitSize']) && ($params['limitSize'])) ? (int)$params['limitSize'] : null;
		$opt['limitFiles']			= (isset($params['limitFiles']) && ($params['limitFiles'])) ? (int)$params['limitFiles'] : null;
		$opt['optionFxDuration']	= (isset($params['optionFxDuration'])) ? (int)$params['optionFxDuration'] : null;
		$opt['container']			= (isset($params['container'])) ? '\\$('.$params['container'].')' : '\\$(\''.$id.'\').getParent()';
		$opt['types']				= (isset($params['types'])) ?'\\'.$params['types'] : '\\{\'All Files (*.*)\': \'*.*\'}';

		// Optional functions
		$opt['createReplacement']	= (isset($params['createReplacement'])) ? '\\'.$params['createReplacement'] : null;
		$opt['onComplete']			= (isset($params['onComplete'])) ? '\\'.$params['onComplete'] : null;
		$opt['onAllComplete']		= (isset($params['onAllComplete'])) ? '\\'.$params['onAllComplete'] : null;

/*  types: Object with (description: extension) pairs, Default: Images (*.jpg; *.jpeg; *.gif; *.png)
 */
/*
		$options = PhocaGalleryFileUpload::getJSObject($opt);

		// Attach tooltips to document
		$document =JFactory::getDocument();
		$uploaderInit = 'sBrowseCaption=\''.JText::_('Browse Files', true).'\';
				sRemoveToolTip=\''.JText::_('Remove from queue', true).'\';
				window.addEvent(\'load\', function(){
				var Uploader = new FancyUpload($(\''.$id.'\'), '.$options.');
				$(\'upload-clear\').adopt(new Element(\'input\', { type: \'button\', events: { click: Uploader.clearList.bind(Uploader, [false])}, value: \''.JText::_('Clear Completed').'\' }));				});';
		$document->addScriptDeclaration($uploaderInit);

		// Set static array
		$uploaders[$id] = true;
		return;
	}

	protected static function getJSObject($array=array())
	{
		// Initialise variables.
		$object = '{';

		// Iterate over array to build objects
		foreach ((array)$array as $k => $v)
		{
			if (is_null($v)) {
				continue;
			}
			if (!is_array($v) && !is_object($v))
			{
				$object .= ' '.$k.': ';
				$object .= (is_numeric($v) || strpos($v, '\\') === 0) ? (is_numeric($v)) ? $v : substr($v, 1) : "'".$v."'";
				$object .= ',';
			}
			else {
				$object .= ' '.$k.': '.PhocaGalleryFileUpload::getJSObject($v).',';
			}
		}
		if (substr($object, -1) == ',') {
			$object = substr($object, 0, -1);
		}
		$object .= '}';

		return $object;
	}*/

	public static function renderFTPaccess() {

		$ftpOutput = '<fieldset title="'.JText::_('COM_PHOCAGALLERY_FTP_LOGIN_LABEL'). '">'
		.'<legend>'. JText::_('COM_PHOCAGALLERY_FTP_LOGIN_LABEL').'</legend>'
		.JText::_('COM_PHOCAGALLERY_FTP_LOGIN_DESC')
		.'<table class="adminform nospace">'
		.'<tr>'
		.'<td width="120"><label for="username">'. JText::_('JGLOBAL_USERNAME').':</label></td>'
		.'<td><input type="text" id="username" name="username" class="input_box" size="70" value="" /></td>'
		.'</tr>'
		.'<tr>'
		.'<td width="120"><label for="password">'. JText::_('JGLOBAL_PASSWORD').':</label></td>'
		.'<td><input type="password" id="password" name="password" class="input_box" size="70" value="" /></td>'
		.'</tr></table></fieldset>';
		return $ftpOutput;
	}

	public static function renderCreateFolder($sessName, $sessId, $currentFolder, $viewBack, $attribs = '') {

		if ($attribs != '') {
			$attribs = '&amp;'.$attribs;
		}

		$folderOutput = '<form action="'. JURI::base()
		.'index.php?option=com_phocagallery&task=phocagalleryu.createfolder&amp;'. $sessName.'='.$sessId.'&amp;'
		.JSession::getFormToken().'=1&amp;viewback='.$viewBack.'&amp;'
		.'folder='.PhocaGalleryText::filterValue($currentFolder, 'folderpath').$attribs .'" name="folderForm" id="folderForm" method="post">'
		//.'<fieldset id="folderview">'
		//.'<legend>'.JText::_('COM_PHOCAGALLERY_FOLDER').'</legend>'
		.'<div class="ph-in"><div class="ph-head-form">'.JText::_('COM_PHOCAGALLERY_CREATE_FOLDER').'</div>'
		.'<dl class="dl-horizontal ph-input">'
		.'<dt><input class="inputbox" type="text" id="foldername" name="foldername"  /></dt>'
		.'<input class="update-folder" type="hidden" name="folderbase" id="folderbase" value="'.PhocaGalleryText::filterValue($currentFolder, 'folderpath').'" />'
		.'<dd><button class="btn btn-success" type="submit">'. JText::_( 'COM_PHOCAGALLERY_CREATE_FOLDER' ).'</button></dd>'
		.'</dl></div>'
	    //.'</fieldset>'
		.JHTML::_( 'form.token' )
		.'</form>';
		return $folderOutput;
	}
}
?>
