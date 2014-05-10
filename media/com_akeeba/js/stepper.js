var AkeebaStepper = function(options){
	this.options = {
		/* Element definition */
		holder      : '#stepper-holder',
		loading     : '#stepper-loading',
		pane        : '#stepper-progress-pane',
		content     : '#stepper-progress-content',
		steps       : '#stepper-steps',
		status      : '#stepper-status',
		step        : '#stepper-step',
		substep     : '#stepper-substep',
		percentage  : '#stepper-percentage',
		timer       : '#response-timer',

		useIframe   : false,
		domainUrl   : 'view=alices&task=domains',
		pollingUrl  : 'view=alices&task=ajax',

		/* Event hooks */
		onBeforeStart : function(polling){},
		onBeforeStep  : function(polling, previousResult){},
		onComplete    : function(result){}
	};

	this.options = jQuery.extend(this.options, options || {});

	// Private members
	var that       = this;
	var akeebaUrl  = 'index.php?option=com_akeeba&tmpl=component&';
	var pollingObj = {
		cache    : false,
		data     : {},
		dataType : 'text',
		success  : function(data){
			var match = new RegExp('###(.*?)###').exec(data);
			var result = JSON.parse(match[1]);

			that.renderBars(result.Domain);

			jQuery(that.options.percentage + ' div.bar').css({'width':result.Progress+'%'});
			jQuery(that.options.step).html(result.Step);
			jQuery(that.options.substep).html(result.Substep);

			if(result.HasRun == 1){
				that.complete(result);
			}
			else{
				setTimeout(function(){that.step(result)}, 10);
			}
		}
	};

	// Private functions
	var getDomains   = function(){
		jQuery.ajax(akeebaUrl + that.options.domainUrl, {
			cache       : false,
			dataType    : 'text',
			beforeSend  : function(){
				jQuery(that.options.loading).show();
			},
			success     : function(data){
				var match = new RegExp('###(.*?)###').exec(data);
				that.domains = JSON.parse(match[1]);

				jQuery(that.options.loading).hide();
				jQuery(that.options.pane).show('fast');

				that.continueAfterLoad();
			}
		})
	};
	var startTimeout = function(){
		var lastResponseSeconds = 0;
		jQuery(that.options.timer + ' div.text').everyTime(1000, 'lastReponse', function(){
			lastResponseSeconds++;
			var lastText = akeeba_translations['UI-LASTRESPONSE'].replace('%s', lastResponseSeconds.toFixed(0));
			jQuery(that.options.timer + ' div.text').html(lastText);
		});
	};
	var resetTimeout = function(){
		jQuery(that.options.timer + ' div.text').stopTime().html(akeeba_translations['UI-LASTRESPONSE'].replace('%s', '0'));
	};

	// Public functions
	this.init = function(){
		getDomains();
	};

	this.continueAfterLoad = function(){
		this.renderBars();
		startTimeout();
		this.start();
	};

	this.renderBars = function(active_step){
		if(active_step == undefined) active_step = '';

		var normal_class = 'label-success';
		var this_class   = '';

		//if( active_step == '' ) normal_class = '';

		jQuery(this.options.steps).html('');
		jQuery.each(this.domains, function(counter, element){
			var step = jQuery(document.createElement('div'))
				.addClass('label')
				.html(element[1])
				.data('domain',element[0])
				.appendTo(that.options.steps);

			if(element[0] == active_step ){
				normal_class = '';
				this_class = 'label-info';
			}
			else{
				this_class = normal_class;
			}

			step.addClass(this_class);
		});
	};

	this.start = function(){
		this.options.onBeforeStart(pollingObj);
		pollingObj.data.ajax = 'start';
		jQuery.ajax(akeebaUrl + this.options.pollingUrl, pollingObj);
	};

	this.step = function(previousResult){
		this.options.onBeforeStep(pollingObj, previousResult);
		resetTimeout();
		startTimeout();
		pollingObj.data.ajax = 'step';
		jQuery.ajax(akeebaUrl + this.options.pollingUrl, pollingObj);
	};

	this.complete = function(result){
		resetTimeout();
		this.options.onComplete(result);
	};
};
