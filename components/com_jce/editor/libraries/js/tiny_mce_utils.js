/* JCE Editor - 2.3.2.4 | 27 March 2013 | http://www.joomlacontenteditor.net | Copyright (C) 2006 - 2013 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
TinyMCE_Utils={getColorPickerHTML:function(id){var h="";h+='<a id="'+id+'_pick_link" href="javascript:;" onclick="tinyMCEPopup.pickColor(event,\''+id+'\');" onmousedown="return false;" class="pickcolor">';h+='<span id="'+id+'_pick" title="'+tinyMCEPopup.getLang('browse')+'"></span></a>';return h;},updateColor:function(parent){if(typeof parent=='string'){parent=document.getElementById(parent);}
document.getElementById(parent.id+'_pick').style.backgroundColor=parent.value;},setBrowserDisabled:function(id,state){var img=document.getElementById(id);var lnk=document.getElementById(id+"_link");if(lnk){if(state){lnk.setAttribute("realhref",lnk.getAttribute("href"));lnk.removeAttribute("href");tinyMCEPopup.dom.addClass(img,'disabled');}else{lnk.setAttribute("href",lnk.getAttribute("realhref"));tinyMCEPopup.dom.removeClass(img,'disabled');}}},getBrowserHTML:function(id,target_form_element,type,prefix){var option=prefix+"_"+type+"_browser_callback",cb,html;var cb,html;cb=tinyMCEPopup.getParam(option,tinyMCEPopup.getParam("file_browser_callback"));if(!cb){return"";}
html="";html+='<a id="'+id+'_link" href="javascript:TinyMCE_Utils.openBrowser(\''+id+'\',\''+target_form_element+'\', \''+type+'\',\''+option+'\');" onmousedown="return false;" class="browse">';html+='<span class="'+type+'" id="'+id+'" title="'+tinyMCEPopup.getLang('browse')+'"></span></a>';return html;},openBrowser:function(img,input,type,option){if(typeof img=='string'){img=document.getElementById(img);}
if(!/mceButtonDisabled/.test(img.className)){tinyMCEPopup.openBrowser(input,type,option);}},fillClassList:function(id){var ed=tinyMCEPopup.editor,lst=document.getElementById(id),v,cl;if(v=tinyMCEPopup.getParam('theme_advanced_styles')){cl=[];tinymce.each(v.split(';'),function(v){var p=v.split('=');cl.push({'title':p[0],'class':p[1]});});}else{cl=ed.dom.getClasses();}
tinymce.each(['jcepopup','jcetooltip'],function(o){lst.options[lst.options.length]=new Option(o,o);});if(cl.length>0){tinymce.each(cl,function(o){lst.options[lst.options.length]=new Option(o.title||o['class'],o['class']);});}}};var themeBaseURL=tinyMCEPopup.editor.baseURI.toAbsolute('themes/'+tinyMCEPopup.getParam("theme"));function getColorPickerHTML(id,target_form_element){return TinyMCE_Utils.getColorPickerHTML(target_form_element);}
function updateColor(img_id,form_element_id){return TinyMCE_Utils.updateColor(form_element_id);}
function setBrowserDisabled(id,state){return TinyMCE_Utils.setBrowserDisabled(id,state);}
function getBrowserHTML(id,target_form_element,type,prefix){return TinyMCE_Utils.getBrowserHTML(id,target_form_element,type,prefix);}
function openBrowser(img_id,target_form_element,type,option){return TinyMCE_Utils.openBrowser(img_id,target_form_element,type,option);}
function selectByValue(form_obj,field_name,value,add_custom,ignore_case){if(!form_obj||!form_obj.elements[field_name]||typeof value=='undefined')
return;var sel=form_obj.elements[field_name];var found=false;for(var i=0;i<sel.options.length;i++){var option=sel.options[i];if(option.value==value||(ignore_case&&option.value.toLowerCase()==value.toLowerCase())){option.selected=true;found=true;}else
option.selected=false;}
if(!found&&add_custom&&value!=''){var option=new Option(value,value);option.selected=true;sel.options[sel.options.length]=option;sel.selectedIndex=sel.options.length-1;}
return found;}
function getSelectValue(form_obj,field_name){var elm=form_obj.elements[field_name];if(elm==null||elm.options==null||elm.selectedIndex===-1)
return"";return elm.options[elm.selectedIndex].value;}
function addSelectValue(form_obj,field_name,name,value){var s=form_obj.elements[field_name];var o=new Option(name,value);s.options[s.options.length]=o;}
function addClassesToList(list_id,specific_option){TinyMCE_Utils.fillClassList(list_id);}
function isVisible(element_id){var elm=document.getElementById(element_id);return elm&&elm.style.display!="none";}
function convertRGBToHex(col){return $.String.toHex(col);}
function convertHexToRGB(col){return $.String.toRGB(col);}
function trimSize(size){return size.replace(/([0-9\.]+)(px|%|in|cm|mm|em|ex|pt|pc)/i,'$1$2');}
function getCSSSize(size){size=trimSize(size);if(size=="")
return"";if(/^[0-9]+$/.test(size))
size+='px';return size;}
function getStyle(elm,attrib,style){var val=tinyMCEPopup.dom.getAttrib(elm,attrib);if(val!='')
return''+val;if(typeof(style)=='undefined')
style=attrib;return tinyMCEPopup.dom.getStyle(elm,style);}
var Validator={isEmail:function(s){return this.test(s,'^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+@[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$');},isAbsUrl:function(s){return this.test(s,'^(news|telnet|nttp|file|http|ftp|https)://[-A-Za-z0-9\\.]+\\/?.*$');},isSize:function(s){return this.test(s,'^[0-9.]+(%|in|cm|mm|em|ex|pt|pc|px)?$');},isId:function(s){return this.test(s,'^[A-Za-z_]([A-Za-z0-9_])*$');},isEmpty:function(s){var nl,i;if(s.nodeName=='SELECT'&&s.selectedIndex<1)
return true;if(s.type=='checkbox'&&!s.checked)
return true;if(s.type=='radio'){for(i=0,nl=s.form.elements;i<nl.length;i++){if(nl[i].type=="radio"&&nl[i].name==s.name&&nl[i].checked)
return false;}
return true;}
return new RegExp('^\\s*$').test(s.nodeType==1?s.value:s);},isNumber:function(s,d){return!isNaN(s.nodeType==1?s.value:s)&&(!d||!this.test(s,'^-?[0-9]*\\.[0-9]*$'));},test:function(s,p){s=s.nodeType==1?s.value:s;return s==''||new RegExp(p).test(s);}};var AutoValidator={settings:{id_cls:'id',int_cls:'int',url_cls:'url',number_cls:'number',email_cls:'email',size_cls:'size',required_cls:'required',invalid_cls:'invalid',min_cls:'min',max_cls:'max'},init:function(s){var n;for(n in s)
this.settings[n]=s[n];},validate:function(f){var i,nl,s=this.settings,c=0;nl=this.tags(f,'label');for(i=0;i<nl.length;i++)
this.removeClass(nl[i],s.invalid_cls);c+=this.validateElms(f,'input');c+=this.validateElms(f,'select');c+=this.validateElms(f,'textarea');return c==3;},invalidate:function(n){this.mark(n.form,n);},reset:function(e){var t=new Array('label','input','select','textarea');var i,j,nl,s=this.settings;if(e==null)
return;for(i=0;i<t.length;i++){nl=this.tags(e.form?e.form:e,t[i]);for(j=0;j<nl.length;j++)
this.removeClass(nl[j],s.invalid_cls);}},validateElms:function(f,e){var nl,i,n,s=this.settings,st=true,va=Validator,v;nl=this.tags(f,e);for(i=0;i<nl.length;i++){n=nl[i];this.removeClass(n,s.invalid_cls);if(this.hasClass(n,s.required_cls)&&va.isEmpty(n))
st=this.mark(f,n);if(this.hasClass(n,s.number_cls)&&!va.isNumber(n))
st=this.mark(f,n);if(this.hasClass(n,s.int_cls)&&!va.isNumber(n,true))
st=this.mark(f,n);if(this.hasClass(n,s.url_cls)&&!va.isAbsUrl(n))
st=this.mark(f,n);if(this.hasClass(n,s.email_cls)&&!va.isEmail(n))
st=this.mark(f,n);if(this.hasClass(n,s.size_cls)&&!va.isSize(n))
st=this.mark(f,n);if(this.hasClass(n,s.id_cls)&&!va.isId(n))
st=this.mark(f,n);if(this.hasClass(n,s.min_cls,true)){v=this.getNum(n,s.min_cls);if(isNaN(v)||parseInt(n.value)<parseInt(v))
st=this.mark(f,n);}
if(this.hasClass(n,s.max_cls,true)){v=this.getNum(n,s.max_cls);if(isNaN(v)||parseInt(n.value)>parseInt(v))
st=this.mark(f,n);}}
return st;},hasClass:function(n,c,d){return new RegExp('\\b'+c+(d?'[0-9]+':'')+'\\b','g').test(n.className);},getNum:function(n,c){c=n.className.match(new RegExp('\\b'+c+'([0-9]+)\\b','g'))[0];c=c.replace(/[^0-9]/g,'');return c;},addClass:function(n,c,b){var o=this.removeClass(n,c);n.className=b?c+(o!=''?(' '+o):''):(o!=''?(o+' '):'')+c;},removeClass:function(n,c){c=n.className.replace(new RegExp("(^|\\s+)"+c+"(\\s+|$)"),' ');return n.className=c!=' '?c:'';},tags:function(f,s){return f.getElementsByTagName(s);},mark:function(f,n){var s=this.settings;this.addClass(n,s.invalid_cls);this.markLabels(f,n,s.invalid_cls);return false;},markLabels:function(f,n,ic){var nl,i;nl=this.tags(f,"label");for(i=0;i<nl.length;i++){if(nl[i].getAttribute("for")==n.id||nl[i].htmlFor==n.id)
this.addClass(nl[i],ic);}
return null;}};