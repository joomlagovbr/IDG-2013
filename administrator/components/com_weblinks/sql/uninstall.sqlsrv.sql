DELETE FROM [#__content_types] WHERE [type_alias] IN ('com_weblinks.weblink', 'com_weblinks.category');

DROP TABLE IF EXISTS [#__weblinks];
