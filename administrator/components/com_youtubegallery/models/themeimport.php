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
jimport('joomla.application.component.modellist');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder' );
jimport('joomla.filesystem.archive' );

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);

/**
 * YoutubeGallery - Theme Import Model
 */
class YoutubeGalleryModelThemeImport extends JModelList
{
		function upload_theme(&$msg)
        {
				$jinput=JFactory::getApplication()->input;
				$files = $input->files->get('themefile');
				print_r($files);
				die;
				//themefile
			 
				if(!isset($file['name']))
				{
						$msg='No file has bee uploaded.';
						return false; //wrong file format, expecting .zip
				}
			 
				$uploadedfile= basename( $file['name']);
				echo 'Uploaded file: "'.$uploadedfile.'"<br/>';
				
				
				$folder_name=$this->getFolderNameOnly($file['name']);
				if($folder_name=='')
				{
						$msg='Wrong file format, expecting ".zip"';
						return false; //wrong file format, expecting .zip
				}
				
				
				$this->prepareFolderYG();
				$path=JPATH_SITE.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'youtubegallery'.DS;
				
				if(file_exists($path.$uploadedfile))
				{
						echo 'Existing "'.$uploadedfile.'" file deleted.<br/>';
						unlink($path.$uploadedfile);
				}

				
				if(!move_uploaded_file($file['tmp_name'], $path.$uploadedfile))
				{
						$msg='Cannot Move File';
						
						return false;
				}
				
				echo 'File "'.$uploadedfile.'" moved form temporary location.<br/>';
				

			 
				$folder_name_created=$this->prepareFolder($folder_name,$path);
				echo 'Folder "tmp'.DIRECTORY_SEPARATOR.'youtubegallery'.DIRECTORY_SEPARATOR.$folder_name_created.'" created.<br/>';
				
				//echo '$folder_name='.$folder_name.'<br/>';
				
			 
				$zip =JArchive::getAdapter('zip');
				
				$zip->extract($path.$uploadedfile, $path.$folder_name_created);
				echo 'File "'.$uploadedfile.'" extracted.<br/>';
						
				unlink($path.$uploadedfile);
				echo 'File "'.$uploadedfile.'" deleted.<br/>';
				
				
				if(file_exists($path.$folder_name_created.DIRECTORY_SEPARATOR.'theme.txt'))
				{
						//Ok archive is fine, looks like it is really YG theme.
						$filedata=file_get_contents ($path.$folder_name_created.DIRECTORY_SEPARATOR.'theme.txt');
						if($filedata=='')
						{
								//Archive doesn't containe Gallery Data
								$msg='Gallery Data file is empty';
								
								JFolder::delete($path.'youtubegallery');
								return false;
						}
						
						$theme_row=unserialize($filedata);
						
						$theme_row->themedescription=file_get_contents ($path.$folder_name_created.DIRECTORY_SEPARATOR.'about.txt');
						
						
						
						echo 'Theme Data Found<br/>';
						
						if($theme_row->mediafolder!='')
						{
								//prepare media folder
								$theme_row->mediafolder=$this->prepareFolder($theme_row->mediafolder,JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DS);
								echo 'Media Folder "'.$theme_row->mediafolder.'" created.<br/>';
								
								//move files
								$this->moveFiles('tmp'.DIRECTORY_SEPARATOR.'youtubegallery'.DIRECTORY_SEPARATOR.$folder_name_created,'images'.DIRECTORY_SEPARATOR.$theme_row->mediafolder);
						}
				}
				else
				{
						$msg="Archive doesn't containe Gallery Data";
						return false;
				}
				
				
				JFolder::delete($path);
				
				//Add record to database
				$theme_row->themename=$this->getThemeName(str_replace('"','',$theme_row->themename));
				echo 'Theme Name: '.$theme_row->themename.'<br/>';
				
			 
				$this->saveTheme($theme_row);
				echo 'Theme Imported<br/>';
			
				return true;
		}
		
