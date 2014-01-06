/* JCE Editor - 2.3.2.4 | 27 March 2013 | http://www.joomlacontenteditor.net | Copyright (C) 2006 - 2013 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
(function(){tinymce.create('tinymce.plugins.FormatPlugin',{init:function(ed,url){var t=this;this.editor=ed;var blocks='p,div,address,pre,h1,h2,h3,h4,h5,h6,dl,dt,dd,code,samp';function isBlock(n){return new RegExp('^('+blocks.replace(',','|','g')+')$','i').test(n.nodeName);}
ed.onBeforeExecCommand.add(function(ed,cmd,ui,v,o){var se=ed.selection,n=se.getNode(),p;switch(cmd){case'FormatBlock':if(!v){o.terminate=true;if(n==ed.getBody()){return;}
ed.undoManager.add();p=ed.dom.getParent(n,blocks)||'';if(p){ed.formatter.toggle(p.nodeName.toLowerCase());}
var cm=ed.controlManager.get('formatselect');if(cm){cm.select(p);}}
break;case'RemoveFormat':if(!v&&isBlock(n)){ed.undoManager.add();p=ed.dom.getParent(n,blocks)||'';if(p){ed.formatter.toggle(p.nodeName.toLowerCase());}
var cm=ed.controlManager.get('formatselect');if(cm){cm.select(p);}
o.terminate=true;}
break;}});ed.onExecCommand.add(function(ed,cmd,ui,v,o){var se=ed.selection,n=se.getNode();switch(cmd){case'FormatBlock':if(v=='dt'||v=='dd'){if(n&&n.nodeName!=='DL'){ed.formatter.apply('dl');}}
break;}});ed.onPreInit.add(function(){function wrapList(node){var sibling=node.prev;if(node.parent&&node.parent.name=='dl'){return;}
if(sibling&&(sibling.name==='dl'||sibling.name==='dl')){sibling.append(node);return;}
sibling=node.next;if(sibling&&(sibling.name==='dl'||sibling.name==='dl')){sibling.insert(node,sibling.firstChild,true);return;}
node.wrap(ed.parser.filterNode(new tinymce.html.Node('dl',1)));}
ed.parser.addNodeFilter('dt,dd',function(nodes){for(var i=0,len=nodes.length;i<len;i++){wrapList(nodes[i]);}});ed.serializer.addNodeFilter('dt,dd',function(nodes){for(var i=0,len=nodes.length;i<len;i++){wrapList(nodes[i]);}});});},getInfo:function(){return{longname:'Format',author:'Ryan Demmer',authorurl:'http://www.joomlacontenteditor.net',infourl:'http://www.joomlacontenteditor.net',version:'2.3.2.4'};}});tinymce.PluginManager.add('format',tinymce.plugins.FormatPlugin);})();