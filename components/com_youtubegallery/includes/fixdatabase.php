<?php
/**
 * YoutubeGallery for Joomla!
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
        
class YouTubeGalleryFixDB
{
    static public function FixDB()
    {
        YouTubeGalleryFixDB::update_to_306();
	YouTubeGalleryFixDB::update_to_313();
	YouTubeGalleryFixDB::update_to_315();
	YouTubeGalleryFixDB::update_to_318();
	YouTubeGalleryFixDB::update_to_324();
	YouTubeGalleryFixDB::update_to_327();
	YouTubeGalleryFixDB::update_to_337();
	YouTubeGalleryFixDB::update_to_339();
	YouTubeGalleryFixDB::update_to_348();
	YouTubeGalleryFixDB::update_to_357();
	YouTubeGalleryFixDB::update_to_358();
	YouTubeGalleryFixDB::update_to_360();
	YouTubeGalleryFixDB::update_to_361();
	YouTubeGalleryFixDB::update_to_362();
	YouTubeGalleryFixDB::update_to_370();
	YouTubeGalleryFixDB::update_to_387();
	YouTubeGalleryFixDB::update_to_390();
	YouTubeGalleryFixDB::update_to_399();
	YouTubeGalleryFixDB::update_to_429();
    }
	static public function update_to_429()
	{
		$query_array=array();
		$query_array[]='ALTER TABLE #__youtubegallery_settings CHANGE value value varchar(1024) NOT NULL;';
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);
	}
	
	static public function update_to_399()
	{
		$query_array=array();
		$query_array[]='ALTER TABLE #__youtubegallery_videolists ADD COLUMN authorurl varchar(1024) NOT NULL;';
		$query_array[]='ALTER TABLE #__youtubegallery_videolists ADD COLUMN image varchar(1024) NOT NULL;';
		$query_array[]='ALTER TABLE #__youtubegallery_videolists ADD COLUMN note varchar(256) NOT NULL;';
		
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);
	}
        
        static public function update_to_390()
	{
		$query_array=array();
		$query_array[]='ALTER TABLE #__youtubegallery_categories  ADD COLUMN description text NOT NULL;';
		$query_array[]='ALTER TABLE #__youtubegallery_categories ADD COLUMN image varchar(255) NOT NULL;';
		
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);
	}
	
	static public function update_to_387()
	{
		$query_array=array();
		$query_array[]='ALTER TABLE #__youtubegallery_videolists ADD COLUMN description text NOT NULL;';
		$query_array[]='ALTER TABLE #__youtubegallery_videolists ADD COLUMN author varchar(50) NOT NULL;';
		$query_array[]='ALTER TABLE #__youtubegallery_videolists ADD COLUMN watchusergroup smallint(6) NOT NULL;';
		
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);
	}

	static public function update_to_370()
	{
		$query_array=array();
		$query_array[]='ALTER TABLE #__youtubegallery_videos CHANGE videoid videoid varchar(128) NOT NULL;';
		
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);
	}
	
	static public function update_to_362()
	{
		$query_array=array();
		$query_array[]='ALTER TABLE #__youtubegallery_videos CHANGE rawdata rawdata MEDIUMTEXT NOT NULL;';
		
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);

	}
	
	static public function update_to_361()
	{
		$query_array=array();
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN datalink varchar(1024) NOT NULL';
		
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);

	}
	

	
	
	static public function update_to_360()
	{
		$query_array=array();
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN rawdata text NOT NULL;';
		
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);

	}
	
	
	static public function update_to_358()
	{
		$query_array=array();
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN alias varchar(255) NOT NULL;';
		
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);

	}
	
	
	static public function update_to_357()
	{
		$query_array=array();
		$query_array[]='ALTER TABLE #__youtubegallery_themes ADD COLUMN allowplaylist tinyint(1) NOT NULL default 0;';
		
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);

	}
	
	static public function update_to_348()
	{
		$query_array=array();
		
		$query_array[]='CREATE TABLE IF NOT EXISTS #__youtubegallery_settings (
  option varchar(50) NOT NULL,
  value varchar(255),

  PRIMARY KEY (option)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';
		
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);

	}
	
	
	static public function update_to_339()
	{
		$query_array=array();
		
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN channel_totaluploadviews int(11) NOT NULL default 0;';
		$query_array[]='ALTER TABLE #__youtubegallery_themes CHANGE prepareheadtags prepareheadtags smallint(6) NOT NULL default 0;';
		
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);

	}


	static public function update_to_337()
	{
		$query_array=array();
		
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN likes int(11) NOT NULL default 0;';
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN dislikesint(11) NOT NULL default 0;';
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN commentcountint(11) NOT NULL default 0;';
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN channel_username varchar(255) NOT NULL;';
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN channel_title varchar(255) NOT NULL;';
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN channel_subscribers int(11) NOT NULL default 0;';
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN channel_subscribed smallint(6) NOT NULL default 0;';
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN channel_location varchar(5) NOT NULL;';
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN channel_commentcount int(11) NOT NULL default 0;';
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN channel_viewcount int(11) NOT NULL default 0;';
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN channel_videocount int(11) NOT NULL default 0;';
		$query_array[]='ALTER TABLE #__youtubegallery_videos ADD COLUMN channel_description text NOT NULL;';
		$query_array[]='ALTER TABLE #__youtubegallery_videos CHANGE imageurl imageurl varchar(1024) NOT NULL;';
		
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);

	}

	static public function update_to_327()
	{
		$query_array=array();
		$query_array[]='ALTER TABLE #__youtubegallery_videos CHANGE link link TEXT NOT NULL;';
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);
	}
	

	static public function update_to_324()
	{
		$query_array=array();
		
		$query_array[]='ALTER TABLE #__youtubegallery_videos CHANGE statistics_favoriteCount statistics_favoriteCount INT( 11 ) NOT NULL DEFAULT "0";';
		$query_array[]='ALTER TABLE #__youtubegallery_videos CHANGE statistics_viewCount statistics_viewCount INT( 11 ) NOT NULL DEFAULT "0";';
		
		YouTubeGalleryFixDB::executeUpdateQueries($query_array);
	}

	static public function update_to_318()
	{
		$db	= JFactory::getDBO();
		YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_videos', 'endsecond', 'smallint(6)', 'NOT NULL default 0');
	}

	static public function update_to_315()
	{
		$db	= JFactory::getDBO();
		YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_videos', 'startsecond', 'smallint(6)', 'NOT NULL default 0');
	}

	static public function update_to_313()
	{
		$db	= JFactory::getDBO();
		YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_themes', 'changepagetitle', 'smallint(6)', 'NOT NULL default 0');
	}

	static public function update_to_306()
	{
		$db	= JFactory::getDBO();
		YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_themes', 'nocookie', 'tinyint(1)', 'NOT NULL default 0');
	}
	
	static public function update_to_300()
	{
		$db	= JFactory::getDBO();
		
		//
		//check if there are already tables created

			
	
	
		$query_array=array();
		//Create a Back Up
		$query_array[]='CREATE TABLE #__youtubegallery_backup LIKE #__youtubegallery;';
		$query_array[]='INSERT #__youtubegallery_backup SELECT * FROM #__youtubegallery;';
		$query_array[]='CREATE TABLE #__youtubegallery_videos_backup LIKE #__youtubegallery_videos;';
		$query_array[]='INSERT #__youtubegallery_videos_backup SELECT * FROM #__youtubegallery_videos;';
	
	
	
		//Create Video List Table
	
		$query_array[]='CREATE TABLE IF NOT EXISTS #__youtubegallery_videolists (
  id int(10) NOT NULL AUTO_INCREMENT,
  listname varchar(50) NOT NULL,
  videolist text,
  catid int(11) NOT NULL,
  updateperiod float NOT NULL default 7,
  lastplaylistupdate datetime NOT NULL,


  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

		//Copy Data
		$query_array[]='INSERT INTO #__youtubegallery_videolists	(
			id, listname, videolist, catid, updateperiod, lastplaylistupdate) SELECT
			id, galleryname, gallerylist, catid, updateperiod, lastplaylistupdate FROM #__youtubegallery;';
	
	
		//Alter Gallery Table
		$query_array[]='ALTER TABLE #__youtubegallery CHANGE galleryname themename varchar(50) NOT NULL;';
		$query_array[]='ALTER TABLE #__youtubegallery CHANGE showgalleryname showlistname tinyint(1) NOT NULL;';
		$query_array[]='ALTER TABLE #__youtubegallery CHANGE gallerynamestyle listnamestyle varchar(255) NOT NULL;';
		$query_array[]='ALTER TABLE #__youtubegallery CHANGE showactivevideotitle showactivevideotitle tinyint(1) NOT NULL;';
		$query_array[]='ALTER TABLE #__youtubegallery CHANGE border border smallint(6) NOT NULL;';	
		
		
		
		//Delete depricated columns
		$query_array[]='ALTER TABLE #__youtubegallery DROP COLUMN gallerylist';
		$query_array[]='ALTER TABLE #__youtubegallery DROP COLUMN catid';
		$query_array[]='ALTER TABLE #__youtubegallery DROP COLUMN updateperiod';
		$query_array[]='ALTER TABLE #__youtubegallery DROP COLUMN randomization';
		$query_array[]='ALTER TABLE #__youtubegallery DROP COLUMN lastplaylistupdate';
		$query_array[]='ALTER TABLE #__youtubegallery DROP COLUMN cache';
		$query_array[]='ALTER TABLE #__youtubegallery DROP COLUMN enablecache';
		
		YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'responsive', 'smallint(6)', 'default 0');
		YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'mediafolder', 'varchar(255)', 'NOT NULL');
		YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'readonly', 'tinyint(1)', 'NOT NULL default 0');
		YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'headscript', 'text', 'NOT NULL');
		YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'themedescription', 'text', 'NOT NULL');


		
		//Create Theme Table
		$query_array[]=YouTubeGalleryFixDB::getCreateThemesTableQuery();
		
		$query_array[]='ALTER TABLE #__youtubegallery_videos CHANGE galleryid listid int(11) NOT NULL;'; //
	
		$query_array[]='INSERT #__youtubegallery_themes SELECT * FROM #__youtubegallery;';


		//Drop Table	
		$query_array[]='DROP TABLE IF EXISTS #__youtubegallery;';
	
		
	
			$i=0;
			
			foreach($query_array as $query)
			{
				$i++;
				$db->setQuery( $query );
				if (!$db->query()){
					
					echo '<span style="color:black;font-weight:bold;">. </span>
<!--
'.$i.'
'.$query.'
'.$db->stderr().
'-->';
if($i==21)
{
	//do not drop yg table
	break;
}
				}
				else
					echo '<span style="color:green;font-weight:bold;">. </span>';
			}
			echo '<p>Backup created.</p>';
			echo '<p>Done.</p>';
		


	}
	
	static public function update_to_229()
	{
		$db	= JFactory::getDBO();
		//2. 2. 9
		YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_categories', 'parentid', 'int(11)', 'NOT NULL');
	}
	
	static public function update_to_227()
	{
		$db	= JFactory::getDBO();
		//2. 2. 7
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'orderby', ' varchar(50)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'customnavlayout', 'text', 'NOT NULL');
	
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_videos', 'publisheddate', 'datetime', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_videos', 'duration', 'int(11)', 'NOT NULL default 0');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_videos', 'rating_average', 'float', 'NOT NULL default 0');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_videos', 'rating_max', 'smallint(6)', 'NOT NULL default 0');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_videos', 'rating_min', 'smallint(6)', 'NOT NULL default 0');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_videos', 'rating_numRaters', 'int(11)', 'NOT NULL default 0');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_videos', 'statistics_favoriteCount', 'smallint(6)', 'NOT NULL default 0');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_videos', 'statistics_viewCount', 'smallint(6)', 'NOT NULL default 0');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_videos', 'keywords', 'text', 'NOT NULL');
	
            $db	= JFactory::getDBO();
	
            $query='ALTER TABLE #__youtubegallery CHANGE openinnewwindow openinnewwindow smallint(6) NOT NULL;';

            $db->setQuery( $query );
            if (!$db->query())
		echo '<p>'.$db->stderr().'</p>';
		
	$query='ALTER TABLE #__youtubegallery CHANGE updateperiod updateperiod float NOT NULL default 7;';

	$db->setQuery( $query );
	if (!$db->query())
		echo '<p>'.$db->stderr().'</p>';
	
	}
	
	static public function update_to_226()
	{
		$db	= JFactory::getDBO();
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'useglass', 'tinyint(1)', 'NOT NULL default 0');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'logocover', 'varchar(255)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'cache', 'text', 'NOT NULL');

                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'customlayout', 'text', 'NOT NULL');

                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'prepareheadtags', 'tinyint(1)', 'NOT NULL default 0');
	
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'updateperiod', 'float', 'NOT NULL default 7'); //updated to 
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'lastplaylistupdate', 'datetime', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'muteonplay', 'tinyint(1)', 'NOT NULL default 0');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'volume', 'smallint(6)', 'NOT NULL default -1');
    

	$db	= JFactory::getDBO();

	$query='
CREATE TABLE IF NOT EXISTS #__youtubegallery_videos (
  id int(10) NOT NULL AUTO_INCREMENT,
  galleryid int(11) NOT NULL,
  parentid int(11) NOT NULL,
  videosource varchar(30) NOT NULL,
  videoid varchar(30) NOT NULL,
  imageurl varchar(255) NOT NULL,
  title varchar(255) NOT NULL,
  description text NOT NULL,
  custom_imageurl varchar(255) NOT NULL,
  custom_title varchar(255) NOT NULL,
  custom_description text NOT NULL,
  specialparams varchar(255) NOT NULL,
  lastupdate datetime NOT NULL,
  allowupdates tinyint(1) NOT NULL default 0,
  status smallint(6) NOT NULL,
  isvideo tinyint(1) NOT NULL default 0,
  link varchar(255) NOT NULL,

  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

	';
				
	$db->setQuery( $query );
	if (!$db->query())
		echo '<p>'.$db->stderr().'</p>';
	

            YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery_videos', 'ordering', 'int(11)', 'NOT NULL default 0');
	}
	
	
	
	static public function update_to_230()
	{
		//2. 3. 0
		$db	= JFactory::getDBO();
		YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'responsive', 'smallint(6)', 'NOT NULL default 0');
	}
	
	static public function update_to_135()
	{
		$db	= JFactory::getDBO();
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'width', 'int(11)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'height', 'int(11)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'playvideo', 'tinyint(1)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'repeat', 'tinyint(1)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'fullscreen', 'tinyint(1)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'autoplay', 'tinyint(1)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'related', 'tinyint(1)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'showinfo', 'tinyint(1)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'bgcolor', 'varchar(20)', '');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'cols', 'smallint(6)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'showtitle', 'tinyint(1)', 'NOT NULL');
	
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'cssstyle', 'varchar(255)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'navbarstyle', 'varchar(255)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'thumbnailstyle', 'varchar(255)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'linestyle', 'varchar(255)', 'NOT NULL');

                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'showgalleryname', 'varchar(255)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'gallerynamestyle', 'varchar(255)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'showactivevideotitle', 'varchar(255)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'activevideotitlestyle', 'varchar(255)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'color1', 'varchar(20)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'color2', 'varchar(20)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'border', 'tinyint(1)', 'NOT NULL');
	
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'description', 'tinyint(1)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'descr_position', 'smallint(6)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'descr_style', 'varchar(255)', 'NOT NULL');

                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'openinnewwindow', 'smallint(6)', 'NOT NULL'); //updated to 
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'rel', 'varchar(255)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'hrefaddon', 'varchar(255)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'pagination', 'smallint(6)', 'NOT NULL');
	
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'customlimit', 'smallint(6)', 'NOT NULL');
	
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'catid', 'int(11)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'controls', 'tinyint(1)', 'NOT NULL default 1');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'youtubeparams', 'varchar(450)', 'NOT NULL');
                YouTubeGalleryFixDB::AddColumnIfNotExist($db->getPrefix().'youtubegallery', 'playertype', 'smallint(6)', 'NOT NULL');
	
	
	
	
	
	$query='
CREATE TABLE IF NOT EXISTS #__youtubegallery_categories (
  id int(10) NOT NULL AUTO_INCREMENT,
  categoryname varchar(50) NOT NULL,

  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;	
	';
				
	$db->setQuery( $query );
	if (!$db->query())
		echo '<p>'.$db->stderr().'</p>';
	
}
	
	
	
	
	
	
	

	static public function AddColumnIfNotExist($tablename, $columnname, $fieldtype, $options,$showerror=false)
	{
	    jimport('joomla.version');
	    $version = new JVersion();
	    $JoomlaVersionRelease=$version->RELEASE;
	    $query="ALTER TABLE ".$tablename." ADD COLUMN ".$columnname." ".$fieldtype." ".$options.";";
	    $q=array($query);
	    YouTubeGalleryFixDB::executeUpdateQueries($q);
	}
	
	static public function executeUpdateQueries($query_array)
	{
	    jimport('joomla.version');
	    $version = new JVersion();
	    $JoomlaVersionRelease=$version->RELEASE;
	    if($JoomlaVersionRelease<1.6)
		$db = JFactory::getDBO();
	    else
		$db = JFactory::getDbo();
		
		
	    $i=0;
			
	    foreach($query_array as $query)
	    {
		$ok=true;
		$i++;
		if($JoomlaVersionRelease<1.6)
		{
		    $db->setQuery( $query );
		    if (!$db->query())
			$ok=false;
			
		}
		else
		{
		    try
		    {
		        $db->transactionStart();
		        $db->setQuery($query);
		        $db->execute();
		        $db->transactionCommit();
		    }
		    catch (Exception $e)
		    {
			// catch any database errors.
			//$db->transactionRollback();
			//JErrorPage::render($e);
			$ok=false;
		    }
		}
		
		if($ok)
		    echo '<span style="color:green;font-weight:bold;">. </span>';
		else
		    echo '<span style="color:black;font-weight:bold;">. </span>';
	    }//for
	}
	
        
        
        static public function getCreateThemesTableQuery()
	{
		$query_array='CREATE TABLE IF NOT EXISTS #__youtubegallery_themes (
  id int(10) NOT NULL AUTO_INCREMENT,
  themename varchar(50) NOT NULL,
  showtitle tinyint(1) NOT NULL,
  playvideo tinyint(1) NOT NULL,
  repeat tinyint(1) NOT NULL,
  fullscreen tinyint(1) NOT NULL,
  autoplay tinyint(1) NOT NULL,
  related tinyint(1) NOT NULL,
  showinfo tinyint(1) NOT NULL,
  bgcolor varchar(20) NOT NULL,
  cols smallint(6) NOT NULL,
  width int(11) NOT NULL,
  height int(11) NOT NULL,
  cssstyle varchar(255) NOT NULL,
  navbarstyle varchar(255) NOT NULL,
  thumbnailstyle varchar(255) NOT NULL,
  linestyle varchar(255) NOT NULL,
  showlistname tinyint(1) NOT NULL,
  listnamestyle varchar(255) NOT NULL,
  showactivevideotitle tinyint(1) NOT NULL,
  activevideotitlestyle varchar(255) NOT NULL,
  color1 varchar(255) NOT NULL,
  color2 varchar(255) NOT NULL,
  border smallint(6) NOT NULL,
  description tinyint(1) NOT NULL,
  descr_position smallint(6) NOT NULL,
  descr_style varchar(255) NOT NULL,
  openinnewwindow smallint(6) NOT NULL,
  rel varchar(255) NOT NULL,
  hrefaddon varchar(255) NOT NULL,
  pagination smallint(6) NOT NULL,
  customlimit smallint(6) NOT NULL,
  controls tinyint(1) NOT NULL default 1,
  youtubeparams varchar(450) NOT NULL,
  playertype smallint(6) NOT NULL,
  useglass tinyint(1) NOT NULL default 0,
  logocover varchar(255) NOT NULL,
  customlayout text NOT NULL,
  prepareheadtags tinyint(1) NOT NULL default 0,
  muteonplay tinyint(1) NOT NULL default 0,
  volume smallint(6) NOT NULL default -1,
  orderby varchar(50) NOT NULL,
  customnavlayout text NOT NULL,
  responsive smallint(6) NOT NULL default 0,
  mediafolder varchar(255) NOT NULL,
  readonly tinyint(1) NOT NULL default 0,
  headscript text NOT NULL,
  themedescription text NOT NULL,

  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';
		return $query_array;
	}
	

}


?>
