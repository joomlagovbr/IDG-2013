/**
 * Akeeba Backup
 * The modular PHP5 site backup software solution
 * This file contains the jQuery-based client-side user interface logic
 * @package akeebaui
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU GPL version 3 or, at your option, any later version
 * @version $Id$
 **/

/**
 * Setup (required for Joomla! 3)
 */
if(typeof(akeeba) == 'undefined') {
	var akeeba = {};
}
if(typeof(akeeba.jQuery) == 'undefined') {
	akeeba.jQuery = jQuery.noConflict();
}

/** @var Root URI for theme files */
var akeeba_ui_theme_root = "";

/** @var The AJAX proxy URL */
var akeeba_ajax_url = "";

/** @var Current backup job's tag */
var akeeba_backup_tag = 'backend';

/** @var The callback function to call on error */
var akeeba_error_callback = dummy_error_handler;

/** @var A URL to return to upon successful backup */
var akeeba_return_url = '';

/** @var Is this the Site Transfer Wizard? If so, we'll ask before redirecting */
var akeeba_is_stw = false;

/** @var System restore point setup information */
var akeeba_srp_info = {};

/** @var The translation strings used in the GUI */
var akeeba_translations = new Array();
akeeba_translations['UI-BROWSE'] = 'Browse...';
akeeba_translations['UI-CONFIG'] = 'Configure...';
akeeba_translations['UI-LASTRESPONSE'] = 'Last server response %ss ago';
akeeba_translations['UI-ROOT'] = '&lt;root&gt;';
akeeba_translations['UI-ERROR-FILTER'] = 'An error occured while applying the filter for "%s"';
akeeba_translations['UI-STW-CONTINUE'] = 'Transfer of your site is almost complete. Click on the OK button to go to your new site, run the restoration script and finish the restoration of your database and site setup. Remember to click on the link to remove the installation directory on the last page of the restoration script.';

/** @var Engine definitions array */
var akeeba_engines = new Array();

/** @var Installers definitions array */
var akeeba_installers = new Array();

/** @var The function used to show the directory browser. Takes two params: starting_directory, input_element */
var akeeba_browser_hook = null;

/** @var An array of domains and descriptions, used during backup */
var akeeba_domains = null;

/** @var A function which causes the visual comment editor to save its contents */
var akeeba_comment_editor_save = null;

/** @var Maximum execution time per step (in msec) */
var akeeba_max_execution_time = 14000;

/** @var Maximum execution time per step bias (in percentage units, 0 to 100) */
var akeeba_time_bias = 75;

/** @var Used for filter reset operations */
var akeeba_current_root = '';

/** @var iFrame pseudo-AJAX success callback */
var akeeba_iframecb_success = null;

/** @var iFrame pseudo-AJAX error callback */
var akeeba_iframecb_error = null;

/** @var iFrame pseudo-AJAX IFRAME element */
var akeeba_iframe = null;

/** @var Should I use IFRAME instead of regular AJAX calls? */
var akeeba_use_iframe = false;

//=============================================================================
//Akeeba Backup -- Common functions
//=============================================================================

/**
 * An extremely simple error handler, dumping error messages to screen
 * @param error The error message string
 */
function dummy_error_handler(error)
{
	alert("An error has occured\n"+error);
}

/**
 * Poor man's AJAX, using IFRAME elements
 * @param data An object with the query data, e.g. a serialized form
 * @param successCallback A function accepting a single object parameter, called on success
 */
function doIframeCall(data, successCallback, errorCallback)
{
	(function($) {
		akeeba_iframecb_success = successCallback;
		akeeba_iframecb_error = errorCallback;
		akeeba_iframe = document.createElement('iframe');
		$(akeeba_iframe)
			.css({
				'display'		: 'none',
				'visibility'	: 'hidden',
				'height'		: '1px'
			})
			.attr('onload','cbIframeCall()')
			.insertAfter('#response-timer');
		var url = akeeba_ajax_url + '&' + $.param(data);
		$(akeeba_iframe).attr('src',url);
	})(akeeba.jQuery);
}

/**
 * Poor man's AJAX, using IFRAME elements: the callback function
 */
function cbIframeCall()
{
	(function($) {
		// Get the contents of the iFrame
		var iframeDoc = null;
		if (akeeba_iframe.contentDocument) {
			iframeDoc = akeeba_iframe.contentDocument; // The rest of the world
		} else {
			iframeDoc = akeeba_iframe.contentWindow.document; // IE on Windows
		}
		var msg = iframeDoc.body.innerHTML;

		// Dispose of the iframe
		$(akeeba_iframe).remove();
		akeeba_iframe = null;

		// Start processing the message
		var junk = null;
		var message = "";

		// Get rid of junk before the data
		var valid_pos = msg.indexOf('###');
		if( valid_pos == -1 ) {
			// Valid data not found in the response
			msg = 'Invalid AJAX data: ' + msg;
			if(akeeba_iframecb_error == null)
			{
				if(akeeba_error_callback != null)
				{
					akeeba_error_callback(msg);
				}
			}
			else
			{
				akeeba_iframecb_error(msg);
			}
			return;
		} else if( valid_pos != 0 ) {
			// Data is prefixed with junk
			junk = msg.substr(0, valid_pos);
			message = msg.substr(valid_pos);
		}
		else
		{
			message = msg;
		}
		message = message.substr(3); // Remove triple hash in the beginning

		// Get of rid of junk after the data
		var valid_pos = message.lastIndexOf('###');
		message = message.substr(0, valid_pos); // Remove triple hash in the end

		try {
			var data = JSON.parse(message);
		} catch(err) {
			var msg = err.message + "\n<br/>\n<pre>\n" + message + "\n</pre>";
			if(akeeba_iframecb_error == null)
			{
				if(akeeba_error_callback != null)
				{
					akeeba_error_callback(msg);
				}
			}
			else
			{
				akeeba_iframecb_error(msg);
			}
			return;
		}

		// Call the callback function
		akeeba_iframecb_success(data);
	})(akeeba.jQuery);
}

/**
 * Performs an AJAX request and returns the parsed JSON output.
 * The global akeeba_ajax_url is used as the AJAX proxy URL.
 * If there is no errorCallback, the global akeeba_error_callback is used.
 * @param data An object with the query data, e.g. a serialized form
 * @param successCallback A function accepting a single object parameter, called on success
 * @param errorCallback A function accepting a single string parameter, called on failure
 */
function doAjax(data, successCallback, errorCallback, useCaching, timeout)
{
	if(akeeba_use_iframe) {
		doIframeCall(data, successCallback, errorCallback)
		return;
	}

	if(useCaching == null) useCaching = true;

	if(!useCaching) {
		var now = new Date().getTime() / 1000;
		var s = parseInt(now, 10);
		var microtime = Math.round((now - s) * 1000) / 1000;
		data._utterUselessCrapRequiredByStupidBrowsersToStopCachingXHR = microtime;
	}

	if(timeout == null) timeout = 600000;
	(function($) {
		var structure =
		{
			type: "POST",
			url: akeeba_ajax_url,
			cache: false,
			data: data,
			timeout: 600000,
			success: function(msg) {
				// Initialize
				var junk = null;
				var message = "";

				// Get rid of junk before the data
				var valid_pos = msg.indexOf('###');
				if( valid_pos == -1 ) {
					// Valid data not found in the response
					msg = 'Invalid AJAX data: ' + msg;
					if(errorCallback == null)
					{
						if(akeeba_error_callback != null)
						{
							akeeba_error_callback(msg);
						}
					}
					else
					{
						errorCallback(msg);
					}
					return;
				} else if( valid_pos != 0 ) {
					// Data is prefixed with junk
					junk = msg.substr(0, valid_pos);
					message = msg.substr(valid_pos);
				}
				else
				{
					message = msg;
				}
				message = message.substr(3); // Remove triple hash in the beginning

				// Get of rid of junk after the data
				var valid_pos = message.lastIndexOf('###');
				message = message.substr(0, valid_pos); // Remove triple hash in the end

				try {
					var data = JSON.parse(message);
				} catch(err) {
					var msg = err.message + "\n<br/>\n<pre>\n" + message + "\n</pre>";
					if(errorCallback == null)
					{
						if(akeeba_error_callback != null)
						{
							akeeba_error_callback(msg);
						}
					}
					else
					{
						errorCallback(msg);
					}
					return;
				}

				// Call the callback function
				successCallback(data);
			},
			error: function(Request, textStatus, errorThrown) {
				var message = '<strong>AJAX Loading Error</strong><br/>HTTP Status: '+Request.status+' ('+Request.statusText+')<br/>';
				message = message + 'Internal status: '+textStatus+'<br/>';
				message = message + 'XHR ReadyState: ' + Request.readyState + '<br/>';
				message = message + 'Raw server response:<br/>'+Request.responseText;

				if(errorCallback == null)
				{
					if(akeeba_error_callback != null)
					{
						akeeba_error_callback(message);
					}
				}
				else
				{
					errorCallback(message);
				}
			}
		};
		if(useCaching)
		{
			$.manageAjax.add('akeeba-ajax-profile', structure);
		}
		else
		{
			$.ajax( structure );
		}
	})(akeeba.jQuery);
}

