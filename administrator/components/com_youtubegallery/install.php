<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 3.5.9
 * @author DesignCompass corp< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
	
/**
 * Script file of Youtube Gallery component
 */
class com_YoutubeGalleryInstallerScript
{
        /**
         * method to install the component
         *
         * @return void
         */
        function install($parent) 
        {

            $manifest = $parent->get("manifest");
            $parent = $parent->getParent();
            $source = $parent->getPath("source");
             
            $installer = new JInstaller();
            
            // Install plugins
            foreach($manifest->plugins->plugin as $plugin) {
                $attributes = $plugin->attributes();
                $plg = $source.DS.$attributes['folder'].DS.$attributes['plugin'];
                $installer->install($plg);
            }
            
            // Install modules
            foreach($manifest->modules->module as $module) {
                $attributes = $module->attributes();
                $mod = $source.DS.$attributes['folder'].DS.$attributes['module'];
                $installer->install($mod);
            }
            
            $db = JFactory::getDbo();
            $tableExtensions = $this->safe_dbNameQuote("#__extensions");
            $columnElement   = $this->safe_dbNameQuote("element");
            $columnType      = $this->safe_dbNameQuote("type");
            $columnEnabled   = $this->safe_dbNameQuote("enabled");
            
            // Enable plugins
            $db->setQuery(
                'UPDATE 
                    '.$tableExtensions.'
                SET
                    '.$columnEnabled.'=1
                WHERE
                    '.$columnElement.'="youtubegallery"
                AND
                    '.$columnType.'="plugin"'
            );
            
            $db->query();
			
			//$this->addDefaultTheme();
            
			echo '<p>' . JText::_('COM_YOUTUBEGALLERY_INSTALL_TEXT') . '</p>';
			
        }
 
        /**
         * method to uninstall the component
         *
         * @return void
         */
		function safe_dbNameQuote($v)
		{
				$db = JFactory::getDbo();
				$v2 = $db->nameQuote($v);
				if($v2=='')
						return '`'.$v.'`';
				else
						return $v2;
		}
		
        function uninstall($parent) 
        {
                // $parent is the class calling this method
				$db = JFactory::getDbo();
				
				
		        $db->setQuery('SELECT extension_id FROM #__extensions WHERE type="plugin" AND element = "youtubegallery" AND folder = "content"  LIMIT 1');
		        $result = $db->loadResult();

		        if ($result)
				{
	                $installer = new JInstaller(); 
	                $installer->uninstall('plugin', $result);
			        
				}
            
	            // Uninstall module
		        
		        $db->setQuery('SELECT extension_id FROM #__extensions WHERE type="module" AND element = "mod_youtubegallery" LIMIT 1');
		        $result = $db->loadResult();
		        if ($result)
				{

	                $installer = new JInstaller(); 
	                $installer->uninstall('module', $result);
			        
				}

			

				
                echo '<p>' . JText::_('COM_YOUTUBEGALLERY_UNINSTALL_TEXT') . '</p>';
        }
 
        /**
         * method to update the component
         *
         * @return void
         */
        function update($parent) 
        {
                // $parent is the class calling this method
				
				$manifest = $parent->get("manifest");
				$parent = $parent->getParent();
				$source = $parent->getPath("source");
             
	            $installer = new JInstaller();
            
	            // Install plugins
	            foreach($manifest->plugins->plugin as $plugin) {
	                $attributes = $plugin->attributes();
	                $plg = $source . DS . $attributes['folder'].DS.$attributes['plugin'];
	                $installer->install($plg);
	            }
            
	            // Install modules
	            foreach($manifest->modules->module as $module) {
	                $attributes = $module->attributes();
	                $mod = $source . DS . $attributes['folder'].DS.$attributes['module'];
	                $installer->install($mod);
	            }
				
				//$this->addDefaultTheme();
				
                echo '<p>' . JText::_('COM_YOUTUBEGALLERY_UPDATE_TEXT') . '</p>';
        }
 
        /**
         * method to run before an install/update/uninstall method
         *
         * @return void
         */
        function preflight($type, $parent) 
        {
                // $parent is the class calling this method
                // $type is the type of change (install, update or discover_install)
                //echo '<p>' . JText::_('COM_YOUTUBEGALLERY_PREFLIGHT_' . $type . '_TEXT') . '</p>';
        }
 
        /**
         * method to run after an install/update/uninstall method
         *
         * @return void
         */
        function postflight($type, $parent) 
        {
                // $parent is the class calling this method
                // $type is the type of change (install, update or discover_install)
                //echo '<p>' . JText::_('COM_YOUTUBEGALLERY_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
        }
		
		
		function addDefaultTheme()
		{
				require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_youtubegallery'.DS.'models'.DS.'themeimport.php');
				
				$ygmti= new YoutubeGalleryModelThemeImport;
				if(!$ygmti->checkIfThemenameExist("Simple Grid - Default"))
				{
						$themecode='O:8:"stdClass":48:{s:9:"themename";s:21:"Simple Grid - Default";s:5:"width";s:3:"605";s:6:"height";s:3:"400";s:9:"playvideo";s:1:"1";s:6:"repeat";s:1:"0";s:10:"fullscreen";s:1:"1";s:8:"autoplay";s:1:"0";s:7:"related";s:1:"0";s:8:"showinfo";s:1:"1";s:7:"bgcolor";s:0:"";s:4:"cols";s:1:"4";s:9:"showtitle";s:1:"1";s:8:"cssstyle";s:0:"";s:11:"navbarstyle";s:0:"";s:14:"thumbnailstyle";s:33:"width:142px;margin:4px;padding:0;";s:9:"linestyle";s:57:"border-color:#E7E7E9;border-style:solid;border-width:1px;";s:12:"showlistname";s:1:"1";s:13:"listnamestyle";s:0:"";s:20:"showactivevideotitle";s:1:"1";s:21:"activevideotitlestyle";s:0:"";s:11:"description";s:1:"1";s:14:"descr_position";s:1:"0";s:11:"descr_style";s:0:"";s:6:"color1";s:6:"DA892E";s:6:"color2";s:6:"141414";s:6:"border";s:1:"1";s:15:"openinnewwindow";s:1:"4";s:3:"rel";s:0:"";s:9:"hrefaddon";s:0:"";s:10:"pagination";s:1:"2";s:11:"customlimit";s:1:"8";s:8:"controls";s:1:"1";s:13:"youtubeparams";s:0:"";s:10:"playertype";s:1:"1";s:8:"useglass";s:1:"0";s:9:"logocover";s:0:"";s:12:"customlayout";s:0:"";s:15:"prepareheadtags";s:1:"1";s:10:"muteonplay";s:1:"0";s:6:"volume";s:1:"5";s:7:"orderby";s:8:"ordering";s:15:"customnavlayout";s:297:"[a][image][/a]<br/>
<div style="width:142px;margin:2px;padding:0;">
<a href="[link]" class="">[title]</a><br/>

[if:duration]
<span style="color: grey;font-size:10px;">[duration] sec.</span>
[endif:duration]<br/>
<span style="color: grey;font-size:10px;">Views: [viewcount]</span>

</div>";s:10:"responsive";s:1:"1";s:11:"mediafolder";s:0:"";s:8:"readonly";s:1:"0";s:10:"headscript";s:0:"";s:8:"nocookie";s:1:"1";s:15:"changepagetitle";s:1:"1";}';

						$msg='';
						$ygmti->createTheme($themecode, $msg);
						if($msg!='')
								echo '<p>'.$msg.'</p>';

				}

		}
}
