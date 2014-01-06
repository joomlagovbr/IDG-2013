/* JCE Editor - 2.3.2.4 | 27 March 2013 | http://www.joomlacontenteditor.net | Copyright (C) 2006 - 2013 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
WFAggregator.add('vimeo',{params:{width:400,height:225,embed:true},props:{color:'',autoplay:0,loop:0,portrait:1,title:1,byline:1,fullscreen:1},setup:function(){$('#vimeo_embed').toggle(this.params.embed);},getTitle:function(){return this.title||this.name;},getType:function(){return $('#vimeo_embed').is(':checked')?'flash':'iframe';},isSupported:function(v){if(typeof v=='object'){v=v.src||v.data||'';}
if(/vimeo(.+)?\/(.+)/.test(v)){if(/\/external\//.test(v)){return false;}
return'vimeo';}
return false;},getValues:function(src){var self=this,data={},args={},type=this.getType(),id='';$.extend(args,$.String.query(src));$('input, select','#vimeo_options').not('#vimeo_embed').each(function(){var k=$(this).attr('id'),v=$(this).val();k=k.substr(k.indexOf('_')+1);if($(this).is(':checkbox')){v=$(this).is(':checked')?1:0;}
if(self.props[k]===v||v===''){return;}
switch(k){case'color':if(v.charAt(0)=='#'){v=v.substr(1);}
break;case'portrait':case'title':case'byline':if(type=='flash'){k='show_'+k;}
break;}
args[k]=v;});if(args.clip_id){id=args.clip_id;}else{var s=/vimeo.com(\/video)?\/([0-9]+)/.exec(src);if(s){id=s.length>1?s[2]:s[1];}}
if(type=='flash'){src='http://vimeo.com/moogaloop.swf?clip_id='+id;}else{src='http://player.vimeo.com/video/'+id;}
if(!/http(s)?:\/\//.test(src)){src='http://'+src;}
var query=$.param(args);if(query){src=src+(/\?/.test(src)?'&':'?')+query;}
data.src=src;if(type=='iframe'){$.extend(data,{frameborder:0});}else{$.extend(true,data,{param:{allowfullscreen:true,wmode:'opaque'}});}
return data;},setValues:function(data){var self=this,src=data.src||data.data||'',id='';if(!src){return data;}
var query=$.String.query(src);$.extend(data,query);src=src.replace(/&amp;/g,'&');if(/moogaloop.swf/.test(src)){data['embed']=true;$.each(['portrait','title','byline'],function(i,s){var v=query['show_'+s];if(typeof v!='undefined'){data[s]=v;delete data['show_'+s];}});id=query['clip_id'];delete data['clip_id'];delete query['clip_id'];}else{var s=/vimeo\.com\/(video\/)?([0-9]+)/.exec(src);if(s&&s.length>2){id=s[2];}}
$.each(query,function(k,v){if(typeof self.props[k]=='undefined'){$('#vimeo_options table').append('<tr><td><label for="vimeo_'+k+'">'+k+'</label><input type="text" id="vimeo_'+k+'" value="'+v+'" /></td></tr>');}});src='http://vimeo.com/'+id;if(data['color']&&data['color'].charAt(0)!='#'){data['color']='#'+data['color'];}
data.src=src;return data;},getAttributes:function(src){var args={},data=this.setValues({src:src})||{};$.each(data,function(k,v){if(k=='src'){return;}
args['vimeo_'+k]=v;});$.extend(args,{'src':data.src||src,'width':this.params.width,'height':this.params.height});return args;},setAttributes:function(){},onSelectFile:function(){},onInsert:function(){}});