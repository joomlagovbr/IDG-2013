/**@license boxplus: a versatile lightweight pop-up window engine for MooTools
 * @author  Levente Hunyadi
 * @version 0.9.3
 * @remarks Copyright (C) 2009-2011 Levente Hunyadi
 * @remarks Licensed under GNU/GPLv3, see http://www.gnu.org/licenses/gpl-3.0.html
 * @see     http://hunyadi.info.hu/projects/boxplus
 **/

/*
* boxplus: a versatile lightweight pop-up window engine for MooTools
* Copyright 2009-2011 Levente Hunyadi
*
* boxplus is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* boxplus is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with boxplus.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
* Requires MooTools Core 1.2 or later.
* Picasa support requires Request.JSONP from MooTools 1.2 More or later.
*
* Annotated for use with Google Closure Compiler's advanced optimization
* method when supplemented with a MooTools extern file.
*
* Search for "EDIT OPTIONS" to find out where to modify default settings.
*/

;
(function ($) {
	Object.append(Element['NativeEvents'], {
		'popstate': 2,
		'dragstart': 2  // listen to browser-native drag-and-drop events
	});

	/**
	* Converts a query string into an object.
	* @param {string} querystring
	* @return {!Object}
	*/
	function fromQueryString(querystring) {
		var data = {};
		if (querystring.length > 1) {
			querystring.substr(1).split('&').each(function (keyvalue) {
				var index = keyvalue.indexOf('=');
				var key = index >= 0 ? keyvalue.substr(0,index) : keyvalue;
				var value = index >= 0 ? keyvalue.substr(index+1) : '';
				data[unescape(key)] = unescape(value);
			});
		}
		return data;
	}

	/**
	* Identifier of boxplus container element.
	* @type {string}
	* @const
	*/
	var BOXPLUS_ID = 'boxplus';
	/**
	* @type {string}
	* @const
	*/
	var BOXPLUS_HIDDEN = 'boxplus-hidden';
	/**
	* @type {string}
	* @const
	*/
	var BOXPLUS_DISABLED = 'boxplus-disabled';
	/**
	* @type {string}
	* @const
	*/
	var BOXPLUS_UNAVAILABLE = 'boxplus-unavailable';
	/**
	* Time between successive scroll animations [ms].
	* @type {number}
	* @const
	*/
	var BOXPLUS_SCROLL_INTERVAL = 10;
	/**
	* Key under which the cloaked href attribute of the anchor should be stored in the mootools Elements storage.
	* @type {string}
	* @const
	*/
	var BOXPLUS_HREF = 'boxplus-href';

	/**
	* Cloaks an anchor href attribute by moving it to the Elements Storage.
	* @param {Element} anchor
	*/
	function cloak(anchor) {
		if (!anchor.retrieve(BOXPLUS_HREF)) {  // prevent double-obfuscating an anchor
			anchor.store(BOXPLUS_HREF, anchor.get('href'));
			anchor.set('href', 'javascript:void(0);');
		}
	}

	/**
	* Uncloaks an anchor by settings its href attribute based on the value in the Elements Storage.
	* @param {Element} anchor
	*/
	function uncloak(anchor) {
		var href = anchor.retrieve(BOXPLUS_HREF);
		if (href) {
			anchor.set('href', href);
			anchor.eliminate(BOXPLUS_HREF);
		}
	}

	/**
	* Represents the boxplus dialog.
	* Events fired are 'close', 'previous', 'next', 'first', 'last', 'start', 'stop' and 'change'.
	*/
	var boxplusDialog = new Class({
		'Implements': [Events, Options],

		// --- EDIT OPTIONS BELOW TO MODIFY DEFAULTS --- //
		// ---  SEE FURTHER BELOW FOR OTHER OPTIONS  --- //

		/**
		* boxplus dialog options.
		* Normally, these would be configured via a boxplus gallery and not directly.
		*/
		'options': {
			/**
			* Pop-up window theme. If set, stylesheets that have a "title" attribute starting with
			* "boxplus" but with a different ending than specified will be disabled. For instance,
			* the value "darksquare" will enable the stylesheet "boxplus-darksquare" but disable
			* "boxplus-darkrounded" and "boxplus-lightsquare".
			* @type {boolean|string}
			*/
			'theme': false,
			/**
			* Whether navigation controls are displayed.
			* @type {boolean}
			*/
			'navigation': true,
			/**
			* Whether to center pop-up windows smaller than browser window size.
			* @type {boolean}
			*/
			'autocenter': true,
			/**
			* Whether to reduce images that would otherwise exceed screen dimensions to fit
			* the browser window when they are displayed.
			* @type {boolean}
			*/
			'autofit': true,
			/**
			* Duration of animation sequences. Expects a value in milliseconds, or one of 'short' or 'long'.
			* @type {string|number}
			*/
			'duration': 'short',
			/**
			* Easing equation to use for the transition effect.
			* The easing equation determines the speed at which the animation progresses
			* at different stages of an animation. Examples values include 'sine', 'linear' and
			* 'bounce'. For a complete list of supported values see the MooTools framework object
			* Fx.Transitions <http://mootools.net/docs/core/Fx/Fx.Transitions>.
			* @type {string}
			*/
			'transition': 'sine',
			/**
			* Client-side image protection feature.
			* This feature suppresses the browser "contextmenu" and "dragstart" events so that
			* a user cannot easily extract the image with conventional methods. Needless to say,
			* such measures are completely ineffective against advanced users who can always
			* extract the image from the browser cache or use developer page inspection tools
			* like Firebug.
			* @type {boolean}
			*/
			'protection': true,
			/**
			* Scroll speed [px/s].
			* @type {number}
			*/
			'scrollspeed': 200,
			/**
			* Acceleration factor, multiplier of scroll speed in fast scroll mode.
			* @type {number}
			*/
			'scrollfactor': 5,
			/**
			* Default width if no width is specified or can be derived.
			* @type {number}
			*/
			'width': 800,
			/**
			* Default height if no height is specified or can be derived.
			* @type {number}
			*/
			'height': 600
		},

		// --- END OF DEFAULT OPTIONS --- //

		// Properties assigned on initialization:
		//    container,
		//    shadedbackground,
		//    popup,
		//    viewer,
		//    viewerimage,
		//    viewerframe,
		//    viewervideo,
		//    viewerobject,
		//    viewercontent
		//    thumbs

		/**
		* Download URL associated with the current item.
		* @type {string}
		*/
		_url: '',
		/**
		* Actual dimensions of the current item.
		*/
		_imagedims: null,

		/**
		* Timer used in animating the progress indicator.
		*/
		_progresstimer: null,
		/**
		* Timer used in scrolling the quick-access navigation bar.
		*/
		_scrolltimer: null,
		/**
		* Current speed of the quick-access navigation bar.
		* @type {number}
		*/
		_scrollspeed: 0,

		/**
		* Injects the pop-up window HTML code into the document.
		*/
		'initialize': function (options) {
			this['setOptions'](options);  // protect "setOptions" from being renamed during minification

			var self = this;
			self.decelerateScroll();

			/**
			* Creates a boxplus pop-up window element.
			* @param {string|Array.<string>} cls A class name or array of class names to apply to the element.
			* @param {!Object=} attrs An object of attributes to apply to the element.
			* @param {...Element} children Child elements to inject into the element.
			* @return {Element}
			*/
			function _create(cls, attrs, children) {
				var elem = new Element('div', {
					'class': typeof(cls) == 'string' ? 'boxplus-' + cls : cls.map(function (classname) { return 'boxplus-' + classname; }).join(' ')
				});
				if (attrs) {
					elem.set(attrs);
				}
				for (var i = 2; i < arguments.length; i++) {
					elem.adopt(arguments[i]);
				}
				return elem;
			}

			/**
			* @return {Element}
			*/
			function _message(cls) {
				return new Element('span', {
					'class': 'boxplus-' + cls
				});
			}

			/**
			* Binds a callback function to an event.
			* @param {function()} callback The callback function to subscribe for the event.
			* @param {string=} eventtype The name of the event.
			*/
			function _bind(cls, callback, eventtype) {
				if (!eventtype) {
					eventtype = 'click';
				}
				self._getElements(cls).addEvent(eventtype, callback.bind(self));
			}

			// navigation controls in the quick-access navigation bar
			var thumbselem = _create('thumbs', {},
				new Element('ul'),
				_create('rewind'),
				_create('forward')
			);

			// title and text for caption
			var captionelem = _create('caption', {},
				_create('title'),
				_create('text')
			);

			// control buttons outside the image area
			var controlselem = _create('controls', {},
				_create('prev'),
				_create('next'),
				_create('start'),
				_create(['stop','unavailable']),
				_create('close'),
				_create('download'),
				_create('metadata')
			);

			/**
			* @type {string}
			* @const
			*/
			var HIDDEN = 'hidden';
			self.container = _create([], {id: BOXPLUS_ID},
				self.shadedbackground = _create(['background',HIDDEN]),
				self.popup = _create(['dialog',HIDDEN], {},
					_create('progress'),
					_create('sideways', {},
						thumbselem.clone(),
						controlselem.clone(),
						captionelem.clone()
					),
					_create('title'),
					_create('main', {},
						self.centerpanel = _create('center', {},
							self.viewer = _create(['viewer',HIDDEN], {},
								/** @type {HTMLImageElement} */
								self.viewerimage = new Element('img'),
								_create('prev'),
								_create('next'),
								_create('resizer', {},
									_create('enlarge').addEvent('click', function () { self.magnify(); }),
									_create(['shrink','unavailable']).addEvent('click', function () { self.magnify(); })
								),
								self.thumbs = thumbselem.clone(),
								/** @type {HTMLIFrameElement} */
								self.viewerframe = new Element('iframe', {
									'frameborder': 0
								}),
								/** @type {HTMLVideoElement} */
								self.viewervideo = new Element('video', {
									'autoplay': true,
									'controls': true
								}),
								/** @type {HTMLObjectElement} */
								self.viewerobject = _create('object'),
								/** @type {HTMLDivElement} */
								self.viewercontent = _create('content'),
								_create('progress')
							)
						),
						_create('bottom', {},
							thumbselem.clone(),
							controlselem.clone(),
							captionelem.clone()
						)
					),
					_create('lt'),
					_create('t'),
					_create('rt'),
					_create('l'),
					_create(['m',HIDDEN]),
					_create('r'),
					_create('lb'),
					_create('b'),
					_create('rb')
				),

				self.popupclone = _create(['dialog',HIDDEN], {},
					self.sidewaysclone = _create('sideways', {},
						thumbselem.clone(),
						captionelem.clone(),
						controlselem.clone()
					),
					_create('title'),
					_create('main', {},
						self.centerclone = _create('center', {},
							self.viewerclone = _create(['viewer',HIDDEN], {},
								self.viewercontentclone = new Element('div')
							)
						),
						self.bottomclone = _create('bottom', {},
							thumbselem.clone(),
							captionelem.clone(),
							controlselem.clone()
						)
					)
				),

				_message('unknown-type'),
				_message('not-found')
			).inject(document.body);

			// close window when user clicks outside window area (but not on mobile devices)
			if (self.container.getStyle('background-repeat') == 'repeat') {  // test for CSS @media handheld
				self.shadedbackground.addEvent('click', function () { self.close(); });
			}

			/**
			* Fired when the user right-clicks or starts to drag an item in the viewer to open the context menu or copy an image.
			* @param {Event} event An event object.
			*/
			self._onProhibitedUIAction = function (event) {
				return !self.options['protection'] || !self.viewer.getElements('*').contains(event.target);
			};

			/**
			* Fired when the user presses a key while the lightweight pop-up window is shown.
			* @param {Event} event An event object.
			*/
			self._onKeyDown = function (event) {
				if (!['input','textarea'].contains($(event.target).get('tag'))) {  // let form elements handle their own input
					var keyindex = [37,39,36,35,13,27].indexOf(event.code);  // keys are [left arrow, right arrow, home, end, ENTER, ESC]
					if (keyindex >= (self['options']['navigation'] ? 0 : 4)) {  // ignore navigation keys if navigation buttons are disabled
						[self.previous,self.next,self.first,self.last,self.magnify,self.close][keyindex].bind(self)();  // call function with proper context for "this"
						return false;  // cancel event propagation
					}
				}
			};

			/**
			* Fired when the user resizes the browser window while the lightweight pop-up window is shown.
			*/
			var resizeTimer;
			self._onResize = function () {
				window.clearTimeout(resizeTimer);
				if (!self.resizing) {
					resizeTimer = window.setTimeout(function () {
						self.resize.bind(self)();
					}, 10);
				}
			};

			_bind('prev', self.previous);
			_bind('next', self.next);
			_bind('start', self.start);
			_bind('stop', self.stop);
			_bind('close', self.close);
			_bind('download', self.download);
			_bind('metadata', self.toggleMetadata);
			_bind('rewind', self.startRewind, 'mouseover');
			_bind('rewind', self.stopScroll, 'mouseout');
			_bind('rewind', self.accelerateScroll, 'mousedown')
			_bind('rewind', self.decelerateScroll, 'mouseup')
			_bind('forward', self.startForward, 'mouseover');
			_bind('forward', self.stopScroll, 'mouseout');
			_bind('forward', self.accelerateScroll, 'mousedown')
			_bind('forward', self.decelerateScroll, 'mouseup')

			self.setEmpty();
		},

		/**
		* @param {string|Array.<string>} cls
		* @return {string}
		*/
		_class: function (cls) {
			return Array.from(cls).map(function (selector) {
				return selector.replace(/\b([\w-]+)/g, '.boxplus-$1');
			}).join(', ');
		},
		/**
		* @param {string|Array.<string>} cls
		* @return {Element}
		*/
		_getElement: function (cls) {
			return this.popup.getElement(this._class(cls));
		},
		/**
		* @param {string|Array.<string>} cls
		* @return {Elements}
		*/
		_getElements: function (cls) {
			return this.popup.getElements(this._class(cls));
		},
		/**
		* @param {string|Array.<string>} cls
		* @return {Elements}
		*/
		_getClonedElements: function (cls) {
			return this.popupclone.getElements(this._class(cls));
		},
		_toggle: function (cls, clstoggle, state) {
			this._getElements(cls)[state ? 'addClass' : 'removeClass'](clstoggle);
		},
		_toggleCloned: function (cls, clstoggle, state) {
			this._getClonedElements(cls)[state ? 'addClass' : 'removeClass'](clstoggle);
		},
		setAvailable: function (cls, state) {
			this._toggle(cls, BOXPLUS_UNAVAILABLE, !state);
		},
		setAllAvailable: function (cls, state) {
			this._toggle(cls, BOXPLUS_UNAVAILABLE, !state);
			this._toggleCloned(cls, BOXPLUS_UNAVAILABLE, !state);
		},
		setEnabled: function (cls, state) {
			this._toggle(cls, BOXPLUS_DISABLED, !state);
		},
		setAllEnabled: function (cls, state) {
			this._toggle(cls, BOXPLUS_DISABLED, !state);
			this._toggleCloned(cls, BOXPLUS_DISABLED, !state);
		},
		setVisible: function (cls, state) {
			this._toggle(cls, BOXPLUS_HIDDEN, !state);
		},
		setAllVisible: function (cls, state) {
			this._toggle(cls, BOXPLUS_HIDDEN, !state);
			this._toggleCloned(cls, BOXPLUS_HIDDEN, !state);
		},
		_bindEvents: function (events, state) {
			var self = this;
			for (var name in events) {
				window[state ? 'addEvent' : 'removeEvent'](name, events[name]);
			}
		},
		_fireEvent: function (event, arg) {
			this['fireEvent'](event, arg);
		},

		getMessage: function (msg) {
			return this.container.getElement('.boxplus-' + msg).get('html');
		},

		/**
		* Shows the lightweight pop-up window.
		* @param {!Object} options
		*/
		show: function (options) {
			var self = this;
			self['setOptions'](options);  // prevent minification of "setOptions"

			// enable associated theme (if any) and disable other themes that might be linked to the page
			var theme = self['options']['theme'];
			if (theme) {
				// disable unused themes and enable selected theme
				$$('link[rel=stylesheet][title^=boxplus]').set('disabled', true).filter('[title="boxplus-' + theme + '"]').set('disabled', false);
			}

			// toggle navigation buttons
			self.setEnabled(['prev','next','start','stop'], self['options']['navigation']);

			// show visuals
			self.setVisible('bottom', false);    // will be shown when resizing terminates
			self.setVisible('sideways', false);  // will be shown when resizing terminates
			self.center(self.popup);
			$$([self.shadedbackground, self.popup]).removeClass(BOXPLUS_HIDDEN);
			self.shadedbackground.fade('hide').fade('in');

			// register events
			self._bindEvents({
				'contextmenu': self._onProhibitedUIAction,
				'dragstart': self._onProhibitedUIAction,
				'keydown': self._onKeyDown,
				'resize': self._onResize
			}, true);
		},

		/**
		* Hides the lightweight pop-up window.
		* Fired when the user clicks the close button, clicks outside the pop-up window or presses the ESC key.
		*/
		hide: function () {
			var self = this;

			// unregister events
			self._bindEvents({
				'contextmenu': self._onProhibitedUIAction,
				'dragstart': self._onProhibitedUIAction,
				'keydown': self._onKeyDown,
				'resize': self._onResize
			}, false);

			// hide visuals
			self.shadedbackground.fade('out');
			$$([self.shadedbackground, self.popup]).addClass(BOXPLUS_HIDDEN);
		},

		/**
		* Closes the pop-up window.
		*/
		close: function () {
			var self = this;
			self.setEmpty();
			self.resize(function () {
				self._fireEvent('close');
			});
		},

		/**
		* Navigates to the first image/content.
		* Fired when the user clicks the navigate to first control or presses the HOME key.
		*/
		first: function () {
			this._fireEvent('first');
		},

		/**
		* Navigates to the previous image/content.
		* Fired when the user clicks the navigate to previous control or presses the left arrow key.
		*/
		previous: function () {
			this._fireEvent('previous');
		},

		/**
		* Navigates to the next image/content.
		* Fired when the user clicks the navigate to next control or presses the right arrow key.
		*/
		next: function () {
			this._fireEvent('next');
		},

		/**
		* Navigates to the last image/content.
		* Fired when the user clicks the navigate to last control or presses the END key.
		*/
		last: function () {
			this._fireEvent('last');
		},

		/**
		* Start the slideshow timer.
		* Fired when the user clicks the play control.
		*/
		start: function () {
			this._fireEvent('start');
		},

		/**
		* Stop the slideshow timer.
		* Fired when the user clicks the stop control.
		*/
		stop: function () {
			this._fireEvent('stop');
		},

		magnify: function () {
			var self = this;
			self._getElements('shrink').toggleClass(BOXPLUS_UNAVAILABLE);
			self._getElements('enlarge').toggleClass(BOXPLUS_UNAVAILABLE);
			self._setPositioning();
			self.resize();
		},

		startRewind: function () {
			this.startScroll(-1);
		},

		startForward: function () {
			this.startScroll(1);
		},

		/**
		* Starts scrolling the thumbs navigation bar either forward or backward.
		* @param {number} dir -1 to scroll backward, 1 to scroll forward, 0 to initialize controls (no scrolling)
		*/
		startScroll: function (dir) {
			var self = this;
			var target = self._getElements('thumbs').getElement('ul');  // thumbs navigation bars in either panel
			var bar = self.thumbs.getElement('ul');  // thumbs navigation bar in main panel

			// current left offset of thumbs navigation bar w.r.t. left edge of viewer
			var x = bar.getStyle('left').toInt();  // 0 > x > minpos
			x = isNaN(x) ? 0 : x;

			// maximum negative value permitted as left offset w.r.t. left edge of viewer
			var minpos = self.viewer.getSize().x - bar.getSize().x;

			// set initial values for current state of forward and rewind scroll buttons
			var forward_current;
			var rewind_current;

			// set initial visibility of forward and rewind buttons
			self.setVisible('forward', true);
			self.setVisible('rewind', true);

			// assign scroll function, avoid complex operations
			var func = function () {
				var forward_next = true;
				var rewind_next = true;

				x -= dir * self._scrollspeed;
				if (x <= minpos) {
					x = minpos;
					forward_next = false;
				}
				if (x >= 0) {
					x = 0;
					rewind_next = false;
				}

				// update visibility of forward and rewind scroll buttons only if their visibility status has changed
				forward_current === forward_next || self.setVisible('forward', forward_current = forward_next);
				rewind_current === rewind_next || self.setVisible('rewind', rewind_current = rewind_next);
				target.setStyle('left', x + 'px');
			};

			// invoke scroll function to force initial visibility
			func();

			// start scrolling only if it would advance thumbs navigation bar in either direction
			if (dir) {
				self._scrolltimer = window.setInterval(func, BOXPLUS_SCROLL_INTERVAL);
			}
		},

		stopScroll: function () {
			this.decelerateScroll();
			if (this._scrolltimer) {
				window.clearInterval(this._scrolltimer);
				this._scrolltimer = null;
			}
		},

		accelerateScroll: function () {
			this._scrollspeed = this['options']['scrollspeed'] * BOXPLUS_SCROLL_INTERVAL * this['options']['scrollfactor'] / 1000;
		},

		decelerateScroll: function () {
			this._scrollspeed = this['options']['scrollspeed'] * BOXPLUS_SCROLL_INTERVAL / 1000;
		},

		download: function () {
			window.location.href = this._url;
		},

		/**
		* @param {boolean} state
		*/
		showMetadata: function (state) {
			var self = this;
			state = !state;  // invert state (show controls when metadata is NOT displayed)
			self.setVisible('resizer', state);
			self.setVisible('thumbs', state);
			self.setVisible('viewer prev', state);
			self.setVisible('viewer next', state);
			var elems = $$([self.viewerimage, self.viewervideo, self.viewerobject, self.viewerframe]);
			if (state) {
				elems.removeClass(BOXPLUS_HIDDEN);
				self.viewercontent.addClass(BOXPLUS_HIDDEN);
			} else {
				elems.addClass(BOXPLUS_HIDDEN);
				self.viewercontent.removeClass(BOXPLUS_HIDDEN);
			}
		},

		/**
		* Shows or hides image metainformation.
		* Fired when the user clicks the metadata icon.
		*/
		toggleMetadata: function () {
			this.showMetadata(!this.isMetadata());
		},

		/**
		* @return {boolean}
		*/
		isMetadata: function () {
			return !this.viewercontent.hasClass(BOXPLUS_HIDDEN);
		},

		/**
		* Sets an image (with metadata) to be shown in the pop-up window.
		* @param {HTMLImageElement} image An image element.
		* @param {string=} url
		* @param {string|Element|HTMLElement=} metadata
		*/
		setImage: function (image, url, metadata) {
			var self = this;
			self.setEmpty();
			if (image) {
				// store image dimensions for future use to be able to restore image to original size
				self._imagedims = {
					width: image.width,
					height: image.height
				};

				// set image
				self.viewerimage.set('src', image.src).set(self._imagedims).removeClass(BOXPLUS_UNAVAILABLE);

				// set download availability
				self._url = url;
				self.setAvailable('download', url);

				// set metadata availability
				if (metadata) {
					switch (typeof(metadata)) {
						case 'string':
							self.viewercontent.set('html', metadata); break;
						default:
							metadata = $(metadata);  // make Element methods available on object
							if (metadata) {
								self.viewercontent.adopt(metadata.clone());
							}
					}
					self.viewercontent.removeClass(BOXPLUS_UNAVAILABLE);
				}
				self.setAvailable('metadata', metadata);
			}
		},

		/**
		* @param {Element} elem
		*/
		setContent: function (elem) {
			var self = this;
			self.setEmpty();

			self.setVisible('resizer', false);
			self.setVisible('thumbs', false);
			self.setVisible('viewer prev', false);
			self.setVisible('viewer next', false);
			self.viewercontent.adopt(elem.clone()).removeClass(BOXPLUS_UNAVAILABLE).removeClass(BOXPLUS_HIDDEN);

			self._imagedims = {
				width: self.options.width,
				height: self.options.height
			};
		},

		/**
		* Set dimensions data based on explicitly set values.
		* @param {HTMLAnchorElement} anchor An HTML anchor element.
		*/
		setDimensions: function (anchor) {
			var dims = fromQueryString(anchor.search);
			dims = {
				width: dims.width ? dims.width.toInt() : this.options.width,
				height: dims.height ? dims.height.toInt() : this.options.height
			};
			this._imagedims = dims;
		},

		/**
		* @param {HTMLAnchorElement} anchor An HTML anchor element.
		*/
		setObject: function (anchor) {
			var self = this;
			self.setEmpty();

			// fetch dimension data
			self.setDimensions(anchor);
			var dims = self._imagedims;

			var href = anchor.href;
			var path = anchor.pathname;
			if (/\.(ogg|webM)$/i.test(path)) {  // supported by HTML5-native <video> tag
				self.viewervideo.set(dims).set('src', href).setStyles(dims).removeClass(BOXPLUS_UNAVAILABLE);
			} else {
				self.viewerobject.setStyles(dims).removeClass(BOXPLUS_UNAVAILABLE);
				if (/\.(pdf|mov)$/i.test(path)) {
					var classid;
					var type;
					var attrs = {
						src: href
					};

					if (/\.pdf$/i.test(path)) {
						classid = 'CA8A9780-280D-11CF-A24D-444553540000';
						type = 'application/pdf';
					} else if (/\.mov$/i.test(path)) {
						classid = '02BF25D5-8C17-4B23-BC80-D3488ABDDC6B';
						type = 'video/quicktime';
					} else if (/\.mpe?g/i.test(path)) {
						classid = '22d6f312-b0f6-11d0-94ab-0080c74c7e95';
						type = 'video/mpeg';
					}

					/**
					* Converts an object into a name="value" HTML attribute list.
					*/
					function _getAsAttributeList(attrs) {
						var s = '';
						for (var name in attrs) {
							s += ' ' + name + '="' + attrs[name] + '"';
						}
						return s;
					}

					/**
					* Converts an object into a list of HTML <param /> elements.
					*/
					function _getAsParameterList(attrs) {
						var s = '';
						for (var name in attrs) {
							s += '<param name="' + name + '" value="' + attrs[name] + '" />';
						}
						return s;
					}

					// build custom HTML string of nested <object> elements with the specified dimensions and attributes

					self.viewerobject.set('html',
						'<object' + _getAsAttributeList(Object.merge({
							'classid': 'clsid:' + classid
						}, dims)) + '>' +
						_getAsParameterList(attrs) +
						'<!--[if lt IE 9]><!--><object' + _getAsAttributeList(Object.merge({
							'type': type,
							'data': href
						}, dims)) + '>' + dialog.getMessage('unknown-type').replace('%s', type) + '</object><!--<![endif]-->' +
						'</object>'
					);
				} else {  // /\.swf$/i.test(path)
					// classid = 'D27CDB6E-AE6D-11cf-96B8-444553540000';
					new Swiff(href, Object.merge({
						'container': self.viewerobject,
						'params': {
							'allowFullScreen': true
						}
					}, dims));
				}
			}
		},

		/**
		* @param {HTMLAnchorElement} anchor An HTML anchor element.
		*/
		setFrame: function (anchor) {
			var self = this;
			self.setEmpty();
			self.setDimensions(anchor);
			self.viewerframe.set('src', anchor.href).setStyles(self._imagedims).removeClass(BOXPLUS_UNAVAILABLE);
		},

		/**
		* Clears all content from the pop-up window.
		*/
		setEmpty: function () {
			var self = this;

			// reset content
			$$([self.viewercontent, self.viewerimage, self.viewervideo, self.viewerobject, self.viewerframe]).addClass(BOXPLUS_UNAVAILABLE);
			self.viewercontent.empty();
			self.viewerimage.erase('src');
			!self.viewervideo.pause || self.viewervideo.pause();
			self.viewervideo.erase('src');
			self.viewerobject.empty();
			self.viewerframe.set('src', 'about:blank').erase('src');

			// reset pop-up window size
			self.viewer.setStyles(self._imagedims = {
				width: 150,
				height: 150
			});

			// clear download URL
			self._url = '';
			self.setAvailable('download', false);
			self.setAvailable('metadata', false)

			// clear title and description text
			self.setCaption();
		},

		/**
		* Sets title and description text.
		* @param {string} title
		* @param {string} text
		*/
		setCaption: function (title, text) {
			var self = this;
			self._getElements('title').set('html', title);
			self._getElements('text').set('html', text);
			self._getClonedElements('title').set('html', title);
			self._getClonedElements('text').set('html', text);
			self.setAllAvailable('title', title);
			self.setAllAvailable('text', text);
			self.setAllAvailable('caption', title || text);
		},

		/**
		* @param {string} pos
		*/
		setCaptionPosition: function (pos) {
			this.setAllEnabled('bottom', false);
			this.setAllEnabled('sideways', false);
			switch (pos) {
				case 'bottom':
					this.setAllEnabled('bottom', true); break;
				case 'sideways':
					this.setAllEnabled('sideways', true); break;
			}
		},

		/**
		* Toggles quick-access thumbnail navigation bars inside/outside image viewport.
		* @param {string} pos 'inside' or 'outside'
		*/
		setThumbPosition: function (pos) {
			this.setAllEnabled('thumbs', false);
			switch (pos) {
				case 'inside':
					this.setAllEnabled('viewer thumbs', true);
					break;
				case 'outside':
					this.setAllEnabled('bottom thumbs', true);
					this.setAllEnabled('sideways thumbs', true);
					break;
			}
		},

		/**
		* Adds thumbnails to the quick-access thumbnail navigation bars.
		* @param {Elements} images The thumbnail images to use.
		*/
		addThumbs: function (images) {
			var self = this;
			self._getElements('thumbs').each(function (item) {
				images.each(function (image) {
					new Element('li').adopt(image.clone().addEvent('click', function () {
						var item = $(this).getParent();
						self._fireEvent('change', item.getParent().getChildren().indexOf(item));
					})).inject(item.getElement('ul'));
				});
			});
			self.setAvailable('thumbs', images.length > 1);
		},

		/**
		* Removes all thumbnails from the quick-access thumbnail navigation bars.
		*/
		clearThumbs: function () {
			this._getElements('thumbs').each(function (item) {
				item.getElement('ul').empty();
			});
		},

		isShrunk: function () {
			var self = this;
			// resizing videos impacts performance and HTML <object> does not always support dynamic resizing
			return self.viewervideo.get('src') || self.viewerobject.getChildren().length ? false : self._getElement('shrink').hasClass(BOXPLUS_UNAVAILABLE);
		},

		/**
		* @param {Element} obj
		*/
		getCenter: function (obj) {
			var winsize = window.getSize();
			var objsize = obj.getSize();
			var x = (0).max((winsize['x'] - objsize['x']) / 2);  // function max avoids dialog extending beyond left or top edge where browser does not let user scroll
			var y = (0).max((winsize['y'] - objsize['y']) / 2);
			if (this.popup.getStyle('position') != 'fixed') {
				var scroll = window.getScroll();
				x += scroll['x'];
				y += scroll['y'];
			}
			return {
				'x': x,
				'y': y
			};
		},
		center: function (obj) {
			obj.setPosition(this.getCenter(obj));
		},

		recenter: function () {
			var self = this;
			self.center(self.popupclone);
			self.popup.set('morph', {
				duration: self.options.duration,
				link: 'cancel'
			}).morph(self.popupclone.getStyles(['left','top']));
		},

		/**
		* Sets absolute or fixed positioning on the pop-up window.
		*/
		_setPositioning: function() {
			var self = this;

			var iscentered = self['options']['autocenter'] && self.isShrunk();
			var dst = iscentered ? 'fixed' : 'absolute';
			var src = self.popup.getStyle('position');

			var position = self.popup.getPosition();
			var x = position['x'];
			var y = position['y'];
			if (src != dst) {
				var scroll = window.getScroll();
				if (iscentered) {  // fixed positioned target, absolute positioned at the moment
					x -= scroll['x'];  // absolute positioning takes into account window scroll
					y -= scroll['y'];
				} else {  // absolute positioned target, fixed positioned at the moment
					x += scroll['x'];  // fixed positioning does not take into account window scroll
					y += scroll['y'];
				}
			}
			self.popup.setStyle('position', dst);
			self.popup.setPosition({
				'x': x,
				'y': y
			});
		},

		/**
		* Resizes the pop-up window dialog.
		* @param {function()=} callback A function to invoke when the animated sizing completes.
		*/
		resize: function (callback) {
			var self = this;
			self.resizing = true;

			// hide bottom and sideways caption area temporarily while resizing
			self.setVisible('bottom', false);
			self.setVisible('sideways', false);

			// hide viewer area
			self.setVisible('viewer', false);

			// whether only HTML content is shown (no image, video or flash)
			var contentonly = !self.viewerimage.get('src') && !self.viewervideo.get('src') && !self.viewerobject.getChildren().length && !self.viewerframe.get('src');

			// show metadata only if available
			self.showMetadata(contentonly);  // hide metadata (show image, video or flash instead)

			// show quick-access thumbnail navigation bar only if available
			self.setVisible('thumbs', self._getElement('thumbs').getChildren().length > 1);

			// calculate pop-up window size based on internal image copy
			var w = self._imagedims.width;
			var h = self._imagedims.height;

			/**
			* Updates the size of the viewer and returns the new dimensions of the dialog.
			*/
			function _dialogresize(w, h) {
				self.viewerclone.setStyles({
					width: w,
					height: h
				});
				return self.popupclone.getSize();
			}
			self.viewerclone.setStyle('margin-right', self.sidewaysclone.getSize().x);

			// calculate size for large images that need shrinking
			var winsize = window.getSize();
			var dlgsize;
			if (contentonly) {
				dlgsize = _dialogresize(w, 'auto');
				self.viewercontentclone.empty();
				var children = self.viewercontent.getChildren();
				if (children.length) {
					self.viewercontentclone.adopt(children);  // temporarily give children to other clone parent
				}
				h = self.viewerclone.getSize().y;
				dlgsize = _dialogresize(w, h);
				dlgsize = _dialogresize(Math.min(winsize.x, dlgsize.x) - (dlgsize.x-w), Math.min(winsize.y, dlgsize.y) - (dlgsize.y-h));
				if (children.length) {
					self.viewercontent.adopt(children);  // take children back from clone parent
				}
			} else {
				dlgsize = _dialogresize(w, h);
				var needShrunk = self['options']['autofit']  // autofit is enabled
					&& ((winsize.x < dlgsize.x) || (winsize.y < dlgsize.y));  // does not fit browser window dimensions
				if (needShrunk && self.isShrunk()) {  // needs shrinking and is in shrunk mode
					var ratio;
					if ((ratio = winsize.x / dlgsize.x) < 1.0) {
						w *= ratio;
						h *= ratio;
						dlgsize = _dialogresize(w, h);
					}
					while ((ratio = winsize.y / dlgsize.y) < 1.0) {
						w *= ratio;
						h *= ratio;
						dlgsize = _dialogresize(w, h);
					}
				}
				self.setAvailable('resizer', needShrunk);
			}

			// set positioning
			self._setPositioning();

			// calculate pop-up window center position
			self.center(self.popupclone);

			function _dialogdimensions() {
				return self.popupclone.getStyles(['left','top','width','height']);
			}

			// hide bottom and sideways panel
			self.setAllAvailable('bottom', false);
			self.setAllAvailable('sideways', false);

			// set center panel to occupy the entire content of the pop-up window
			self.centerpanel.setStyle('height', '100%');

			// morph pop-up window to appropriate position and size
			var dims = _dialogdimensions();  // reduced height
			var params = {
				'duration': self['options']['duration'],
				'link': 'cancel',
				'transition': self['options']['transition']
			};
			var morph = new Fx.Morph(self.popup, Object.merge(params, {
				'onComplete': function () {
					// clear forced height of center panel, the pop-up window dimensions should allow for bottom and sideways panel
					self.centerpanel.setStyle('height', 'auto');

					// set dimensions of viewer when first resizing phase completes
					var viewerdims = self.viewerclone.getStyles(['width','height']);
					self.viewerframe.setStyles(viewerdims);  // inline frame size should reflect viewer size
					self.viewer.setStyles(viewerdims);

					// scroll inner content window into view
					self.viewercontent.scrollTo(0,0);

					// show viewer content
					self.setVisible('viewer', true);

					// reset thumbnail quick-access navigation bar
					self.startScroll(0);

					// invoke callback if defined
					callback && callback();

					new Fx.Morph(self.popup, Object.merge(params, {
						'onComplete': function () {
							self.setAvailable('bottom', true);
							self.setAvailable('sideways', true);

							// show bottom and sideways caption area temporarily hidden
							self.setVisible('bottom', true);
							self.setVisible('sideways', true);

							// set resizing complete
							self.resizing = false;

							// re-center dialog if page has been scrolled during resizing
							self.recenter();
						}
					})).start(_dialogdimensions());
				}
			}));

			morph.start(dims);
			self.bottomclone.removeClass(BOXPLUS_UNAVAILABLE);
			self.sidewaysclone.removeClass(BOXPLUS_UNAVAILABLE);
		},

		/**
		* Enables or disables progress indicators.
		* A progress indicator is a PNG image with alpha transparency
		* @param {boolean} state
		*/
		toggleProgress: function (state) {
			var indicators = this._getElements('progress');
			if (this._progresstimer) {
				window.clearInterval(this._progresstimer);
			}
			if (indicators.length && state) {
				/** @type {string} */
				var backpos = indicators[0].getStyle('background-position');  // extract first integer from a string like "-64px 0px"
				var offset = backpos ? backpos.toInt() : 0;
				this._progresstimer = window.setInterval(function () {
					indicators.setStyle('background-position', (offset = (offset - 32) % 384) + 'px 0');  // 384px = 12 states * 32px width
				}, 150);
			} else {
				this._progresstimer = null;
			}
			this.setVisible('progress', state);
		}
	});

	var dialog;
	window.addEvent('domready', function () {
		dialog = new boxplusDialog();  // inject pop-up HTML to document
	});

	/**
	* A boxplus gallery.
	* Fires the following events:
	* 1. change:  fired when new content is being shown
	* 2. open:    fired when the pop-up window opens
	* 3. close:   fired when the pop-up window closes
	* @constructor
	* @param {Element|Elements} el
	* @param {!Object=} options
	*/
	var boxplus = new Class({
		'Implements': [Events,Options],

		/**
		* The index of the currently shown item. Read only.
		* @type {number}
		*/
		current: -1,
		/**
		* @type {Elements}
		*/
		_anchors: $$([]),
		_timer: null,

		// --- EDIT FUNCTIONS BELOW TO MODIFY DEFAULT TITLE, DESCRIPTION, DOWNLOAD URL AND METADATA --- //

		/**
		* Title text that belongs an anchor.
		* @param {Element} anchor A mootools Element object representing the anchor.
		* @return {?string} Raw HTML data as a string.
		*/
		_getTitle: function (anchor) {
			var image = anchor.getElement('img');
			return anchor.retrieve('title') || (image ? image.getProperty('alt') : '');
		},
		/**
		* Description text that belongs to an anchor.
		* @param {Element} anchor A mootools Element object representing the anchor.
		* @return {string} Raw HTML data as a string.
		*/
		_getText: function (anchor) {
			return anchor.retrieve('summary') || anchor.getProperty('title');
		},
		/**
		* Download URL associated with an anchor.
		* @param {Element} anchor A mootools Element object representing the anchor.
		* @return {string} A URL.
		*/
		_getDownloadUrl: function (anchor) {
			return anchor.retrieve('download');
		},
		/**
		* Metadata associated with an anchor.
		* @param {Element} anchor A mootools Element object representing the anchor.
		* @return {string|Element} Raw HTML data as a string, or an Element object.
		*/
		_getMetadata: function (anchor) { },

		// --- EDIT OPTIONS BELOW TO MODIFY DEFAULTS --- //
		// ---  SEE FURTHER ABOVE FOR OTHER OPTIONS  --- //

		/**
		* boxplus gallery options.
		*/
		'options': {
			/*
			onChange: function () {},    // triggered when the current item shown is about to be changed
			onClose: function () {},     // triggered when the pop-up window has been closed
			*/
			/**
			* Time spent viewing an image when slideshow mode is active, or 0 to disable slideshow mode.
			* @type {number}
			*/
			'slideshow': 0,
			/**
			* Whether to start a slideshow when the dialog opens.
			* @type {boolean}
			*/
			'autostart': false,
			/**
			* Whether the image/content sequence loops such that the first image/content follows the last.
			* @type {boolean}
			*/
			'loop': false,
			/**
			* Placement of captions. Takes the value 'bottom' (below the image), 'sideways' (next to the image) or 'none'.
			*/
			'captions': 'bottom',
			/**
			* Placement of thumbnail navigation bar. Takes the value 'inside' (over the image), 'outside' (in the caption area) or 'none'.
			*/
			'thumbs': 'inside',
			/**
			* Whether to cloak anchor URLs. Cloaked anchors will not reveal the target image URL
			* when the user positions the mouse cursor over the image.
			* Cloaking URLs also prevents other javascript code from reading the anchor "href" attribute.
			*/
			'cloak': false
		},

		// Other options include (see their default definition above):
		//  getTitle
		//  getText
		//  getDownloadUrl
		//  getMetadata

		// --- END OF DEFAULT OPTIONS --- //

		/**
		* @param {Element} elem
		* @param {!Object} options
		*/
		'initialize': function (elem, options) {
			var self = this;

			// find anchors that belong to a gallery.
			var anchors;  // a collection of anchors that form the gallery
			var tag = elem.get('tag');
			if (elem.each) {  // is a collection
				if (tag.every(function (item) { return item == 'a'; })) {
					anchors = elem;
					self.current = 0;
				} else {
					return;
				}
			} else if (tag == 'a') {  // is a single anchor item
				boxplus['extenders'].each(function (fn) {
					anchors = fn.bind(self)(elem);
				});

				if (!anchors) {  // anchor not recognized by any of the extenders
					// extend with related anchors (if any) whose rel attribute starts with "boxplus-" or "boxplus["
					var rel = elem.get('rel');
					anchors = $$(rel && rel.test(/^boxplus\b(?!$)/) ? 'a[rel="' + rel + '"]' : elem);

					self.current = anchors.indexOf(elem);
				}
			} else if (/^[udo]l$/.test(tag)) {  // is a single list item
				anchors = $$(elem.getChildren('li,dt').map(function (item) {
					return item.getElement('a');
				}).filter(function (item) {
					return item;  // filter null values
				}));
				self.current = anchors.length > 0 ? 0 : -1;
			} else {
				self.initialize(elem.getElement('ul,ol,dl'), options);
				return;
			}

			// interpret settings
			self['setOptions'](options);
			if (options) {
				self._getTitle = [options['getTitle'], self._getTitle].pick();
				self._getText = [options['getText'], self._getText].pick();
				self._getDownloadUrl = [options['getDownloadUrl'], self._getDownloadUrl].pick();
				self._getMetadata = [options['getMetadata'], self._getMetadata].pick();
			}

			// click event bindings
			anchors.addEvent('click', function () {  // event "click" opens the gallery showing image of selected anchor
				self.show['delay'](1, self, this);  // this = image clicked, delay required for seamless history support (event processing function must exit before function "show" is invoked)
				return false;
			});

			// cloak anchor href attributes
			self['options']['cloak'] && anchors.each(cloak);

			// set anchors used in extracting image properties
			self._anchors = anchors;
		},

		/**
		* Shows the gallery in a pop-up window.
		* @param {Element} anchor An anchor (HTML <a>) that points to an image/content, or a list (HTML <ul> or <ol>) with each item containing an anchor.
		*/
		'show': function (anchor) {
			var self = this;

			if (anchor) {
				self.current = (0).max(self._anchors.get('href').indexOf(anchor.get('href')));
			}

			dialog.hide();  // hide other dialog if visible

			// show progress indicator
			dialog.toggleProgress(true);

			// toggle captions below/next to image viewport based on settings
			dialog.setCaptionPosition(self['options']['captions']);

			// toggle thumbnail bars inside/outside image viewport based on settings
			dialog.setThumbPosition(self['options']['thumbs']);

			// add thumbnails
			dialog.addThumbs($$(self._anchors.map(function (anchor) {
				var image = [anchor.retrieve('thumb'), anchor.getElement('img')].pick();  // use thumbnail associated with anchor (via element storage) or find an image child
				if (image) {
					// thumbnail image source, inspecting all candidate attributes
					var attrthumb = image.get('data-thumb');
					return new Element('img', {
						'src': attrthumb ? attrthumb : image.get('src')
					});
				} else {
					return null;
				}
			})));

			// associate events with callback functions
			var events = ['previous','next','first','last','start','stop','change','close'];
			var callbacks = events.map(function (event) {
				return self[event].bind(self);
			});
			self.dialogevents = callbacks.associate(events);

			// add history support
			self._updateHistory(anchor['href']);
			window.addEvent('popstate', self.close['bind'](self));

			// subscribe to dialog box events
			dialog.addEvents(self.dialogevents);
			dialog.show(self['options']);

			// toggle slideshow start button
			var allowSlideshow = self['options']['slideshow'] && self._anchors.length > 1;
			dialog.setEnabled('start', allowSlideshow);

			// change to image
			self._replace(self.current);

			if (allowSlideshow && self['options']['autostart']) {
				self.start();
			}

			// fire onShow event
			self._fireEvent('open');
		},

		'close': function () {
			dialog.setEmpty();
			dialog.removeEvents(this.dialogevents);
			dialog.hide();
			dialog.clearThumbs();

			// unwind history stack
			while (window.history.state == 'boxplus') {  // boxplus uses the special history state string "boxplus"
				window.history.go(-1);
			}

			// fire onClose event
			this._fireEvent('close');
		},

		'previous': function () {
			this._change(this.current - 1);
		},

		'next': function () {
			this._change(this.current + 1);
		},

		'first': function () {
			this._change(0);
		},

		'last': function () {
			this._change(this._anchors.length - 1);
		},

		/**
		* @param {number} index
		*/
		'change': function (index) {
			this._change(index);
		},

		'start': function () {
			var self = this;
			if (!self._timer) {
				self._timer = setTimeout(self.next.bind(self), self['options']['slideshow']);
			}

			dialog.setAvailable('start', false);
			dialog.setAvailable('stop', true);
		},

		'stop': function () {
			var self = this;
			if (self._timer) {
				clearTimeout(self._timer);
				self._timer = null;
			}

			dialog.setAvailable('stop', false);
			dialog.setAvailable('start', true);
		},

		/**
		* @param {number} index
		*/
		_change: function (index) {
			var self = this;
			if (index != self.current && (self['options']['loop'] || index >= 0 && index < self._anchors.length)) {
				self._replace(index);
				self._fireEvent('change');
			}
		},

		_fireEvent: function (event, arg) {
			this['fireEvent'](event, arg);
		},

		/**
		* Updates the latest history entry, either injecting a new entry or updating an existing one.
		* The history entry is updated only if the latest entry has also been injected by this class.
		* @param {string} href The new URL for the topmost history entry.
		*/
		_updateHistory: function (href) {
			// check if the latest history entry has been injected by boxplus (which uses the special history state string "boxplus")
			var hist = window.history;
			var name = hist.state == 'boxplus' ? 'replaceState' : 'pushState';

			// check for history support and execute function
			var fn = hist[name];
			if (fn) {
				try {
					fn('boxplus', '', href);
				} catch (err) {
					// catch security vulnerability errors (e.g. site URL domain does not match image URL domain)
				}
			}
		},

		/**
		* @param {number} index
		*/
		_replace: function (index) {
			var self = this;
			var noSlideshow = !self._timer;
			self.stop();  // stop slideshow animation

			dialog.setEmpty();  // clear current image
			var count = self._anchors.length;
			var last = index >= count - 1;
			dialog.setVisible('viewer', false);  // hide viewer
			dialog.setAvailable('start', !last);
			var loop = self['options']['loop'];
			dialog.setAvailable('prev', loop && count > 1 || index > 0);
			dialog.setAvailable('next', loop && count > 1 || !last);

			function _show() {
				dialog.resize(function () {
					dialog.toggleProgress(false);
					noSlideshow || last || self.start();  // continue slideshow if possible
				});
			}

			function _showCaption(anchor) {
				dialog.setCaption(self._getTitle(anchor), self._getText(anchor));
			}

			function _showImage(anchor, image) {
				dialog.setImage(image, self._getDownloadUrl(anchor), self._getMetadata(anchor));
				_showCaption(anchor);
				_show();
			}

			function _showContent(elem) {
				dialog.setContent(elem);
				_show();
			}

			if (count > 0) {
				self.current = (index + count) % count;  // avoid mod operator with negative numbers
				var anchor = self._anchors[self.current];

				// un-cloak URLs to be able to extract anchor parameters
				uncloak(anchor);

				// update history
				self._updateHistory(anchor['href']);

				// extract anchor parameters
				var href = anchor.get('href');  // <a> href (as it occurs in source)
				var url = anchor.href;  // <a> href (resolved)
				var path = anchor.pathname;

				if (/^#/.test(href)) {  // content in the same document
					var elem = $(href.substr(1));
					switch (elem.get('tag')) {
						case 'img':
							_showImage(anchor, elem);
							break;
						default:
							_showContent(elem);
					}
				} else if (/\.(txt|html?)$/i.test(path) && anchor['host'] == window['location']['host']) {  // use AJAX only for requests to the same domain
					new Request.HTML({
						'url': url,
						'onSuccess': function (html) {
							_showContent($$(html));
						},
						'onFailure': function (xhr) {
							alert(dialog.getMessage('not-found'));
							dialog.hide();
						}
					}).get();
				} else if (/\.(gif|jpe?g|png)$/i.test(path)) {  // preload image
					$(new Image).addEvent('load', function () {  // triggered when the image has been preloaded
						_showImage(anchor, this);  // the keyword 'this' refers to the image
					}).set('src', url);
				} else if (/\.(pdf|mov|mpe?g|ogg|swf|webM|wmv)$/i.test(path) || /(viddler|vimeo|youtube)\.com$/.test(anchor.hostname)) {
					dialog.setObject(anchor);
					_showCaption(anchor);
					_show();
				} else if (anchor.protocol != 'javascript:') {
					dialog.setFrame(anchor);
					_showCaption(anchor);
					_show();
				}

				// re-cloak URLs if needed
				self['options']['cloak'] && cloak(anchor);
			}
		}
	});

	// create constructor and add static methods
	Object.append(window['boxplus'] = boxplus, {
		/**
		* List of custom URL parser functions.
		*/
		'extenders': [
			/**
			* Picasa extender.
			* Remove this function if you do not need Picasa support.
			*/
			function (anchor) {
				var self = this;
				var match;  // results of a regular expression match
				if (Request.JSONP && (match = anchor.href.match(/https?:\/\/picasaweb.google.com\/data\/feed\/(?:api|base)\/user\/([^\/?#]+)\/albumid\/([^\/?#]+)/))) {
					new Request.JSONP({
						'url': 'http://picasaweb.google.com/data/feed/api/user/' + match[1] + '/albumid/' + match[2],
						'data': {
							'alt': 'json',
							'imgmax': 800,      // maximum image size (uncropped)
							'kind': 'photo',
							'thumbsize': '64u'  // maximum thumbnail image size (uncropped or cropped)
						},
						'onComplete': function (data) {
							self._anchors = $$([]);
							data['feed']['entry'].each(function (entry) {
								var thumb = entry['media$group']['media$thumbnail'][0];
								self._anchors.include(
									new Element('a', {
										'href': entry['content']['src'],
										'title': entry['summary']['$t']
									}).adopt(
										new Element('img', {
											'width': thumb['width'],
											'height': thumb['height'],
											'alt': entry['title']['$t'],
											'src': thumb['url']
										})
									)
								);
							});
							self.current = 0;
						}
					}).send();
					return $$(anchor);
				}
			}
		],

		/**
		* Auto-discovery service.
		* Remove this function if you do not need automatic boxplus binding.
		* @param {boolean} strict
		* @param {!Object} options
		*/
		'autodiscover': function (strict, options) {
			window.addEvent('domready', function () {
				// links part of a gallery
				var groups = [];
				$$('a[rel^=boxplus]:not([rel=boxplus])').each(function (item) {  // leave out individual items
					groups.include(item.get('rel'));
				});
				groups.each(function (group) {
					new boxplus($$('a[rel="'+ group +'"]'), options);
				});

				// individual links with rel attribute set
				$$('a[rel=boxplus]').each(function (item) {
					new boxplus(item, options);
				});

				if (!strict) {
					// individual links to images or flash not part of a gallery
					$$('a[href]:not([rel^=boxplus])').filter(function (item) {
						return /\.(gif|jpe?g|png|swf)$/i.test(item.pathname) && !/_(blank|parent|self|top)/.test(item.get('target'));
					}).each(function (item) {
						new boxplus(item, options);
					});
				}
			});
		}
	});
})(document.id);