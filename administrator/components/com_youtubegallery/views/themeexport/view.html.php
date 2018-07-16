<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
jimport('joomla.filesystem.folder' );
jimport('joomla.filesystem.file' );
jimport('joomla.version');
$version = new JVersion();
$JoomlaVersionRelease=$version->RELEASE;



    
if($JoomlaVersionRelease>=3.0)
{
        //joomla 3.x
	class YoutubeGalleryViewThemeExport extends JViewLegacy
	{
        
		public function display($tpl = null) 
	        {
	                echo '<div style="width:100%;position:relative;min-height:200px;"><p style="color:grey;">';
	
			ygExportTheme::addToolBar();
			$theme_zip_file=ygExportTheme::d();
			$this->assignRef('theme_zip_file', $theme_zip_file);
			parent::display($tpl);
			echo '</div>';
		}
	}
}
else
{
	//for joomla 2.5
	class YoutubeGalleryViewThemeExport extends JView
	{
		public function display($tpl = null) 
	        {
	                echo '<div style="width:100%;position:relative;min-height:200px;"><p style="color:grey;">';
	
			ygExportTheme::addToolBar();
			$theme_zip_file=ygExportTheme::d();
			$this->assignRef('theme_zip_file', $theme_zip_file);
			parent::display($tpl);
			echo '</div>';
		}
	}
}

class ygExportTheme
{
	/**
         * display method of Youtube Gallery view
         * @return void
         */
        

        
        static public function d() 
        {
                
                $id=JFactory::getApplication()->input->getInt('themeid', 0);
                if($id==0)
                {
                        echo '<p>Theme not selected.</p>';
                        return;
                }       
               
                // get the Data
                require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
                $misc=new YouTubeGalleryMisc;
                if(!$misc->getThemeTableRow($id))
                        return  '<p>No video found</p>';
                        
		$themename=$misc->theme_row->themename;
	       
                // Prepare Folder
                $folder_base_name=ygExportTheme::cleanThemeName($themename);

                $folder=ygExportTheme::prepareFolder($folder_base_name);
                if($folder=='')
                        return '';
                echo 'Folder "tmp/youtubegallery/'.$folder.'" created.<br/>';
                
                
                //Copy Files
                if($misc->theme_row->mediafolder!='')
                        $files_to_archive=ygExportTheme::copyFiles('images'.DIRECTORY_SEPARATOR.str_replace('/',DIRECTORY_SEPARATOR,$misc->theme_row->mediafolder), 'tmp'.DIRECTORY_SEPARATOR.'youtubegallery'.DIRECTORY_SEPARATOR.$folder);
                        
                $path=JPATH_SITE.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'youtubegallery'.DIRECTORY_SEPARATOR;
                
                       
                        
                //Save About info
                if($misc->theme_row->themedescription!='')
                {
                        file_put_contents ($path.$folder.DIRECTORY_SEPARATOR.'about.txt',$misc->theme_row->themedescription);
                        echo 'File "about.txt" created.<br/>';
                }
                
                //Clean Theme Array
                unset($misc->theme_row->id);
                unset($misc->theme_row->themedescription);
                        
                //Save Theme
		$filename='theme.txt';
                $save_as=serialize($misc->theme_row);
		$save_as=str_replace('s:8:"readonly";s:1:"0";','s:8:"readonly";s:1:"1";',$save_as);
                file_put_contents ($path.$folder.DIRECTORY_SEPARATOR.$filename,$save_as);
                echo 'File "'.$filename.'" created.<br/>';
        
	
		//Save XML file
		$xmlfile=ygExportTheme::saveXMLFile($folder_base_name,$themename);
		$xmlfilename='YoutubeGalleryTheme_'.$folder_base_name.'.xml';
		file_put_contents ($path.$folder.DIRECTORY_SEPARATOR.$xmlfilename,$xmlfile);
                echo 'File "'.$xmlfilename.'" created.<br/>';
		
		//Save Script file
		$scriptfile=ygExportTheme::saveScriptFile($folder_base_name);
		file_put_contents ($path.$folder.DIRECTORY_SEPARATOR.'install.php',$scriptfile);
                echo 'File "install.php" created.<br/>';
	

                //create ZIP archive
                
                jimport( 'joomla.filesystem.archive' );
       
                $filesArray = array();
                $archivename = $path.$folder.'.zip';

                $files = JFolder::files($path.$folder, '.*', true, true);

                foreach($files as $file)
                {
                    $data = JFile::read($file);
                    $filesArray[] = array('name' => ygExportTheme::getFileNameOnly($file), 'data' => $data);
                }

                $zip = JArchive::getAdapter('zip');
                $zip->create($archivename, $filesArray);
        
                echo 'File "tmp/youtubegallery/'.$folder.'.zip" created.<br/>';

                JFolder::delete($path.$folder);
                echo 'Folder "tmp/youtubegallery/'.$folder.'" deleted.<br/>';

                echo '</p>';
                
                // Display the template
                
                $theme_zip_file=$folder.'.zip';

                return $theme_zip_file;
                
        }
        
