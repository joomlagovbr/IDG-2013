/**
 * Akeeba Backup
 * The modular PHP5 site backup software solution
 * This file contains the configuration-wizard client-side business logic
 * @package akeebaui
 * @copyright Copyright (c)2010-2014 Nicholas K. Dionysopoulos
 * @license GNU GPL version 3 or, at your option, any later version
 * @version $Id: confwiz.js 527 2011-03-31 14:47:36Z nikosdion $
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

var akeeba_confwiz_exectimes_table = [30,25,20,14,7,5,3];
var akeeba_confwiz_blocksizes_table = [240, 200, 160, 80, 40, 16, 4, 2, 1];

/**
 * Boot up the Configuration Wizard benchmarking process
 */
function akeeba_confwiz_boot()
{
	(function($){
		// Initialization
		akeeba_confwiz_exectimes_table = [30,25,20,14,7,5,3];
		// Part sizes to check. Given in number of 128Kb chunks, i.e.
		// 480 = 60Mb, 400 = 50Mb, 240 = 30Mb, 200 = 25Mb, 160 = 20Mb,
		// 80 = 10Mb, 40 = 5Mb, 16 = 2Mb, 4 = 512Kb, 2 = 256Kb, 1 = 128Kb
		akeeba_confwiz_blocksizes_table = [480, 400, 240, 200, 160, 80, 40, 16, 4, 2, 1];
		
		// Show GUI
		$('#backup-progress-pane').css('display','block');
		reset_timeout_bar();
		
		// Go!
		akeeba_confwiz_tryajax();
	})(akeeba.jQuery);
}

/**
 * Try to figure out the optimal AJAX method
 */
function akeeba_confwiz_tryajax()
{
	(function($){
		akeeba_use_iframe = false;
		reset_timeout_bar();
		start_timeout_bar(10000,100);
		$('#step-ajax').addClass('step-active');
		$('#backup-substep').text( akeeba_translations['UI-TRYAJAX'] );
		doAjax(
			{act: 'ping'},
			function() {
				// Successful AJAX call!
				akeeba_use_iframe = false;
				$('#step-ajax').removeClass('label-info');
				$('#step-ajax').addClass('label-success');
				akeeba_confwiz_minexec();
			},
			function() {
				// Let's try IFRAME
				akeeba_use_iframe = true;
				reset_timeout_bar();
				start_timeout_bar(10000,100);
				$('#backup-substep').text( akeeba_translations['UI-TRYIFRAME'] );
				doAjax(
					{ act: 'ping' },
					function() {
						// Successful IFRAME call
						$('#step-ajax').removeClass('label-info');
						$('#step-ajax').addClass('label-success');
						akeeba_confwiz_minexec();
					},
					function() {
						// Unsuccessful IFRAME call, we've ran out if ideas!
						$('#backup-progress-pane').css('display','none');
						$('#error-panel').css('display','block');
						$('#backup-error-message').html( akeeba_translations['UI-CANTUSEAJAX'] );
					},
					false,
					10000
				);
			},
			false,
			10000
		);
	})(akeeba.jQuery);	
}

/**
 * Determine the optimal Minimum Execution Time
 * @param seconds float How many seconds to test
 * @return
 */
function akeeba_confwiz_minexec(seconds, repetition)
{
	(function($){
		if(seconds == null) seconds = 0;
		if(repetition == null) repetition = 0;
		
		reset_timeout_bar();
		start_timeout_bar((2 * seconds + 5) * 1000,100);
		var substepText = akeeba_translations['UI-MINEXECTRY'].replace('%s', seconds.toFixed(1));
		$('#backup-substep').text( substepText );
		$('#step-minexec').addClass('label-info');
		doAjax(
			{act: 'minexec', 'seconds': seconds},
			function(msg) {
				// The ping was successful. Add a repetition count.
				repetition++;
				if(repetition < 3) {
					// We need more repetitions
					akeeba_confwiz_minexec(seconds, repetition);
				} else {
					// Three repetitions reached. Success!
					akeeba_confwiz_apply_minexec(seconds);
				}
			},
			function() {
				// We got a failure. Add half a second
				seconds += 0.5;
				if(seconds > 20) {
					// Uh-oh... We exceeded our maximum allowance!
					$('#backup-progress-pane').css('display','none');
					$('#error-panel').css('display','block');
					$('#backup-error-message').html( akeeba_translations['UI-CANTDETERMINEMINEXEC'] );					
				} else {
					akeeba_confwiz_minexec(seconds,0);
				}
			},
			false,
			(2 * seconds + 5) * 1000
		);
	})(akeeba.jQuery);		
}

/**
 * Applies the AJAX preference and the minimum execution time determined in the previous steps
 * @param seconds float The minimum execution time, in seconds
 */
function akeeba_confwiz_apply_minexec(seconds)
{
	(function($){
		reset_timeout_bar();
		start_timeout_bar(25000,100);
		$('#backup-substep').text( akeeba_translations['UI-SAVEMINEXEC'] );
		var iframe_opt = 0;
		if(akeeba_use_iframe) iframe_opt = 1;
		doAjax(
			{act: 'applyminexec', 'iframes': iframe_opt, 'minexec': seconds},
			function(msg) {
				$('#step-minexec').removeClass('label-info');
				$('#step-minexec').addClass('label-success');
				
				akeeba_confwiz_directories();
			},
			function() {
				// Unsuccessful call. Oops!
				$('#backup-progress-pane').css('display','none');
				$('#error-panel').css('display','block');
				$('#backup-error-message').html( akeeba_translations['UI-CANTSAVEMINEXEC'] );
			},
			false
		);
	})(akeeba.jQuery);
}