//=============================================================================
//Akeeba Backup -- Configuration page
//=============================================================================

/**
 * Parses the JSON decoded data object defining engine and GUI parameters for the
 * configuration page
 * @param data The nested objects of engine and GUI definitions
 */
function parse_config_data(data)
{
	parse_config_engine_data(data.engines);
	parse_config_installer_data(data.installers);
	parse_config_gui_data(data.gui);
}

/**
 * Parses the engine definition data passed from Akeeba Engine to the UI via JSON
 * @param data Nested objects of engine definitions
 */
function parse_config_engine_data(data)
{
	// As simple as it can possibly be!
	akeeba_engines = data;
}

/**
 * Parses the installer definition data passed from Akeeba Engine to the UI via JSON
 * @param data Nested objects of installer definitions
 */
function parse_config_installer_data(data)
{
	akeeba_installers = data;
}

/**
 * Parses the main configuration GUI definition, generating the on-page widgets
 * @param data The nested objects of the GUI definition ('gui' key of JSON data)
 * @param rootnode The jQuery extended root DOM element in which to create the widgets
 */
function parse_config_gui_data(data, rootnode)
{
	(function($) {
		if(rootnode == null)
		{
			// The default root node is the form itself
			rootnode = $('#akeebagui');
		}

		// Begin by slashing contents of the akeebagui DIV
		rootnode.empty();

		// This is the workhorse, looping through groupdefs and creating HTML elements
		var group_id = 0;
		$.each(data,function(headertext, groupdef) {
			// Loop for each group definition
			group_id++;

			// Create a fieldset container
			var container = $( document.createElement('div') );
			container
				.addClass('well')
				.appendTo( rootnode );

			// Create a group header
			var header = $( document.createElement('h4') );
			header.attr('id', 'auigrp_'+rootnode.attr('id')+'_'+group_id);
			header.html(headertext);
			header.appendTo(container);

			// Loop each element
			$.each(groupdef, function(config_key, defdata){
				// Parameter ID
				var current_id = 'var['+config_key+']';

				if( (defdata['type'] != 'hidden') && (defdata['type'] != 'none') )
				{
					// Option row DIV
					var row_div = $(document.createElement('div')).addClass('akeeba-ui-optionrow control-group');
					row_div.appendTo(container);

					// Create label
					var label = $(document.createElement('label'));
					label.addClass('control-label')
						.attr('for', current_id)
						.html( defdata['title'] )
						;
					if(defdata['description']) {
						label
							.attr('rel', 'popover')
							.attr('data-original-title', defdata['title'])
							.attr('data-content', defdata['description'])
					}
					if(defdata['bold']) label.css('font-weight','bold');
					label.appendTo( row_div );
				}

				// Create GUI representation based on type
				var controlWrapper = $(document.createElement('div')).addClass('controls');

				switch( defdata['type'] )
				{
					// A do-not-display field
					case 'none':
						break;

					// A hidden field
					case 'hidden':
						var hiddenfield = $(document.createElement('input')).attr({
							type:		'hidden',
							id:			current_id,
							name:		current_id,
							size:		'40',
							value:		defdata['default']
						});
						hiddenfield.appendTo( container );
						break;

					// A separator
					case 'separator':
						var separator = $(document.createElement('div')).addClass('akeeba_ui_separator');
						separator.appendTo( container );
						break;

					// Checks if the field data is empty and renders the data in a hidden field
					case 'checkandhide':
						// Container for selection & button
						var span = $(document.createElement('span'));
						span.appendTo( controlWrapper );
						controlWrapper.appendTo( row_div );

						var hiddenfield = $(document.createElement('input')).attr({
							type:		'hidden',
							id:			current_id,
							name:		current_id,
							size:		'40',
							value:		defdata['default']
						});
						hiddenfield.appendTo( span );

						var myLabel = '';
						if(defdata['default'] == '') {
							myLabel = defdata['labelempty'];
						} else {
							myLabel = defdata['labelnotempty'];
						}
						var span2 = $(document.createElement('span'));
						span2
							.text(myLabel)
							.appendTo(span)
							.data('labelempty',defdata['labelempty'])
							.data('labelnotempty', defdata['labelnotempty']);
						break;

					// An installer selection
					case 'installer':
						// Create the select element
						var editor = $(document.createElement('select')).attr({
							id:			current_id,
							name:		current_id
						});
						$.each(akeeba_installers, function(key, element){
							var option = $(document.createElement('option')).attr('value', key).html(element.name);
							if( defdata['default'] == key ) option.attr('selected',1);
							option.appendTo( editor );
						});

						editor.appendTo( controlWrapper );
						controlWrapper.appendTo( row_div );

						break;

					// An engine selection
					case 'engine':
						var engine_type = defdata['subtype'];
						if( akeeba_engines[engine_type] == null ) break;

						// Container for engine parameters, initially hidden
						var engine_config_container = $(document.createElement('div')).attr({
							id:			config_key+'_config'
						})
							.addClass('ui-helper-hidden well')
							.appendTo( controlWrapper );

						// Create the select element
						var editor = $(document.createElement('select')).attr({
							id:			current_id,
							name:		current_id
						});
						$.each(akeeba_engines[engine_type], function(key, element){
							var option = $(document.createElement('option')).attr('value', key).html(element.information.title);
							if( defdata['default'] == key ) option.attr('selected',1);
							option.appendTo( editor );
						});
						editor.bind("change",function(e){
							// When the selection changes, we have to repopulate the config container
							// First, save any changed values
							var old_values = new Object;
							$(engine_config_container).find('input').each(function(i){
								if( $(this).attr('type') == 'checkbox' )
								{
									old_values[$(this).attr('id')] = $(this).is(':checked');
								}
								else
								{
									old_values[$(this).attr('id')] = $(this).val();
								}
							});
							// Create the new interface
							var new_engine = $(this).val();
							var enginedef = akeeba_engines[engine_type][new_engine];
							var enginetitle = enginedef.information.title;
							var new_data = new Object;
							var engine_params = enginedef.parameters;
							new_data[enginetitle] = engine_params;
							parse_config_gui_data(new_data, engine_config_container);
							$(engine_config_container)
								.find('legend:first')
								.after(
									$(document.createElement('p'))
									.addClass('alert alert-info')
									.html(enginedef.information.description)
								);
							// Reapply changed values
							engine_config_container.find('input').each(function(i){
								var old = old_values[$(this).attr('id')];
								if( (old != null) && (old != undefined) )
								{
									if( $(this).attr('type') == 'checkbox' )
									{$(this).attr('checked', old);}
									else if ( $(this).attr('type') == 'hidden' )
									{
										$(this).next().next().slider( 'value' , old );
									}
									else
									{$(this).val(old);}
								}
							});
						});

						// Add a configuration show/hide button
						var button = $(document.createElement('button'))
							.html(akeeba_translations['UI-CONFIG'])
							.addClass('btn btn-mini');
						var icon = $(document.createElement('i'))
							.addClass('icon-wrench')
							.prependTo(button);
						button.bind('click', function(e){
							engine_config_container.toggleClass('ui-helper-hidden');
							e.preventDefault();
						});

						var spacerSpan = $(document.createElement('span')).html('&nbsp;');

						button.prependTo( controlWrapper );
						spacerSpan.prependTo( controlWrapper );
						editor.prependTo( controlWrapper );

						controlWrapper.appendTo( row_div );

						// Populate config container with the default engine data
						if(akeeba_engines[engine_type][defdata['default']] != null)
						{
							var new_engine = defdata['default'];
							var enginedef = akeeba_engines[engine_type][new_engine];
							var enginetitle = enginedef.information.title;
							var new_data = new Object;
							var engine_params = enginedef.parameters;
							new_data[enginetitle] = engine_params;

							// Is it a protected field?
							if(defdata['protected'] != 0) {
								var titleSpan = $(document.createElement('span'))
									.text(enginetitle);
								titleSpan.prependTo(span);
								editor.css('display','none');
							}

							parse_config_gui_data(new_data, engine_config_container);
							$(engine_config_container)
							.find('legend:first')
							.after(
								$(document.createElement('p'))
								.html(enginedef.information.description)
							);
						}
						break;

					// A text box with an option to launch a browser
					case 'browsedir':
						var editor = $(document.createElement('input')).attr({
							type:		'text',
							id:			current_id,
							name:		current_id,
							size:		'30',
							value:		defdata['default']
						});

						var button = $(document.createElement('button'))
							.attr('title',akeeba_translations['UI-BROWSE'])
							.html('&nbsp;')
							.addClass('btn');

						var icon = $(document.createElement('i'))
							.addClass('icon-folder-open')
							.prependTo(button);

						button.bind('click',function(event){
							event.preventDefault();
							if( akeeba_browser_hook != null ) akeeba_browser_hook( editor.val(), editor );
						});

						var span = $(document.createElement('span')).addClass('input-append');

						editor.appendTo( span );
						button.appendTo( span );

						span.appendTo( controlWrapper )

						controlWrapper.appendTo( row_div );
						break;

					// A text box with a button
					case 'buttonedit':
						var editortype = defdata['editortype'] == 'hidden' ? 'hidden' : 'text';

						var editor = $(document.createElement('input')).attr({
							type:		editortype,
							id:			current_id,
							name:		current_id,
							size:		'30',
							value:		defdata['default']
						});
						if(defdata['editordisabled'] == '1') {
							editor.attr('disabled', 'disabled');
						}

						//var button = $(document.createElement('button')).addClass('ui-state-default').html(akeeba_translations[defdata['buttontitle']]);
						var button = $(document.createElement('button'))
							.html(akeeba_translations[defdata['buttontitle']])
							.addClass('btn');
						button.bind('click',function(event){
							event.preventDefault();
							var hook = defdata['hook'];
							try {
								eval(hook+'()');
							} catch(err) {}
						});

						var span = $(document.createElement('span')).addClass('input-append');
						editor.appendTo( span );
						button.appendTo( span );

						span.appendTo( controlWrapper );
						controlWrapper.appendTo( row_div );
						break;

					// A drop-down list
					case 'enum':
						var editor = $(document.createElement('select')).attr({
							id:			current_id,
							name:		current_id
						});
						// Create and append options
						var enumvalues = defdata['enumvalues'].split("|");
						var enumkeys = defdata['enumkeys'].split("|");

						$.each(enumvalues, function(counter, value){
							var item_description = enumkeys[counter];
							var option = $(document.createElement('option')).attr('value', value).html(item_description);
							if(value == defdata['default']) option.attr('selected',1);
							option.appendTo( editor );
						});

						editor.appendTo( controlWrapper );
						controlWrapper.appendTo( row_div );
						break;

					// A simple single-line, unvalidated text box
					case 'string':
						var editor = $(document.createElement('input')).attr({
							type:		'text',
							id:			current_id,
							name:		current_id,
							size:		'40',
							value:		defdata['default']
						});
						editor.appendTo( controlWrapper );
						controlWrapper.appendTo( row_div );
						break;

					// A simple single-line, unvalidated password box
					case 'password':
						var editor = $(document.createElement('input')).attr({
							type:			'password',
							id:				current_id,
							name:			current_id,
							size:			'40',
							value:			defdata['default'],
							autocomplete:	'off'
						});
						editor.appendTo( controlWrapper );
						controlWrapper.appendTo( row_div );
						break;

					case 'integer':
						// Hidden form element with the real value
						var hidden_input = $(document.createElement('input')).attr({
							id:		config_key,
							name:	current_id,
							type:	'hidden'
						}).val(defdata['default']);
						// Hidden custom value element
						var custom = $(document.createElement('input'))
							.attr('type', 'text')
							.attr('size', '10')
							.attr('id',config_key+'_custom')
							.css('display','none')
							.css('margin-left', '6px')
							.addClass('input-mini');
						custom.blur(function(){
							var value = parseFloat(custom.val());
							value = value * defdata['scale'];
							if(value < defdata['min']) {
								value = defdata['min'];
							} else if(value > defdata['max']) {
								value = defdata['max'];
							}
							hidden_input.val(value);
							var newValue = value / defdata['scale'];
							custom.val(newValue.toFixed(2));
						});
						// Drop-down
						var dropdown = $(document.createElement('select')).attr({
							id:			config_key+'_dropdown',
							name:		config_key+'_dropdown'
						}).addClass('input-small');
						// Create and append options
						var enumvalues = defdata['shortcuts'].split("|");
						var quantizer = defdata['scale'];
						var isPresetOption = false;
						$.each(enumvalues, function(counter, value){
							var item_description = value / quantizer;
							var option = $(document.createElement('option')).attr('value', value).html(item_description.toFixed(2));
							if(value == defdata['default']) {
								option.attr('selected',1);
								isPresetOption = true;
							}
							option.appendTo( dropdown );
						});
						var option = $(document.createElement('option')).attr('value', -1).html('Custom...');
						if(!isPresetOption) {
							option.attr('selected',1);
							custom
								.val( (defdata['default']/defdata['scale']).toFixed(2) )
								.show();
						}
						option.appendTo( dropdown );
						// Rig the dropdown
						dropdown.change(function(){
							var value = dropdown.val();
							if(value == -1) {
								custom
									.val( (defdata['default']/defdata['scale']).toFixed(2) )
									.show()
									.focus();
								custom.next().addClass('add-on');
							} else {
								hidden_input.val(value);
								custom.hide();
								custom.next().removeClass('add-on');
							}
						});
						// Label
						var uom = defdata['uom'];
						if( (typeof(uom) != 'string') || empty(uom) ) {
							uom = '';

							dropdown.appendTo(controlWrapper);
							custom.appendTo(controlWrapper);
						} else {
							var inputAppendWrapper = $(document.createElement('div'))
								.addClass('input-append');
							var label = $(document.createElement('span')).
								text(' '+uom);
							if(!isPresetOption) {
								label.addClass('add-on');
							}
							dropdown.appendTo(inputAppendWrapper);
							custom.appendTo(inputAppendWrapper);
							label.appendTo(inputAppendWrapper);
							inputAppendWrapper.appendTo(controlWrapper);
						}


						hidden_input.appendTo(controlWrapper);

						controlWrapper.appendTo( row_div );

						break;

					// A toggle button
					case 'bool':
						var wrap_div = $(document.createElement('div')).addClass('akeeba-ui-checkbox');
						// Necessary hack: when the checkbox is unchecked, nothing gets submitted.
						// We need the hidden input to submit a zero value.
						$(document.createElement('input')).attr({
							name:			current_id,
							type:			'hidden',
							value:			0
						}).appendTo( wrap_div );
						// Create a checkbox
						var editor = $(document.createElement('input')).attr({
							name:			current_id,
							id:				current_id,
							type:			'checkbox',
							value:			1
						});
						if( defdata['default'] != 0 ) editor.attr('checked','checked');
						editor.appendTo( wrap_div );
						wrap_div.appendTo( controlWrapper );
						controlWrapper.appendTo( row_div );
						break;

					// Button with a custom hook function
					case 'button':
						// Create the button
						var hook = defdata['hook'];
						var labeltext = label.html();
						var editor = $(document.createElement('button'))
							.attr('id', current_id).html(labeltext)
							.addClass('btn');
						label.html('&nbsp;');
						editor.bind('click', function(e){
							e.preventDefault();
							try {
								eval(hook+'()');
							} catch(err) {}
						});
						editor.appendTo( controlWrapper );
						controlWrapper.appendTo( row_div );
						break;

					// An extension is being used
					default:
						var method = 'akeeba_render_'+defdata['type'];
						var fn = window[method];
						fn(config_key, defdata, label, row_div);
				}
			});

		});
	})(akeeba.jQuery);
}

