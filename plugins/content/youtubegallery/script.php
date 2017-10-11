<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
  
        
class PlgContentYoutubeGalleryInstallerScript
{
    function preflight($route, $adapter)
    {
        if (!(JFile::delete($adapter->get('parent')->getPath('source').'/youtubegallery.xml')))
        {
                // do nothing
        }
    }
     
    function install($adapter) {}
 
    function update($adapter) {}
 
    function uninstall($adapter) {}
 
    function postflight($route, $adapter)
    {
        
        if (stripos($route, 'install') !== false || stripos($route, 'update') !== false)
        {
            return $this->fixManifest($adapter);
        }
    }
     
    private function fixManifest($adapter)
    {
        $filedelete=array();
        
        $filesource = $adapter->get('parent')->getPath('source').'/youtubegallery_253x.xml';
        $filedest = $adapter->get('parent')->getPath('extension_root').'/youtubegallery.xml';
        $filedelete[] = $adapter->get('parent')->getPath('extension_root').'/youtubegallery_253x.xml';
        

        
        if (!(JFile::copy($filesource, $filedest)))
        {
            JLog::add(JText::sprintf('JLIB_INSTALLER_ERROR_FAIL_COPY_FILE', $filesource, $filedest), JLog::WARNING, 'jerror');
             
            if (class_exists('JError'))
            {
                JError::raiseWarning(1, 'JInstaller::install: '.JText::sprintf('Failed to copy file to', $filesource, $filedest));
            }
            else
            {
                throw new Exception('JInstaller::install: '.JText::sprintf('Failed to copy file to', $filesource, $filedest));
            }
            return false;
        }
        
        foreach($filedelete as $f)
        {
            if (!(JFile::delete($f)))
            {
                // do nothing
            }
        }
        
        return true;
    }
    
    
}

?>