/**
 * Automatically determine the optimal output and temporary directories,
 * then make sure they are writable
 */
function akeeba_confwiz_directories()
{
	(function($){
		reset_timeout_bar();
		start_timeout_bar(10000,100);
		$('#backup-substep').text( '' );
		$('#step-directory').addClass('label-info');
		doAjax(
			{act: 'directories'},
			function(msg) {
				if(msg) {
					$('#step-directory').removeClass('label-info');
					$('#step-directory').addClass('label-success');
					akeeba_confwiz_database();
				} else {
					$('#backup-progress-pane').css('display','none');
					$('#error-panel').css('display','block');
					$('#backup-error-message').html( akeeba_translations['UI-CANTFIXDIRECTORIES'] );					
				}
			},
			function() {
				$('#backup-progress-pane').css('display','none');
				$('#error-panel').css('display','block');
				$('#backup-error-message').html( akeeba_translations['UI-CANTFIXDIRECTORIES'] );
			},
			false
		);
	})(akeeba.jQuery);
}

/**
 * Determine the optimal database dump options, analyzing the site's database
 */
function akeeba_confwiz_database()
{
	(function($){
		reset_timeout_bar();
		start_timeout_bar(30000,50);
		$('#backup-substep').text( '' );
		$('#step-dbopt').addClass('label-info');
		doAjax(
			{act: 'database'},
			function(msg) {
				if(msg) {
					$('#step-dbopt').removeClass('label-info');
					$('#step-dbopt').addClass('label-success');
					akeeba_confwiz_maxexec();
				} else {
					$('#backup-progress-pane').css('display','none');
					$('#error-panel').css('display','block');
					$('#backup-error-message').html( akeeba_translations['UI-CANTDBOPT'] );					
				}
			},
			function() {
				$('#backup-progress-pane').css('display','none');
				$('#error-panel').css('display','block');
				$('#backup-error-message').html( akeeba_translations['UI-CANTDBOPT'] );
			},
			false
		);
	})(akeeba.jQuery);
}

/**
 * Determine the optimal maximum execution time which doesn't cause a timeout or server error
 */
function akeeba_confwiz_maxexec()
{
	(function($){
		var exec_time = array_shift(akeeba_confwiz_exectimes_table);
		if(empty(akeeba_confwiz_exectimes_table) || (exec_time == null)) {
			// Darn, we ran out of options
			$('#backup-progress-pane').css('display','none');
			$('#error-panel').css('display','block');
			$('#backup-error-message').html( akeeba_translations['UI-EXECTOOLOW'] );
			return;
		}
		
		reset_timeout_bar();
		start_timeout_bar((exec_time * 1.2)*1000, 80);
		
		$('#step-maxexec').addClass('label-info');
		var substepText = akeeba_translations['UI-MINEXECTRY'].replace('%s', exec_time.toFixed(0));
		$('#backup-substep').text( substepText );
		
		doAjax(
			{act:'maxexec', 'seconds': exec_time},
			function(msg){
				if(msg) {
					// Success! Save this value.
					akeeba_confwiz_apply_maxexec(exec_time);
				} else {
					// Uh... we have to try something lower than that
					akeeba_confwiz_maxexec();
				}
			},
			function(){
				// Uh... we have to try something lower than that
				akeeba_confwiz_maxexec();				
			}
		);
	})(akeeba.jQuery);
}

function akeeba_confwiz_apply_maxexec(seconds)
{
	(function($){
		reset_timeout_bar();
		start_timeout_bar(10000,100);
		$('#backup-substep').text( akeeba_translations['UI-SAVINGMAXEXEC'] );
		
		doAjax(
			{act: 'applymaxexec', 'seconds': seconds},
			function() {
				$('#step-maxexec').removeClass('label-info');
				$('#step-maxexec').addClass('label-success');
				akeeba_confwiz_partsize();
			},
			function() {
				$('#backup-progress-pane').css('display','none');
				$('#error-panel').css('display','block');
				$('#backup-error-message').html( akeeba_translations['UI-CANTSAVEMAXEXEC'] );
			}
		);
	})(akeeba.jQuery);	
}

function akeeba_confwiz_partsize()
{
	(function($){
		reset_timeout_bar();
		
		var block_size = array_shift(akeeba_confwiz_blocksizes_table);
		if(empty(akeeba_confwiz_blocksizes_table) || (block_size == null) ) {
			// Uh... I think you are running out of disk space, dude
			$('#backup-progress-pane').css('display','none');
			$('#error-panel').css('display','block');
			$('#backup-error-message').html( akeeba_translations['UI-CANTDETERMINEPARTSIZE'] );
			
			return;
		}
		
		var part_size = block_size / 8; // Translate to Mb
		
		start_timeout_bar(30000,100);
		var substepText = akeeba_translations['UI-PARTSIZE'].replace('%s', part_size.toFixed(3));
		$('#backup-substep').text( substepText );
		
		$('#step-splitsize').addClass('label-info');
		
		doAjax(
			{act: 'partsize', blocks: block_size},
			function(msg) {
				if(msg) {
					// We are done
					$('#step-splitsize').removeClass('label-info');
					$('#step-splitsize').addClass('label-success');
					akeeba_confwiz_done();
				} else {
					// Let's try the next (lower) value
					akeeba_confwiz_partsize();
				}
			},
			function(msg) {
				// The server blew up on our face. Let's try the next (lower) value.
				akeeba_confwiz_partsize();
			},
			false,
			60000
		);
	})(akeeba.jQuery);	
}

function akeeba_confwiz_done()
{
	(function($){
		$('#backup-progress-pane').hide();
		$('#backup-complete').show();
	})(akeeba.jQuery);
}