//=============================================================================
//Akeeba Backup -- Backup Now page
//=============================================================================

function set_ajax_timer()
{
	setTimeout('akeeba_ajax_timer_tick()', 10);
}

function akeeba_ajax_timer_tick()
{
	(function($){
		doAjax({
			// Data to send to AJAX
			'ajax'	: 'step',
			'tag'	: akeeba_backup_tag
		}, backup_step, backup_error, false );
	})(akeeba.jQuery);
}

function start_timeout_bar(max_allowance, bias)
{
	(function($) {
		var lastResponseSeconds = 0;
		$('#response-timer div.text').everyTime(1000, 'lastReponse', function(){
			lastResponseSeconds++;
			var lastText = akeeba_translations['UI-LASTRESPONSE'].replace('%s', lastResponseSeconds.toFixed(0));
			$('#response-timer div.text').html(lastText);
		});

	})(akeeba.jQuery);
}

function reset_timeout_bar()
{
	(function($){
		$('#response-timer div.text').stopTime();
		/*
		$('#response-timer div.color-overlay').stop(true);
		$('#response-timer div.color-overlay')
		.css({
			backgroundColor: '#00cc00',
			width: '1px'
		});
		*/
		var lastText = akeeba_translations['UI-LASTRESPONSE'].replace('%s', '0');
		$('#response-timer div.text').html(lastText);
	})(akeeba.jQuery);
}

