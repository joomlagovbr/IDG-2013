/* JCE Editor - 2.3.4.4 | 12 December 2013 | http://www.joomlacontenteditor.net | Copyright (C) 2006 - 2013 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
(function(){var VK=tinymce.VK;var blocks='section,nav,article,aside,h1,h2,h3,h4,h5,h6,header,footer,address,main,p,pre,blockquote,figure,figcaption,div';tinymce.create('tinymce.plugins.FormatPlugin',{init:function(ed,url){var self=this;this.editor=ed;function isBlock(n,s){s=s||blocks;return new RegExp('^('+s.replace(',','|','g')+')$','i').test(n.nodeName);}
ed.onKeyDown.add(function(ed,e){if((e.keyCode===VK.ENTER||e.keyCode===VK.UP)&&e.altKey){self._clearBlocks(ed,e);}});ed.onBeforeExecCommand.add(function(ed,cmd,ui,v,o){var se=ed.selection,n=se.getNode(),p;switch(cmd){case'FormatBlock':if(!v){o.terminate=true;if(n==ed.getBody()){return;}
ed.undoManager.add();p=ed.dom.getParent(n,blocks)||'';if(p){ed.formatter.toggle(p.nodeName.toLowerCase());}
var cm=ed.controlManager.get('formatselect');if(cm){cm.select(p);}}
break;case'RemoveFormat':var s='p,div,address,pre,h1,h2,h3,h4,h5,h6,code,samp,span,section,article,aside,figure,dt,dd';if(!v){if(isBlock(n,s)){ed.undoManager.add();p=ed.dom.getParent(n,blocks)||'';if(p){ed.formatter.toggle(p.nodeName.toLowerCase());}
var cm=ed.controlManager.get('formatselect');if(cm){cm.select(p);}}else{var cm=ed.controlManager.get('styleselect');if(cm.selectedValue){ed.formatter.remove(cm.selectedValue);}}}
break;}});ed.onExecCommand.add(function(ed,cmd,ui,v,o){var se=ed.selection,n=se.getNode();switch(cmd){case'FormatBlock':if(v=='dt'||v=='dd'){if(n&&n.nodeName!=='DL'){ed.formatter.apply('dl');}}
break;}});ed.onPreInit.add(function(){function wrapList(node){var sibling=node.prev;if(node.parent&&node.parent.name=='dl'){return;}
if(sibling&&(sibling.name==='dl'||sibling.name==='dl')){sibling.append(node);return;}
sibling=node.next;if(sibling&&(sibling.name==='dl'||sibling.name==='dl')){sibling.insert(node,sibling.firstChild,true);return;}
node.wrap(ed.parser.filterNode(new tinymce.html.Node('dl',1)));}
ed.parser.addNodeFilter('dt,dd',function(nodes){for(var i=0,len=nodes.length;i<len;i++){wrapList(nodes[i]);}});ed.serializer.addNodeFilter('dt,dd',function(nodes){for(var i=0,len=nodes.length;i<len;i++){wrapList(nodes[i]);}});});},_clearBlocks:function(ed,e){var p,n=ed.selection.getNode();p=ed.dom.getParents(n,blocks);if(p&&p.length>1){var h='&nbsp;',tag=ed.getParam('forced_root_block');if(!tag&&ed.getParam('force_p_newlines')){tag='p';}else{tag='br';}
e.preventDefault();var block=p[p.length-1];if(block===ed.getBody()){return;}
var el=ed.dom.create(tag);if(tag==='br'){h='<br data-mce-bogus="1" />';}
ed.dom.setHTML(el,h);if(e.keyCode===VK.ENTER){ed.dom.insertAfter(el,block);}else{block.parentNode.insertBefore(el,block);}
ed.selection.select(el);ed.selection.collapse(1);}},getInfo:function(){return{longname:'Format',author:'Ryan Demmer',authorurl:'http://www.joomlacontenteditor.net',infourl:'http://www.joomlacontenteditor.net',version:'2.3.4.4'};}});tinymce.PluginManager.add('format',tinymce.plugins.FormatPlugin);})();