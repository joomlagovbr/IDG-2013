/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function() {
    var each = tinymce.each;
	
    tinymce.create('tinymce.plugins.XHTMLXtrasPlugin', {
        init : function(ed, url) {          
            // Register commands
            ed.addCommand('mceCite', function() {
                ed.windowManager.open({
                    file : ed.getParam('site_url') + 'index.php?option=com_jce&view=editor&layout=plugin&plugin=xhtmlxtras&element=cite',
                    width : 440 + parseInt(ed.getLang('xhtmlxtras.cite_delta_width', 0)),
                    height : 380 + parseInt(ed.getLang('xhtmlxtras.cite_delta_height', 0)),
                    inline : 1,
                    popup_css : false
                }, {
                    plugin_url : url,
                    element : 'cite'
                });
            });


            ed.addCommand('mceAcronym', function() {
                ed.windowManager.open({
                    file : ed.getParam('site_url') + 'index.php?option=com_jce&view=editor&layout=plugin&plugin=xhtmlxtras&element=acronym',
                    width : 440 + parseInt(ed.getLang('xhtmlxtras.acronym_delta_width', 0)),
                    height : 380 + parseInt(ed.getLang('xhtmlxtras.acronym_delta_height', 0)),
                    inline : 1,
                    popup_css : false
                }, {
                    plugin_url : url,
                    element : 'acronym'
                });
            });


            ed.addCommand('mceAbbr', function() {
                ed.windowManager.open({
                    file : ed.getParam('site_url') + 'index.php?option=com_jce&view=editor&layout=plugin&plugin=xhtmlxtras&element=abbr',
                    width : 440 + parseInt(ed.getLang('xhtmlxtras.abbr_delta_width', 0)),
                    height : 380 + parseInt(ed.getLang('xhtmlxtras.abbr_delta_height', 0)),
                    inline : 1,
                    popup_css : false
                }, {
                    plugin_url : url,
                    element : 'abbr'
                });
            });


            ed.addCommand('mceDel', function() {
                ed.windowManager.open({
                    file : ed.getParam('site_url') + 'index.php?option=com_jce&view=editor&layout=plugin&plugin=xhtmlxtras&element=del',
                    width : 440 + parseInt(ed.getLang('xhtmlxtras.del_delta_width', 0)),
                    height : 440 + parseInt(ed.getLang('xhtmlxtras.del_delta_height', 0)),
                    inline : 1,
                    popup_css : false
                }, {
                    plugin_url : url,
                    element : 'del'
                });
            });


            ed.addCommand('mceIns', function() {
                ed.windowManager.open({
                    file : ed.getParam('site_url') + 'index.php?option=com_jce&view=editor&layout=plugin&plugin=xhtmlxtras&element=ins',
                    width : 440 + parseInt(ed.getLang('xhtmlxtras.ins_delta_width', 0)),
                    height : 440 + parseInt(ed.getLang('xhtmlxtras.ins_delta_height', 0)),
                    inline : 1,
                    popup_css : false
                }, {
                    plugin_url : url,
                    element : 'ins'
                });
            });


            ed.addCommand('mceAttributes', function() {
                ed.windowManager.open({
                    file : ed.getParam('site_url') + 'index.php?option=com_jce&view=editor&layout=plugin&plugin=xhtmlxtras&element=attributes',
                    width : 440,
                    height : 420,
                    inline : 1,
                    popup_css : false
                }, {
                    plugin_url : url
                });
            });

            // Register buttons
            ed.addButton('cite', {
                title : 'xhtmlxtras.cite_desc',
                cmd : 'mceCite'
            });
            ed.addButton('acronym', {
                title : 'xhtmlxtras.acronym_desc',
                cmd : 'mceAcronym'
            });
            ed.addButton('abbr', {
                title : 'xhtmlxtras.abbr_desc',
                cmd : 'mceAbbr'
            });
            ed.addButton('del', {
                title : 'xhtmlxtras.del_desc',
                cmd : 'mceDel'
            });
            ed.addButton('ins', {
                title : 'xhtmlxtras.ins_desc',
                cmd : 'mceIns'
            });
            ed.addButton('attribs', {
                title : 'xhtmlxtras.attribs_desc',
                cmd : 'mceAttributes'
            });

            ed.onNodeChange.add(function(ed, cm, n, co) {
                n = ed.dom.getParent(n, 'CITE,ACRONYM,ABBR,DEL,INS');

                cm.setDisabled('cite', co);
                cm.setDisabled('acronym', co);
                cm.setDisabled('abbr', co);
                cm.setDisabled('del', co);
                cm.setDisabled('ins', co);
                cm.setDisabled('attribs', n && n.nodeName == 'BODY');
                cm.setActive('cite', 0);
                cm.setActive('acronym', 0);
                cm.setActive('abbr', 0);
                cm.setActive('del', 0);
                cm.setActive('ins', 0);

                // Activate all
                if(n) {
                    do {
                        cm.setDisabled(n.nodeName.toLowerCase(), 0);
                        cm.setActive(n.nodeName.toLowerCase(), 1);
                    } while (n = n.parentNode);
                }
            });

            ed.onPreInit.add(function() {
                // Fixed IE issue where it can't handle these elements correctly
                ed.dom.create('abbr');

                ed.formatter.register({
                    cite : {
                        inline : 'cite',
                        remove : 'all',
                        onformat : function(elm, fmt, vars) {
                            each(vars, function(value, key) {
                                ed.dom.setAttrib(elm, key, value);
                            });
	
                        }
	
                    },
	
                    acronym : {
                        inline : 'acronym',
                        remove : 'all',
                        onformat : function(elm, fmt, vars) {
                            each(vars, function(value, key) {
                                ed.dom.setAttrib(elm, key, value);
                            });
	
                        }
	
                    },
	
                    abbr : {
                        inline : 'abbr',
                        remove : 'all',
                        onformat : function(elm, fmt, vars) {
                            each(vars, function(value, key) {
                                ed.dom.setAttrib(elm, key, value);
                            });
	
                        }
	
                    },
	
                    del : {
                        inline : 'del',
                        remove : 'all',	
                        onformat : function(elm, fmt, vars) {
                            each(vars, function(value, key) {
                                ed.dom.setAttrib(elm, key, value);
                            });
	
                        }
	
                    },
	
                    ins : {
                        inline : 'ins',
                        remove : 'all',
                        onformat : function(elm, fmt, vars) {
                            each(vars, function(value, key) {
                                ed.dom.setAttrib(elm, key, value);
                            });
	
                        }
	
                    },
                    
                    attributes : {
                        inline : 'span',
                        remove : 'all',
                        onformat : function(elm, fmt, vars) {
                            each(vars, function(value, key) {
                                ed.dom.setAttrib(elm, key, value);
                            });
	
                        }
	
                    }
                });
            });
        },

        getInfo : function() {
            return {
                longname : 'XHTML Xtras Plugin',
                author : 'Moxiecode Systems AB',
                authorurl : 'http://tinymce.moxiecode.com',
                infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/xhtmlxtras',
                version : tinymce.majorVersion + "." + tinymce.minorVersion
            };
        }

    });

    // Register plugin
    tinymce.PluginManager.add('xhtmlxtras', tinymce.plugins.XHTMLXtrasPlugin);
})();