function render_backup_steps(active_step)
{
	(function($){
		var normal_class = 'label-success';
		if( active_step == '' ) normal_class = '';

		$('#backup-steps').html('');
		$.each(akeeba_domains, function(counter, element){
			var step = $(document.createElement('div'))
				.addClass('label')
				.html(element[1])
				.data('domain',element[0])
				.appendTo('#backup-steps');

			if(step.data('domain') == active_step )
			{
				normal_class = '';
				this_class = 'label-info';
			}
			else
			{
				this_class = normal_class;
			}
			step.addClass(this_class);
		});
	})(akeeba.jQuery);
}

function backup_start()
{
	(function($){
		// Check for AVG Link Scanner
		if(window.AVGRUN) {
			var r = confirm('You are running AVG Antivirus with Link Scanner enabled. This is known to cause backup issues. Please disable the Link Scanner feature if you run into any problems.\n\nAre you sure you want to continue despite that warning?');
			if(!r) return;
		}

		// Save the editor contents
		try {
			if( akeeba_comment_editor_save != null ) akeeba_comment_editor_save();
		} catch(err) {
			// If the editor failed to save its content, just move on and ignore the error
			$('#comment').val("");
		}
		// Get encryption key (if applicable)
		var jpskey = '';
		try {
			jpskey = $('#jpskey').val();
		} catch(err) {
			jpskey = '';
		}
		var angiekey = '';
		try {
			angiekey = $('#angiekey').val();
		} catch(err) {
			angiekey = '';
		}
		// Hide the backup setup
		$('#backup-setup').hide("fast");
		// Show the backup progress
		$('#backup-progress-pane').show("fast");

        // Let's check if we have a password even if we didn't set it in the profile (maybe a password manager filled it?)
        if ((angiekey) && (config_angie_key == ''))
        {
            $('#angie-password-warning').show();
        }

		// Initialise Piecon
		Piecon.setOptions({
			color: '#333333',
			background: '#e0e0e0',
			shadow: '#000000',
			fallback: 'force'
		});

		// Initialize steps
		render_backup_steps('');
		// Start the response timer
		start_timeout_bar(akeeba_max_execution_time, akeeba_time_bias);
		// Perform Ajax request
		akeeba_backup_tag = akeeba_srp_info.tag;

                var ajax_request = {
                    // Data to send to AJAX
                    'ajax': 'start',
                    description: $('#backup-description').val(),
                    comment: $('#comment').val(),
                    jpskey: jpskey,
                    angiekey: angiekey
                };

                ajax_request = array_merge(ajax_request, akeeba_srp_info);

		doAjax(ajax_request, backup_step, backup_error, false );
	})(akeeba.jQuery);
}

function backup_step(data)
{
	try {
		console.debug('Running backup step');
		console.log(data);
	} catch(e) {
	}

	// Update visual step progress from active domain data
	reset_timeout_bar();
	render_backup_steps(data.Domain);
	(function($){
		// Update percentage display
		var percentageText = data.Progress + '%';
		//$('#backup-percentage div.text').html(percentageText);
		$('#backup-percentage div.bar').css({
			'width':			data.Progress+'%'
		});

		if (data.Progress >= 100)
		{
			Piecon.setProgress(99);
		}
		else
		{
			Piecon.setProgress(data.Progress);
		}

		// Update step/substep display
		$('#backup-step').html(data.Step);
		$('#backup-substep').html(data.Substep);
		// Do we have warnings?
		if( data.Warnings.length > 0 )
		{
			$('#backup-percentage').addClass('progress-warning');
			$.each(data.Warnings, function(i, warning){
				var newDiv = $(document.createElement('div'))
					.html(warning)
					.appendTo( $('#warnings-list') );
			});
			if( $('#backup-warnings-panel').is(":hidden") )
			{
				$('#backup-warnings-panel').show('fast');
			}
		}
		// Do we have errors?
		var error_message = data.Error;
		if(error_message != '')
		{
			// Uh-oh! An error has occurred.
			backup_error(error_message);
			return;
		}
		else
		{
			// No errors. Good! Are we finished yet?
			if(data["HasRun"] == 1)
			{
				// Yes. Show backup completion page.
				try {
					Piecon.reset();
				} catch (e) {}
				backup_complete();
			}
			else
			{
				// No. Set the backup tag
				akeeba_backup_tag = akeeba_backup_tag;
				if(empty(akeeba_backup_tag)) akeeba_backup_tag = 'backend';
				// Start the response timer...
				start_timeout_bar(akeeba_max_execution_time, akeeba_time_bias);
				// ...and send an AJAX command
				set_ajax_timer();
			}
		}
	})(akeeba.jQuery);
}

function backup_error(message)
{
	(function($){
		// Make sure the timer is stopped
		reset_timeout_bar();
		// Hide progress and warnings
		$('#backup-progress-pane').hide("fast");
		$('#backup-warnings-panel').hide("fast");
		// Setup and show error pane
		$('#backup-error-message').html(message);
		$('#error-panel').show();
	})(akeeba.jQuery);
}

function backup_complete()
{
	(function($){
		// Make sure the timer is stopped
		reset_timeout_bar();
		// Hide progress
		$('#backup-progress-pane').hide("fast");
		// Show finished pane
		$('#backup-complete').show();
		$('#backup-warnings-panel').width('100%');

		// Proceed to the return URL if it is set
		if(akeeba_return_url != '')
		{
			// If it's the Site Transfer Wizard, show a message first
			if(akeeba_is_stw) {
				alert(akeeba_translations['UI-STW-CONTINUE']);
			}

			window.location = akeeba_return_url;
		}
	})(akeeba.jQuery);
}

function akeeba_restore_backup_defaults()
{
	(function($){
		$('#backup-description').val(default_short_descr);

		if($('#angiekey').length)
		{
			$('#angiekey').val(config_angie_key);
		}

		if($('#jpskey').length)
		{
			$('#jpskey').val(jsp_key);
		}

		$('#comment').val('');
	})(akeeba.jQuery);
}

//=============================================================================
//Akeeba Backup -- Filesystem Filters (direct)
//=============================================================================

/**
 * Loads the contents of a directory
 * @param data
 * @return
 */
function fsfilter_load(data)
{
	// Add the verb to the data
	data.verb = 'list';
	// Convert to JSON
	var json = JSON.stringify(data);
	// Assemble the data array and send the AJAX request
	var new_data = new Object;
	new_data.action = json;
	doAjax(new_data, function(response){
		fsfilter_render(response);
	});
}

/**
 * Toggles a filesystem filter
 * @param data
 * @param caller
 * @return
 */
