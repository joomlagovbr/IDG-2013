<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class PhocaGalleryCpControllerPhocaGalleryinstall extends PhocaGalleryCpController
{
	function __construct() {
		parent::__construct();
		$this->registerTask( 'install'  , 'install' );
		$this->registerTask( 'upgrade'  , 'upgrade' );		
	}

	function install() {		
		$db			= JFactory::getDBO();
		//$dbPref 	= $db->getPrefix();
		$msgSQL 	= $msgFile = $msgError = '';		
	
		// ------------------------------------------
		// PHOCAGALLERY
		// ------------------------------------------
		$query =' DROP TABLE IF EXISTS '.$db->quoteName('#__phocagallery').';';
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query =' CREATE TABLE '.$db->quoteName('#__phocagallery').'('."\n";
		$query.=' '.$db->quoteName('id').' int(11) unsigned NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('catid').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('sid').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('title').' varchar(250) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('alias').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('filename').' varchar(250) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('description').' text,'."\n";
		$query.=' '.$db->quoteName('date').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('hits').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('latitude').' varchar(20) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('longitude').' varchar(20) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('zoom').' int(3) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('geotitle').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('userid').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('videocode').' text,'."\n";
		$query.=' '.$db->quoteName('vmproductid').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('pcproductid').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('imgorigsize').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('approved').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('metakey').' text,'."\n";
		$query.=' '.$db->quoteName('metadesc').' text,'."\n";
		$query.=' '.$db->quoteName('metadata').' text,'."\n";
		$query.=' '.$db->quoteName('extlink1').' text,'."\n";
		$query.=' '.$db->quoteName('extlink2').' text,'."\n";
		$query.=' '.$db->quoteName('extid').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('exttype').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('extl').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('extm').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('exts').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('exto').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('extw').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('exth').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').'),'."\n";
		$query.=' KEY '.$db->quoteName('catid').' ('.$db->quoteName('catid').','.$db->quoteName('published').')'."\n";
		$query.=') DEFAULT CHARSET=utf8;'."\n";
		$query.=''."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// ------------------------------------------
		// PHOCAGALLERY CATEGORIES
		// ------------------------------------------
		$query=' DROP TABLE IF EXISTS '.$db->quoteName('#__phocagallery_categories').';'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query=' CREATE TABLE '.$db->quoteName('#__phocagallery_categories').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('parent_id').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('owner_id').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('title').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('name').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('alias').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('image').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('section').' varchar(50) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('image_position').' varchar(30) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('description').' text,'."\n";
		$query.=' '.$db->quoteName('date').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('approved').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('editor').' varchar(50) default NULL,'."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('access').' tinyint(3) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('count').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('hits').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('accessuserid').' text,'."\n";
		$query.=' '.$db->quoteName('uploaduserid').' text,'."\n";
		$query.=' '.$db->quoteName('deleteuserid').' text,'."\n";
		$query.=' '.$db->quoteName('userfolder').' text,'."\n";
		$query.=' '.$db->quoteName('latitude').' varchar(20) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('longitude').' varchar(20) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('zoom').' int(3) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('geotitle').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('extid').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('exta').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('extu').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('extauth').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('extfbuid').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('extfbcatid').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('metakey').' text,'."\n";
		$query.=' '.$db->quoteName('metadesc').' text,'."\n";
		$query.=' '.$db->quoteName('metadata').' text,'."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').'),'."\n";
		$query.=' KEY '.$db->quoteName('cat_idx').' ('.$db->quoteName('section').','.$db->quoteName('published').','.$db->quoteName('access').'),'."\n";
		$query.=' KEY '.$db->quoteName('idx_access').' ('.$db->quoteName('access').'),'."\n";
		$query.=' KEY '.$db->quoteName('idx_checkout').' ('.$db->quoteName('checked_out').')'."\n";
		$query.=') DEFAULT CHARSET=utf8;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// ------------------------------------------
		// PHOCAGALLERY VOTES
		// ------------------------------------------
		$query ='DROP TABLE IF EXISTS '.$db->quoteName('#__phocagallery_votes').';'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query =' CREATE TABLE '.$db->quoteName('#__phocagallery_votes').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('catid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('userid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('date').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('rating').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') DEFAULT CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// ------------------------------------------
		// PHOCAGALLERY COMMENTS
		// ------------------------------------------
		$query ='DROP TABLE IF EXISTS '.$db->quoteName('#__phocagallery_comments').';'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query =' CREATE TABLE '.$db->quoteName('#__phocagallery_comments').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('catid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('userid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('date').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('title').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('alias').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('comment').' text,'."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') DEFAULT CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// ------------------------------------------
		// PHOCAGALLERY VOTES STATISTICS
		// ------------------------------------------
		$query ='DROP TABLE IF EXISTS '.$db->quoteName('#__phocagallery_votes_statistics').';'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}

		$query =' CREATE TABLE '.$db->quoteName('#__phocagallery_votes_statistics').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('catid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('count').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('average').' float(8,6) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') DEFAULT CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// ------------------------------------------
		// PHOCAGALLERY USER CATEGORY
		// ------------------------------------------
		// Removed in 2.6.0
	/*	$query ='DROP TABLE IF EXISTS '.$db->quoteName('#__phocagallery_user_category').';'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
	
		$query =' CREATE TABLE '.$db->quoteName('#__phocagallery_user_category').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('catid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('userid').' int(11) NOT NULL default 0,'."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').'),'."\n";
		$query.=' KEY '.$db->quoteName('catid').' ('.$db->quoteName('catid').','.$db->quoteName('userid').')'."\n";
		$query.=') ENGINE=MyISAM CHARACTER SET '.$db->quoteName('utf8').';'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
	*/	
		// ------------------------------------------
		// PHOCAGALLERY IMAGE VOTES (2.5.0)
		// ------------------------------------------
		$query ='DROP TABLE IF EXISTS '.$db->quoteName('#__phocagallery_img_votes').';'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
	
		$query =' CREATE TABLE '.$db->quoteName('#__phocagallery_img_votes').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('imgid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('userid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('date').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('rating').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') DEFAULT CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// ------------------------------------------
		// PHOCAGALLERY IMAGE VOTES STATISTICS (2.5.0)
		// ------------------------------------------
		$query ='DROP TABLE IF EXISTS '.$db->quoteName('#__phocagallery_img_votes_statistics').';'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}

		$query =' CREATE TABLE '.$db->quoteName('#__phocagallery_img_votes_statistics').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('imgid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('count').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('average').' float(8,6) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') DEFAULT CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
	
	
		// ------------------------------------------
		// PHOCAGALLERY USER (2.6.0)
		// ------------------------------------------
		$query ='DROP TABLE IF EXISTS '.$db->quoteName('#__phocagallery_user').';'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
	
		$query =' CREATE TABLE '.$db->quoteName('#__phocagallery_user').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('userid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('avatar').' varchar(40) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('approved').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').'),'."\n";
		$query.=' KEY '.$db->quoteName('userid').' ('.$db->quoteName('userid').')'."\n";
		$query.=') DEFAULT CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		// ------------------------------------------
		// PHOCAGALLERY IMAGES COMMENTS 2.6.0
		// ------------------------------------------
		$query ='DROP TABLE IF EXISTS '.$db->quoteName('#__phocagallery_img_comments').';'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query =' CREATE TABLE '.$db->quoteName('#__phocagallery_img_comments').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('imgid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('userid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('date').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('title').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('alias').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('comment').' text,'."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') DEFAULT CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// ------------------------------------------
		// PHOCAGALLERY FB 3.0.0
		// ------------------------------------------
		$query ='DROP TABLE IF EXISTS '.$db->quoteName('#__phocagallery_fb_users').';'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query =' CREATE TABLE '.$db->quoteName('#__phocagallery_fb_users').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('appid').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('appsid').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('uid').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('name').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('link').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('secret').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('base_domain').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('expires').' varchar(100) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('session_key').' text,'."\n";
		$query.=' '.$db->quoteName('access_token').' text,'."\n";
		$query.=' '.$db->quoteName('sig').' text,'."\n";
		$query.=' '.$db->quoteName('fanpageid').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('comments').' text,'."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') DEFAULT CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// ------------------------------------------
		// PHOCAGALLERY TAGS (3.1.0)
		// ------------------------------------------
		
		$query =' DROP TABLE IF EXISTS '.$db->quoteName('#__phocagallery_tags').' ;';
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query ='CREATE TABLE '.$db->quoteName('#__phocagallery_tags').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('title').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('alias').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('link_cat').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('link_ext').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('description').' text,'."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') DEFAULT CHARSET=utf8;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		
		$query =' DROP TABLE IF EXISTS '.$db->quoteName('#__phocagallery_tags_ref').' ;';
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query ='CREATE TABLE '.$db->quoteName('#__phocagallery_tags_ref').' ('."\n";
		$query.=' '.$db->quoteName('id').' SERIAL,'."\n";
		$query.=' '.$db->quoteName('imgid').' int(11) NOT NULL default 0,'."\n";
		$query.=' '.$db->quoteName('tagid').' int(11) NOT NULL default 0,'."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').'),'."\n";
		$query.=' UNIQUE KEY '.$db->quoteName('i_imgid').' ('.$db->quoteName('imgid').','.$db->quoteName('tagid').')'."\n";
		$query.=') DEFAULT CHARSET=utf8;'."\n";
		
		$query =' DROP TABLE IF EXISTS '.$db->quoteName('#__phocagallery_styles').' ;';
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query ='CREATE TABLE '.$db->quoteName('#__phocagallery_styles').' ('."\n";
		$query.=' '.$db->quoteName('id').' int(11) NOT NULL auto_increment,'."\n";
		$query.=' '.$db->quoteName('title').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('alias').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('filename').' varchar(255) NOT NULL default \'\','."\n";
		$query.=' '.$db->quoteName('menulink').' text,'."\n";
		$query.=' '.$db->quoteName('type').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('published').' tinyint(1) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out').' int(11) unsigned NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('checked_out_time').' datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.=' '.$db->quoteName('ordering').' int(11) NOT NULL default \'0\','."\n";
		$query.=' '.$db->quoteName('params').' text,'."\n";
		$query.=' '.$db->quoteName('language').' char(7) NOT NULL default \'\','."\n";
		$query.=' PRIMARY KEY  ('.$db->quoteName('id').')'."\n";
		$query.=') DEFAULT CHARSET=utf8;'."\n";
		

		
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
	
		
		// Error
		if ($msgSQL !='') {
			$msgError .= '<br />' . $msgSQL;
		}
		/*if ($msgFile !='') {
			$msgError .= '<br />' . $msgFile;
		}*/
		
		// End Message
		if ($msgError !='') {
			$msg = JText::_( 'Phoca Gallery not successfully installed' ) . ': ' . $msgError;
		} else {
			$msg = JText::_( 'Phoca Gallery successfully installed' );
		}
		
		$link = 'index.php?option=com_phocagallery';
		$this->setRedirect($link, $msg);
	}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function upgrade() {
		
		$db			=JFactory::getDBO();
		//$dbPref 	= $db->getPrefix();
		$msgSQL 	= $msgFile = $msgError = '';
		
		
		
	
		
		
		

		// Error
		if ($msgSQL !='') {
			$msgError .= '<br />' . $msgSQL;
		}
		/*
		/*if ($msgFile !='') {
			$msgError .= '<br />' . $msgFile;
		}*/	
		// End Message
		if ($msgError !='') {
			$msg = JText::_( 'Phoca Gallery not successfully upgraded' ) . ': ' . $msgError;
		} else {
			$msg = JText::_( 'Phoca Gallery successfully upgraded' );
		}
		/*
		$linkUpgrade = '';
		foreach ($convertDataNeeded as $key => $value) {
			if ($value == 1) {
				$linkUpgrade .= '&'.$key.'=1';
			}
		}
		if ($linkUpgrade != '') {
			$link = 'index.php?option=com_phocagallery&view=phocagalleryupgrade'.$linkUpgrade;
		} else {
			$link = 'index.php?option=com_phocagallery';
		}*/
		$link = 'index.php?option=com_phocagallery';
		$this->setRedirect($link, $msg);
	}
	
	
	function AddColumnIfNotExists(&$errorMsg, $table, $column, $attributes = "INT( 11 ) NOT NULL default '0'", $after = '' ) {
		
		$app	= JFactory::getApplication();
		$db				=JFactory::getDBO();
		$columnExists 	= false;

		$query = 'SHOW COLUMNS FROM '.$table;
		$db->setQuery( $query );
		if (!$result = $db->query()){return false;}
		$columnData = $db->loadObjectList();
		
		foreach ($columnData as $valueColumn) {
			if ($valueColumn->Field == $column) {
				$columnExists = true;
				break;
			}
		}
		
		if (!$columnExists) {
			if ($after != '') {
				$query = 'ALTER TABLE '.$db->quoteName($table).' ADD '.$db->quoteName($column).' '.$attributes.' AFTER '.$db->quoteName($after).';';
			} else {
				$query = 'ALTER TABLE '.$db->quoteName($table).' ADD '.$db->quoteName($column).' '.$attributes.';';
			}
			$db->setQuery( $query );
			if (!$result = $db->query()){return false;}
			$errorMsg = 'notexistcreated';
		}
		
		return true;
	}
}
// utf-8 test: ä,ö,ü,ř,ž
?>