/**
 * @package         Regular Labs Library
 * @version         18.7.10792
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

var RegularLabsForm = null;

(function($) {
	"use strict";

	RegularLabsForm = {
		getValue: function(name, escape) {
			var $field = $('[name="' + name + '"]');

			if (!$field.length) {
				$field = $('[name="' + name + '[]"]');
			}

			if (!$field.length) {
				return;
			}

			var type = $field.attr('type');

			if (typeof type == "undefined" && $field.prop("tagName").toLowerCase() == 'select') {
				type = 'select';
			}

			switch (type) {
				case 'checkbox':
					return this.getValuesFromList($('[name="' + name + '[]"]:checked'), escape);

				case 'select':
					return this.getValuesFromList($field.find('option:checked'), escape);

				case 'radio':
					$field = $('[name="' + name + '"]:checked');
					break;
			}

			return this.prepareValue($field.val(), escape);
		},

		getValuesFromList: function($elements, escape) {
			var self = this;

			var values = [];

			$elements.each(function() {
				values.push(self.prepareValue($(this).val(), escape));
			});

			return values;
		},

		prepareValue: function(value, escape) {
			if (!isNaN(value) && value.indexOf('.') < 0) {
				return parseInt(value);
			}

			if (escape) {
				value = value.replace(/"/g, '\\"');
			}

			return value.trim();
		},

		toTextValue: function(str) {
			return (str + '').replace(/^[\s-]*/, '').trim();
		},

		toSimpleValue: function(str) {
			return (str + '').toLowerCase().replace(/[^0-9a-z]/g, '').trim();
		},

		preg_quote: function(str) {
			return (str + '').replace(/([\\\.\+\*\?\[\^\]\$\(\)\{\}\=\!<>\|\:])/g, '\\$1');
		},

		escape: function(str) {
			return (str + '').replace(/([\"])/g, '\\$1');
		}
	}
})(jQuery);
