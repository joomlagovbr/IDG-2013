/**
 * @package         Regular Labs Library
 * @version         18.7.10792
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

var RegularLabsScripts = null;

(function($) {
	"use strict";

	$(document).ready(function() {
		// remove all empty control groups
		$('div.control-group > div').each(function(i, el) {
			if (
				$(el).html().trim() == ''
				&& (
					$(el).attr('class') == 'control-label'
					|| $(el).attr('class') == 'controls'
				)
			) {
				$(el).remove();
			}
		});
		$('div.control-group').each(function(i, el) {
			if ($(el).html().trim() == '') {
				$(el).remove();
			}
		});
		$('div.control-group > div.hide').each(function(i, el) {
			$(el).parent().css('margin', 0);
		});

		$('.rl_resize_textarea').click(function() {
			var $el    = $(this);
			var $field = $('#' + $el.attr('data-id'));

			if ($el.hasClass('rl_minimize')) {
				$el.removeClass('rl_minimize').addClass('rl_maximize');
				$field.css({'height': $el.attr('data-min')});
				return;
			}

			$el.removeClass('rl_maximize').addClass('rl_minimize');
			$field.css({'height': $el.attr('data-max')});
		});
	});

	RegularLabsScripts = {
		ajax_list        : [],
		started_ajax_list: false,
		ajax_list_timer  : null,

		loadajax: function(url, success, fail, query, timeout, dataType, cache) {
			// console.log(url);

			if (url.substr(0, 9) != 'index.php') {
				url = url.replace('http://', '');
				url = 'index.php?rl_qp=1&url=' + encodeURIComponent(url);
				if (timeout) {
					url += '&timeout=' + timeout;
				}
				if (cache) {
					url += '&cache=' + cache;
				}
			}

			var base = window.location.pathname;
			base     = base.substring(0, base.lastIndexOf('/'));

			if (
				typeof Joomla !== 'undefined'
				&& typeof Joomla.getOptions !== 'undefined'
				&& Joomla.getOptions('system.paths')
			) {
				var paths = Joomla.getOptions('system.paths');
				base      = paths.base;
			}

			// console.log(url);

			$.ajax({
				type    : 'post',
				url     : base + '/' + url,
				dataType: dataType ? dataType : '',
				success : function(data) {
					if (success) {
						eval(success + ';');
					}
				},
				error   : function(data) {
					if (fail) {
						eval(fail + ';');
					}
				}
			});
		},

		displayVersion: function(data, extension, version) {
			if (!data) {
				return;
			}

			var xml = RegularLabsScripts.getObjectFromXML(data);

			if (!xml) {
				return;
			}

			if (typeof xml[extension] === 'undefined') {
				return;
			}

			var dat = xml[extension];

			if (!dat || typeof dat['version'] === 'undefined' || !dat['version']) {
				return;
			}

			var new_version = dat['version'];
			var compare     = RegularLabsScripts.compareVersions(version, new_version);

			if (compare != '<') {
				return;
			}

			var el = $('#nonumber_newversionnumber_' + extension);
			if (el) {
				el.text(new_version);
			}

			el = $('#nonumber_version_' + extension);
			if (el) {
				el.css('display', 'block');
				el.parent().removeClass('hide');
			}
		},

		addToLoadAjaxList: function(url, success, error) {
			// wrap inside the loadajax function (and escape string values)
			var action = "RegularLabsScripts.loadajax(" +
				"'" + url.replace(/'/g, "\\'") + "'," +
				"'" + success.replace(/'/g, "\\'") + ";RegularLabsScripts.ajaxRun();'," +
				"'" + error.replace(/'/g, "\\'") + ";RegularLabsScripts.ajaxRun();'" +
				")";

			this.addToAjaxList(action);
		},

		addToAjaxList: function(action) {
			this.ajax_list.push(action);

			if (!this.started_ajax_list) {
				this.ajaxRun();
			}
		},

		ajaxRun: function() {
			if (typeof RegularLabsToggler !== 'undefined') {
				RegularLabsToggler.initialize();
			}

			if (!this.ajax_list.length) {
				return;
			}

			clearTimeout(this.ajax_list_timer);

			this.started_ajax_list = true;

			var action = this.ajax_list.shift();

			eval(action + ';');

			if (!this.ajax_list.length) {
				return;
			}

			// Re-trigger this ajaxRun function just in case it hangs somewhere
			this.ajax_list_timer = setTimeout(
				function() {
					RegularLabsScripts.ajaxRun();
				},
				5000
			);
		},

		toggleSelectListSelection: function(id) {
			var el = document.getElement('#' + id);
			if (el && el.options) {
				for (var i = 0; i < el.options.length; i++) {
					if (!el.options[i].disabled) {
						el.options[i].selected = !el.options[i].selected;
					}
				}
			}
		},

		toggleSelectListSize: function(id) {
			var link = document.getElement('#toggle_' + id);
			var el   = document.getElement('#' + id);
			if (link && el) {
				if (!el.getAttribute('rel')) {
					el.setAttribute('rel', el.getAttribute('size'));
				}
				if (el.getAttribute('size') == el.getAttribute('rel')) {
					el.setAttribute('size', (el.length > 100) ? 100 : el.length);
					link.getElement('span.show').setStyle('display', 'none');
					link.getElement('span.hide').setStyle('display', 'inline');
					if (typeof RegularLabsToggler !== 'undefined') {
						RegularLabsToggler.autoHeightDivs();
					}
				} else {
					el.setAttribute('size', el.getAttribute('rel'));
					link.getElement('span.hide').setStyle('display', 'none');
					link.getElement('span.show').setStyle('display', 'inline');
				}
			}
		},

		prependTextarea: function(id, content, separator) {
			var textarea     = jQuery('#' + id);
			var orig_content = textarea.val().trim();

			if (orig_content && separator) {
				orig_content = "\n\n" + separator + "\n\n" + orig_content;
			}

			textarea.val(content + orig_content);
		},

		in_array: function(needle, haystack, casesensitive) {
			if ({}.toString.call(needle).slice(8, -1) != 'Array') {
				needle = [needle];
			}
			if ({}.toString.call(haystack).slice(8, -1) != 'Array') {
				haystack = [haystack];
			}

			for (var h = 0; h < haystack.length; h++) {
				for (var n = 0; n < needle.length; n++) {
					if (casesensitive) {
						if (haystack[h] == needle[n]) {
							return true;
						}
					} else {
						if (haystack[h].toLowerCase() == needle[n].toLowerCase()) {
							return true;
						}
					}
				}
			}
			return false;
		},

		getObjectFromXML: function(xml) {
			if (!xml) {
				return;
			}

			var obj = [];
			$(xml).find('extension').each(function() {
				var el = [];
				$(this).children().each(function() {
					el[this.nodeName.toLowerCase()] = String($(this).text()).trim();
				});
				if (typeof el.alias !== 'undefined') {
					obj[el.alias] = el;
				}
				if (typeof el.extname !== 'undefined' && el.extname != el.alias) {
					obj[el.extname] = el;
				}
			});

			return obj;
		},

		compareVersions: function(num1, num2) {
			num1 = num1.split('.');
			num2 = num2.split('.');

			var let1 = '';
			var let2 = '';

			var max = Math.max(num1.length, num2.length);
			for (var i = 0; i < max; i++) {
				if (typeof num1[i] === 'undefined') {
					num1[i] = '0';
				}
				if (typeof num2[i] === 'undefined') {
					num2[i] = '0';
				}

				let1    = num1[i].replace(/^[0-9]*(.*)/, '$1');
				num1[i] = parseInt(num1[i]);
				let2    = num2[i].replace(/^[0-9]*(.*)/, '$1');
				num2[i] = parseInt(num2[i]);

				if (num1[i] < num2[i]) {
					return '<';
				}

				if (num1[i] > num2[i]) {
					return '>';
				}
			}

			// numbers are same, so compare trailing letters
			if (let2 && (!let1 || let1 > let2)) {
				return '>';
			}

			if (let1 && (!let2 || let1 < let2)) {
				return '<';
			}

			return '=';
		},

		setRadio: function(id, value) {
			value = value ? 1 : 0;
			document.getElements('input#jform_' + id + value + ',input#jform_params_' + id + value + ',input#advancedparams_' + id + value).each(function(el) {
				el.click();
			});
		},

		setToggleTitleClass: function(input, value) {
			var el = $(input).parent().parent().parent().parent();

			el.removeClass('alert-success').removeClass('alert-error');
			if (value === 2) {
				el.addClass('alert-error');
			} else if (value) {
				el.addClass('alert-success');
			}
		},

		initCheckAlls: function(id, classname) {
			$('#' + id).attr('checked', RegularLabsScripts.allChecked(classname));
			$('input.' + classname).click(function() {
				$('#' + id).attr('checked', RegularLabsScripts.allChecked(classname));
			});
		},

		allChecked: function(classname) {
			return $('input.' + classname + ':checkbox:not(:checked)').length < 1;
		},

		checkAll: function(checkbox, classname) {
			var allchecked = RegularLabsScripts.allChecked(classname);
			$(checkbox).attr('checked', !allchecked);
			$('input.' + classname).attr('checked', !allchecked);
		},

		getEditorSelection: function(editorname) {
			var editor_textarea = document.getElementById(editorname);

			if (!editor_textarea) {
				return '';
			}

			var iframes = editor_textarea.parentNode.getElementsByTagName('iframe');

			if (!iframes.length) {
				return '';
			}

			var editor_frame  = iframes[0];
			var contentWindow = editor_frame.contentWindow;

			if (typeof contentWindow.getSelection !== 'undefined') {
				var sel = contentWindow.getSelection();

				if (sel.rangeCount) {
					var container = contentWindow.document.createElement("div");
					var len       = sel.rangeCount;
					for (var i = 0; i < len; ++i) {
						container.appendChild(sel.getRangeAt(i).cloneContents());
					}

					return container.innerHTML;
				}

				return '';
			}

			if (typeof contentWindow.document.selection !== 'undefined') {
				if (contentWindow.document.selection.type == "Text") {
					return contentWindow.document.selection.createRange().htmlText;
				}
			}

			return '';
		},

		initResizeCodeMirror: function(id) {
			if (!$('#' + id + ' .CodeMirror').length) {
				setTimeout(function() {
					RegularLabsScripts.initResizeCodeMirror(id);
				}, 1000);
				return;
			}

			RegularLabsScripts.resizeCodeMirror(id);

			$(window).resize(function() {
				RegularLabsScripts.resizeCodeMirror(id);
			});
		},

		resizeCodeMirror: function(id) {
			$('#' + id + ' .CodeMirror').width(100);
			setTimeout(function() {
				$('#' + id + ' .CodeMirror').each(function() {
					var new_width = $(this).parent().width();

					if (new_width <= 100) {
						setTimeout(function() {
							RegularLabsScripts.resizeCodeMirror(id);
						}, 1000);
						return;
					}

					$(this).width(new_width);
				})
			}, 100);
		}
	};

	$(document).ready().delay(1000, function() {
		$('.btn-group.rl_btn-group label').click(function() {
			var label = $(this);
			var input = $('#' + label.attr('for'));

			label.closest('.btn-group').find('label').removeClass('active btn-success btn-danger btn-primary');
			if (input.val() == '' || input.val() == -2) {
				label.addClass('active btn-primary');
			} else if (input.val() == -1) {
				label.addClass('active');
			} else if (input.val() == 0) {
				label.addClass('active btn-danger');
			} else {
				label.addClass('active btn-success');
			}
			input.prop('checked', true);
		});
		$('.btn-group.rl_btn-group input[checked=checked]').each(function() {
			$('label[for=' + $(this).attr('id') + ']').removeClass('active btn-success btn-danger btn-primary');
			if ($(this).val() == '' || $(this).val() == -2) {
				$('label[for=' + $(this).attr('id') + ']').addClass('active btn-primary');
			} else if ($(this).val() == -1) {
				$('label[for=' + $(this).attr('id') + ']').addClass('active');
			} else if ($(this).val() == 0) {
				$('label[for=' + $(this).attr('id') + ']').addClass('active btn-danger');
			} else {
				$('label[for=' + $(this).attr('id') + ']').addClass('active btn-success');
			}
		});
	});
})(jQuery);
