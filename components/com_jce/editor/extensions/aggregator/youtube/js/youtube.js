/* JCE Editor - 2.3.4.4 | 12 December 2013 | http://www.joomlacontenteditor.net | Copyright (C) 2006 - 2013 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
WFAggregator.add('youtube',{params:{width:425,height:350,embed:true},props:{rel:1,autohide:2,autoplay:0,controls:1,enablejsapi:0,loop:0,playlist:'',start:'',privacy:0},setup:function(){},getTitle:function(){return this.title||this.name;},getType:function(){return $('#youtube_embed:visible').is(':checked')?'flash':'iframe';},isSupported:function(v){if(typeof v=='object'){v=v.src||v.data||'';}
if(/youtu(\.)?be(.+)?\/(.+)/.test(v)){return'youtube';}
return false;},getValues:function(src){var self=this,data={},args={},type=this.getType(),id;$.extend(args,$.String.query(src));src=src.replace(/^http(s)?:\/\//,'//');$(':input','#youtube_options').not('#youtube_embed, #youtube_https, #youtube_privacy').each(function(){var k=$(this).attr('id'),v=$(this).val();k=k.substr(k.indexOf('_')+1);if($(this).is(':checkbox')){v=$(this).is(':checked')?1:0;}
if(k=='autohide'){v=parseInt(v);}
if(self.props[k]===v||v===''){return;}
args[k]=v;});src=src.replace(/youtu(\.)?be([^\/]+)?\/(.+)/,function(a,b,c,d){d=d.replace(/(watch\?v=|v\/|embed\/)/,'');if(b&&!c){c='.com';}
id=d;return'youtube'+c+'/'+(type=='iframe'?'embed':'v')+'/'+d;});if(id&&args.loop&&!args.playlist){args.playlist=id;}
if($('#youtube_privacy').is(':checked')){src=src.replace(/youtube\./,'youtube-nocookie.');}else{src=src.replace(/youtube-nocookie\./,'youtube.');}
if(type=='iframe'){$.extend(data,{allowfullscreen:true,frameborder:0});args['wmode']='opaque';}else{$.extend(true,data,{param:{allowfullscreen:true,wmode:'opaque'}});}
var query=$.param(args);if(query){src=src+(/\?/.test(src)?'&':'?')+query;}
data.src=src;return data;},setValues:function(data){var self=this,id='',src=data.src||data.data||'';if(!src){return data;}
var query=$.String.query(src);$.extend(data,query);src=src.replace(/^http(s)?:\/\//,'//');if(src.indexOf('youtube-nocookie')!==-1){data['privacy']=true;}
if(data.param){data['embed']=true;}
if(query.v){id=query.v;delete query.v;}else{var s=/(\.be|\/(embed|v))\/([^\/\?&]+)/.exec(src);if(s.length>2){id=s[3];}}
if(data.playlist){data.playlist=decodeURIComponent(data.playlist);}
if(data.playlist===id){data.playlist=null;}
if(query.wmode){delete query.wmode;}
$.each(query,function(k,v){if(typeof self.props[k]=='undefined'){$('#youtube_options table').append('<tr><td><label for="youtube_'+k+'">'+k+'</label><input type="text" id="youtube_'+k+'" value="'+v+'" /></td></tr>');}});src=src.replace(/youtu(\.)?be([^\/]+)?\/(.+)/,function(a,b,c,d){var args='youtube';if(b){args+='.com';}
if(c){args+=c;}
if($('#youtube_embed').is(':checked')){args+='/v';}else{args+='/embed';}
args+='/'+id;return args;}).replace(/\/\/youtube/i,'//www.youtube');data.src=src;return data;},getAttributes:function(src){var args={},data=this.setValues({src:src})||{};$.each(data,function(k,v){if(k=='src'){return;}
args['youtube_'+k]=v;});$.extend(args,{'src':data.src||src,'width':this.params.width,'height':this.params.height});return args;},setAttributes:function(){},onSelectFile:function(){},onInsert:function(){}});