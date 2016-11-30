(function ($, window, document, undefined) {

	// Create the defaults once
	var pluginName = "searchtools";

	var defaults = {
		// Form options
		formSelector            : '.js-stools-form',

		// Search
		searchFieldSelector     : '.js-stools-field-search',
		clearBtnSelector        : '.js-stools-btn-clear',

		// Global container
		mainContainerSelector   : '.js-stools',

		// Filter fields
		searchBtnSelector       : '.js-stools-btn-search',
		filterBtnSelector       : '.js-stools-btn-filter',
		filterContainerSelector : '.js-stools-container-filters',
		filtersHidden           : true,

		// List fields
		listBtnSelector         : '.js-stools-btn-list',
		listContainerSelector   : '.js-stools-container-list',
		listHidden              : false,

		// Ordering specific
		orderColumnSelector     : '.js-stools-column-order',
		orderBtnSelector        : '.js-stools-btn-order',
		orderFieldSelector      : '.js-stools-field-order',
		orderFieldName          : 'list[fullordering]',
		limitFieldSelector      : '.js-stools-field-limit',
		defaultLimit            : 20,

		activeOrder             : null,
		activeDirection         : 'ASC',

		// Extra
		chosenSupport           : true,
		clearListOptions        : false
	};

	// The actual plugin constructor
	function Plugin(element, options) {
		this.element = element;
		this.options = $.extend({}, defaults, options);
		this._defaults = defaults;

		// Initialise selectors
		this.theForm        = $(this.options.formSelector);

		// Filters
		this.filterButton    = $(this.options.formSelector + ' ' + this.options.filterBtnSelector);
		this.filterContainer = $(this.options.formSelector + ' ' + this.options.filterContainerSelector);
		this.filtersHidden   = this.options.filtersHidden;

		// List fields
		this.listButton    = $(this.options.formSelector + ' ' + this.options.listBtnSelector);
		this.listContainer = $(this.options.formSelector + ' ' + this.options.listContainerSelector);
		this.listHidden    = this.options.listHidden;

		// Main container
		this.mainContainer = $(this.options.mainContainerSelector);

		// Search
		this.searchButton = $(this.options.formSelector + ' ' + this.options.searchBtnSelector);
		this.searchField  = $(this.options.formSelector + ' ' + this.options.searchFieldSelector);
		this.searchString = null;
		this.clearButton  = $(this.options.clearBtnSelector);

		// Ordering
		this.orderCols  = $(this.options.formSelector + ' ' + this.options.orderColumnSelector);
		this.orderField = $(this.options.formSelector + ' ' + this.options.orderFieldSelector);

		// Limit
		this.limitField = $(this.options.formSelector + ' ' + this.options.limitFieldSelector);

		// Init trackers
		this.activeColumn    = null;
		this.activeDirection = this.options.activeDirection;
		this.activeOrder     = this.options.activeOrder;
		this.activeLimit     = null;

		// Extra options
		this.chosenSupport    = this.options.chosenSupport;
		this.clearListOptions = this.options.clearListOptions;

		// Selector values
		this._name = pluginName;

		this.init();
	}

	Plugin.prototype = {
		init: function () {
			var self = this;

			// IE < 9 - Avoid to submit placeholder value
			if(!document.addEventListener  ) {
				if (this.searchField.val() === this.searchField.attr('placeholder')) {
					this.searchField.val('');
				}
			}

			// Get values
			this.searchString = this.searchField.val();

			if (this.filtersHidden) {
				this.hideFilters();
			} else {
				this.showFilters();
			}

			if (this.listHidden) {
				this.hideList();
			} else {
				this.showList();
			}

			self.filterButton.click(function(e) {
				self.toggleFilters();
				e.stopPropagation();
				e.preventDefault();
			});

			self.listButton.click(function(e) {
				self.toggleList();
				e.stopPropagation();
				e.preventDefault();
			});

			// Do we need to add to mark filter as enabled?
			self.getFilterFields().each(function(i, element) {
				self.checkFilter(element);
				$(element).change(function () {
					self.checkFilter(element);
				});
			});

			self.clearButton.click(function(e) {
				self.clear();
			});

			// Check/create ordering field
			this.createOrderField();

			this.orderCols.click(function() {

				// Order to set
				var newOrderCol  = $(this).attr('data-order');
				var newDirection = $(this).attr('data-direction');
				var newOrdering  = newOrderCol + ' ' + newDirection;

				// The data-order attrib is required
				if (newOrderCol.length)
				{
					self.activeColumn = newOrderCol;

					if (newOrdering !== self.activeOrder)
					{
						self.activeDirection = newDirection;
						self.activeOrder  = newOrdering;

						// Update the order field
						self.updateFieldValue(self.orderField, newOrdering);
					}
					else
					{
						self.toggleDirection();
					}

					self.theForm.submit();
				}

			});
		},
		checkFilter: function (element) {
			var self = this;

			var option = $(element).find('option:selected');
			if (option.val() !== '') {
				self.activeFilter(element);
			} else {
				self.deactiveFilter(element);
			}
		},
		clear: function () {
			var self = this;

			self.getFilterFields().each(function(i, element) {
				$(element).val('');
				self.checkFilter(element);

				if (self.chosenSupport) {
					$(element).trigger('liszt:updated');
				}
			});

			if (self.clearListOptions) {
				self.getListFields().each(function(i, element) {
					$(element).val('');
					self.checkFilter(element);

					if (self.chosenSupport) {
						$(element).trigger('liszt:updated');
					}
				});

				// Special case to limit box to the default config limit
				$('#list_limit').val(self.options.defaultLimit);
				if (self.chosenSupport) {
					$('#list_limit').trigger('liszt:updated');
				}
			}

			self.searchField.val('');
			self.theForm.submit();
		},
		activeFilter: function (element) {
			var self = this;

			$(element).addClass('active');
			var chosenId = '#' + $(element).attr('id') + '_chzn';
			$(chosenId).addClass('active');
		},
		deactiveFilter: function (element) {
			var self = this;

			$(element).removeClass('active');
			var chosenId = '#' + $(element).attr('id') + '_chzn';
			$(chosenId).removeClass('active');
		},
		getFilterFields: function () {
			return this.filterContainer.find('select,input');
		},
		getListFields: function () {
			return this.listContainer.find('select');
		},
		// Common container functions
		hideContainer: function (container) {
			$(container).hide('fast');
			$(container).removeClass('shown');
		},
		showContainer: function (container) {
			$(container).show('fast');
			$(container).addClass('shown');
		},
		toggleContainer: function (container) {
			if ($(container).hasClass('shown')) {
				this.hideContainer(container);
			} else {
				this.showContainer(container);
			}
		},
		// List container management
		hideList: function () {
			this.hideContainer(this.listContainer);
			this.listButton.removeClass('btn-primary');
		},
		showList: function () {
			this.showContainer(this.listContainer);
			this.listButton.addClass('btn-primary');
		},
		toggleList: function () {
			this.toggleContainer(this.listContainer);

			if (this.listContainer.hasClass('shown')) {
				this.listButton.addClass('btn-primary');
			} else {
				this.listButton.removeClass('btn-primary');
			}
		},
		// Filters container management
		hideFilters: function () {
			this.hideContainer(this.filterContainer);
			this.filterButton.removeClass('btn-primary');
		},
		showFilters: function () {
			this.showContainer(this.filterContainer);
			this.filterButton.addClass('btn-primary');
		},
		toggleFilters: function () {
			this.toggleContainer(this.filterContainer);

			if (this.filterContainer.hasClass('shown')) {
				this.filterButton.addClass('btn-primary');
			} else {
				this.filterButton.removeClass('btn-primary');
			}
		},
		toggleDirection: function () {
			var self = this;

			var newDirection = 'ASC';

			if (self.activeDirection.toUpperCase() == 'ASC')
			{
				newDirection = 'DESC';
			}

			self.activeDirection = newDirection;
			self.activeOrder  = self.activeColumn + ' ' + newDirection;

			self.updateFieldValue(self.orderField, self.activeOrder);
		},
		createOrderField: function () {

			var self = this;

			if (!this.orderField.length)
			{
				this.orderField = $('<input>').attr({
				    type: 'hidden',
				    id: 'js-stools-field-order',
				    'class': 'js-stools-field-order',
				    name: self.options.orderFieldName,
				    value: self.activeOrder + ' ' + this.activeDirection
				});

				this.orderField.appendTo(this.theForm);
			}

			// Add missing columns to the order select
			if (this.orderField.is('select'))
			{
				this.orderCols.each(function(){
					var value     = $(this).attr('data-order');
					var name      = $(this).attr('data-name');
					var direction = $(this).attr('data-direction');

					if (value.length)
					{
						value = value + ' ' + direction;

						var option = self.findOption(self.orderField, value);

						if (!option.length)
						{
							var option = $('<option>');
							option.text(name).val(value);

							// If it is the active option select it
							if ($(this).hasClass('active'))
							{
								option.attr('selected', 'selected');
							}

							// Append the option an repopulate the chosen field
							self.orderField.append(option);
						}
					}

				});

				this.orderField.trigger('liszt:updated');
			}

			this.activeOrder  = this.orderField.val();
		},
		updateFieldValue: function (field, newValue) {
			var self = this;
			var type = field.attr('type');

			if (type === 'hidden' || type === 'text')
			{
				field.attr('value', newValue);
			}
			else if (field.is('select'))
			{
				// Select the option result
				var desiredOption = field.find('option').filter(function () { return $(this).val() == newValue; });

				if (desiredOption.length)
				{
					desiredOption.attr('selected', 'selected');
				}
				// If the option does not exist create it on the fly
				else
				{
					var option = $('<option>');
					option.text(newValue).val(newValue);
					option.attr('selected','selected');

					// Append the option an repopulate the chosen field
					field.append(option);
				}

				// Trigger the chosen update
				if (self.chosenSupport) {
					field.trigger('liszt:updated');
				}
			}
		},
		findOption: function(select, value) {
			return select.find('option').filter(function () { return $(this).val() == value; });
		}
	};

	// A really lightweight plugin wrapper around the constructor,
	// preventing against multiple instantiations
	$.fn[pluginName] = function (options) {
		return this.each(function () {
			if (!$.data(this, "plugin_" + pluginName)) {
				$.data(this, "plugin_" + pluginName, new Plugin(this, options));
			}
		});
	};

})(jQuery, window, document);