function fsfilter_toggle(data, caller, callback, use_inner_child)
{
	if(use_inner_child == null) use_inner_child = true;
	(function($){
		// Make the icon spin
		if(caller != null)
		{
			// Do not allow multiple simultaneous AJAX requests on the same object
			if( caller.data('loading') == true ) return;

			caller.data('loading', true);
			if(use_inner_child) {
				var icon_span = caller.children('span:first');
			} else {
				var icon_span = caller;
			}
			caller.data('icon', icon_span.attr('class') );
			icon_span.removeClass(caller.data('icon'));
			icon_span.addClass('ui-icon');
			icon_span.addClass('ak-toggle-button');
			icon_span.addClass('ak-toggle-button-spinning');
			icon_span.addClass('ui-icon-arrowrefresh-1-w');
			icon_span.everyTime(100, 'spinner', function(){
				if(icon_span.hasClass('ui-icon-arrowrefresh-1-w'))
				{
					icon_span.removeClass('ui-icon-arrowrefresh-1-w');
					icon_span.addClass('ui-icon-arrowrefresh-1-n');
				} else
				if(icon_span.hasClass('ui-icon-arrowrefresh-1-n'))
				{
					icon_span.removeClass('ui-icon-arrowrefresh-1-n');
					icon_span.addClass('ui-icon-arrowrefresh-1-e');
				} else
				if(icon_span.hasClass('ui-icon-arrowrefresh-1-e'))
				{
					icon_span.removeClass('ui-icon-arrowrefresh-1-e');
					icon_span.addClass('ui-icon-arrowrefresh-1-s');
				} else
				{
					icon_span.removeClass('ui-icon-arrowrefresh-1-s');
					icon_span.addClass('ui-icon-arrowrefresh-1-w');
				}
			});
		}


		// Convert to JSON
		var json = JSON.stringify(data);
		// Assemble the data array and send the AJAX request
		var new_data = new Object;
		new_data.action = json;
		doAjax(new_data, function(response){
			if(caller != null)
			{
				icon_span.stopTime();
				icon_span.attr('class', caller.data('icon'));
				caller.removeData('icon');
				caller.removeData('loading');
			}
			if( response.success == true )
			{
				if(caller != null)
				{
					if(use_inner_child)
					{
						// Update the on-screen filter state
						if(response.newstate == true)
						{
							caller.removeClass('ui-state-normal');
							caller.addClass('ui-state-highlight');
						}
						else
						{
							caller.addClass('ui-state-normal');
							caller.removeClass('ui-state-highlight');
						}
					}
				}
				if(!(callback == null)) callback(response, caller);
			}
			else
			{
				if(!(callback == null)) callback(response, caller);
				// An error occured
				var dialog_element = $("#dialog");
				dialog_element.html(''); // Clear the dialog's contents
				$(document.createElement('p')).html(akeeba_translations['UI-ERROR-FILTER'].replace('%s', data.node)).appendTo(dialog_element);
				dialog_element.dialog('open');
			}
		}, function(msg){
			// Error handler
			if(caller != null)
			{
				icon_span.stopTime();
				icon_span.attr('class', caller.data('icon'));
				caller.removeData('icon');
				caller.removeData('loading');
			}

			akeeba_error_callback(msg);
		});
	})(akeeba.jQuery);
}

/**
 * Renders the Filesystem Filters page
 * @param data
 * @return
 */
function fsfilter_render(data)
{
	akeeba_current_root = data.root;
	(function($){
		// ----- Render the crumbs bar
		// Create a new crumbs data array
		var crumbsdata = new Array;
		// Push the "navigate to root" element
		var newCrumb = new Array;
		newCrumb[0] = akeeba_translations['UI-ROOT'];	// [0] : UI Label
		newCrumb[1] = data.root;						// [1] : Root node
		newCrumb[2] = new Array;						// [2] : Crumbs to current directory
		newCrumb[3] = '';								// [3] : Node element
		crumbsdata.push(newCrumb);
		// Iterate existing crumbs
		if(data.crumbs.length > 0)
		{
			var crumbs = new Array;
			$.each(data.crumbs,function(counter, crumb) {
				var newCrumb = new Array;
				newCrumb[0] = crumb;
				newCrumb[1] = data.root;
				newCrumb[2] = crumbs.slice(0); // Otherwise it is copied by reference
				newCrumb[3] = crumb;
				crumbsdata.push(newCrumb);
				crumbs.push(crumb); // Push this dir into the crumb list
			});
		}
		// Render the UI crumbs elements
		var akcrumbs = $('#ak_crumbs');
		akcrumbs.html('');
		$.each(crumbsdata, function(counter, def){
			var myLi = $(document.createElement('li'))
			$(document.createElement('a'))
				.attr('href','javascript:')
				.html(def[0])
				.click(function(){
					$(this).append(
						$(document.createElement('img'))
						.attr('src', akeeba_ui_theme_root+'../icons/loading.gif')
						.attr({
							width: 16,
							height: 11,
							border: 0,
							alt: 'Loading...'
						})
						.css({
							marginTop: '5px',
							marginLeft: '5px'
						})
					);

					var new_data = new Object;
					new_data.root = def[1];
					new_data.crumbs = def[2];
					new_data.node = def[3];
					fsfilter_load(new_data);
				})
				.appendTo(myLi);
			$(document.createElement('span'))
				.addClass('divider')
				.text('/')
				.appendTo(myLi);
			myLi.appendTo(akcrumbs);
			//if(counter < (crumbsdata.length-1) ) akcrumbs.append(' / ');
		});

		// ----- Render the subdirectories
		var akfolders = $('#folders');
		akfolders.html('');
		if(data.crumbs.length > 0)
		{
			// The parent directory element
			var uielement = $(document.createElement('div'))
				.addClass('folder-container');
			uielement
				.append($(document.createElement('span')).addClass('folder-padding'))
				.append($(document.createElement('span')).addClass('folder-padding'))
				.append($(document.createElement('span')).addClass('folder-padding'))
				.append(
					$(document.createElement('span'))
					.addClass('folder-name folder-up')
					.html('('+akcrumbs.find('li:last').prev().find('a').html()+')')
					.prepend(
						$(document.createElement('span'))
						.addClass('ui-icon ui-icon-arrowreturnthick-1-w')
					)
					.click(function(){
						akcrumbs.find('li:last').prev().find('a').click();
					})
				)
				.appendTo(akfolders);
		}
		$.each(data.folders, function(folder, def){
			var uielement = $(document.createElement('div'))
				.addClass('folder-container');

			var available_filters = new Array;
			available_filters.push('directories');
			available_filters.push('skipdirs');
			available_filters.push('skipfiles');
			$.each(available_filters, function(counter, filter){
				var ui_icon = $(document.createElement('span')).addClass('folder-icon-container');
				switch(filter)
				{
					case 'directories':
						ui_icon.append('<span class="ak-toggle-button ui-icon ui-icon-cancel"></span>');
						break;
					case 'skipdirs':
						ui_icon.append('<span class="ak-toggle-button ui-icon ui-icon-folder-open"></span>');
						break;
					case 'skipfiles':
						ui_icon.append('<span class="ak-toggle-button ui-icon ui-icon-document"></span>');
						break;
				}
				ui_icon.tooltip({
					top: 24,
					left: 0,
					track: false,
					delay: 0,
					showURL: false,
					opacity: 1,
					fixPNG: true,
					fade: 0,
					extraClass: 'ui-dialog ui-corner-all',
					bodyHandler: function() {
						html = '<div class="tooltip-arrow-up-leftaligned"></div><div>'+akeeba_translations['UI-FILTERTYPE-'+filter.toUpperCase()]+'</div>';
						return html;
					}
				});

				switch(def[filter])
				{
					case 2:
						ui_icon.addClass('ui-state-error');
						break;

					case 1:
						ui_icon.addClass('ui-state-highlight');
						// Don't break; we have to add the handler!

					case 0:
						ui_icon.click(function(){
							var new_data = new Object;
							new_data.root = data.root;
							new_data.crumbs = crumbs;
							new_data.node = folder;
							new_data.filter = filter;
							new_data.verb = 'toggle';
							fsfilter_toggle(new_data, ui_icon);
						});
				}
				ui_icon.appendTo(uielement);
			}); // filter loop
			// Add the folder label and make clicking on it load its listing
			$(document.createElement('span'))
				.html(folder)
				.addClass('folder-name')
				.click(function(){
					// Show "loading" animation
					$(this).append(
						$(document.createElement('img'))
						.attr('src', akeeba_ui_theme_root+'../icons/loading.gif')
						.attr({
							width: 16,
							height: 11,
							border: 0,
							alt: 'Loading...'
						})
						.css({
							marginTop: '3px',
							marginLeft: '5px'
						})
					);

					var new_data = new Object;
					new_data.root = data.root;
					new_data.crumbs = crumbs;
					new_data.node = folder;
					fsfilter_load(new_data);
				})
				.appendTo(uielement);
			// Render
			uielement.appendTo(akfolders);
		});

		// ----- Render the files
		var akfiles = $('#files');
		akfiles.html('');
		$.each(data.files, function(file, def){
			var uielement = $(document.createElement('div'))
				.addClass('file-container');

			var available_filters = new Array;
			available_filters.push('files');
			$.each(available_filters, function(counter, filter){
				var ui_icon = $(document.createElement('span')).addClass('file-icon-container');
				switch(filter)
				{
					case 'files':
						ui_icon.append('<span class="ak-toggle-button ui-icon ui-icon-cancel"></span>');
						break;
				}
				ui_icon.tooltip({
					top: 24,
					left: 0,
					track: false,
					delay: 0,
					showURL: false,
					opacity: 1,
					fixPNG: true,
					fade: 0,
					extraClass: 'ui-dialog ui-corner-all',
					bodyHandler: function() {
						html = '<div class="tooltip-arrow-up-leftaligned"></div><div>'+akeeba_translations['UI-FILTERTYPE-'+filter.toUpperCase()]+'</div>';
						return html;
					}
				});
				switch(def[filter])
				{
					case 2:
						ui_icon.addClass('ui-state-error');
						break;

					case 1:
						ui_icon.addClass('ui-state-highlight');
						// Don't break; we have to add the handler!

					case 0:
						ui_icon.click(function(){
							var new_data = new Object;
							new_data.root = data.root;
							new_data.crumbs = crumbs;
							new_data.node = file;
							new_data.filter = filter;
							new_data.verb = 'toggle';
							fsfilter_toggle(new_data, ui_icon);
						});
				}
				ui_icon.appendTo(uielement);
			}); // filter loop
			// Add the file label
			uielement
			.append(
				$(document.createElement('span'))
				.addClass('file-name')
				.html(file)
			)
			.append(
				$(document.createElement('span'))
				.addClass('file-size')
				.html(size_format(def['size']))
			);
			// Render
			uielement.appendTo(akfiles);
		});
	})(akeeba.jQuery);
}

