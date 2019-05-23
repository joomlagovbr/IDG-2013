CREATE TABLE IF NOT EXISTS #__youtubegallery_settings (
  `option` varchar(50) NOT NULL,
  `value` varchar(1024) NOT NULL,

  PRIMARY KEY (`option`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS #__youtubegallery_videolists (
  id int(10) NOT NULL AUTO_INCREMENT,
  listname varchar(50) NOT NULL,
  videolist text,
  catid int(11) NOT NULL,
  updateperiod float NOT NULL default 7,
  lastplaylistupdate datetime NOT NULL,
  description text NOT NULL,
  author varchar(50) NOT NULL,
  watchusergroup smallint(6) NOT NULL,
  authorurl varchar(1024) NOT NULL,
  image varchar(1024) NOT NULL,
  note varchar(256) NOT NULL,

  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS #__youtubegallery_themes (
  id int(10) NOT NULL AUTO_INCREMENT,
  themename varchar(50) NOT NULL,
  showtitle tinyint(1) NOT NULL,
  playvideo tinyint(1) NOT NULL,
  `repeat` tinyint(1) NOT NULL,
  fullscreen tinyint(1) NOT NULL,
  autoplay tinyint(1) NOT NULL,
  related tinyint(1) NOT NULL,
  showinfo tinyint(1) NOT NULL,
  bgcolor varchar(20) NOT NULL,
  `cols` smallint(6) NOT NULL,
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
  `rel` varchar(255) NOT NULL,
  hrefaddon varchar(255) NOT NULL,
  pagination smallint(6) NOT NULL,
  customlimit smallint(6) NOT NULL,
  controls tinyint(1) NOT NULL default 1,
  youtubeparams varchar(450) NOT NULL,
  playertype smallint(6) NOT NULL,
  useglass tinyint(1) NOT NULL default 0,
  logocover varchar(255) NOT NULL,
  customlayout text NOT NULL,
  prepareheadtags smallint(6) NOT NULL default 0,
  muteonplay tinyint(1) NOT NULL default 0,
  volume smallint(6) NOT NULL default -1,
  orderby varchar(50) NOT NULL,
  customnavlayout text NOT NULL,
  responsive smallint(6) NOT NULL default 0,
  mediafolder varchar(255) NOT NULL,
  readonly tinyint(1) NOT NULL default 0,
  headscript text NOT NULL,
  themedescription text NOT NULL,
  nocookie tinyint(1) NOT NULL default 0,
  changepagetitle smallint(6) NOT NULL default 0,
  allowplaylist tinyint(1) NOT NULL default 0,

  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS #__youtubegallery_categories (
  id int(10) NOT NULL AUTO_INCREMENT,
  categoryname varchar(50) NOT NULL,
  description text NOT NULL,
  image varchar(255) NOT NULL,
  parentid int(11) NOT NULL,

  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS #__youtubegallery_videos (
  id int(10) NOT NULL AUTO_INCREMENT,
  listid int(11) NOT NULL,
  parentid int(11) NOT NULL,
  videosource varchar(30) NOT NULL,
  videoid varchar(128) NOT NULL,
  imageurl varchar(1024) NOT NULL,
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
  link text NOT NULL,
  ordering int(11) NOT NULL default 0,


  publisheddate datetime NOT NULL,
  duration int(11) NOT NULL default 0,
  rating_average float NOT NULL default 0,
  rating_max smallint(6) NOT NULL default 0,
  rating_min smallint(6) NOT NULL default 0,
  rating_numRaters int(11) NOT NULL default 0,
  statistics_favoriteCount int(11) NOT NULL default 0,
  statistics_viewCount int(11) NOT NULL default 0,
  keywords text NOT NULL,
  startsecond smallint(6) NOT NULL default 0,
  endsecond smallint(6) NOT NULL default 0,

  likes int(11) NOT NULL default 0,
  dislikes int(11) NOT NULL default 0,
  commentcount int(11) NOT NULL default 0,
  channel_username varchar(255) NOT NULL,
  channel_title varchar(255) NOT NULL,
  channel_subscribers int(11) NOT NULL default 0,
  channel_subscribed smallint(6) NOT NULL default 0,
  channel_location varchar(5) NOT NULL,
  channel_commentcount int(11) NOT NULL default 0,
  channel_viewcount int(11) NOT NULL default 0,
  channel_videocount int(11) NOT NULL default 0,
  channel_description text NOT NULL,
  channel_totaluploadviews int(11) NOT NULL default 0,

  alias varchar(255) NOT NULL,
  rawdata mediumtext NOT NULL,
  datalink varchar(1024) NOT NULL,

  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;