--
-- IMPORTANT - THIS FILE MUST BE SAVED WITH UTF-8 ENCODING ONLY. BEWARE IF EDITING!
--

TRUNCATE TABLE "#__assets";
TRUNCATE TABLE "#__categories";
TRUNCATE TABLE "#__menu";
TRUNCATE TABLE "#__menu_types";
TRUNCATE TABLE "#__modules";
TRUNCATE TABLE "#__modules_menu";
TRUNCATE TABLE "#__template_styles";

--
-- Dumping data for table `#__assets`
--

SET IDENTITY_INSERT "#__assets" ON;

INSERT INTO "#__assets" ("id", "parent_id", "lft", "rgt", "level", "name", "title", "rules") VALUES
(1, 0, 0, 91, 0, 'root.1', 'Root Asset', '{"core.login.site":{"6":1,"2":1},"core.login.admin":{"6":1},"core.login.offline":{"6":1},"core.admin":{"8":1},"core.manage":{"7":1},"core.create":{"6":1,"3":1},"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}'),
(2, 1, 1, 2, 1, 'com_admin', 'com_admin', '{}'),
(3, 1, 3, 6, 1, 'com_banners', 'com_banners', '{"core.admin":{"7":1},"core.manage":{"6":1}}'),
(4, 1, 7, 8, 1, 'com_cache', 'com_cache', '{"core.admin":{"7":1},"core.manage":{"7":1}}'),
(5, 1, 9, 10, 1, 'com_checkin', 'com_checkin', '{"core.admin":{"7":1},"core.manage":{"7":1}}'),
(6, 1, 11, 12, 1, 'com_config', 'com_config', '{}'),
(7, 1, 13, 16, 1, 'com_contact', 'com_contact', '{"core.admin":{"7":1},"core.manage":{"6":1}}'),
(8, 1, 17, 28, 1, 'com_content', 'com_content', '{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":{"3":1},"core.edit":{"4":1},"core.edit.state":{"5":1}}'),
(9, 1, 29, 30, 1, 'com_cpanel', 'com_cpanel', '{}'),
(10, 1, 31, 32, 1, 'com_installer', 'com_installer', '{"core.manage":{"7":0},"core.delete":{"7":0},"core.edit.state":{"7":0}}'),
(11, 1, 33, 34, 1, 'com_languages', 'com_languages', '{"core.admin":{"7":1}}'),
(12, 1, 35, 36, 1, 'com_login', 'com_login', '{}'),
(13, 1, 37, 38, 1, 'com_mailto', 'com_mailto', '{}'),
(14, 1, 39, 40, 1, 'com_massmail', 'com_massmail', '{}'),
(15, 1, 41, 42, 1, 'com_media', 'com_media', '{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":{"3":1},"core.delete":{"5":1}}'),
(16, 1, 43, 44, 1, 'com_menus', 'com_menus', '{"core.admin":{"7":1}}'),
(17, 1, 45, 46, 1, 'com_messages', 'com_messages', '{"core.admin":{"7":1},"core.manage":{"7":1}}'),
(18, 1, 47, 60, 1, 'com_modules', 'com_modules', '{"core.admin":{"7":1}}'),
(19, 1, 61, 64, 1, 'com_newsfeeds', 'com_newsfeeds', '{"core.admin":{"7":1},"core.manage":{"6":1}}'),
(20, 1, 65, 66, 1, 'com_plugins', 'com_plugins', '{"core.admin":{"7":1}}'),
(21, 1, 67, 68, 1, 'com_redirect', 'com_redirect', '{"core.admin":{"7":1}}'),
(22, 1, 69, 70, 1, 'com_search', 'com_search', '{"core.admin":{"7":1},"core.manage":{"6":1}}'),
(23, 1, 71, 72, 1, 'com_templates', 'com_templates', '{"core.admin":{"7":1}}'),
(24, 1, 73, 76, 1, 'com_users', 'com_users', '{"core.admin":{"7":1},"core.edit.own":{"6":1}}'),
(26, 1, 77, 78, 1, 'com_wrapper', 'com_wrapper', '{}'),
(27, 8, 18, 23, 2, 'com_content.category.2', 'Uncategorised', '{}'),
(28, 3, 4, 5, 2, 'com_banners.category.3', 'Uncategorised', '{}'),
(29, 7, 14, 15, 2, 'com_contact.category.4', 'Uncategorised', '{}'),
(30, 19, 62, 63, 2, 'com_newsfeeds.category.5', 'Uncategorised', '{}'),
(32, 24, 74, 75, 2, 'com_users.category.7', 'Uncategorised', '{}'),
(33, 1, 79, 80, 1, 'com_finder', 'com_finder', '{"core.admin":{"7":1},"core.manage":{"6":1}}'),
(35, 27, 19, 20, 3, 'com_content.article.2', 'About Us', '{}'),
(36, 8, 24, 27, 2, 'com_content.category.8', 'News', '{}'),
(37, 36, 25, 26, 3, 'com_content.article.3', 'Article 1 Title', '{}'),
(40, 27, 21, 22, 3, 'com_content.article.6', 'Creating Your Site', '{}'),
(41, 1, 81, 82, 1, 'com_joomlaupdate', 'com_joomlaupdate', '{}'),
(42, 1, 83, 84, 1, 'com_tags', 'com_tags', '{"core.admin":{"8":1},"core.manage":{"7":1},"core.create":{"6":1,"3":1},"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}'),
(43, 1, 85, 86, 1, 'com_contenthistory', 'com_contenthistory', '{}'),
(44, 1, 87, 88, 1, 'com_ajax', 'com_ajax', '{}'),
(47, 1, 89, 90, 1, 'com_postinstall', 'com_postinstall', '{}'),
(48, 18, 48, 49, 2, 'com_modules.module.10', 'Logged-in Users', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}'),
(49, 18, 50, 51, 2, 'com_modules.module.3', 'Popular Articles', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}'),
(50, 18, 52, 53, 2, 'com_modules.module.4', 'Recently Added Articles', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}'),
(51, 18, 54, 55, 2, 'com_modules.module.89', 'Site Information', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}'),
(52, 18, 56, 57, 2, 'com_modules.module.88', 'Image', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}'),
(53, 18, 58, 59, 2, 'com_modules.module.90', 'Release News', '{"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}');

