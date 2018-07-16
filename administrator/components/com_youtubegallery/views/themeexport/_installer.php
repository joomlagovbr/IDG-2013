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


class YoutubeGalleryTheme_'.$themeName.'InstallerScript
{
	function preflight($route, $adapter)
	{
		YoutubeGalleryTheme_'.$themeName.'InstallerScript::deleteIfExists();
	}
	
	function deleteIfExists()
	{
		//delete extension - type="file" AND 
		$db = JFactory::getDbo();
		$db->setQuery('DELETE FROM #__extensions WHERE name = "YoutubeGalleryTheme_'.$themeName.'" ');
		if (!$db->query())    die( $db->stderr());      
	}
    
        function install($parent) 
        {
		$theme_name=''.$themeName.'';
		$manifest = $parent->get("manifest");
		$parent = $parent->getParent();
		$source = $parent->getPath("source");
		$installer = new JInstaller();
		YoutubeGalleryTheme_'.$themeName.'InstallerScript::deleteIfExists();
        	
	    	if(file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'themeimport.php'))
		{
		    $this->addTheme($source);
		}
		else
		{
		    echo '<h1 style="text-align:center;">Youtube Gallery not found. Please install it first.<br/>
		    <a href="http://www.joomlaboat.com/youtube-gallery">Youtube Gallery Home Page.</a>
		    </h1><br/><br/>';
		    return false;
		}
		
	}	
 
    function update($parent) 
    {
		$this->install($parent);
    }

	
	
	function uninstall($parent) 
    {
        
	}
	
	function addTheme($path)//$themecode
	{

	    if(file_exists($path.DIRECTORY_SEPARATOR.'theme.txt'))
	    {
		require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'themeimport.php');
		$ygmti= new YoutubeGalleryModelThemeImport;
		
		//Ok archive is fine, looks like it is really YG theme.
		$filedata=file_get_contents ($path.DIRECTORY_SEPARATOR.'theme.txt');
		if($filedata=='')
		{
		    //Archive doesn't containe Theme Data
		    $msg='Theme Data file is empty';
		    echo '<h1>'.$msg.'</h1>';
		    return;
		}
		
		$theme_row=unserialize($filedata);
		
		if(file_exists($path.DIRECTORY_SEPARATOR.'about.txt'))
		    $theme_row->themedescription=file_get_contents ($path.DIRECTORY_SEPARATOR.'about.txt');
		else						
		    $theme_row->themedescription="";


		if($theme_row->mediafolder!='')
		{
		    //prepare media folder
		    $theme_row->mediafolder=$ygmti->prepareFolder($theme_row->mediafolder,JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR);
		    echo '<p>Media Folder "'.$theme_row->mediafolder.'" created.</p>';
		    //move files
		    $ygmti->moveFiles($path,JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$theme_row->mediafolder);
		}
		
		//Add record to database
		$theme_row->themename=$ygmti->getThemeName(str_replace('"','',$theme_row->themename));
		echo '<p>New Theme Name: '.$theme_row->themename.'</p>';

		$ygmti->saveTheme($theme_row);
		echo '<p>Theme Imported</p>';
		
	    }
	    else
	    {
		echo '<h1>File "theme.txt" not found.</h1>';
		$msg='Archive doesn\'t contain Gallery Data';
		return false;
	    }

	}


}


?>