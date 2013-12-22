<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
jimport( 'joomla.filesystem.folder' );

class com_phocagalleryInstallerScript
{
	function install($parent) {
		//echo '<p>' . JText::_('COM_PHOCAGALLLERY_INSTALL_TEXT') . '</p>';
		
		
		$folder[0][0]	=	'images' . DS . 'phocagallery' . DS ;
		$folder[0][1]	= 	JPATH_ROOT . DS .  $folder[0][0];
		$folder[1][0]	=	'images' . DS . 'phocagallery' . DS . 'avatars' . DS;
		$folder[1][1]	= 	JPATH_ROOT . DS .  $folder[1][0];
		
		$message = '';
		$error	 = array();
		foreach ($folder as $key => $value)
		{
			if (!JFolder::exists( $value[1]))
			{
				if (JFolder::create( $value[1], 0755 ))
				{
					
					$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
					JFile::write($value[1].DS."index.html", $data);
					$message .= '<div><b><span style="color:#009933">Folder</span> ' . $value[0] 
							   .' <span style="color:#009933">created!</span></b></div>';
					$error[] = 0;
				}	 
				else
				{
					$message .= '<div><b><span style="color:#CC0033">Folder</span> ' . $value[0]
							   .' <span style="color:#CC0033">creation failed!</span></b> Please create it manually.</div>';
					$error[] = 1;
				}
			}
			else//Folder exist
			{
				$message .= '<div><b><span style="color:#009933">Folder</span> ' . $value[0] 
							   .' <span style="color:#009933">exists!</span></b></div>';
				$error[] = 0;
			}
		}
	
		//$app		= JFactory::getApplication();
		//$app->redirect(JRoute::_('index.php?option=com_phocagallery'), $message);
		JFactory::getApplication()->enqueueMessage($message);
		$parent->getParent()->setRedirectURL('index.php?option=com_phocagallery');
	}
	function uninstall($parent) {
		//echo '<p>' . JText::_('COM_PHOCAGALLLERY_UNINSTALL_TEXT') . '</p>';
	}

	function update($parent) {
		//echo '<p>' . JText::sprintf('COM_PHOCAGALLERY_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
		
		$folder[0][0]	=	'images' . DS . 'phocagallery' . DS ;
		$folder[0][1]	= 	JPATH_ROOT . DS .  $folder[0][0];
		$folder[1][0]	=	'images' . DS . 'phocagallery' . DS . 'avatars' . DS;
		$folder[1][1]	= 	JPATH_ROOT . DS .  $folder[1][0];
		
		$message = '';
		$error	 = array();
		foreach ($folder as $key => $value)
		{
			if (!JFolder::exists( $value[1]))
			{
				if (JFolder::create( $value[1], 0755 ))
				{
					
					$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
					JFile::write($value[1].DS."index.html", $data);
					$message .= '<div><b><span style="color:#009933">Folder</span> ' . $value[0] 
							   .' <span style="color:#009933">created!</span></b></div>';
					$error[] = 0;
				}	 
				else
				{
					$message .= '<div><b><span style="color:#CC0033">Folder</span> ' . $value[0]
							   .' <span style="color:#CC0033">creation failed!</span></b> Please create it manually.</div>';
					$error[] = 1;
				}
			}
			else//Folder exist
			{
				$message .= '<div><b><span style="color:#009933">Folder</span> ' . $value[0] 
							   .' <span style="color:#009933">exists!</span></b></div>';
				$error[] = 0;
			}
		}
		
		
		$msg =  JText::_('COM_PHOCAGALLERY_UPDATE_TEXT');
		$msg .= ' (' . JText::_('COM_PHOCAGALLERY_VERSION'). ': ' . $parent->get('manifest')->version . ')';
		$msg .= '<br />'. $message;
		$app		= JFactory::getApplication();
		$app->enqueueMessage($msg);
		$app->redirect(JRoute::_('index.php?option=com_phocagallery'));
	}

	function preflight($type, $parent) {
		//echo '<p>' . JText::_('COM_PHOCAGALLERY_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}

	function postflight($type, $parent)  {
		//echo '<p>' . JText::_('COM_PHOCAGALLERY_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
	}
}