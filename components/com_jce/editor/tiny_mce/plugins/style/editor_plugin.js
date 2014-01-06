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
    tinymce.create('tinymce.plugins.StylePlugin', {
        init : function(ed, url) {
            // Register commands
            ed.addCommand('mceStyleProps', function() {
                var applyStyleToBlocks = false;
                var blocks = ed.selection.getSelectedBlocks();
                var styles = [];

                if (blocks.length === 1) {
                    styles.push(ed.selection.getNode().style.cssText);
                }
                else {
                    tinymce.each(blocks, function(block) {
                        styles.push(ed.dom.getAttrib(block, 'style'));
                    });
                    applyStyleToBlocks = true;
                }
                
                ed.windowManager.open({
                    file 	: ed.getParam('site_url') + 'index.php?option=com_jce&view=editor&layout=plugin&plugin=style',
                    width 	: 620 + parseInt(ed.getLang('style.delta_width', 0)),
                    height 	: 360 + parseInt(ed.getLang('style.delta_height', 0)),
                    inline 	: 1,
                    popup_css : false
                }, {
                    applyStyleToBlocks : applyStyleToBlocks,
                    plugin_url : url,
                    styles : styles
                });
            });

            ed.addCommand('mceSetElementStyle', function(ui, v) {
                if (e = ed.selection.getNode()) {
                    ed.dom.setAttrib(e, 'style', v);
                    ed.execCommand('mceRepaint');
                }
            });

            ed.onNodeChange.add(function(ed, cm, n) {
                cm.setDisabled('style', (n.nodeName === 'BODY' || (n.nodeName === 'BR' && n.getAttribute('data-mce-bogus'))));
            });

            // Register buttons
            ed.addButton('style', {
                title : 'style.desc', 
                cmd : 'mceStyleProps'
            });
        },

        getInfo : function() {
            return {
                longname : 'Style',
                author : 'Moxiecode Systems AB',
                authorurl : 'http://tinymce.moxiecode.com',
                infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/style',
                version : tinymce.majorVersion + "." + tinymce.minorVersion
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('style', tinymce.plugins.StylePlugin);
})();