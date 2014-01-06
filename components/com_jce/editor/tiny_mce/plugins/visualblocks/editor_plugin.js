/**
 * editor_plugin_src.js
 *
 * Copyright 2012, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function() {
    var cookie = tinymce.util.Cookie;
    
    tinymce.create('tinymce.plugins.VisualBlocks', {
        init : function(ed, url) {
            var cssId;

            // We don't support older browsers like IE6/7 and they don't provide prototypes for DOM objects
            if (!window.NodeList) {
                return;
            }
            
            // get state from cookie
            var state = cookie.get('wf_visualblocks_state');
            
            if (state && tinymce.is(state, 'string')) {
                if (state == 'null') {
                    state = 0;
                }
                
                state = parseFloat(state);
            }
            
            state = ed.getParam('visualblocks_default_state', state);

            ed.addCommand('mceVisualBlocks', function() {
                var dom = ed.dom, linkElm;

                if (!cssId) {
                    cssId = dom.uniqueId();
                    linkElm = dom.create('link', {
                        id: cssId,
                        rel : 'stylesheet',
                        href : url + '/css/visualblocks.css'
                    });

                    ed.getDoc().getElementsByTagName('head')[0].appendChild(linkElm);
                } else {
                    linkElm = dom.get(cssId);
                    linkElm.disabled = !linkElm.disabled;
                }

                ed.controlManager.setActive('visualblocks', !linkElm.disabled);
                
                if (linkElm.disabled) {
                    cookie.set('wf_visualblocks_state', 0);
                } else {
                    cookie.set('wf_visualblocks_state', 1);
                }
            });

            ed.onSetContent.add(function() {
                var dom = ed.dom, linkElm;
                
                if (cssId) {
                    linkElm = dom.get(cssId);
                    ed.controlManager.setActive('visualblocks', !linkElm.disabled);
                }
                
            });

            ed.addButton('visualblocks', {
                title : 'visualblocks.desc', 
                cmd : 'mceVisualBlocks'
            });

            ed.onInit.add(function() {
                if (state) {
                    ed.execCommand('mceVisualBlocks', false, null, {
                        skip_focus : true
                    });
                }
            });
        },

        getInfo : function() {
            return {
                longname : 'Visual blocks',
                author : 'Moxiecode Systems AB',
                authorurl : 'http://tinymce.moxiecode.com',
                infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/visualblocks',
                version : tinymce.majorVersion + "." + tinymce.minorVersion
            };
        }
    });
    
    /*
       Useful little script for creating images 
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext("2d");

        canvas.width = 10;
        canvas.height = 8;

        $('<div id="output"/ >').appendTo('body');

        $.each(['P', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'HGROUP', 'ASIDE', 'DIV', 'SECTION', 'ARTICLE', 'BLOCKQUOTE', 'ADDRESS', 'PRE', 'FIGURE', 'UL', 'OL', 'DL', 'DT', 'DD'], function(i, s) {
            var metrics = ctx.measureText(s);
            canvas.width = metrics.width;

            ctx.fillStyle = '#999999';
            ctx.font = 'bold 7pt Helvetica';
            ctx.textBaseline = "bottom";
            ctx.fillText(s, 0, 10);

            var text = $('#output').text();

            $('<p><img src="' + canvas.toDataURL('image/png') + '" />').insertBefore('#output');

            $('#output').text(text + '\n\r' + s.toLowerCase() + ' {background-image: url(' + canvas.toDataURL('image/png') + ');}');

            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });​
     */

    // Register plugin
    tinymce.PluginManager.add('visualblocks', tinymce.plugins.VisualBlocks);
})();