/**
 * Loads the tabular view of the Filesystems Filter for a given root
 * @param root
 * @return
 */
function fsfilter_load_tab(root)
{
	var data = new Object;
	data.verb = 'tab';
	data.root = root;
	// Convert to JSON
	var json = JSON.stringify(data);
	// Assemble the data array and send the AJAX request
	var new_data = new Object;
	new_data.action = json;
	doAjax(new_data, function(response){
		fsfilter_render_tab(response);
	});
}

/**
 * Add a row in the tabular view of the Filesystems Filter
 * @param def
 * @param append_to_here
 * @return
 */
function fsfilter_add_row(def, append_to_here)
{
	(function($){
		// Turn def.type into something human readable
		var type_text = akeeba_translations['UI-FILTERTYPE-'+def.type.toUpperCase()];
		if(type_text == null) type_text = def.type;

		$(document.createElement('tr'))
		.addClass('ak_filter_row')
		.append(
			// Filter title
			$(document.createElement('td'))
			.addClass('ak_filter_type')
			.append(type_text)
		)
		.append(
			$(document.createElement('td'))
			.addClass('ak_filter_item')
			.append(
				$(document.createElement('span'))
				.addClass('ak_filter_tab_icon_container')
				.click(function(){
					if( def.node == '' )
					{
						// An empty filter is normally not saved to the database; it's a new record row which has to be removed...
						$(this).parent().parent().remove();
						return;
					}

					var new_data = new Object;
					new_data.root = $('#active_root').val();
					new_data.crumbs = new Array();
					new_data.node = def.node;
					new_data.filter = def.type;
					new_data.verb = 'toggle';
					fsfilter_toggle(new_data, $(this), function(response, caller){
						if(response.success)
						{
							caller.parent().parent().remove();
						}
					});
				})
				.append(
						$(document.createElement('span'))
						.addClass('ak-toggle-button ui-icon ui-icon-trash deletebutton')
				)
			)
			.append(
				$(document.createElement('span'))
				.addClass('ak_filter_tab_icon_container')
				.click(function(){
					if( $(this).siblings('span.ak_filter_tab_icon_container:first').next().data('editing') ) return;
					$(this).siblings('span.ak_filter_tab_icon_container:first').next().data('editing',true);
					$(this).next().hide();
					$(document.createElement('input'))
					.attr({
						type: 'text',
						size: 60
					})
					.val( $(this).next().html() )
					.appendTo( $(this).parent() )
					.blur(function(){
						var new_value = $(this).val();
						if(new_value == '')
						{
							// Well, if the user meant to remove the filter, let's help him!
							$(this).parent().children('span.ak_filter_name').show();
							$(this).siblings('span.ak_filter_tab_icon_container').find('span.deletebutton').click();
							$(this).remove();
							return;
						}

						// First, remove the old filter
						var new_data = new Object;
						new_data.root = $('#active_root').val();
						new_data.crumbs = new Array();
						new_data.old_node = def.node;
						new_data.new_node = new_value;
						new_data.filter = def.type;
						new_data.verb = 'swap';

						var input_box = $(this);

						fsfilter_toggle(new_data,
							input_box.siblings('span.ak_filter_tab_icon_container:first').next(),
							function(response, caller){
								// Remove the editor
								input_box.siblings('span.ak_filter_tab_icon_container:first').next().removeData('editing');
								input_box.parent().find('span.ak_filter_name').show();
								input_box.siblings('span.ak_filter_tab_icon_container:first').next().removeClass('ui-state-highlight');
								input_box.parent().find('span.ak_filter_name').html( new_value );
								input_box.remove();
							}
						);
					})
					.focus();
				})
				.append(
					$(document.createElement('span'))
					.addClass('ak-toggle-button ui-icon ui-icon-pencil editbutton')
				)
			)
			.append(
				$(document.createElement('span'))
				.addClass('ak_filter_name')
				.html(def.node)
			)
		)
		.appendTo( $(append_to_here) );
	})(akeeba.jQuery);
}

function fsfilter_addnew(filtertype)
{
	(function($){
		// Add a row below ourselves
		var new_def = new Object;
		new_def.type = filtertype;
		new_def.node = '';
		fsfilter_add_row(new_def, $('#ak_list_table') );
		$('#ak_list_table tr:last').children('td:last').children('span.ak_filter_tab_icon_container:last').click();
	})(akeeba.jQuery);
}

/**
 * Renders the tabular view of the Filesystems Filter
 * @param data
 * @return
 */
function fsfilter_render_tab(data)
{
	(function($){
		var tbody = $('#ak_list_contents');
		tbody.html('');
		$.each(data, function(counter, def){
			fsfilter_add_row(def, tbody);
		});
	})(akeeba.jQuery);
}

/**
 * Wipes out the filesystem filters
 * @return
 */
function fsfilter_nuke()
{
	var data = new Object;
	data.root = akeeba_current_root;
	data.verb = 'reset';
	// Convert to JSON
	var json = JSON.stringify(data);
	// Assemble the data array and send the AJAX request
	var new_data = new Object;
	new_data.action = json;
	doAjax(new_data, function(response){
		fsfilter_render(response);
	});
}

//=============================================================================
//Akeeba Backup -- Database Filters (direct)
//=============================================================================

/**
 * Loads the contents of a database
 * @param data
 * @return
 */
function dbfilter_load(data)
{
	// Add the verb to the data
	data.verb = 'list';
	// Convert to JSON
	var json = JSON.stringify(data);
	// Assemble the data array and send the AJAX request
	var new_data = new Object;
	new_data.action = json;
	doAjax(new_data, function(response){
		dbfilter_render(response);
	});
}

/**
 * Toggles a database filter
 * @param data
 * @param caller
 * @return
 */
function dbfilter_toggle(data, caller, callback)
{
	fsfilter_toggle(data, caller, callback);
}

/**
 * Renders the Database Filters page
 * @param data
 * @return
 */