		function createTheme($themecode, &$msg)
		{
				
				
				$theme_row=unserialize($themecode);
				if($theme_row===false)
				{

						$msg='Theme Code is corrupted.';
						return false;
				}
				

				if($theme_row->themename=='')
				{
						
						$msg= 'Theme Code is incorrect.';
						return false;
				}
				
				//Add record to database
				$theme_row->themename=$this->getThemeName(str_replace('"','',$theme_row->themename));
				echo 'Theme Name: '.$theme_row->themename.'<br/>';
				
			 
				$this->saveTheme($theme_row);
				echo 'Theme Imported<br/>';
			
				return true;
		}
		
		
		function saveTheme(&$theme_row)
		{
				$fields=array();
				
				$fields[]='themename="'.$this->mysqlrealescapestring($theme_row->themename).'"';
				$fields[]='width="'.$this->mysqlrealescapestring($theme_row->width).'"';
				$fields[]='height="'.$this->mysqlrealescapestring($theme_row->height).'"';
				$fields[]='playvideo="'.$this->mysqlrealescapestring($theme_row->playvideo).'"';
				$fields[]='`repeat`="'.$this->mysqlrealescapestring($theme_row->repeat).'"';
				$fields[]='fullscreen="'.$this->mysqlrealescapestring($theme_row->fullscreen).'"';
				$fields[]='autoplay="'.$this->mysqlrealescapestring($theme_row->autoplay).'"';
				$fields[]='related="'.$this->mysqlrealescapestring($theme_row->related).'"';
				$fields[]='showinfo="'.$this->mysqlrealescapestring($theme_row->showinfo).'"';
				$fields[]='bgcolor="'.$this->mysqlrealescapestring($theme_row->bgcolor).'"';
				$fields[]='cols="'.$this->mysqlrealescapestring($theme_row->cols).'"';
				$fields[]='showtitle="'.$this->mysqlrealescapestring($theme_row->showtitle).'"';
				$fields[]='cssstyle="'.$this->mysqlrealescapestring($theme_row->cssstyle).'"';
				$fields[]='navbarstyle="'.$this->mysqlrealescapestring($theme_row->navbarstyle).'"';
				$fields[]='thumbnailstyle="'.$this->mysqlrealescapestring($theme_row->thumbnailstyle).'"';
				$fields[]='linestyle="'.$this->mysqlrealescapestring($theme_row->linestyle).'"';
				$fields[]='showlistname="'.$this->mysqlrealescapestring($theme_row->showlistname).'"';
				$fields[]='listnamestyle="'.$this->mysqlrealescapestring($theme_row->listnamestyle).'"';
				$fields[]='showactivevideotitle="'.$this->mysqlrealescapestring($theme_row->showactivevideotitle).'"';
				$fields[]='activevideotitlestyle="'.$this->mysqlrealescapestring($theme_row->activevideotitlestyle).'"';
				$fields[]='description="'.$this->mysqlrealescapestring($theme_row->description).'"';
				$fields[]='descr_position="'.$this->mysqlrealescapestring($theme_row->descr_position).'"';
				$fields[]='descr_style="'.$this->mysqlrealescapestring($theme_row->descr_style).'"';
				$fields[]='color1="'.$this->mysqlrealescapestring($theme_row->color1).'"';
				$fields[]='color2="'.$this->mysqlrealescapestring($theme_row->color2).'"';
				$fields[]='border="'.$this->mysqlrealescapestring($theme_row->border).'"';
				$fields[]='openinnewwindow="'.$this->mysqlrealescapestring($theme_row->openinnewwindow).'"';
				$fields[]='rel="'.$this->mysqlrealescapestring($theme_row->rel).'"';
				$fields[]='hrefaddon="'.$this->mysqlrealescapestring($theme_row->hrefaddon).'"';
				$fields[]='pagination="'.$this->mysqlrealescapestring($theme_row->pagination).'"';
				$fields[]='customlimit="'.$this->mysqlrealescapestring($theme_row->customlimit).'"';
				$fields[]='controls="'.$this->mysqlrealescapestring($theme_row->controls).'"';
				$fields[]='youtubeparams="'.$this->mysqlrealescapestring($theme_row->youtubeparams).'"';
				$fields[]='playertype="'.$this->mysqlrealescapestring($theme_row->playertype).'"';
				$fields[]='useglass="'.$this->mysqlrealescapestring($theme_row->useglass).'"';
				$fields[]='logocover="'.$this->mysqlrealescapestring($theme_row->logocover).'"';
				$fields[]='customlayout="'.$this->mysqlrealescapestring($theme_row->customlayout).'"';

				$fields[]='prepareheadtags="'.$this->mysqlrealescapestring($theme_row->prepareheadtags).'"';
				$fields[]='muteonplay="'.$this->mysqlrealescapestring($theme_row->muteonplay).'"';
				$fields[]='volume="'.$this->mysqlrealescapestring($theme_row->volume).'"';
				$fields[]='orderby="'.$this->mysqlrealescapestring($theme_row->orderby).'"';
				$fields[]='customnavlayout="'.$this->mysqlrealescapestring($theme_row->customnavlayout).'"';
				$fields[]='responsive="'.$this->mysqlrealescapestring($theme_row->responsive).'"';
				$fields[]='mediafolder="'.$this->mysqlrealescapestring($theme_row->mediafolder).'"';
				$fields[]='readonly="'.$this->mysqlrealescapestring($theme_row->readonly).'"';
				$fields[]='headscript="'.$this->mysqlrealescapestring($theme_row->headscript).'"';
				$fields[]='themedescription="'.$this->mysqlrealescapestring($theme_row->themedescription).'"';

				if(isset($theme_row->nocookie))
						$fields[]='nocookie="'.$this->mysqlrealescapestring($theme_row->nocookie).'"';

				if(isset($theme_row->changepagetitle))
						$fields[]='changepagetitle="'.$this->mysqlrealescapestring($theme_row->changepagetitle).'"';
				
				$query='INSERT #__youtubegallery_themes SET '.implode(', ',$fields); 

				$db = JFactory::getDBO();

				$db->setQuery($query);
				if (!$db->query())    die ( $db->stderr());
		}
		
		
		function mysqlrealescapestring($inp)
		{
		

		
		if(is_array($inp))
			return array_map(__METHOD__, $inp);

		if(!empty($inp) && is_string($inp)) {
		    return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
	    }

	    return $inp;


		}
	
