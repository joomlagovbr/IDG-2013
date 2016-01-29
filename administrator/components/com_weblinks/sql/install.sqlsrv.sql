/****** Insert data into table [#__content_types] for UCM functions ******/
INSERT [#__content_types] ([type_title], [type_alias], [table], [rules], [field_mappings], [router], [content_history_options])
SELECT 'Weblink', 'com_weblinks.weblink', '{"special":{"dbtable":"#__weblinks","key":"id","type":"Weblink","prefix":"WeblinksTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}', '', '{"common":{"core_content_item_id":"id","core_title":"title","core_state":"state","core_alias":"alias","core_created_time":"created","core_modified_time":"modified","core_body":"description", "core_hits":"hits","core_publish_up":"publish_up","core_publish_down":"publish_down","core_access":"access", "core_params":"params", "core_featured":"featured", "core_metadata":"metadata", "core_language":"language", "core_images":"images", "core_urls":"urls", "core_version":"version", "core_ordering":"ordering", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"catid", "core_xreference":"xreference", "asset_id":"null"}, "special":{}}', 'WeblinksHelperRoute::getWeblinkRoute', '{"formFile":"administrator\/components\/com_weblinks\/models\/forms\/weblink.xml", "hideFields":["asset_id","checked_out","checked_out_time","version","featured","images"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time", "version", "hits"], "convertToInt":["publish_up", "publish_down", "featured", "ordering"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"} ]}'
UNION ALL
SELECT 'Weblinks Category', 'com_weblinks.category', '{"special":{"dbtable":"#__categories","key":"id","type":"Category","prefix":"JTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}', '', '{"common":{"core_content_item_id":"id","core_title":"title","core_state":"published","core_alias":"alias","core_created_time":"created_time","core_modified_time":"modified_time","core_body":"description", "core_hits":"hits","core_publish_up":"null","core_publish_down":"null","core_access":"access", "core_params":"params", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"null", "core_urls":"null", "core_version":"version", "core_ordering":"null", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"parent_id", "core_xreference":"null", "asset_id":"asset_id"}, "special":{"parent_id":"parent_id","lft":"lft","rgt":"rgt","level":"level","path":"path","extension":"extension","note":"note"}}', 'WeblinksHelperRoute::getCategoryRoute', '{"formFile":"administrator\/components\/com_categories\/models\/forms\/category.xml", "hideFields":["asset_id","checked_out","checked_out_time","version","lft","rgt","level","path","extension"], "ignoreChanges":["modified_user_id", "modified_time", "checked_out", "checked_out_time", "version", "hits", "path"],"convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"created_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"parent_id","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"}]}';

/****** Object:  Table [#__weblinks] ******/
SET QUOTED_IDENTIFIER ON;

CREATE TABLE [#__weblinks](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[catid] [int] NOT NULL DEFAULT 0,
	[title] [nvarchar](250) NOT NULL DEFAULT '',
	[alias] [nvarchar](255) NOT NULL,
	[url] [nvarchar](250) NOT NULL DEFAULT '',
	[description] [nvarchar](max) NOT NULL,
	[hits] [int] NOT NULL DEFAULT 0,
	[state] [smallint] NOT NULL DEFAULT 0,
	[checked_out] [int] NOT NULL DEFAULT 0,
	[checked_out_time] [datetime] NOT NULL DEFAULT '1900-01-01T00:00:00.000',
	[ordering] [int] NOT NULL DEFAULT 0,
	[access] [int] NOT NULL DEFAULT 1,
	[params] [nvarchar](max) NOT NULL,
	[language] [nvarchar](7) NOT NULL DEFAULT '',
	[created] [datetime] NOT NULL DEFAULT '1900-01-01T00:00:00.000',
	[created_by] [bigint] NOT NULL DEFAULT 0,
	[created_by_alias] [nvarchar](255) NOT NULL DEFAULT '',
	[modified] [datetime] NOT NULL DEFAULT '1900-01-01T00:00:00.000',
	[modified_by] [bigint] NOT NULL DEFAULT 0,
	[metakey] [nvarchar](max) NOT NULL,
	[metadesc] [nvarchar](max) NOT NULL,
	[metadata] [nvarchar](max) NOT NULL,
	[featured] [tinyint] NOT NULL DEFAULT 0,
	[xreference] [nvarchar](50) NOT NULL,
	[publish_up] [datetime] NOT NULL DEFAULT '1900-01-01T00:00:00.000',
	[publish_down] [datetime] NOT NULL DEFAULT '1900-01-01T00:00:00.000',
  [version] [bigint] NOT NULL DEFAULT 1,
	[images] [nvarchar](max) NOT NULL,
 CONSTRAINT [PK_#__weblinks_id] PRIMARY KEY CLUSTERED
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY];

CREATE NONCLUSTERED INDEX [idx_access] ON [#__weblinks]
(
	[access] ASC
)WITH (STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF);

CREATE NONCLUSTERED INDEX [idx_catid] ON [#__weblinks]
(
	[catid] ASC
)WITH (STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF);

CREATE NONCLUSTERED INDEX [idx_checkout] ON [#__weblinks]
(
	[checked_out] ASC
)WITH (STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF);

CREATE NONCLUSTERED INDEX [idx_createdby] ON [#__weblinks]
(
	[created_by] ASC
)WITH (STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF);

CREATE NONCLUSTERED INDEX [idx_featured_catid] ON [#__weblinks]
(
	[featured] ASC,
	[catid] ASC
)WITH (STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF);

CREATE NONCLUSTERED INDEX [idx_language] ON [#__weblinks]
(
	[language] ASC
)WITH (STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF);

CREATE NONCLUSTERED INDEX [idx_state] ON [#__weblinks]
(
	[state] ASC
)WITH (STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF);

CREATE NONCLUSTERED INDEX [idx_xreference] ON [#__weblinks]
(
	[xreference] ASC
)WITH (STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF);
