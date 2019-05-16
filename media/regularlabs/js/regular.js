/**
 * @package         RegularJS
 * @description     A light and simple JavaScript Library
 *
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            https://github.com/regularlabs/regularjs
 * @copyright       Copyright © 2018 Regular Labs - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/*jslint node: true */
"use strict";

window.Regular = {
	addClass: function(el, clss) {
		if (!el) {
			return;
		}

		el.className += ' ' + clss;

		var classes = el.className.split(' ');

		classes = classes.filter(function(value, index, classes) {
			return classes.indexOf(value) === index;
		});

		el.className = classes.join(' ');
	},

	removeClass: function(el, clss) {
		if (!el) {
			return;
		}

		var classes = el.className.split(' ');

		classes = classes.filter(function(value, index, classes) {
			return classes.indexOf(value) === index;
		});

		var index = classes.indexOf(clss);

		if (index != -1) {
			classes.splice(index, 1);
		}

		el.className = classes.join(' ');
	},

	hasClass: function(el, clss) {
		if (!el) {
			return false;
		}

		var classes = el.className.split(' ');

		return classes.indexOf(clss) > -1;
	},

	toggleClass: function(el, clss) {
		if (!el) {
			return;
		}

		if (this.hasClass(el, clss)) {
			this.removeClass(el, clss);
			return;
		}

		this.addClass(el, clss);
	},

	show: function(el) {
		el.style.opacity = 100;

		if (el.style.display == 'none') {
			el.style.display = 'block';
		}
	},

	hide: function(el) {
		el.style.opacity = 0;
		el.style.display = 'none';
	},

	fadeIn: function(el, duration, oncomplete) {
		var self = this;

		duration = duration ? duration : 250; // total time to fade from 1 to 0 opacity

		var wait        = 50; // amount of time between steps
		var nr_of_steps = duration / wait;
		var change      = 1 / nr_of_steps; // time to wait before next step

		if (!el.style.opacity || el.style.opacity == 1) {
			el.style.opacity = 0;
		}
		if (el.style.display == 'none') {
			el.style.display = 'block';
		}

		(function fade() {
			el.style.opacity = parseFloat(el.style.opacity) + change;
			if (el.style.opacity >= 1) {
				self.show(el);
				if (oncomplete) {
					oncomplete.call();
				}
				return;
			}
			setTimeout(function() {
				fade.call();
			}, wait);
		})();
	},

	fadeOut: function(el, duration, oncomplete) {
		var self = this;

		duration = duration ? duration : 250; // total time to fade from 1 to 0 opacity

		var wait        = 50; // amount of time between steps
		var nr_of_steps = duration / wait;
		var change      = 1 / nr_of_steps; // time to wait before next step

		if (!el.style.opacity || el.style.opacity == 0) {
			el.style.opacity = 1;
		}

		(function fade() {
			el.style.opacity = parseFloat(el.style.opacity) - change;
			if (el.style.opacity <= 0) {
				self.hide(el);
				if (oncomplete) {
					oncomplete.call();
				}
				return;
			}
			setTimeout(function() {
				fade.call();
			}, wait);
		})();
	}
};
