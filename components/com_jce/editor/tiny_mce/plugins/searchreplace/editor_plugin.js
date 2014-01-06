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
    var DOM = tinymce.DOM, Event = tinymce.dom.Event, each = tinymce.each;
    
    tinymce.create('tinymce.plugins.SearchReplacePlugin', {
        init : function(ed, url) {
            
            function open(m) {
                ed.windowManager.open({
                    file 	: ed.getParam('site_url') + 'index.php?option=com_jce&view=editor&layout=plugin&plugin=searchreplace',
                    width 	: 420 + parseInt(ed.getLang('searchreplace.delta_width', 0)),
                    height 	: 190 + parseInt(ed.getLang('searchreplace.delta_height', 0)),
                    inline 	: 1,
                    auto_focus : 0,
                    popup_css : false
                }, {
                    mode : m,
                    search_string : ed.selection.getContent({
                        format : 'text'
                    }),
                    plugin_url : url
                });
            };
            
            var self = this;
            
            this.bookmark = null;
            
            this.editor = ed;

            // Register commands
            ed.addCommand('mceSearch', function(ui, s) {
                if (ed.getParam('searchreplace_use_dialog', 1)) {
                    return open('find');
                }
                
                var se = ed.selection, r, b, w = ed.getWin(), ca = s.casesensitive , v = s.value || '', b = s.backwards, fl = 0, fo = 0, rs = s.replace, result;
                
                // Whats the point
                if (!v)
                    return;
                
                if (tinymce.isIE) {
                    r = ed.getDoc().selection.createRange();
                }
                
                // IE flags
                if (ca)
                    fl = fl | 4;
                
                function replace() {
                    ed.selection.setContent(rs); // Needs to be duplicated due to selection bug in IE
                };
                
                
                var complete    = s.onComplete  || function(){};
                var find        = s.onFind      || function(){};

                switch (s.mode) {
                    case 'all':
                        // Move caret to beginning of text
                        ed.execCommand('SelectAll');
                        ed.selection.collapse(true);

                        if (tinymce.isIE) {
                            ed.focus();
                            r = ed.getDoc().selection.createRange();

                            while (r.findText(s, b ? -1 : 1, fl)) {
                                r.scrollIntoView();
                                r.select();
                                replace();
                                fo = 1;

                                if (b) {
                                    r.moveEnd("character", -(rs.length)); // Otherwise will loop forever
                                }
                            }

                        //tinyMCEPopup.storeSelection();
                        } else {
                            while (w.find(s, ca, b, false, false, false, false)) {
                                replace();
                                fo = 1;
                            }
                        }

                        complete.call(s.scope || this, !!fo);

                    case 'current':
                        if (!ed.selection.isCollapsed()) {
                            replace();
                        }

                        break;
                }
                
                se.collapse(b);
                r = se.getRng();

                if (tinymce.isIE) {
                    ed.focus();
                    r = ed.getDoc().selection.createRange();

                    if (r.findText(v, b ? -1 : 1, fl)) {
                        r.scrollIntoView();
                        r.select();
                        
                        result = true;
                        
                        find.call(s.scope || this);
                    } else {
                        result = false;
                    }
                } else {
                    result = w.find(v, ca, b, true, false, false, false);
                    
                    if (result) {
                        find.call(s.scope || this);
                    }
                }
                
                complete.call(s.scope || this, result);
            });
            
            if (ed.getParam('searchreplace_use_dialog', 1)) {
                ed.addCommand('mceReplace', function() {
                    open('replace');
                });

                // Register buttons
                ed.addButton('search', {
                    title : 'searchreplace.search_desc', 
                    cmd : 'mceSearch'
                });
                ed.addButton('replace', {
                    title : 'searchreplace.replace_desc', 
                    cmd : 'mceReplace'
                });
            }
        
            ed.addShortcut('ctrl+f', 'searchreplace.search_desc', function() {
                if (ed.getParam('searchreplace_use_dialog', 1)) {
                    return ed.execCommand('mceSearch');
                }
                
                var cm = ed.controlManager, c = cm.get(cm.prefix + 'searchreplace_search');
                if (c && !c.isDisabled()) {
                    c.showDialog();
                }
            });
        },
        
        createControl: function(n, cm) {
            var self = this, ed = this.editor;

            switch (n) {
 
                case 'replace':
                    if (ed.getParam('searchreplace_use_dialog', 1)) {
                        return;
                    }
                    
                    var content = DOM.create('div');
                        
                    var fieldset = DOM.add(content, 'fieldset', {}, '<legend>' + ed.getLang('searchreplace.replace_desc', 'Replace') + '</legend>');
                        
                    var n = DOM.add(fieldset, 'div');

                    DOM.add(n, 'label', {
                        'for' : ed.id + '_searchreplace_find'
                    }, ed.getLang('searchreplace.find', 'Find What'));

                    var find   = DOM.add(n, 'input', {
                        type    : 'text',
                        id      : ed.id + '_searchreplace_find',
                        style : {
                            'width' : 210
                        }
                    });
                    
                    n = DOM.add(fieldset, 'div');
                    
                    DOM.add(n, 'label', {
                        'for' : ed.id + '_searchreplace_replace'
                    }, ed.getLang('searchreplace.replace', 'Replace with'));

                    var replace = DOM.add(n, 'input', {
                        type    : 'text',
                        id      : ed.id + '_searchreplace_replace',
                        style : {
                            'width' : 210
                        }
                    });
                    
                    n = DOM.add(fieldset, 'div');
                    
                    var casesensitive = DOM.add(n, 'input', {
                        type    : 'checkbox',
                        id      : ed.id + '_searchreplace_casesensitive'
                    });
                    
                    DOM.add(n, 'label', {
                        'for' : ed.id + '_searchreplace_casesensitive'
                    }, ed.getLang('searchreplace.casesensitive', 'Match Case'));

                    var c = new tinymce.ui.ButtonDialog(cm.prefix + 'searchreplace_search', {
                        title           : ed.getLang('searchreplace.replace_desc', 'Search / Replace'),
                        'class'         : 'mce_search',
                        'dialog_class'  : ed.getParam('skin') + 'Skin',
                        'content'       : content,
                        'width'         : 320,
                        buttons         : [{
                            title : ed.getLang('searchreplace.find_next', 'Next'),
                            id    : 'searchreplace_find_next',
                            click : function(e) {                                    
                                
                                if (!find.value) {
                                    return false;
                                }
                                
                                DOM.removeClass(find, 'search_error');
                                
                                var r = ed.execCommand('mceSearch', false, {
                                    value : find.value,
                                    casesensitive : casesensitive.checked, 
                                    onComplete : function(r) {
                                        if (!r) {
                                            DOM.addClass(find, 'search_error');
                                        }
                                    },
                                    onFind : function() {
                                        c.storeSelection();
                                    }
                                });
 
                                    
                                return false;
                            },
                            scope : self
                        },{
                            title    : ed.getLang('searchreplace.find_previous', 'Previous'),
                            id      : 'searchreplace_find_previous',
                            click   : function(e) {                                    
                                
                                if (!find.value) {
                                    return false;
                                }
                                
                                DOM.removeClass(find, 'search_error');
                                
                                var r = ed.execCommand('mceSearch', false, {
                                    value           : find.value,
                                    casesensitive   : casesensitive.checked,
                                    backwards       : true,
                                    onComplete : function(r) {
                                        if (!r) {
                                            DOM.addClass(find, 'search_error');
                                        }
                                    }
                                });
                                    
                                return false;
                            },
                            scope : self
                        },{
                            title    : ed.getLang('searchreplace.replace', 'Replace'),
                            id      : 'searchreplace_replace',
                            click   : function(e) {                                    
                                
                                if (!find.value || !replace.value) {
                                    return false;
                                }
                                
                                var r = ed.execCommand('mceSearch', false, {
                                    value           : find.value,
                                    casesensitive   : casesensitive.checked,
                                    replace         : replace.value,
                                    mode            : 'current'
                                });
   
                                return false;
                            },
                            scope : self
                        },{
                            title    : ed.getLang('searchreplace.replace_all', 'Replace All'),
                            id      : 'searchreplace_replace_all',
                            click   : function(e) {                                    
                                
                                if (!find.value || !replace.value) {
                                    return false;
                                }
                                
                                var r = ed.execCommand('mceSearch', false, {
                                    value           : find.value,
                                    casesensitive   : casesensitive.checked,
                                    replace         : replace.value,
                                    mode            : 'all'
                                });
                                    
                                return false;
                            },
                            scope : self
                        }]
                    }, ed);

                    c.onShowDialog.add(function() {  
                        find.focus();
                    });
                    
                    c.onHideDialog.add(function() {
                        DOM.removeClass(find, 'search_error');
                        find.value = replace.value = '';
                        
                        c.restoreSelection();
                    });
					
                    // Remove the menu element when the editor is removed
                    ed.onRemove.add(function() {
                        c.destroy();
                    });

                    return cm.add(c);
                    break;
            }

            return null;
        },

        getInfo : function() {
            return {
                longname : 'Search/Replace',
                author : 'Moxiecode Systems AB',
                authorurl : 'http://tinymce.moxiecode.com',
                infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/searchreplace',
                version : tinymce.majorVersion + "." + tinymce.minorVersion
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('searchreplace', tinymce.plugins.SearchReplacePlugin);
})();