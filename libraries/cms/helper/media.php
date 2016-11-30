<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

/**
 * Media helper class
 *
 * @since  3.2
 */
class JHelperMedia
{
	/**
	 * Checks if the file is an image
	 *
	 * @param   string  $fileName  The filename
	 *
	 * @return  boolean
	 *
	 * @since   3.2
	 */
	public function isImage($fileName)
	{
		static $imageTypes = 'xcf|odg|gif|jpg|png|bmp';

		return preg_match("/\.(?:$imageTypes)$/i", $fileName);
	}

	/**
	 * Gets the file extension for purposed of using an icon
	 *
	 * @param   string  $fileName  The filename
	 *
	 * @return  string  File extension to determine icon
	 *
	 * @since   3.2
	 */
	public static function getTypeIcon($fileName)
	{
		return strtolower(substr($fileName, strrpos($fileName, '.') + 1));
	}

	/**
	 * Checks if the file can be uploaded
	 *
	 * @param   array   $file       File information
	 * @param   string  $component  The option name for the component storing the parameters
	 *
	 * @return  boolean
	 *
	 * @since   3.2
	 */
	public function canUpload($file, $component = 'com_media')
	{
		$app    = JFactory::getApplication();
		$params = JComponentHelper::getParams($component);

		if (empty($file['name']))
		{
			$app->enqueueMessage(JText::_('JLIB_MEDIA_ERROR_UPLOAD_INPUT'), 'notice');

			return false;
		}

		jimport('joomla.filesystem.file');

		if (str_replace(' ', '', $file['name']) != $file['name'] || $file['name'] !== JFile::makeSafe($file['name']))
		{
			$app->enqueueMessage(JText::_('JLIB_MEDIA_ERROR_WARNFILENAME'), 'notice');

			return false;
		}

		$filetypes = explode('.', $file['name']);

		if (count($filetypes) < 2)
		{
			// There seems to be no extension
			$app->enqueueMessage(JText::_('JLIB_MEDIA_ERROR_WARNFILETYPE'), 'notice');

			return false;
		}

		array_shift($filetypes);

		// Media file names should never have executable extensions buried in them.
		$executable = array(
			'php', 'js', 'exe', 'phtml', 'java', 'perl', 'py', 'asp','dll', 'go', 'ade', 'adp', 'bat', 'chm', 'cmd', 'com', 'cpl', 'hta', 'ins', 'isp',
			'jse', 'lib', 'mde', 'msc', 'msp', 'mst', 'pif', 'scr', 'sct', 'shb', 'sys', 'vb', 'vbe', 'vbs', 'vxd', 'wsc', 'wsf', 'wsh'
		);

		$check = array_intersect($filetypes, $executable);

		if (!empty($check))
		{
			$app->enqueueMessage(JText::_('JLIB_MEDIA_ERROR_WARNFILETYPE'), 'notice');

			return false;
		}

		$filetype = array_pop($filetypes);
		$allowable = array_map('trim', explode(',', $params->get('upload_extensions')));
		$ignored   = array_map('trim', explode(',', $params->get('ignore_extensions')));

		if ($filetype == '' || $filetype == false || (!in_array($filetype, $allowable) && !in_array($filetype, $ignored)))
		{
			$app->enqueueMessage(JText::_('JLIB_MEDIA_ERROR_WARNFILETYPE'), 'notice');

			return false;
		}

		$maxSize = (int) ($params->get('upload_maxsize', 0) * 1024 * 1024);

		if ($maxSize > 0 && (int) $file['size'] > $maxSize)
		{
			$app->enqueueMessage(JText::_('JLIB_MEDIA_ERROR_WARNFILETOOLARGE'), 'notice');

			return false;
		}

		if ($params->get('restrict_uploads', 1))
		{
			$images = array_map('trim', explode(',', $params->get('image_extensions')));

			if (in_array($filetype, $images))
			{
				// If it is an image run it through getimagesize
				// If tmp_name is empty, then the file was bigger than the PHP limit
				if (!empty($file['tmp_name']))
				{
					if (($imginfo = getimagesize($file['tmp_name'])) === false)
					{
						$app->enqueueMessage(JText::_('JLIB_MEDIA_ERROR_WARNINVALID_IMG'), 'notice');

						return false;
					}
				}
				else
				{
					$app->enqueueMessage(JText::_('JLIB_MEDIA_ERROR_WARNFILETOOLARGE'), 'notice');

					return false;
				}
			}
			elseif (!in_array($filetype, $ignored))
			{
				// If it's not an image, and we're not ignoring it
				$allowed_mime = array_map('trim', explode(',', $params->get('upload_mime')));
				$illegal_mime = array_map('trim', explode(',', $params->get('upload_mime_illegal')));

				if (function_exists('finfo_open') && $params->get('check_mime', 1))
				{
					// We have fileinfo
					$finfo = finfo_open(FILEINFO_MIME);
					$type  = finfo_file($finfo, $file['tmp_name']);

					if (strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime))
					{
						$app->enqueueMessage(JText::_('JLIB_MEDIA_ERROR_WARNINVALID_MIME'), 'notice');

						return false;
					}

					finfo_close($finfo);
				}
				elseif (function_exists('mime_content_type') && $params->get('check_mime', 1))
				{
					// We have mime magic.
					$type = mime_content_type($file['tmp_name']);

					if (strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime))
					{
						$app->enqueueMessage(JText::_('JLIB_MEDIA_ERROR_WARNINVALID_MIME'), 'notice');

						return false;
					}
				}
				elseif (!JFactory::getUser()->authorise('core.manage', $component))
				{
					$app->enqueueMessage(JText::_('JLIB_MEDIA_ERROR_WARNNOTADMIN'), 'notice');

					return false;
				}
			}
		}

		$xss_check = file_get_contents($file['tmp_name'], false, null, -1, 256);

		$html_tags = array(
			'abbr', 'acronym', 'address', 'applet', 'area', 'audioscope', 'base', 'basefont', 'bdo', 'bgsound', 'big', 'blackface', 'blink',
			'blockquote', 'body', 'bq', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'comment', 'custom', 'dd', 'del',
			'dfn', 'dir', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'fn', 'font', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
			'head', 'hr', 'html', 'iframe', 'ilayer', 'img', 'input', 'ins', 'isindex', 'keygen', 'kbd', 'label', 'layer', 'legend', 'li', 'limittext',
			'link', 'listing', 'map', 'marquee', 'menu', 'meta', 'multicol', 'nobr', 'noembed', 'noframes', 'noscript', 'nosmartquotes', 'object',
			'ol', 'optgroup', 'option', 'param', 'plaintext', 'pre', 'rt', 'ruby', 's', 'samp', 'script', 'select', 'server', 'shadow', 'sidebar',
			'small', 'spacer', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'title',
			'tr', 'tt', 'ul', 'var', 'wbr', 'xml', 'xmp', '!DOCTYPE', '!--'
		);

		foreach ($html_tags as $tag)
		{
			// A tag is '<tagname ', so we need to add < and a space or '<tagname>'
			if (stristr($xss_check, '<' . $tag . ' ') || stristr($xss_check, '<' . $tag . '>'))
			{
				$app->enqueueMessage(JText::_('JLIB_MEDIA_ERROR_WARNIEXSS'), 'notice');

				return false;
			}
		}

		return true;
	}

	/**
	 * Calculate the size of a resized image
	 *
	 * @param   integer  $width   Image width
	 * @param   integer  $height  Image height
	 * @param   integer  $target  Target size
	 *
	 * @return  array  The new width and height
	 *
	 * @since   3.2
	 */
	public static function imageResize($width, $height, $target)
	{
		/*
		 * Takes the larger size of the width and height and applies the
		 * formula accordingly. This is so this script will work
		 * dynamically with any size image
		 */
		if ($width > $height)
		{
			$percentage = ($target / $width);
		}
		else
		{
			$percentage = ($target / $height);
		}

		// Gets the new value and applies the percentage, then rounds the value
		$width  = round($width * $percentage);
		$height = round($height * $percentage);

		return array($width, $height);
	}

	/**
	 * Counts the files and directories in a directory that are not php or html files.
	 *
	 * @param   string  $dir  Directory name
	 *
	 * @return  array  The number of media files and directories in the given directory
	 *
	 * @since   3.2
	 */
	public function countFiles($dir)
	{
		$total_file = 0;
		$total_dir  = 0;

		if (is_dir($dir))
		{
			$d = dir($dir);

			while (false !== ($entry = $d->read()))
			{
				if (substr($entry, 0, 1) != '.' && is_file($dir . DIRECTORY_SEPARATOR . $entry)
					&& strpos($entry, '.html') === false && strpos($entry, '.php') === false)
				{
					$total_file++;
				}

				if (substr($entry, 0, 1) != '.' && is_dir($dir . DIRECTORY_SEPARATOR . $entry))
				{
					$total_dir++;
				}
			}

			$d->close();
		}

		return array($total_file, $total_dir);
	}

	/**
	 * Small helper function that properly converts any
	 * configuration options to their byte representation.
	 *
	 * @param   string|integer  $val  The value to be converted to bytes.
	 *
	 * @return integer The calculated bytes value from the input.
	 *
	 * @since 3.3
	 */
	public function toBytes($val)
	{
		switch ($val[strlen($val) - 1])
		{
			case 'M':
			case 'm':
				return (int) $val * 1048576;
			case 'K':
			case 'k':
				return (int) $val * 1024;
			case 'G':
			case 'g':
				return (int) $val * 1073741824;
			default:
				return $val;
		}
	}
}
