<?php
/**
 * @package   	JCE
 * @copyright 	Copyright (c) 2009-2013 Ryan Demmer. All rights reserved.
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
abstract class WFPluginsHelper {
    
    /**
     * Get a list of installed JCE add-ons
     * @return array Associative array of add-ons arranged by name listing version, title and description and relative path
     */
    public static function getInstalledPlugins() {        
        $addons = array();
        
        jimport('joomla.filesystem.file');
        
        // path to editor
        $path = JPATH_SITE . '/components/com_jce/editor';
        
        // get all plugin folders
        $plugins    = JFolder::folders($path . '/tiny_mce/plugins', '.', false, true);
        // get all extensions
        $extensions = JFolder::files($path . '/extensions', '\.xml$', true, true);
        
        $language   = JFactory::getLanguage();
        $language->load('com_jce', JPATH_ADMINISTRATOR);

        foreach ($plugins as $plugin) {
            $name       = basename($plugin);
            $manifest   = $plugin . '/' . $name . '.xml';
            
            if (is_file($manifest)) {
                $xml = JFactory::getXML($manifest);
                
                // cannot load xml file
                if (!$xml) {
                    continue;
                }
                
                // not a valid plugin/extension
                if ($xml->getName() != 'install' && $xml->getName() != 'extension') {
                    continue;
                }
            
                if ((int) $xml->attributes()->core == 0) {
                    $language->load('com_jce_' . $name, JPATH_SITE);
                    
                    $addons[$name] = array(
                        'version'       => (string) $xml->version, 
                        'title'         => JText::_((string) $xml->name), 
                        'description'   => JText::_((string) $xml->description),
                        'path'          => 'components/com_jce/editor/tiny_mce/plugins/' . $name
                    );       
                }
            }
        }
        
        foreach ($extensions as $extension) {
            // extension name, eg: jcemediabox
            $name   = basename($extension, '.xml');
            // extension folder, eg: popups
            $folder = basename(dirname($extension)); 
            
            $xml = JFactory::getXML($extension);
            
            // cannot load xml file
            if (!$xml) {
                continue;
            }
            
            // not a valid plugin/extension
            if ($xml->getName() != 'install' && $xml->getName() != 'extension') {
                continue;
            }
            
            if ((int) $xml->attributes()->core == 0) {
                $language->load('com_jce_' . $folder . '_' . $name, JPATH_SITE);
                
                $addons[$folder . '_' . $name] = array(
                    'version'       => (string) $xml->version, 
                    'title'         => JText::_((string) $xml->name), 
                    'description'   => JText::_((string) $xml->description),
                    'path'          => 'components/com_jce/editor/extensions/' . $folder
                );
            }
        }

        return $addons;
    }
}
?>