function dbfilter_render(data)
{
	akeeba_current_root = data.root;
	(function($){
		// ----- Render the tables
		var aktables = $('#tables');
		aktables.html('');
		$.each(data.tables, function(table, dbef){
			var uielement = $(document.createElement('div'))
				.addClass('table-container');

			var available_filters = new Array;
			available_filters.push('tables');
			available_filters.push('tabledata');
			$.each(available_filters, function(counter, filter){
				var ui_icon = $(document.createElement('span')).addClass('table-icon-container');
				switch(filter)
				{
					case 'tables':
						ui_icon.append('<span class="ak-toggle-button ui-icon ui-icon-cancel"></span>');
						break;
					case 'tabledata':
						ui_icon.append('<span class="ak-toggle-button ui-icon ui-icon-contact"></span>');
						break;
				}
				ui_icon.tooltip({
					top: 24,
					left: 0,
					track: false,
					delay: 0,
					showURL: false,
					opacity: 1,
					fixPNG: true,
					fade: 0,
					extraClass: 'ui-dialog ui-corner-all',
					bodyHandler: function() {
						html = '<div class="tooltip-arrow-up-leftaligned"></div><div>'+akeeba_translations['UI-FILTERTYPE-'+filter.toUpperCase()]+'</div>';
						return html;
					}
				});

				switch(dbef[filter])
				{
					case 2:
						ui_icon.addClass('ui-state-error');
						break;

					case 1:
						ui_icon.addClass('ui-state-highlight');
						// Don't break; we have to add the handler!

					case 0:
						ui_icon.click(function(){
							var new_data = new Object;
							new_data.root = data.root;
							new_data.node = table;
							new_data.filter = filter;
							new_data.verb = 'toggle';
							dbfilter_toggle(new_data, ui_icon);
						});
				}
				ui_icon.appendTo(uielement);
			}); // filter loop
			// Add the table label
			var iconclass = 'ui-icon-link';
			var icontip = 'UI-TABLETYPE-MISC';
			switch(dbef.type)
			{
				case 'table':
					iconclass = 'ui-icon-calculator';
					icontip = 'UI-TABLETYPE-TABLE';
					break;
				case 'view':
					iconclass = 'ui-icon-copy';
					icontip = 'UI-TABLETYPE-VIEW';
					break;
				case 'procedure':
					iconclass = 'ui-icon-script';
					icontip = 'UI-TABLETYPE-PROCEDURE';
					break;
				case 'function':
					iconclass = 'ui-icon-gear';
					icontip = 'UI-TABLETYPE-FUNCTION';
					break;
				case 'trigger':
					iconclass = 'ui-icon-video';
					icontip = 'UI-TABLETYPE-TRIGGER';
					break;
			}
			$(document.createElement('span'))
				.addClass('table-name')
				.html(table)
				.append(
					$(document.createElement('span'))
					.addClass('table-icon-container')
					.addClass('table-icon-noclick')
					.addClass('table-icon-small')
					.append(
						$(document.createElement('span'))
						.addClass('ui-icon')
						.addClass('ui-icon-grip-dotted-vertical')
					)
				)
				.append(
					$(document.createElement('span'))
					.addClass('table-icon-container')
					.addClass('table-icon-noclick')
					.addClass('table-icon-small')
					.append(
						$(document.createElement('span'))
						.addClass('ui-icon')
						.addClass(iconclass)
					)
					.tooltip({
						top: 24,
						left: 0,
						track: false,
						delay: 0,
						showURL: false,
						opacity: 1,
						fixPNG: true,
						fade: 0,
						extraClass: 'ui-dialog ui-corner-all',
						bodyHandler: function() {
							html = '<div class="tooltip-arrow-up-leftaligned"></div><div>'+akeeba_translations[icontip]+'</div>';
							return html;
						}
					})
				)
				.appendTo(uielement);
			// Render
			uielement.appendTo(aktables);
		});
	})(akeeba.jQuery);
}

/**
 * Loads the tabular view of the Database Filter for a given root
 * @param root
 * @return
 */
function dbfilter_load_tab(root)
{
	var data = new Object;
	data.verb = 'tab';
	data.root = root;
	// Convert to JSON
	var json = JSON.stringify(data);
	// Assemble the data array and send the AJAX request
	var new_data = new Object;
	new_data.action = json;
	doAjax(new_data, function(response){
		dbfilter_render_tab(response);
	});
}

/**
 * Add a row in the tabular view of the Filesystems Filter
 * @param def
 * @param append_to_here
 * @return
 */
function dbfilter_add_row(def, append_to_here)
{
	(function($){
		// Turn def.type into something human readable
		var type_text = akeeba_translations['UI-FILTERTYPE-'+def.type.toUpperCase()];
		if(type_text == null) type_text = def.type;

		$(document.createElement('tr'))
		.addClass('ak_filter_row')
		.append(
			// Filter title
			$(document.createElement('td'))
			.addClass('ak_filter_type')
			.append(type_text)
		)
		.append(
			$(document.createElement('td'))
			.addClass('ak_filter_item')
			.append(
				$(document.createElement('span'))
				.addClass('ak_filter_tab_icon_container')
				.click(function(){
					if( def.node == '' )
					{
						// An empty filter is normally not saved to the database; it's a new record row which has to be removed...
						$(this).parent().parent().remove();
						return;
					}

					var new_data = new Object;
					new_data.root = $('#active_root').val();
					new_data.node = def.node;
					new_data.filter = def.type;
					new_data.verb = 'remove';
					dbfilter_toggle(new_data, $(this), function(response, caller){
						if(response.success)
						{
							caller.parent().parent().remove();
						}
					});
				})
				.append(
						$(document.createElement('span'))
						.addClass('ak-toggle-button ui-icon ui-icon-trash deletebutton')
				)
			)
			.append(
				$(document.createElement('span'))
				.addClass('ak_filter_tab_icon_container')
				.click(function(){
					if( $(this).siblings('span.ak_filter_tab_icon_container:first').next().data('editing') ) return;
					$(this).siblings('span.ak_filter_tab_icon_container:first').next().data('editing',true);
					$(this).next().hide();
					$(document.createElement('input'))
					.attr({
						type: 'text',
						size: 60
					})
					.val( $(this).next().html() )
					.appendTo( $(this).parent() )
					.blur(function(){
						var new_value = $(this).val();
						if(new_value == '')
						{
							// Well, if the user meant to remove the filter, let's help him!
							$(this).parent().children('span.ak_filter_name').show();
							$(this).siblings('span.ak_filter_tab_icon_container').find('span.deletebutton').click();
							$(this).remove();
							return;
						}

						// First, remove the old filter
						var new_data = new Object;
						new_data.root = $('#active_root').val();
						new_data.old_node = def.node;
						new_data.new_node = new_value;
						new_data.filter = def.type;
						new_data.verb = 'swap';

						var input_box = $(this);

						dbfilter_toggle(new_data,
							input_box.siblings('span.ak_filter_tab_icon_container:first').next(),
							function(response, caller){
								// Remove the editor
								input_box.siblings('span.ak_filter_tab_icon_container:first').next().removeData('editing');
								input_box.parent().find('span.ak_filter_name').show();
								input_box.siblings('span.ak_filter_tab_icon_container:first').next().removeClass('ui-state-highlight');
								input_box.parent().find('span.ak_filter_name').html( new_value );
								input_box.remove();
							}
						);
					})
					.focus();
				})
				.append(
					$(document.createElement('span'))
					.addClass('ak-toggle-button ui-icon ui-icon-pencil editbutton')
				)
			)
			.append(
				$(document.createElement('span'))
				.addClass('ak_filter_name')
				.html(def.node)
			)
		)
		.appendTo( $(append_to_here) );
	})(akeeba.jQuery);
}

function dbfilter_addnew(filtertype)
{
	(function($){
		// Add a row below ourselves
		var new_def = new Object;
		new_def.type = filtertype;
		new_def.node = '';
		dbfilter_add_row(new_def, $('#ak_list_table') );
		$('#ak_list_table tr:last').children('td:last').children('span.ak_filter_tab_icon_container:last').click();
	})(akeeba.jQuery);
}

/**
 * Renders the tabular view of the Database Filter
 * @param data
 * @return
 */
function dbfilter_render_tab(data)
{
	(function($){
		var tbody = $('#ak_list_contents');
		tbody.html('');
		$.each(data, function(counter, def){
			dbfilter_add_row(def, tbody);
		});
	})(akeeba.jQuery);
}

/**
 * Activates the exclusion filters for non-CMS tables
 */
