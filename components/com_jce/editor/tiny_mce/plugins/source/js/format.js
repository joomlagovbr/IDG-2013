/* JCE Editor - 2.3.4.4 | 12 December 2013 | http://www.joomlacontenteditor.net | Copyright (C) 2006 - 2013 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
(function(){var options={'indent_size':1,'indent_char':'\t','unformatted':['a','abbr','acronym','b','bdo','big','br','cite','code','dfn','em','i','img','input','kbd','label','q','samp','select','small','span','strong','sub','sup','textarea','tt','var','pre'],'max_char':0};SourceEditor.formatHTML=function(html,o){for(var n in o){options[n]=o[n];}
html=style_html(html,options);return html.replace(new RegExp('\n*\t*<('+options.unformatted.join('|')+')','gi'),'<$1').replace(/\n\t<\/(li|dt|dd)>/gi,'</$1>').replace(/\n+/g,'\n');};function js_beautify(js_source_text,options){var input,output,token_text,last_type,last_text,last_last_text,last_word,flags,flag_store,indent_string;var whitespace,wordchar,punct,parser_pos,line_starters,digits;var prefix,token_type,do_block_just_closed;var wanted_newline,just_added_newline,n_newlines;var preindent_string='';options=options?options:{};var opt_brace_style;if(options.space_after_anon_function!==undefined&&options.jslint_happy===undefined){options.jslint_happy=options.space_after_anon_function;}
if(options.braces_on_own_line!==undefined){opt_brace_style=options.braces_on_own_line?"expand":"collapse";}
opt_brace_style=options.brace_style?options.brace_style:(opt_brace_style?opt_brace_style:"collapse");var opt_indent_size=options.indent_size?options.indent_size:4;var opt_indent_char=options.indent_char?options.indent_char:' ';var opt_preserve_newlines=typeof options.preserve_newlines==='undefined'?true:options.preserve_newlines;var opt_max_preserve_newlines=typeof options.max_preserve_newlines==='undefined'?false:options.max_preserve_newlines;var opt_jslint_happy=options.jslint_happy==='undefined'?false:options.jslint_happy;var opt_keep_array_indentation=typeof options.keep_array_indentation==='undefined'?false:options.keep_array_indentation;var opt_space_before_conditional=typeof options.space_before_conditional==='undefined'?true:options.space_before_conditional;var opt_indent_case=typeof options.indent_case==='undefined'?false:options.indent_case;just_added_newline=false;var input_length=js_source_text.length;function trim_output(eat_newlines){eat_newlines=typeof eat_newlines==='undefined'?false:eat_newlines;while(output.length&&(output[output.length-1]===' '||output[output.length-1]===indent_string||output[output.length-1]===preindent_string||(eat_newlines&&(output[output.length-1]==='\n'||output[output.length-1]==='\r')))){output.pop();}}
function trim(s){return s.replace(/^\s\s*|\s\s*$/,'');}
function split_newlines(s)
{s=s.replace(/\x0d/g,'');var out=[],idx=s.indexOf("\n");while(idx!=-1){out.push(s.substring(0,idx));s=s.substring(idx+1);idx=s.indexOf("\n");}
if(s.length){out.push(s);}
return out;}
function force_newline()
{var old_keep_array_indentation=opt_keep_array_indentation;opt_keep_array_indentation=false;print_newline()
opt_keep_array_indentation=old_keep_array_indentation;}
function print_newline(ignore_repeated){flags.eat_next_space=false;if(opt_keep_array_indentation&&is_array(flags.mode)){return;}
ignore_repeated=typeof ignore_repeated==='undefined'?true:ignore_repeated;flags.if_line=false;trim_output();if(!output.length){return;}
if(output[output.length-1]!=="\n"||!ignore_repeated){just_added_newline=true;output.push("\n");}
if(preindent_string){output.push(preindent_string);}
for(var i=0;i<flags.indentation_level;i+=1){output.push(indent_string);}
if(flags.var_line&&flags.var_line_reindented){output.push(indent_string);}
if(flags.case_body){output.push(indent_string);}}
function print_single_space(){if(last_type==='TK_COMMENT'){return print_newline(true);}
if(flags.eat_next_space){flags.eat_next_space=false;return;}
var last_output=' ';if(output.length){last_output=output[output.length-1];}
if(last_output!==' '&&last_output!=='\n'&&last_output!==indent_string){output.push(' ');}}
function print_token(){just_added_newline=false;flags.eat_next_space=false;output.push(token_text);}
function indent(){flags.indentation_level+=1;}
function remove_indent(){if(output.length&&output[output.length-1]===indent_string){output.pop();}}
function set_mode(mode){if(flags){flag_store.push(flags);}
flags={previous_mode:flags?flags.mode:'BLOCK',mode:mode,var_line:false,var_line_tainted:false,var_line_reindented:false,in_html_comment:false,if_line:false,in_case_statement:false,in_case:false,case_body:false,eat_next_space:false,indentation_baseline:-1,indentation_level:(flags?flags.indentation_level+(flags.case_body?1:0)+((flags.var_line&&flags.var_line_reindented)?1:0):0),ternary_depth:0};}
function is_array(mode){return mode==='[EXPRESSION]'||mode==='[INDENTED-EXPRESSION]';}
function is_expression(mode){return in_array(mode,['[EXPRESSION]','(EXPRESSION)','(FOR-EXPRESSION)','(COND-EXPRESSION)']);}
function restore_mode(){do_block_just_closed=flags.mode==='DO_BLOCK';if(flag_store.length>0){var mode=flags.mode;flags=flag_store.pop();flags.previous_mode=mode;}}
function all_lines_start_with(lines,c){for(var i=0;i<lines.length;i++){var line=trim(lines[i]);if(line.charAt(0)!==c){return false;}}
return true;}
function is_special_word(word)
{return in_array(word,['case','return','do','if','throw','else']);}
function in_array(what,arr){for(var i=0;i<arr.length;i+=1){if(arr[i]===what){return true;}}
return false;}
function look_up(exclude){var local_pos=parser_pos;var c=input.charAt(local_pos);while(in_array(c,whitespace)&&c!=exclude){local_pos++;if(local_pos>=input_length)return 0;c=input.charAt(local_pos);}
return c;}
function get_next_token(){n_newlines=0;if(parser_pos>=input_length){return['','TK_EOF'];}
wanted_newline=false;var c=input.charAt(parser_pos);parser_pos+=1;var keep_whitespace=opt_keep_array_indentation&&is_array(flags.mode);if(keep_whitespace){var whitespace_count=0;while(in_array(c,whitespace)){if(c==="\n"){trim_output();output.push("\n");just_added_newline=true;whitespace_count=0;}else{if(c==='\t'){whitespace_count+=4;}else if(c==='\r'){}else{whitespace_count+=1;}}
if(parser_pos>=input_length){return['','TK_EOF'];}
c=input.charAt(parser_pos);parser_pos+=1;}
if(flags.indentation_baseline===-1){flags.indentation_baseline=whitespace_count;}
if(just_added_newline){var i;for(i=0;i<flags.indentation_level+1;i+=1){output.push(indent_string);}
if(flags.indentation_baseline!==-1){for(i=0;i<whitespace_count-flags.indentation_baseline;i++){output.push(' ');}}}}else{while(in_array(c,whitespace)){if(c==="\n"){n_newlines+=((opt_max_preserve_newlines)?(n_newlines<=opt_max_preserve_newlines)?1:0:1);}
if(parser_pos>=input_length){return['','TK_EOF'];}
c=input.charAt(parser_pos);parser_pos+=1;}
if(opt_preserve_newlines){if(n_newlines>1){for(i=0;i<n_newlines;i+=1){print_newline(i===0);just_added_newline=true;}}}
wanted_newline=n_newlines>0;}
if(in_array(c,wordchar)){if(parser_pos<input_length){while(in_array(input.charAt(parser_pos),wordchar)){c+=input.charAt(parser_pos);parser_pos+=1;if(parser_pos===input_length){break;}}}
if(parser_pos!==input_length&&c.match(/^[0-9]+[Ee]$/)&&(input.charAt(parser_pos)==='-'||input.charAt(parser_pos)==='+')){var sign=input.charAt(parser_pos);parser_pos+=1;var t=get_next_token(parser_pos);c+=sign+t[0];return[c,'TK_WORD'];}
if(c==='in'){return[c,'TK_OPERATOR'];}
if(wanted_newline&&last_type!=='TK_OPERATOR'&&last_type!=='TK_EQUALS'&&!flags.if_line&&(opt_preserve_newlines||last_text!=='var')){print_newline();}
return[c,'TK_WORD'];}
if(c==='('||c==='['){return[c,'TK_START_EXPR'];}
if(c===')'||c===']'){return[c,'TK_END_EXPR'];}
if(c==='{'){return[c,'TK_START_BLOCK'];}
if(c==='}'){return[c,'TK_END_BLOCK'];}
if(c===';'){return[c,'TK_SEMICOLON'];}
if(c==='/'){var comment='';var inline_comment=true;if(input.charAt(parser_pos)==='*'){parser_pos+=1;if(parser_pos<input_length){while(parser_pos<input_length&&!(input.charAt(parser_pos)==='*'&&input.charAt(parser_pos+1)&&input.charAt(parser_pos+1)==='/')){c=input.charAt(parser_pos);comment+=c;if(c==="\n"||c==="\r"){inline_comment=false;}
parser_pos+=1;if(parser_pos>=input_length){break;}}}
parser_pos+=2;if(inline_comment&&n_newlines==0){return['/*'+comment+'*/','TK_INLINE_COMMENT'];}else{return['/*'+comment+'*/','TK_BLOCK_COMMENT'];}}
if(input.charAt(parser_pos)==='/'){comment=c;while(input.charAt(parser_pos)!=='\r'&&input.charAt(parser_pos)!=='\n'){comment+=input.charAt(parser_pos);parser_pos+=1;if(parser_pos>=input_length){break;}}
parser_pos+=1;if(wanted_newline){print_newline();}
return[comment,'TK_COMMENT'];}}
if(c==="'"||c==='"'||(c==='/'&&((last_type==='TK_WORD'&&is_special_word(last_text))||(last_text===')'&&in_array(flags.previous_mode,['(COND-EXPRESSION)','(FOR-EXPRESSION)']))||(last_type==='TK_COMMENT'||last_type==='TK_START_EXPR'||last_type==='TK_START_BLOCK'||last_type==='TK_END_BLOCK'||last_type==='TK_OPERATOR'||last_type==='TK_EQUALS'||last_type==='TK_EOF'||last_type==='TK_SEMICOLON')))){var sep=c;var esc=false;var resulting_string=c;if(parser_pos<input_length){if(sep==='/'){var in_char_class=false;while(esc||in_char_class||input.charAt(parser_pos)!==sep){resulting_string+=input.charAt(parser_pos);if(!esc){esc=input.charAt(parser_pos)==='\\';if(input.charAt(parser_pos)==='['){in_char_class=true;}else if(input.charAt(parser_pos)===']'){in_char_class=false;}}else{esc=false;}
parser_pos+=1;if(parser_pos>=input_length){return[resulting_string,'TK_STRING'];}}}else{while(esc||input.charAt(parser_pos)!==sep){resulting_string+=input.charAt(parser_pos);if(!esc){esc=input.charAt(parser_pos)==='\\';}else{esc=false;}
parser_pos+=1;if(parser_pos>=input_length){return[resulting_string,'TK_STRING'];}}}}
parser_pos+=1;resulting_string+=sep;if(sep==='/'){while(parser_pos<input_length&&in_array(input.charAt(parser_pos),wordchar)){resulting_string+=input.charAt(parser_pos);parser_pos+=1;}}
return[resulting_string,'TK_STRING'];}
if(c==='#'){if(output.length===0&&input.charAt(parser_pos)==='!'){resulting_string=c;while(parser_pos<input_length&&c!='\n'){c=input.charAt(parser_pos);resulting_string+=c;parser_pos+=1;}
output.push(trim(resulting_string)+'\n');print_newline();return get_next_token();}
var sharp='#';if(parser_pos<input_length&&in_array(input.charAt(parser_pos),digits)){do{c=input.charAt(parser_pos);sharp+=c;parser_pos+=1;}while(parser_pos<input_length&&c!=='#'&&c!=='=');if(c==='#'){}else if(input.charAt(parser_pos)==='['&&input.charAt(parser_pos+1)===']'){sharp+='[]';parser_pos+=2;}else if(input.charAt(parser_pos)==='{'&&input.charAt(parser_pos+1)==='}'){sharp+='{}';parser_pos+=2;}
return[sharp,'TK_WORD'];}}
if(c==='<'&&input.substring(parser_pos-1,parser_pos+3)==='<!--'){parser_pos+=3;c='<!--';while(input.charAt(parser_pos)!='\n'&&parser_pos<input_length){c+=input.charAt(parser_pos);parser_pos++;}
flags.in_html_comment=true;return[c,'TK_COMMENT'];}
if(c==='-'&&flags.in_html_comment&&input.substring(parser_pos-1,parser_pos+2)==='-->'){flags.in_html_comment=false;parser_pos+=2;if(wanted_newline){print_newline();}
return['-->','TK_COMMENT'];}
if(in_array(c,punct)){while(parser_pos<input_length&&in_array(c+input.charAt(parser_pos),punct)){c+=input.charAt(parser_pos);parser_pos+=1;if(parser_pos>=input_length){break;}}
if(c==='='){return[c,'TK_EQUALS'];}else{return[c,'TK_OPERATOR'];}}
return[c,'TK_UNKNOWN'];}
indent_string='';while(opt_indent_size>0){indent_string+=opt_indent_char;opt_indent_size-=1;}
while(js_source_text&&(js_source_text.charAt(0)===' '||js_source_text.charAt(0)==='\t')){preindent_string+=js_source_text.charAt(0);js_source_text=js_source_text.substring(1);}
input=js_source_text;last_word='';last_type='TK_START_EXPR';last_text='';last_last_text='';output=[];do_block_just_closed=false;whitespace="\n\r\t ".split('');wordchar='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_$'.split('');digits='0123456789'.split('');punct='+ - * / % & ++ -- = += -= *= /= %= == === != !== > < >= <= >> << >>> >>>= >>= <<= && &= | || ! !! , : ? ^ ^= |= ::';punct+=' <%= <% %> <?= <? ?>';punct=punct.split(' ');line_starters='continue,try,throw,return,var,if,switch,case,default,for,while,break,function'.split(',');flag_store=[];set_mode('BLOCK');parser_pos=0;while(true){var t=get_next_token(parser_pos);token_text=t[0];token_type=t[1];if(token_type==='TK_EOF'){break;}
switch(token_type){case'TK_START_EXPR':if(token_text==='['){if(last_type==='TK_WORD'||last_text===')'){if(in_array(last_text,line_starters)){print_single_space();}
set_mode('(EXPRESSION)');print_token();break;}
if(flags.mode==='[EXPRESSION]'||flags.mode==='[INDENTED-EXPRESSION]'){if(last_last_text===']'&&last_text===','){if(flags.mode==='[EXPRESSION]'){flags.mode='[INDENTED-EXPRESSION]';if(!opt_keep_array_indentation){indent();}}
set_mode('[EXPRESSION]');if(!opt_keep_array_indentation){print_newline();}}else if(last_text==='['){if(flags.mode==='[EXPRESSION]'){flags.mode='[INDENTED-EXPRESSION]';if(!opt_keep_array_indentation){indent();}}
set_mode('[EXPRESSION]');if(!opt_keep_array_indentation){print_newline();}}else{set_mode('[EXPRESSION]');}}else{set_mode('[EXPRESSION]');}}else{if(last_word==='for'){set_mode('(FOR-EXPRESSION)');}else if(in_array(last_word,['if','while'])){set_mode('(COND-EXPRESSION)');}else{set_mode('(EXPRESSION)');}}
if(last_text===';'||last_type==='TK_START_BLOCK'){print_newline();}else if(last_type==='TK_END_EXPR'||last_type==='TK_START_EXPR'||last_type==='TK_END_BLOCK'||last_text==='.'){if(wanted_newline){print_newline();}}else if(last_type!=='TK_WORD'&&last_type!=='TK_OPERATOR'){print_single_space();}else if(last_word==='function'||last_word==='typeof'){if(opt_jslint_happy){print_single_space();}}else if(in_array(last_text,line_starters)||last_text==='catch'){if(opt_space_before_conditional){print_single_space();}}
print_token();break;case'TK_END_EXPR':if(token_text===']'){if(opt_keep_array_indentation){if(last_text==='}'){remove_indent();print_token();restore_mode();break;}}else{if(flags.mode==='[INDENTED-EXPRESSION]'){if(last_text===']'){restore_mode();print_newline();print_token();break;}}}}
restore_mode();print_token();break;case'TK_START_BLOCK':if(last_word==='do'){set_mode('DO_BLOCK');}else{set_mode('BLOCK');}
if(opt_brace_style=="expand"||opt_brace_style=="expand-strict"){var empty_braces=false;if(opt_brace_style=="expand-strict")
{empty_braces=(look_up()=='}');if(!empty_braces){print_newline(true);}}else{if(last_type!=='TK_OPERATOR'){if(last_text==='='||(is_special_word(last_text)&&last_text!=='else')){print_single_space();}else{print_newline(true);}}}
print_token();if(!empty_braces)indent();}else{if(last_type!=='TK_OPERATOR'&&last_type!=='TK_START_EXPR'){if(last_type==='TK_START_BLOCK'){print_newline();}else{print_single_space();}}else{if(is_array(flags.previous_mode)&&last_text===','){if(last_last_text==='}'){print_single_space();}else{print_newline();}}}
indent();print_token();}
break;case'TK_END_BLOCK':restore_mode();if(opt_brace_style=="expand"||opt_brace_style=="expand-strict"){if(last_text!=='{'){print_newline();}
print_token();}else{if(last_type==='TK_START_BLOCK'){if(just_added_newline){remove_indent();}else{trim_output();}}else{if(is_array(flags.mode)&&opt_keep_array_indentation){opt_keep_array_indentation=false;print_newline();opt_keep_array_indentation=true;}else{print_newline();}}
print_token();}
break;case'TK_WORD':if(do_block_just_closed){print_single_space();print_token();print_single_space();do_block_just_closed=false;break;}
if(token_text==='function'){if(flags.var_line){flags.var_line_reindented=true;}
if((just_added_newline||last_text===';')&&last_text!=='{'&&last_type!='TK_BLOCK_COMMENT'&&last_type!='TK_COMMENT'){n_newlines=just_added_newline?n_newlines:0;if(!opt_preserve_newlines){n_newlines=1;}
for(var i=0;i<2-n_newlines;i++){print_newline(false);}}}
if(token_text==='case'||(token_text==='default'&&flags.in_case_statement)){if(last_text===':'||flags.case_body){remove_indent();}else{if(!opt_indent_case)
flags.indentation_level--;print_newline();if(!opt_indent_case)
flags.indentation_level++;}
print_token();flags.in_case=true;flags.in_case_statement=true;flags.case_body=false;break;}
prefix='NONE';if(last_type==='TK_END_BLOCK'){if(!in_array(token_text.toLowerCase(),['else','catch','finally'])){prefix='NEWLINE';}else{if(opt_brace_style=="expand"||opt_brace_style=="end-expand"||opt_brace_style=="expand-strict"){prefix='NEWLINE';}else{prefix='SPACE';print_single_space();}}}else if(last_type==='TK_SEMICOLON'&&(flags.mode==='BLOCK'||flags.mode==='DO_BLOCK')){prefix='NEWLINE';}else if(last_type==='TK_SEMICOLON'&&is_expression(flags.mode)){prefix='SPACE';}else if(last_type==='TK_STRING'){prefix='NEWLINE';}else if(last_type==='TK_WORD'){if(last_text==='else'){trim_output(true);}
prefix='SPACE';}else if(last_type==='TK_START_BLOCK'){prefix='NEWLINE';}else if(last_type==='TK_END_EXPR'){print_single_space();prefix='NEWLINE';}
if(in_array(token_text,line_starters)&&last_text!==')'){if(last_text=='else'){prefix='SPACE';}else{prefix='NEWLINE';}
if(token_text==='function'&&(last_text==='get'||last_text==='set')){prefix='SPACE';}}
if(flags.if_line&&last_type==='TK_END_EXPR'){flags.if_line=false;}
if(in_array(token_text.toLowerCase(),['else','catch','finally'])){if(last_type!=='TK_END_BLOCK'||opt_brace_style=="expand"||opt_brace_style=="end-expand"||opt_brace_style=="expand-strict"){print_newline();}else{trim_output(true);print_single_space();}}else if(prefix==='NEWLINE'){if((last_type==='TK_START_EXPR'||last_text==='='||last_text===',')&&token_text==='function'){}else if(token_text==='function'&&last_text=='new'){print_single_space();}else if(is_special_word(last_text)){print_single_space();}else if(last_type!=='TK_END_EXPR'){if((last_type!=='TK_START_EXPR'||token_text!=='var')&&last_text!==':'){if(token_text==='if'&&last_word==='else'&&last_text!=='{'){print_single_space();}else{flags.var_line=false;flags.var_line_reindented=false;print_newline();}}}else if(in_array(token_text,line_starters)&&last_text!=')'){flags.var_line=false;flags.var_line_reindented=false;print_newline();}}else if(is_array(flags.mode)&&last_text===','&&last_last_text==='}'){print_newline();}else if(prefix==='SPACE'){print_single_space();}
print_token();last_word=token_text;if(token_text==='var'){flags.var_line=true;flags.var_line_reindented=false;flags.var_line_tainted=false;}
if(token_text==='if'){flags.if_line=true;}
if(token_text==='else'){flags.if_line=false;}
break;case'TK_SEMICOLON':print_token();flags.var_line=false;flags.var_line_reindented=false;if(flags.mode=='OBJECT'){flags.mode='BLOCK';}
break;case'TK_STRING':if(last_type==='TK_END_EXPR'&&in_array(flags.previous_mode,['(COND-EXPRESSION)','(FOR-EXPRESSION)'])){print_single_space();}else if(last_type=='TK_STRING'||last_type==='TK_START_BLOCK'||last_type==='TK_END_BLOCK'||last_type==='TK_SEMICOLON'){print_newline();}else if(last_type==='TK_WORD'){print_single_space();}
print_token();break;case'TK_EQUALS':if(flags.var_line){flags.var_line_tainted=true;}
print_single_space();print_token();print_single_space();break;case'TK_OPERATOR':var space_before=true;var space_after=true;if(flags.var_line&&token_text===','&&(is_expression(flags.mode))){flags.var_line_tainted=false;}
if(flags.var_line){if(token_text===','){if(flags.var_line_tainted){print_token();flags.var_line_reindented=true;flags.var_line_tainted=false;print_newline();break;}else{flags.var_line_tainted=false;}}}
if(is_special_word(last_text)){print_single_space();print_token();break;}
if(token_text=='*'&&last_type=='TK_UNKNOWN'&&!last_last_text.match(/^\d+$/)){print_token();break;}
if(token_text===':'&&flags.in_case){if(opt_indent_case)
flags.case_body=true;print_token();print_newline();flags.in_case=false;break;}
if(token_text==='::'){print_token();break;}
if(token_text===','){if(flags.var_line){if(flags.var_line_tainted){print_token();print_newline();flags.var_line_tainted=false;}else{print_token();print_single_space();}}else if(last_type==='TK_END_BLOCK'&&flags.mode!=="(EXPRESSION)"){print_token();if(flags.mode==='OBJECT'&&last_text==='}'){print_newline();}else{print_single_space();}}else{if(flags.mode==='OBJECT'){print_token();print_newline();}else{print_token();print_single_space();}}
break;}else if(in_array(token_text,['--','++','!'])||(in_array(token_text,['-','+'])&&(in_array(last_type,['TK_START_BLOCK','TK_START_EXPR','TK_EQUALS','TK_OPERATOR'])||in_array(last_text,line_starters)))){space_before=false;space_after=false;if(last_text===';'&&is_expression(flags.mode)){space_before=true;}
if(last_type==='TK_WORD'&&in_array(last_text,line_starters)){space_before=true;}
if(flags.mode==='BLOCK'&&(last_text==='{'||last_text===';')){print_newline();}}else if(token_text==='.'){space_before=false;}else if(token_text===':'){if(flags.ternary_depth==0){if(flags.mode=='BLOCK'){flags.mode='OBJECT';}
space_before=false;}else{flags.ternary_depth-=1;}}else if(token_text==='?'){flags.ternary_depth+=1;}
if(space_before){print_single_space();}
print_token();if(space_after){print_single_space();}
if(token_text==='!'){}
break;case'TK_BLOCK_COMMENT':var lines=split_newlines(token_text);if(all_lines_start_with(lines.slice(1),'*')){print_newline();output.push(lines[0]);for(i=1;i<lines.length;i++){print_newline();output.push(' ');output.push(trim(lines[i]));}}else{if(lines.length>1){print_newline();}else{if(last_type==='TK_END_BLOCK'){print_newline();}else{print_single_space();}}
for(i=0;i<lines.length;i++){output.push(lines[i]);output.push("\n");}}
if(look_up('\n')!='\n')
print_newline();break;case'TK_INLINE_COMMENT':print_single_space();print_token();if(is_expression(flags.mode)){print_single_space();}else{force_newline();}
break;case'TK_COMMENT':if(last_type=='TK_COMMENT'){print_newline();if(wanted_newline){print_newline(false);}}else{if(wanted_newline){print_newline();}else{print_single_space();}}
print_token();if(look_up('\n')!='\n')
force_newline();break;case'TK_UNKNOWN':if(is_special_word(last_text)){print_single_space();}
print_token();break;}
last_last_text=last_text;last_type=token_type;last_text=token_text;}
var sweet_code=preindent_string+output.join('').replace(/[\r\n ]+$/,'');return sweet_code;}
function css_beautify(source_text,options){options=options||{};var indentSize=options.indent_size||4;var indentCharacter=options.indent_char||' ';if(typeof indentSize=="string")
indentSize=parseInt(indentSize);var whiteRe=/^\s+$/;var wordRe=/[\w$\-_]/;var pos=-1,ch;function next(){return ch=source_text.charAt(++pos)}
function peek(){return source_text.charAt(pos+1)}
function eatString(comma){var start=pos;while(next()){if(ch=="\\"){next();next();}else if(ch==comma){break;}else if(ch=="\n"){break;}}
return source_text.substring(start,pos+1);}
function eatWhitespace(){var start=pos;while(whiteRe.test(peek()))
pos++;return pos!=start;}
function skipWhitespace(){var start=pos;do{}while(whiteRe.test(next()))
return pos!=start+1;}
function eatComment(){var start=pos;next();while(next()){if(ch=="*"&&peek()=="/"){pos++;break;}}
return source_text.substring(start,pos+1);}
function lookBack(str,index){return output.slice(-str.length+(index||0),index).join("").toLowerCase()==str;}
var indentString=source_text.match(/^[\r\n]*[\t ]*/)[0];var singleIndent=Array(indentSize+1).join(indentCharacter);var indentLevel=0;function indent(){indentLevel++;indentString+=singleIndent;}
function outdent(){indentLevel--;indentString=indentString.slice(0,-indentSize);}
print={}
print["{"]=function(ch){print.singleSpace();output.push(ch);print.newLine();}
print["}"]=function(ch){print.newLine();output.push(ch);print.newLine();}
print.newLine=function(keepWhitespace){if(!keepWhitespace)
while(whiteRe.test(output[output.length-1]))
output.pop();if(output.length)
output.push('\n');if(indentString)
output.push(indentString);}
print.singleSpace=function(){if(output.length&&!whiteRe.test(output[output.length-1]))
output.push(' ');}
var output=[];if(indentString)
output.push(indentString);while(true){var isAfterSpace=skipWhitespace();if(!ch)
break;if(ch=='{'){indent();print["{"](ch);}else if(ch=='}'){outdent();print["}"](ch);}else if(ch=='"'||ch=='\''){output.push(eatString(ch))}else if(ch==';'){output.push(ch,'\n',indentString);}else if(ch=='/'&&peek()=='*'){print.newLine();output.push(eatComment(),"\n",indentString);}else if(ch=='('){output.push(ch);eatWhitespace();if(lookBack("url",-1)&&next()){if(ch!=')'&&ch!='"'&&ch!='\'')
output.push(eatString(')'));else
pos--;}}else if(ch==')'){output.push(ch);}else if(ch==','){eatWhitespace();output.push(ch);print.singleSpace();}else if(ch==']'){output.push(ch);}else if(ch=='['||ch=='='){eatWhitespace();output.push(ch);}else{if(isAfterSpace)
print.singleSpace();output.push(ch);}}
var sweetCode=output.join('').replace(/[\n ]+$/,'');return sweetCode;}
function style_html(html_source,options){var multi_parser,indent_size,indent_character,max_char,brace_style;options=options||{};indent_size=options.indent_size||4;indent_character=options.indent_char||' ';brace_style=options.brace_style||'collapse';max_char=options.max_char==0?Infinity:options.max_char||70;unformatted=options.unformatted||['a'];function Parser(){this.pos=0;this.token='';this.current_mode='CONTENT';this.tags={parent:'parent1',parentcount:1,parent1:''};this.tag_type='';this.token_text=this.last_token=this.last_text=this.token_type='';this.Utils={whitespace:"\n\r\t ".split(''),single_token:'br,input,link,meta,!doctype,basefont,base,area,hr,wbr,param,img,isindex,?xml,embed'.split(','),extra_liners:'head,body,/html'.split(','),in_array:function(what,arr){for(var i=0;i<arr.length;i++){if(what===arr[i]){return true;}}
return false;}}
this.get_content=function(){var input_char='';var content=[];var space=false;while(this.input.charAt(this.pos)!=='<'){if(this.pos>=this.input.length){return content.length?content.join(''):['','TK_EOF'];}
input_char=this.input.charAt(this.pos);this.pos++;this.line_char_count++;if(this.Utils.in_array(input_char,this.Utils.whitespace)&&input_char!=' '){if(content.length){space=true;}
this.line_char_count--;continue;}
else if(space){if(this.line_char_count>=this.max_char){content.push('\n');for(var i=0;i<this.indent_level;i++){content.push(this.indent_string);}
this.line_char_count=0;}
else{content.push(' ');this.line_char_count++;}
space=false;}
content.push(input_char);}
return content.length?content.join(''):'';}
this.get_contents_to=function(name){if(this.pos==this.input.length){return['','TK_EOF'];}
var input_char='';var content='';var reg_match=new RegExp('\<\/'+name+'\\s*\>','igm');reg_match.lastIndex=this.pos;var reg_array=reg_match.exec(this.input);var end_script=reg_array?reg_array.index:this.input.length;if(this.pos<end_script){content=this.input.substring(this.pos,end_script);this.pos=end_script;}
return content;}
this.record_tag=function(tag){if(this.tags[tag+'count']){this.tags[tag+'count']++;this.tags[tag+this.tags[tag+'count']]=this.indent_level;}
else{this.tags[tag+'count']=1;this.tags[tag+this.tags[tag+'count']]=this.indent_level;}
this.tags[tag+this.tags[tag+'count']+'parent']=this.tags.parent;this.tags.parent=tag+this.tags[tag+'count'];}
this.retrieve_tag=function(tag){if(this.tags[tag+'count']){var temp_parent=this.tags.parent;while(temp_parent){if(tag+this.tags[tag+'count']===temp_parent){break;}
temp_parent=this.tags[temp_parent+'parent'];}
if(temp_parent){this.indent_level=this.tags[tag+this.tags[tag+'count']];this.tags.parent=this.tags[temp_parent+'parent'];}
delete this.tags[tag+this.tags[tag+'count']+'parent'];delete this.tags[tag+this.tags[tag+'count']];if(this.tags[tag+'count']==1){delete this.tags[tag+'count'];}
else{this.tags[tag+'count']--;}}}
this.get_tag=function(){var input_char='';var content=[];var space=false;do{if(this.pos>=this.input.length){return content.length?content.join(''):['','TK_EOF'];}
input_char=this.input.charAt(this.pos);this.pos++;this.line_char_count++;if(this.Utils.in_array(input_char,this.Utils.whitespace)){space=true;this.line_char_count--;continue;}
if(input_char==="'"||input_char==='"'){if(!content[1]||content[1]!=='!'){input_char+=this.get_unformatted(input_char);space=true;}}
if(input_char==='='){space=false;}
if(content.length&&content[content.length-1]!=='='&&input_char!=='>'&&space){if(this.line_char_count>=this.max_char){this.print_newline(false,content);this.line_char_count=0;}
else{content.push(' ');this.line_char_count++;}
space=false;}
content.push(input_char);}while(input_char!=='>');var tag_complete=content.join('');var tag_index;if(tag_complete.indexOf(' ')!=-1){tag_index=tag_complete.indexOf(' ');}
else{tag_index=tag_complete.indexOf('>');}
var tag_check=tag_complete.substring(1,tag_index).toLowerCase();if(tag_complete.charAt(tag_complete.length-2)==='/'||this.Utils.in_array(tag_check,this.Utils.single_token)){this.tag_type='SINGLE';}
else if(tag_check==='script'){this.record_tag(tag_check);this.tag_type='SCRIPT';}
else if(tag_check==='style'){this.record_tag(tag_check);this.tag_type='STYLE';}
else if(this.Utils.in_array(tag_check,unformatted)){var comment=this.get_unformatted('</'+tag_check+'>',tag_complete);content.push(comment);this.tag_type='SINGLE';}
else if(tag_check.charAt(0)==='!'){if(tag_check.indexOf('[if')!=-1){if(tag_complete.indexOf('!IE')!=-1){var comment=this.get_unformatted('-->',tag_complete);content.push(comment);}
this.tag_type='START';}
else if(tag_check.indexOf('[endif')!=-1){this.tag_type='END';this.unindent();}
else if(tag_check.indexOf('[cdata[')!=-1){var comment=this.get_unformatted(']]>',tag_complete);content.push(comment);this.tag_type='SINGLE';}
else{var comment=this.get_unformatted('-->',tag_complete);content.push(comment);this.tag_type='SINGLE';}}
else{if(tag_check.charAt(0)==='/'){this.retrieve_tag(tag_check.substring(1));this.tag_type='END';}
else{this.record_tag(tag_check);this.tag_type='START';}
if(this.Utils.in_array(tag_check,this.Utils.extra_liners)){this.print_newline(true,this.output);}}
return content.join('');}
this.get_unformatted=function(delimiter,orig_tag){if(orig_tag&&orig_tag.indexOf(delimiter)!=-1){return'';}
var input_char='';var content='';var space=true;do{if(this.pos>=this.input.length){return content;}
input_char=this.input.charAt(this.pos);this.pos++
if(this.Utils.in_array(input_char,this.Utils.whitespace)){if(!space){this.line_char_count--;continue;}
if(input_char==='\n'||input_char==='\r'){content+='\n';this.line_char_count=0;continue;}}
content+=input_char;this.line_char_count++;space=true;}while(content.indexOf(delimiter)==-1);return content;}
this.get_token=function(){var token;if(this.last_token==='TK_TAG_SCRIPT'||this.last_token==='TK_TAG_STYLE'){var type=this.last_token.substr(7)
token=this.get_contents_to(type);if(typeof token!=='string'){return token;}
return[token,'TK_'+type];}
if(this.current_mode==='CONTENT'){token=this.get_content();if(typeof token!=='string'){return token;}
else{return[token,'TK_CONTENT'];}}
if(this.current_mode==='TAG'){token=this.get_tag();if(typeof token!=='string'){return token;}
else{var tag_name_type='TK_TAG_'+this.tag_type;return[token,tag_name_type];}}}
this.get_full_indent=function(level){level=this.indent_level+level||0;if(level<1)
return'';return Array(level+1).join(this.indent_string);}
this.printer=function(js_source,indent_character,indent_size,max_char,brace_style){this.input=js_source||'';this.output=[];this.indent_character=indent_character;this.indent_string='';this.indent_size=indent_size;this.brace_style=brace_style;this.indent_level=0;this.max_char=max_char;this.line_char_count=0;for(var i=0;i<this.indent_size;i++){this.indent_string+=this.indent_character;}
this.print_newline=function(ignore,arr){this.line_char_count=0;if(!arr||!arr.length){return;}
if(!ignore){while(this.Utils.in_array(arr[arr.length-1],this.Utils.whitespace)){arr.pop();}}
arr.push('\n');for(var i=0;i<this.indent_level;i++){arr.push(this.indent_string);}}
this.print_token=function(text){this.output.push(text);}
this.indent=function(){this.indent_level++;}
this.unindent=function(){if(this.indent_level>0){this.indent_level--;}}}
return this;}
multi_parser=new Parser();multi_parser.printer(html_source,indent_character,indent_size,max_char,brace_style);while(true){var t=multi_parser.get_token();multi_parser.token_text=t[0];multi_parser.token_type=t[1];if(multi_parser.token_type==='TK_EOF'){break;}
switch(multi_parser.token_type){case'TK_TAG_START':multi_parser.print_newline(false,multi_parser.output);multi_parser.print_token(multi_parser.token_text);multi_parser.indent();multi_parser.current_mode='CONTENT';break;case'TK_TAG_STYLE':case'TK_TAG_SCRIPT':multi_parser.print_newline(false,multi_parser.output);multi_parser.print_token(multi_parser.token_text);multi_parser.current_mode='CONTENT';break;case'TK_TAG_END':if(multi_parser.last_token==='TK_CONTENT'&&multi_parser.last_text===''){var tag_name=multi_parser.token_text.match(/\w+/)[0];var tag_extracted_from_last_output=multi_parser.output[multi_parser.output.length-1].match(/<\s*(\w+)/);if(tag_extracted_from_last_output===null||tag_extracted_from_last_output[1]!==tag_name)
multi_parser.print_newline(true,multi_parser.output);}
multi_parser.print_token(multi_parser.token_text);multi_parser.current_mode='CONTENT';break;case'TK_TAG_SINGLE':multi_parser.print_newline(false,multi_parser.output);multi_parser.print_token(multi_parser.token_text);multi_parser.current_mode='CONTENT';break;case'TK_CONTENT':if(multi_parser.token_text!==''){multi_parser.print_token(multi_parser.token_text);}
multi_parser.current_mode='TAG';break;case'TK_STYLE':case'TK_SCRIPT':if(multi_parser.token_text!==''){multi_parser.output.push('\n');var text=multi_parser.token_text;if(multi_parser.token_type=='TK_SCRIPT'){var _beautifier=typeof js_beautify=='function'&&js_beautify;}else if(multi_parser.token_type=='TK_STYLE'){var _beautifier=typeof css_beautify=='function'&&css_beautify;}
if(options.indent_scripts=="keep"){var script_indent_level=0;}else if(options.indent_scripts=="separate"){var script_indent_level=-multi_parser.indent_level;}else{var script_indent_level=1;}
var indentation=multi_parser.get_full_indent(script_indent_level);if(_beautifier){text=_beautifier(text.replace(/^\s*/,indentation),options);}else{var white=text.match(/^\s*/)[0];var _level=white.match(/[^\n\r]*$/)[0].split(multi_parser.indent_string).length-1;var reindent=multi_parser.get_full_indent(script_indent_level-_level);text=text.replace(/^\s*/,indentation).replace(/\r\n|\r|\n/g,'\n'+reindent).replace(/\s*$/,'');}
if(text){multi_parser.print_token(text);multi_parser.print_newline(true,multi_parser.output);}}
multi_parser.current_mode='TAG';break;}
multi_parser.last_token=multi_parser.token_type;multi_parser.last_text=multi_parser.token_text;}
return multi_parser.output.join('');}})();