SET IDENTITY_INSERT "#__assets" OFF;

--
-- Dumping data for table `#__categories`
--

SET IDENTITY_INSERT "#__categories" ON;

INSERT INTO "#__categories" ("id", "asset_id", "parent_id", "lft", "rgt", "level", "path", "extension", "title", "alias", "note", "description", "published", "checked_out", "checked_out_time", "access", "params", "metadesc", "metakey", "metadata", "created_user_id", "created_time", "modified_user_id", "modified_time", "hits", "language", "version") VALUES
(1, 0, 0, 0, 13, 0, '', 'system', 'ROOT', 'root', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{}', '', '', '', 42, '2011-01-01 00:00:01', 0, '1900-01-01 00:00:00', 0, '*', 1),
(2, 27, 1, 1, 2, 1, 'uncategorised', 'com_content', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2011-01-01 00:00:01', 0, '1900-01-01 00:00:00', 0, '*', 1),
(3, 28, 1, 3, 4, 1, 'uncategorised', 'com_banners', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":"","foobar":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2011-01-01 00:00:01', 0, '1900-01-01 00:00:00', 0, '*', 1),
(4, 29, 1, 5, 6, 1, 'uncategorised', 'com_contact', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2011-01-01 00:00:01', 0, '1900-01-01 00:00:00', 0, '*', 1),
(5, 30, 1, 7, 8, 1, 'uncategorised', 'com_newsfeeds', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2011-01-01 00:00:01', 0, '1900-01-01 00:00:00', 0, '*', 1),
(7, 32, 1, 9, 10, 1, 'uncategorised', 'com_users', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2011-01-01 00:00:01', 0, '1900-01-01 00:00:00', 0, '*', 1),
(8, 36, 1, 11, 12, 1, 'news', 'com_content', 'News', 'news', '', '<p>This is the latest news from us.</p><p>You can edit this description in the Content Category Manager.</p><p>This will show the most recent article. You can easily change it to show more if you wish.</p>', 1, 0, '1900-01-01 00:00:00', 1, '{"category_layout":"","image":""}', '', '', '{"author":"","robots":""}', 42, '2011-01-01 00:00:01', 0, '1900-01-01 00:00:00', 0, '*', 1);

SET IDENTITY_INSERT "#__categories" OFF;

--
-- Dumping data for table `#__contact_details`
--

SET IDENTITY_INSERT "#__contact_details" ON;

INSERT INTO "#__contact_details" ("id", "name", "alias", "con_position", "address", "suburb", "state", "country", "postcode", "telephone", "fax", "misc", "image", "email_to", "default_con", "published", "checked_out", "checked_out_time", "ordering", "params", "user_id", "catid", "access", "mobile", "webpage", "sortname1", "sortname2", "sortname3", "language", "created", "created_by", "created_by_alias", "modified", "modified_by", "metakey", "metadesc", "metadata", "featured", "xreference", "publish_up", "publish_down", "version", "hits") VALUES
(1, 'Your Name', 'your-name', '', 'This is a contact form which you can edit in the contact manager. Put your address or other information here. This can be a good place to put things like business hours too. Don''t forget to put a real email address. You also may want to enable Captcha in the global configuration to prevent spam submissions of contact forms. ', '', '', '', '', '', '', '', '', 'email@example.com', 0, 1, 0, '1900-01-01 00:00:00', 1, '{"show_contact_category":"","show_contact_list":"","presentation_style":"","show_name":"","show_position":"","show_email":"","show_street_address":"","show_suburb":"","show_state":"","show_postcode":"","show_country":"","show_telephone":"","show_mobile":"","show_fax":"","show_webpage":"","show_misc":"","show_image":"","allow_vcard":"","show_articles":"","show_profile":"","show_links":"","linka_name":"","linka":"","linkb_name":"","linkb":"","linkc_name":"","linkc":"","linkd_name":"","linkd":"","linke_name":"","linke":"","contact_layout":"","show_email_form":"","show_email_copy":"","banned_email":"","banned_subject":"","banned_text":"","validate_session":"","custom_reply":"","redirect":""}', 0, 4, 1, '', '', '', '', '', '*', '2011-01-01 00:00:01', 42, 'Joomla', '1900-01-01 00:00:00', 0, '', '', '{"robots":"","rights":""}', 0, '', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 0);

SET IDENTITY_INSERT "#__contact_details" OFF;

--
-- Dumping data for table `#__content`
--

SET IDENTITY_INSERT "#__content" ON;

INSERT INTO "#__content" ("id", "asset_id", "title", "alias", "introtext", "fulltext", "state", "catid", "created", "created_by", "created_by_alias", "modified", "modified_by", "checked_out", "checked_out_time", "publish_up", "publish_down", "images", "urls", "attribs", "version", "ordering", "metakey", "metadesc", "access", "hits", "metadata", "featured", "language", "xreference") VALUES
(2, 35, 'About Us', 'about-us', '<p>Put more information on this page.</p>', '', 1, 2, '2011-01-01 00:00:01', 316, 'Joomla', '1900-01-01 00:00:00', 0, 0, '1900-01-01 00:00:00', '2012-01-04 03:06:07', '1900-01-01 00:00:00', '{"image_intro":"","float_intro":"","image_intro_alt":"","image_intro_caption":"","image_fulltext":"","float_fulltext":"","image_fulltext_alt":"","image_fulltext_caption":""}', '{"urla":"","urlatext":"","targeta":"","urlb":"","urlbtext":"","targetb":"","urlc":"","urlctext":"","targetc":""}', '{"show_title":"","link_titles":"","show_intro":"","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","link_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_vote":"","show_hits":"","show_noauth":"","alternative_readmore":"","article_layout":"","show_publishing_options":"","show_article_options":"","show_urls_images_backend":"","show_urls_images_frontend":""}', 1, 1, '', '', 1, 7, '{"robots":"","author":"","rights":"","xreference":""}', 0, '*', ''),
(3, 37, 'Article 1 Title', 'article-1-title', '<p>Here is a news article.</p>', '', 1, 8, '2011-01-01 00:00:01', 316, 'Joomla', '1900-01-01 00:00:00', 0, 0, '1900-01-01 00:00:00', '2012-01-04 03:10:06', '1900-01-01 00:00:00', '{"image_intro":"","float_intro":"","image_intro_alt":"","image_intro_caption":"","image_fulltext":"","float_fulltext":"","image_fulltext_alt":"","image_fulltext_caption":""}', '{"urla":"","urlatext":"","targeta":"","urlb":"","urlbtext":"","targetb":"","urlc":"","urlctext":"","targetc":""}', '{"show_title":"","link_titles":"","show_intro":"","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","link_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_vote":"","show_hits":"","show_noauth":"","alternative_readmore":"","article_layout":"","show_publishing_options":"","show_article_options":"","show_urls_images_backend":"","show_urls_images_frontend":""}', 1, 0, '', '', 1, 0, '{"robots":"","author":"","rights":"","xreference":""}', 0, '*', ''),
(6, 40, 'Creating Your Site', 'creating-your-site', '<p>Joomla! is all about allowing you to create a site that matches your vision. The possibilities are limitless; this sample site will get you started.</p><p>There are a few things you should know to get you started.</p><p>Every Joomla! website has two parts: the Site (which is what your site visitors see) and the Administrator (which is where you will do a lot of the site management). You need to log in to the Administrator separately with the same username and password. There is a link to the administrator on the top menu that you will see when you log in.</p><p>You can edit articles in the Site by clicking on the edit icon. You can create a new article by clicking on the Create Article link in the top menu.</p><p>To do basic changes to the appearance your site click Home, Site Settings and Home, Template Settings.</p><p>To do more advanced things, like edit the contact form, manage users, or install a new template or extension, login to the Administrator.</p><p>Some quick tips for working in the Administrator</p><ul><li>To change the image on all the pages: Go to the Module Manager and click on Image Module.</li><li>To edit the Side Module: Go to Extensions, Module Manager and click on Side Module.</li><li>To edit the Contact Form: Go to Components, Contacts. Click on Your Name.</li></ul><p>Once you have your basic site you may want to install your own template (that controls the overall design of your site) and then, perhaps additional extensions.</p><p>There is a lot of help available for Joomla!. You can visit the <a href="https://forum.joomla.org/">Joomla! forums</a> and the<a href="https://docs.joomla.org/" target="_blank"> Joomla! documentation site</a> to get started.</p>', '', 1, 2, '2011-01-01 00:00:01', 237, 'Joomla', '2013-10-29 12:46:03', 237, 0, '1900-01-01 00:00:00', '2012-01-04 04:27:11', '1900-01-01 00:00:00', '{"image_intro":"","float_intro":"","image_intro_alt":"","image_intro_caption":"","image_fulltext":"","float_fulltext":"","image_fulltext_alt":"","image_fulltext_caption":""}', '{"urla":null,"urlatext":"","targeta":"","urlb":null,"urlbtext":"","targetb":"","urlc":null,"urlctext":"","targetc":""}', '{"show_title":"","link_titles":"","show_intro":"","info_block_position":"0","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","link_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_vote":"","show_hits":"","show_noauth":"","urls_position":"","alternative_readmore":"","article_layout":"","show_publishing_options":"","show_article_options":"","show_urls_images_backend":"","show_urls_images_frontend":""}', 8, 0, '', '', 1, 161, '{"robots":"","author":"","rights":"","xreference":""}', 0, '*', '');

SET IDENTITY_INSERT "#__content" OFF;

--
-- Dumping data for table `#__menu`
--

SET IDENTITY_INSERT "#__menu" ON;

INSERT INTO "#__menu" ("id", "menutype", "title", "alias", "note", "path", "link", "type", "published", "parent_id", "level", "component_id", "checked_out", "checked_out_time", "browserNav", "access", "img", "template_style_id", "params", "lft", "rgt", "home", "language", "client_id") VALUES
(1, '', 'Menu_Item_Root', 'root', '', '', '', '', 1, 0, 0, 0, 0, '1900-01-01 00:00:00', 0, 0, '', 0, '', 0, 61, 0, '*', 0),
(2, 'main', 'com_banners', 'Banners', '', 'Banners', 'index.php?option=com_banners', 'component', 1, 1, 1, 4, 0, '1900-01-01 00:00:00', 0, 0, 'class:banners', 0, '', 1, 10, 0, '*', 1),
(3, 'main', 'com_banners', 'Banners', '', 'Banners/Banners', 'index.php?option=com_banners', 'component', 1, 2, 2, 4, 0, '1900-01-01 00:00:00', 0, 0, 'class:banners', 0, '', 2, 3, 0, '*', 1),
(4, 'main', 'com_banners_categories', 'Categories', '', 'Banners/Categories', 'index.php?option=com_categories&extension=com_banners', 'component', 1, 2, 2, 6, 0, '1900-01-01 00:00:00', 0, 0, 'class:banners-cat', 0, '', 4, 5, 0, '*', 1),
(5, 'main', 'com_banners_clients', 'Clients', '', 'Banners/Clients', 'index.php?option=com_banners&view=clients', 'component', 1, 2, 2, 4, 0, '1900-01-01 00:00:00', 0, 0, 'class:banners-clients', 0, '', 6, 7, 0, '*', 1),
(6, 'main', 'com_banners_tracks', 'Tracks', '', 'Banners/Tracks', 'index.php?option=com_banners&view=tracks', 'component', 1, 2, 2, 4, 0, '1900-01-01 00:00:00', 0, 0, 'class:banners-tracks', 0, '', 8, 9, 0, '*', 1),
(7, 'main', 'com_contact', 'Contacts', '', 'Contacts', 'index.php?option=com_contact', 'component', 1, 1, 1, 8, 0, '1900-01-01 00:00:00', 0, 0, 'class:contact', 0, '', 21, 26, 0, '*', 1),
(8, 'main', 'com_contact_contacts', 'Contacts', '', 'Contacts/Contacts', 'index.php?option=com_contact', 'component', 1, 7, 2, 8, 0, '1900-01-01 00:00:00', 0, 0, 'class:contact', 0, '', 22, 23, 0, '*', 1),
(9, 'main', 'com_contact_categories', 'Categories', '', 'Contacts/Categories', 'index.php?option=com_categories&extension=com_contact', 'component', 1, 7, 2, 6, 0, '1900-01-01 00:00:00', 0, 0, 'class:contact-cat', 0, '', 24, 25, 0, '*', 1),
(10, 'main', 'com_messages', 'Messaging', '', 'Messaging', 'index.php?option=com_messages', 'component', 1, 1, 1, 15, 0, '1900-01-01 00:00:00', 0, 0, 'class:messages', 0, '', 33, 36, 0, '*', 1),
(11, 'main', 'com_messages_add', 'New Private Message', '', 'Messaging/New Private Message', 'index.php?option=com_messages&task=message.add', 'component', 1, 10, 2, 15, 0, '1900-01-01 00:00:00', 0, 0, 'class:messages-add', 0, '', 34, 35, 0, '*', 1),
(13, 'main', 'com_newsfeeds', 'News Feeds', '', 'News Feeds', 'index.php?option=com_newsfeeds', 'component', 1, 1, 1, 17, 0, '1900-01-01 00:00:00', 0, 0, 'class:newsfeeds', 0, '', 39, 44, 0, '*', 1),
(14, 'main', 'com_newsfeeds_feeds', 'Feeds', '', 'News Feeds/Feeds', 'index.php?option=com_newsfeeds', 'component', 1, 13, 2, 17, 0, '1900-01-01 00:00:00', 0, 0, 'class:newsfeeds', 0, '', 40, 41, 0, '*', 1),
(15, 'main', 'com_newsfeeds_categories', 'Categories', '', 'News Feeds/Categories', 'index.php?option=com_categories&extension=com_newsfeeds', 'component', 1, 13, 2, 6, 0, '1900-01-01 00:00:00', 0, 0, 'class:newsfeeds-cat', 0, '', 42, 43, 0, '*', 1),
(16, 'main', 'com_redirect', 'Redirect', '', 'Redirect', 'index.php?option=com_redirect', 'component', 1, 1, 1, 24, 0, '1900-01-01 00:00:00', 0, 0, 'class:redirect', 0, '', 45, 46, 0, '*', 1),
(17, 'main', 'com_search', 'Basic Search', '', 'Basic Search', 'index.php?option=com_search', 'component', 1, 1, 1, 19, 0, '1900-01-01 00:00:00', 0, 0, 'class:search', 0, '', 47, 48, 0, '*', 1),
(18, 'main', 'com_finder', 'Smart Search', '', 'Smart Search', 'index.php?option=com_finder', 'component', 1, 1, 1, 27, 0, '1900-01-01 00:00:00', 0, 0, 'class:finder', 0, '', 49, 50, 0, '*', 1),
(19, 'main', 'com_joomlaupdate', 'Joomla! Update', '', 'Joomla! Update', 'index.php?option=com_joomlaupdate', 'component', 1, 1, 1, 28, 0, '1900-01-01 00:00:00', 0, 0, 'class:joomlaupdate', 0, '', 51, 52, 0, '*', 1),
(20, 'main', 'com_tags', 'Tags', '', 'Tags', 'index.php?option=com_tags', 'component', 1, 1, 1, 29, 0, '1900-01-01 00:00:00', 0, 1, 'class:tags', 0, '', 53, 54, 0, '', 1),
(21, 'main', 'com_postinstall', 'Post-installation messages', '', 'Post-installation messages', 'index.php?option=com_postinstall', 'component', 1, 1, 1, 32, 0, '1900-01-01 00:00:00', 0, 1, 'class:postinstall', 0, '', 55, 56, 0, '*', 1),
(22, 'main', 'com_associations', 'Multilingual Associations', '', 'Multilingual Associations', 'index.php?option=com_associations', 'component', 1, 1, 1, 34, 0, '1900-01-01 00:00:00', 0, 0, 'class:associations', 0, '', 57, 58, 0, '*', 1),
(101, 'mainmenu', 'Home', 'home', '', 'home', 'index.php?option=com_content&view=article&id=6', 'component', 1, 1, 1, 22, 0, '1900-01-01 00:00:00', 0, 1, '', 0, '{"show_title":"","link_titles":"","show_intro":"","info_block_position":"","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","link_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"0","show_vote":"","show_tags":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_hits":"","show_noauth":"","urls_position":"","menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}', 11, 16, 1, '*', 0),
(102, 'mainmenu', 'About Us', 'about-us', '', 'about-us', 'index.php?option=com_content&view=article&id=2', 'component', 1, 1, 1, 22, 0, '1900-01-01 00:00:00', 0, 1, '', 0, '{"show_title":"","link_titles":"","show_intro":"","info_block_position":"0","show_category":"0","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"0","link_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"0","show_item_navigation":"0","show_vote":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_hits":"0","show_noauth":"","urls_position":"","menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}', 17, 18, 0, '*', 0),
(103, 'mainmenu', 'News', 'news', '', 'news', 'index.php?option=com_content&view=category&layout=blog&id=8', 'component', 1, 1, 1, 22, 0, '1900-01-01 00:00:00', 0, 1, '', 0, '{"layout_type":"blog","show_category_title":"","show_description":"1","show_description_image":"","maxLevel":"","show_empty_categories":"","show_no_articles":"","show_subcat_desc":"","show_cat_num_articles":"","page_subheading":"","num_leading_articles":"1","num_intro_articles":"0","num_columns":"1","num_links":"3","multi_column_order":"","show_subcategory_content":"","orderby_pri":"","orderby_sec":"","order_date":"published","show_pagination":"0","show_pagination_results":"0","show_title":"","link_titles":"","show_intro":"","show_category":"0","link_category":"","show_parent_category":"","link_parent_category":"","info_bloc_position":"0","show_author":"","link_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_vote":"","show_readmore":"","show_readmore_title":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_hits":"0","show_noauth":"","show_feed_link":"","feed_summary":"","menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}', 19, 20, 0, '*', 0),
(104, 'mainmenu', 'Login', 'login', '', 'login', 'index.php?option=com_users&view=login', 'component', 1, 1, 1, 25, 0, '1900-01-01 00:00:00', 0, 4, '', 0, '{"login_redirect_url":"","logindescription_show":"1","login_description":"","login_image":"","logout_redirect_url":"","logoutdescription_show":"1","logout_description":"","logout_image":"","menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}', 29, 30, 0, '*', 0),
(105, 'mainmenu', 'Edit Profile', 'edit-profile', '', 'edit-profile', 'index.php?option=com_users&view=profile&layout=edit', 'component', 1, 1, 1, 25, 0, '1900-01-01 00:00:00', 0, 2, '', 0, '{"menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}', 31, 32, 0, '*', 0),
(106, 'mainmenu', 'Contact Us', 'contact-us', '', 'contact-us', 'index.php?option=com_contact&view=contact&id=1', 'component', 1, 1, 1, 8, 0, '1900-01-01 00:00:00', 0, 1, '', 0, '{"presentation_style":"","show_contact_category":"","show_contact_list":"","show_name":"","show_position":"","show_email":"","show_street_address":"","show_suburb":"","show_state":"","show_postcode":"","show_country":"","show_telephone":"","show_mobile":"","show_fax":"","show_webpage":"","show_misc":"","show_image":"","allow_vcard":"","show_articles":"","show_links":"","linka_name":"","linkb_name":"","linkc_name":"","linkd_name":"","linke_name":"","show_email_form":"","show_email_copy":"","banned_email":"","banned_subject":"","banned_text":"","validate_session":"","custom_reply":"","redirect":"","menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}', 27, 28, 0, '*', 0),
(107, 'mainmenu', 'Administrator', '2012-01-04-04-05-24', '', '2012-01-04-04-05-24', 'administrator', 'url', 1, 1, 1, 0, 0, '1900-01-01 00:00:00', 1, 3, '', 0, '{"menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1}', 59, 60, 0, '*', 0),
(109, 'mainmenu', 'Create an Article', 'create-an-article', '', 'create-an-article', 'index.php?option=com_content&view=form&layout=edit', 'component', 1, 1, 1, 22, 0, '1900-01-01 00:00:00', 0, 3, '', 0, '{"enable_category":"0","catid":"2","menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}', 37, 38, 0, '*', 0),
(112, 'mainmenu', 'Site Settings', 'site-settings', '', 'home/site-settings', 'index.php?option=com_config&view=config&controller=config.display.config', 'component', 1, 101, 2, 23, 0, '1900-01-01 00:00:00', 0, 6, '', 0, '{"menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}', 12, 13, 0, '*', 0),
(113, 'mainmenu', 'Template Settings', 'template-settings', '', 'home/template-settings', 'index.php?option=com_config&view=templates&controller=config.display.templates', 'component', 1, 101, 2, 23, 0, '1900-01-01 00:00:00', 0, 6, '', 0, '{"menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}', 14, 15, 0, '*', 0);

SET IDENTITY_INSERT "#__menu" OFF;

--
-- Dumping data for table `#__menu_types`
--

SET IDENTITY_INSERT "#__menu_types" ON;

INSERT INTO "#__menu_types" ("id", "asset_id", "menutype", "title", "description", "client_id") VALUES
(1, 0, 'mainmenu', 'Main Menu', 'The main menu for the site', 0);

SET IDENTITY_INSERT "#__menu_types" OFF;

--
-- Dumping data for table `#__modules`
--

SET IDENTITY_INSERT "#__modules" ON;

INSERT INTO "#__modules" ("id", "asset_id", "title", "note", "content", "ordering", "position", "checked_out", "checked_out_time", "publish_up", "publish_down", "published", "module", "access", "showtitle", "params", "client_id", "language") VALUES
(1, 0, 'Main Menu', '', '', 1, 'position-1', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_menu', 1, 1, '{"menutype":"mainmenu","startLevel":"1","endLevel":"0","showAllChildren":"1","tag_id":"","class_sfx":" nav-pills","window_open":"","layout":"_:default","moduleclass_sfx":"","cache":"1","cache_time":"900","cachemode":"itemid","module_tag":"div","bootstrap_size":"0","header_tag":"h3","header_class":"","style":"0"}', 0, '*'),
(2, 0, 'Login', '', '', 1, 'login', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_login', 1, 1, '', 1, '*'),
(3, 49, 'Popular Articles', '', '', 2, 'cpanel', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_popular', 3, 1, '{"count":"5","catid":"","user_id":"0","layout":"_:default","moduleclass_sfx":"","cache":"0","module_tag":"div","bootstrap_size":"6","header_tag":"h3","header_class":"","style":"0"}', 1, '*'),
(4, 50, 'Recently Added Articles', '', '', 1, 'cpanel', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_latest', 3, 1, '{"count":"5","ordering":"c_dsc","catid":"","user_id":"0","layout":"_:default","moduleclass_sfx":"","cache":"0","module_tag":"div","bootstrap_size":"6","header_tag":"h3","header_class":"","style":"0"}', 1, '*'),
(8, 0, 'Toolbar', '', '', 1, 'toolbar', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_toolbar', 3, 1, '', 1, '*'),
(9, 0, 'Quick Icons', '', '', 1, 'icon', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_quickicon', 3, 1, '', 1, '*'),
(10, 48, 'Logged-in Users', '', '', 3, 'cpanel', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_logged', 3, 1, '{"count":"5","name":"1","layout":"_:default","moduleclass_sfx":"","cache":"0","module_tag":"div","bootstrap_size":"6","header_tag":"h3","header_class":"","style":"0"}', 1, '*'),
(12, 0, 'Admin Menu', '', '', 1, 'menu', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_menu', 3, 1, '{"layout":"","moduleclass_sfx":"","shownew":"1","showhelp":"1","cache":"0"}', 1, '*'),
(13, 0, 'Admin Submenu', '', '', 1, 'submenu', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_submenu', 3, 1, '', 1, '*'),
(14, 0, 'User Status', '', '', 2, 'status', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_status', 3, 1, '', 1, '*'),
(15, 0, 'Title', '', '', 1, 'title', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_title', 3, 1, '', 1, '*'),
(16, 0, 'Login Form', '', '', 2, 'position-7', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_login', 1, 1, '{"pretext":"","posttext":"","login":"","logout":"","greeting":"1","name":"0","usesecure":"0","layout":"_:default","moduleclass_sfx":"","cache":"0","module_tag":"div","bootstrap_size":"0","header_tag":"h3","header_class":"","style":"0"}', 0, '*'),
(17, 0, 'Breadcrumbs', '', '', 1, 'position-2', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_breadcrumbs', 1, 1, '{"moduleclass_sfx":"","showHome":"1","homeText":"","showComponent":"1","separator":"","cache":"0","cache_time":"0","cachemode":"itemid"}', 0, '*'),
(79, 0, 'Multilanguage status', '', '', 1, 'status', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 0, 'mod_multilangstatus', 3, 1, '{"layout":"_:default","moduleclass_sfx":"","cache":"0"}', 1, '*'),
(80, 0, 'Search', '', '', -2, 'position-0', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_search', 1, 0, '{"label":"","width":"20","text":"","button":"","button_pos":"right","imagebutton":"","button_text":"","opensearch":"1","opensearch_title":"","set_itemid":"","layout":"_:default","moduleclass_sfx":"","cache":"1","cache_time":"900","cachemode":"itemid"}', 0, '*'),
(82, 0, 'News Flash', '', '', 1, '', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_articles_news', 1, 1, '{"catid":["8"],"image":"0","item_title":"1","link_titles":"1","item_heading":"h4","showLastSeparator":"0","readmore":"0","count":"5","ordering":"a.publish_up","layout":"_:default","moduleclass_sfx":"","cache":"1","cache_time":"900","cachemode":"itemid","module_tag":"div","bootstrap_size":"0","header_tag":"h3","header_class":"","style":"0"}', 0, '*'),
(83, 0, 'Side Module', '', '<p>This is a module where you might want to add some more information or an image,  a link to your social media presence, or whatever makes sense for your site.  </p><p>You can edit this module in the module manager. Look for the Side Module.</p>', 1, 'position-7', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_custom', 1, 1, '{"prepare_content":"1","backgroundimage":"","layout":"_:default","moduleclass_sfx":"","cache":"1","cache_time":"900","cachemode":"static"}', 0, '*'),
(86, 0, 'Joomla Version', '', '', 1, 'footer', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_version', 3, 1, '{"format":"short","product":"1","layout":"_:default","moduleclass_sfx":"","cache":"0"}', 1, '*'),
(87, 0, 'Footer', '', '', 1, 'footer', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_footer', 1, 1, '{"layout":"_:default","moduleclass_sfx":"","cache":"1","cache_time":"900","cachemode":"static","module_tag":"div","bootstrap_size":"0","header_tag":"h3","header_class":"","style":"0"}', 0, '*'),
(88, 52, 'Image', '', '<p><img src="images/headers/windows.jpg" alt="" /></p>', 1, 'position-3', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_custom', 1, 0, '{"prepare_content":"1","backgroundimage":"","layout":"_:default","moduleclass_sfx":"","cache":"1","cache_time":"900","cachemode":"static","module_tag":"div","bootstrap_size":"0","header_tag":"h3","header_class":"","style":"0"}', 0, '*'),
(89, 51, 'Site Information', '', '', 4, 'cpanel', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_stats_admin', 6, 1, '{"serverinfo":"1","siteinfo":"1","counter":"1","increase":"0","layout":"_:default","moduleclass_sfx":"","cache":"1","cache_time":"900","cachemode":"static","module_tag":"div","bootstrap_size":"6","header_tag":"h3","header_class":"","style":"0"}', 1, '*'),
(90, 53, 'Release News', '', '', 0, 'postinstall', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_feed', 1, 1, '{"rssurl":"https:\/\/www.joomla.org\/announcements\/release-news.feed","rssrtl":"0","rsstitle":"1","rssdesc":"1","rssimage":"1","rssitems":"3","rssitemdesc":"1","word_count":"0","layout":"_:default","moduleclass_sfx":"","cache":"1","cache_time":"900","module_tag":"div","bootstrap_size":"0","header_tag":"h3","header_class":"","style":"0"}', 1, '*');

SET IDENTITY_INSERT "#__modules" OFF;

--
-- Dumping data for table `#__modules_menu`
--

INSERT INTO "#__modules_menu" ("moduleid", "menuid") VALUES
(1, 0),
(2, 0),
(3, 0),
(4, 0),
(6, 0),
(7, 0),
(8, 0),
(9, 0),
(10, 0),
(12, 0),
(13, 0),
(14, 0),
(15, 0),
(16, 0),
(17, 0),
(79, 0),
(80, 0),
(83, 0),
(85, 0),
(86, 0),
(88, 0);

--
-- Dumping data for table `#__template_styles`
--

SET IDENTITY_INSERT "#__template_styles" ON;

INSERT INTO "#__template_styles" ("id", "template", "client_id", "home", "title", "params") VALUES
(4, 'beez3', 0, '0', 'Beez3 - Default', '{"wrapperSmall":"53","wrapperLarge":"72","logo":"images\/joomla_black.png","sitetitle":"Joomla!","sitedescription":"Open Source Content Management","navposition":"left","templatecolor":"personal","html5":"0"}'),
(5, 'hathor', 1, '0', 'Hathor - Default', '{"showSiteName":"0","colourChoice":"","boldText":"0"}'),
(7, 'protostar', 0, '1', 'My Default Style (Protostar)', '{"templateColor":"#696969","templateBackgroundColor":"#E3E3E3","logoFile":"","googleFont":"1","googleFontName":"Open+Sans","fluidContainer":"0"}'),
(8, 'isis', 1, '1', 'isis - Default', '{"templateColor":"#000000","logoFile":"","admin_menus":1,"displayHeader":1,"statusFixed":1,"stickyToolbar":1}');

SET IDENTITY_INSERT "#__template_styles" OFF;