function dbfilter_exclude_noncms()
{
	(function($){
		$('#tables div').each(function(i, element){
			// Get the table name
			var tablename = $(element).find('span.table-name:first').text();
			var prefix = tablename.substr(0,3);
			// If the prefix is #__ it's a CMS table and I have to skip it
			if( prefix != '#__' )
			{
				var icon = $(element).find('span.table-icon-container span.ui-icon:first');
				if ( !($(icon).parent().hasClass('ui-state-highlight')) )
				{
					$(icon).click();
				}
			}
		});
	})(akeeba.jQuery);
}

/**
 * Wipes out the database filters
 * @return
 */
function dbfilter_nuke()
{
	var data = new Object;
	data.root = akeeba_current_root;
	data.verb = 'reset';
	// Convert to JSON
	var json = JSON.stringify(data);
	// Assemble the data array and send the AJAX request
	var new_data = new Object;
	new_data.action = json;
	doAjax(new_data, function(response){
		dbfilter_render(response);
	});
}

//=============================================================================
//Akeeba Backup Core - System Restore Point roll-back
//=============================================================================
var akeeba_srprestoration_error_callback = akeeba_srprestoration_error_callback_default;
var akeeba_srprestoration_stat_inbytes = 0;
var akeeba_srprestoration_stat_outbytes = 0;
var akeeba_srprestoration_stat_files = 0;
var akeeba_srprestoration_factory = null;

/**
 * Callback script for AJAX errors
 * @param msg
 * @return
 */
function akeeba_srprestoration_error_callback_default(msg)
{
	(function($) {
		$('#restoration-progress').hide();
		$('#restoration-database-progress').hide();
		$('#restoration-error').show();
		$('#backup-error-message').html(msg);
	})(akeeba.jQuery);
}

/**
 * Performs an AJAX request to the file restoration script
 * @param data
 * @param successCallback
 * @param errorCallback
 * @return
 */
function doSRPRestorationAjax(data, successCallback, errorCallback)
{
    (function($) {
        json = JSON.stringify(data);
        var post_data = {json: json, ajax: data.ajax};

        var structure =
        {
                type: "POST",
                url: akeeba_srprestoration_ajax_url,
                cache: false,
                data: post_data,
                timeout: 600000,
                success: function(msg) {
                        // Initialize
                        var junk = null;
                        var message = "";

                        // Get rid of junk before the data
                        var valid_pos = msg.indexOf('###');
                        if( valid_pos == -1 ) {
                                // Valid data not found in the response
                                msg = 'Invalid AJAX data: ' + msg;
                                if(errorCallback == null)
                                {
                                        if(akeeba_srprestoration_error_callback != null)
                                        {
                                                akeeba_srprestoration_error_callback(msg);
                                        }
                                }
                                else
                                {
                                        errorCallback(msg);
                                }
                                return;
                        } else if( valid_pos != 0 ) {
                                // Data is prefixed with junk
                                junk = msg.substr(0, valid_pos);
                                message = msg.substr(valid_pos);
                        }
                        else
                        {
                                message = msg;
                        }
                        message = message.substr(3); // Remove triple hash in the beginning

                        // Get of rid of junk after the data
                        var valid_pos = message.lastIndexOf('###');
                        message = message.substr(0, valid_pos); // Remove triple hash in the end

                        try {
                            var data = JSON.parse(message);
                        } catch(err) {
                            var msg = err.message + "\n<br/>\n<pre>\n" + message + "\n</pre>";
                            if(errorCallback == null)
                            {
                                    if(akeeba_srprestoration_error_callback != null)
                                    {
                                            akeeba_srprestoration_error_callback(msg);
                                    }
                            }
                            else
                            {
                                    errorCallback(msg);
                            }
                            return;
                        }

                        // Call the callback function
                        successCallback(data);
                },
                error: function(Request, textStatus, errorThrown) {
                        var message = 'AJAX Loading Error: '+textStatus;
                        if(errorCallback == null)
                        {
                                if(akeeba_srprestoration_error_callback != null)
                                {
                                        akeeba_srprestoration_error_callback(message);
                                }
                        }
                        else
                        {
                                errorCallback(message);
                        }
                }
        };
        $.ajax( structure );
    })(akeeba.jQuery);
}

/**
 * Pings the restoration script (making sure its executable!!)
 * @return
 */
function pingSRPRestoration()
{
	// Reset variables
	akeeba_srprestoration_stat_inbytes = 0;
	akeeba_srprestoration_stat_outbytes = 0;
	akeeba_srprestoration_stat_files = 0;

	// Do AJAX post
	var post = {ajax : 'restoreFilesPing'};
	start_timeout_bar(5000,80);
	doSRPRestorationAjax(post, function(data){
		startSRPRestoration(data);
	});
}

/**
 * Starts the restoration
 * @return
 */
function startSRPRestoration()
{
	// Reset variables
	akeeba_srprestoration_stat_inbytes = 0;
	akeeba_srprestoration_stat_outbytes = 0;
	akeeba_srprestoration_stat_files = 0;

	// Do AJAX post
	var post = {ajax : 'restoreFilesStart'};
	start_timeout_bar(5000,80);
	doSRPRestorationAjax(post, function(data){
		processSRPRestorationStep(data);
	});
}

/**
 * Steps through the restoration
 * @param data
 * @return
 */
function processSRPRestorationStep(data)
{
	reset_timeout_bar();
	if(data.status == false)
	{
		// handle failure
		akeeba_srprestoration_error_callback_default(data.message);
	}
	else
	{
		if(data.done)
		{
			(function($){
				startSRPdbRestoration();
			})(akeeba.jQuery);
		}
		else
		{
			// Add data to variables
			akeeba_srprestoration_stat_inbytes += data.bytesIn;
			akeeba_srprestoration_stat_outbytes += data.bytesOut;
			akeeba_srprestoration_stat_files += data.files;

			// Display data
			(function($){
				$('#extbytesin').html( akeeba_srprestoration_stat_inbytes );
				$('#extbytesout').html( akeeba_srprestoration_stat_outbytes );
				$('#extfiles').html( akeeba_srprestoration_stat_files );
			})(akeeba.jQuery);

			// Do AJAX post
			post = {
				ajax: 'restoreFilesStep',
				factory: data.factory
			};
			start_timeout_bar(5000,80);
			doSRPRestorationAjax(post, function(data){
				processSRPRestorationStep(data);
			});
		}
	}
}

function finalizeSRPRestoration()
{
	// Do AJAX post
	var post = {ajax : 'restoreFilesFinalize', factory: akeeba_srprestoration_factory};
	start_timeout_bar(5000,80);
	doSRPRestorationAjax(post, function(data){
		SRPRestorationFinished(data);
	});
}

function startSRPdbRestoration() {
	(function($){
		$('#restoration-progress').hide();
		$('#restoration-db-progress').show();
	})(akeeba.jQuery);
	var post = {ajax : 'dbRestoreStart'};
	doSRPRestorationAjax(post, doSRPdbRestoration);
}

function doSRPdbRestoration(data) {
	if(data.error) {
		akeeba_srprestoration_error_callback_default(data.error);
	} else if(data.done == 1) {
		finalizeSRPRestoration();
	} else {
		// TODO Maybe add a progress bar?
		(function($){
			$('#restoration-db-progress-message').html(data.message);
			var post = {ajax : 'dbRestore'};
			doSRPRestorationAjax(post, doSRPdbRestoration);
		})(akeeba.jQuery);
	}
}

function SRPRestorationFinished()
{
	// We're just finished - return to the back-end Control Panel
	(function($){
		$('#restoration-db-progress').hide();
		$('#restoration-done').show();
	})(akeeba.jQuery);
}

//=============================================================================
// Akeeba's jQuery extensions
//=============================================================================
//Custom no easing plug-in
akeeba.jQuery.extend(akeeba.jQuery.easing, {
	none: function(fraction, elapsed, attrStart, attrDelta, duration) {
		return attrStart + attrDelta * fraction;
	}
});

//=============================================================================
// 							I N I T I A L I Z A T I O N
//=============================================================================
akeeba.jQuery(document).ready(function($){
	// Create an AJAX manager
	var akeeba_ajax_manager = $.manageAjax.create('akeeba_ajax_profile', {
		queue: true,
		abortOld: false,
		maxRequests: 1,
		preventDoubbleRequests: false,
		cacheResponse: false
	});
	// Add hover state to buttons and other non jQuery UI elements
	$('.ui-state-default').hover(
	   function(){$(this).addClass('ui-state-hover');},
	   function(){$(this).removeClass('ui-state-hover');}
	);
});