		function getThemeName($themename)
		{
				//echo 'Get Theme Name<br/>';
				if(!$this->checkIfThemenameExist($themename))
						return $themename;
				
				$i=0;
				do
				{
					$i++;	
				}while($this->checkIfThemenameExist($themename.' ('.$i.')'));
				
				return $themename.' ('.$i.')';
		}
		
		function checkIfThemenameExist($themename)
		{
				
				$db = JFactory::getDBO();
				//echo 'Theme name "'.$themename.'" checking..<br/>';
		
				$query = 'SELECT id FROM #__youtubegallery_themes WHERE themename="'.$themename.'" LIMIT 1';
				$db->setQuery($query);
				if (!$db->query())    die ( $db->stderr());
	
				return $db->getNumRows()>0;
		
		}
		
        function moveFiles($dirpath_from,$dirpath_to)
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
    
                        if($file!='.' and $file!='..' and $file!='theme.txt' and $file!='about.txt')
                        {
                                if(!is_dir($sys_path.DIRECTORY_SEPARATOR.$file))
                                {
                                        $destination_file=JPATH_SITE.DIRECTORY_SEPARATOR.$dirpath_to.DIRECTORY_SEPARATOR.$file;
										
										if(file_exists($sys_path.DIRECTORY_SEPARATOR.$file)===false)
										{
												echo '<span style="color:red;">file "'.$file.'" ('.$sys_path.DIRECTORY_SEPARATOR.$file.') not found.</span><br/>';
										}
										else
										{
												if(!(file_exists($destination_file)===false))
														unlink($destination_file);
												
												if(rename($sys_path.DIRECTORY_SEPARATOR.$file,$destination_file)===false)
														echo '<span style="color:red;">file "'.$file.'" cannot be moved.</span><br/>';
												else
														echo 'File "'.$file.'" moved.<br/>';
										}
                                }
                        }
    
                        
				}
                
			}
        }
		
		function getFolderNameOnly($filename)
		{
				//echo 'File name: '.$filename.'<br/>';
				
				$p=explode('.',$filename);
				
				if(count($p)<2)
						return '';
				
				if(strtolower($p[1])!='zip')
						return '';
				
				return $p[0];
		}
	
		function prepareFolderYG()
		{
				$path=JPATH_SITE.DIRECTORY_SEPARATOR.'tmp'.DS;
                
				if(file_exists($path.'youtubegallery'))
				{
				        //JFolder::delete($path.'youtubegallery');
				}
				else
				{
				        echo 'Folder "tmp/youtubegallery" created.<br/>';
				        mkdir($path.'youtubegallery');
				}
		}
		
		function prepareFolder($folder_base_name, $path)
		{
				$this->prepareFolderYG();
				
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
}