	static protected function saveXMLFile($themeName,$themetitle)
	{
		$createdDate=date('F Y');
		$b=JURI::base();
		$user = JFactory::getUser();
		//$author=str_replace('http://','',$b);
		//$author=str_replace('https://','',$author);
		
		$result='<?xml version="1.0" encoding="utf-8"?>
<extension type="file" version="2.5.0" method="install">
    <name>YoutubeGalleryTheme_'.$themeName.'</name>
    <version>4.4.5</version>
    <creationDate>'.$createdDate.'</creationDate>
    <author>'.$user->name.'</author>
    <authorEmail>'.$user->email.'</authorEmail>
    <authorUrl>'.$b.'</authorUrl>
    <license>http://www.gnu.org/licenseses/gpl-2.0.html GNU/GPL</license>
    <description><![CDATA[

		      <h3>YoutubaGallery Theme - '.$themetitle.'</h3>
<div><a href="http://www.joomlaboat.com/youtube-gallery/youtube-gallery-themes" target="_blank">
More Themes</a></div>
			 <div><br/>If you use YoutubeGallery, please post a
			 review at the <a href="http://extensions.joomla.org/extensions/social-web/social-media/video-channels/13075" target="_blank">Joomla! Extensions Directory.</a> Thank you.<div>
			 <br/><br/>
]]></description>
	
	<installfile>install.php</installfile>
	<scriptfile>install.php</scriptfile>
	
	<files>
		<filename>'.$themeName.'.xml</filename>
		<filename>theme.txt</filename>
		<filename>install.php</filename>
</files>
</extension>
';
		return $result;
	}
	static protected function saveScriptFile($themeName)
	{
		$path=JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'themeexport'.DIRECTORY_SEPARATOR;
		$result=file_get_contents($path.'_installer.php');
		$result=str_replace('\'.$themeName.\'',$themeName,$result);
		
		return $result;
	}
	
	static protected function getFileNameOnly($filename)
        {
                $p=explode(DIRECTORY_SEPARATOR,$filename);
                return $p[count($p)-1];
        }
        
        static protected function cleanThemeName($originalName)
        {
                return trim(preg_replace("/[^_a-zA-Z0-9]/", "_", $originalName));
        }
        
        static protected function copyFiles($dirpath_from,$dirpath_to)
        {
           		//$siteURL		= JURI::base();
                $files_to_archive=array();
                
                $sys_path=JPATH_SITE.DIRECTORY_SEPARATOR.$dirpath_from;
                if(file_exists($sys_path)===false)
                {
                        echo '<p>Media Folder "'.$dirpath_from.' ('.$sys_path.')" not found.</p>';
                        return $files_to_archive;
                }
                
                if ($handle = opendir($sys_path)) {
                
				while (false !== ($file = readdir($handle))) {
    
                        if($file!='.' and $file!='..')
                        {
                                if(!is_dir($sys_path.DIRECTORY_SEPARATOR.$file))
                                {
                                        $destination_file=JPATH_SITE.DIRECTORY_SEPARATOR.$dirpath_to.DIRECTORY_SEPARATOR.$file;
                                        if(copy ($sys_path.DIRECTORY_SEPARATOR.$file, $destination_file)===false)
                                                echo '<span style="color:red;">file "'.$file.'" cannot be copied.</span><br/>';
                                        else
                                        {
                                                echo 'File "'.$file.'" copied.<br/>';
                                                $files_to_archive[]=$destination_file;
                                        }
                                }
                        }
    
                        
				}
                
			}
        }
        
        static protected function prepareFolder($folder_base_name)
        {
                $path=JPATH_SITE.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR;
                
                if(file_exists($path.'youtubegallery'))
                {
                        //JFolder::delete($path.'youtubegallery');
                }
                else
                {
                        echo 'Folder "tmp/youtubegallery" created.<br/>';
                        mkdir($path.'youtubegallery');
                }
                        
                        
                $path.='youtubegallery'.DIRECTORY_SEPARATOR;
                
                if(file_exists($path.$folder_base_name) or file_exists($path.$folder_base_name.'.zip'))
                {
                        $i=0;
                                do
                                {
                                        $i++;
                                        $folder=$folder_base_name.'_'.$i;
                                }while(file_exists($path.$folder) or file_exists($path.$folder.'.zip'));
                }
                else
                        $folder=$folder_base_name;
                
                if(mkdir($path.$folder)===false)
                {
                        echo '<p>Cannot create temporary folder in "tmp/"</p>';
                        return '';
                }
                
                return $folder;
        }
 
        /**
         * Setting the toolbar
         */
	
        static public function addToolBar() 
        {
                $jinput = JFactory::getApplication()->input;
$jinput->get->set('hidemainmenu',true);

                
                JToolBarHelper::title(JText::_('COM_YOUTUBEGALLERY_THEME_EXPORT'));
                JToolBarHelper::cancel('themeexport.cancel', 'JTOOLBAR_CLOSE');
        }
}

?>