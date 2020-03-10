if (typeof jQuery === "undefined")
	throw new Error("Javascript requires jQuery!");

jQuery(function($) {
	"use strict";

	//init
	init();

	var defaultFontSize = parseFloat($("body").css("font-size"));
	var fontSize = defaultFontSize;

	$("a.increase-font").click(function() {
		$("body").css("font-size", ++fontSize);
	});

	$("a.decrease-font").click(function() {
		if (fontSize > defaultFontSize) {
			$("body").css("font-size", --fontSize);
		}
	});

	$("a.resize-font").click(function() {
		fontSize = defaultFontSize;
		$("body").css("font-size", fontSize);
	});

	//acao botao de alto contraste
	$("a.toggle-contraste").click(function() {
		if (!$("div.layout").hasClass("contraste")) {
			$("div.layout").addClass("contraste");
			layout_classes = $.cookie("layout_classes");
			if (layout_classes != "undefined")
				layout_classes = layout_classes + " contraste";
			else layout_classes = "contraste";
			$.cookie("layout_classes", layout_classes);
		} else {
			$("div.layout").removeClass("contraste");
			layout_classes = $.cookie("layout_classes");
			layout_classes = layout_classes.replace("contraste", "");
			$.cookie("layout_classes", layout_classes);
		}
	});
	//fim acao botao de alto contraste

	// botao de menu para resolucoes menores ou iguais a 800 x 1280
	$("a.mainmenu-toggle").click(function() {
		if (!$("#navigation-section").is(":visible")) {
			$("#navigation-section").slideDown();

			if ($(document).width() > 767) {
				$("#em-destaque").fadeOut();
			}
		} else {
			$("#navigation-section").slideUp();

			if ($(document).width() > 767) {
				$("#em-destaque").fadeIn();
			}
		}

		return false;
	});
	// fim botao de menu para resolucoes menores ou iguais a 800 x 1280

	//botao de acao de voltar para o topo
	$(".voltar-ao-topo a").click(function() {
		if (
			location.pathname.replace(/^\//, "") ==
				this.pathname.replace(/^\//, "") &&
			location.hostname == this.hostname
		) {
			var target = $(this.hash);
			target = target.length ? target : $("[name=" + this.hash.slice(1) + "]");
			if (target.length) {
				$("html,body").animate(
					{
						scrollTop: target.offset().top
					},
					1000
				);
				return false;
			}
		}
	});
	//fim botao de acao de voltar para o topo

	//menus fechados e interacoes de menu principal
	$("#navigation-section > nav.closed > ul").hide();
	$("#navigation-section > nav > h2").click(function() {
		$(this)
			.next()
			.slideToggle();
		$(this)
			.find("i")
			.toggleClass("icon-chevron-down");
		$(this)
			.find("i")
			.toggleClass("icon-chevron-up");
		$(this)
			.parent()
			.toggleClass("closed");
	});
	$("#navigation-section > nav > h2 > i:not(.visible-tablet)")
		.parent()
		.css("cursor", "pointer");
	URL = document.URL;
	URL = URL.replace("http://", "");
	URL = URL.replace("https://", "");
	URL = URL.substring(URL.indexOf("/"));
	$("#navigation-section > nav.closed > ul a").each(function(i) {
		link = $(this).attr("href");
		if (URL == link) {
			$(this)
				.parents("nav.closed > ul")
				.slideToggle();
		}
	});
	//fim menus fechados e interacoes de menu principal

	$(window).resize(function() {
		resize();
	});

	if (
		/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
			navigator.userAgent
		)
	) {
		window.addEventListener(
			"orientationchange",
			function() {
				resize();
			},
			false
		);
	}

	function init() {
		//classes de layout
		$("div.layout").addClass($.cookie("layout_classes"));

		//ajustes conforme navegador
		browser_adjusts();

		//inicializacao de carrossel
		$(".gallery-pane .carousel").carousel();
		carousel_addons();

		//resize para responsividade
		resize();

		//ajuste de caixas de modulos do tipo module_box
		module_box_adjust(null);

		//paginas internas:
		delaySocialItems();

		//remocao de conflito com tooltips de mootools
		// jQuery('.hasTooltip').tooltip('disable');
		// jQuery('[rel=tooltip]').tooltip('disable');
		$(".hasTooltip").mouseout(function() {
			// jQuery(this).tooltip('disable');
			$(this).show();
		});
	}

	function resize() {
		//ajustes de responsividade
		if ($(document).width() < 979) {
			if (!$("body").hasClass("responsivo-menor-979")) {
				$("body").addClass("responsivo-menor-979");
				$("#navigation h2")
					.next()
					.hide();
				$("#navigation h2")
					.find("i")
					.removeClass("icon-chevron-up");
				$("#navigation h2")
					.find("i")
					.addClass("icon-chevron-down");
			}
			if ($("#navigation-section").is(":visible"))
				$("#navigation-section").hide();

			if (!$("#em-destaque").is(":visible")) $("#em-destaque").fadeIn();
		} else {
			$("#navigation-section nav.closed h2 i")
				.removeClass("icon-chevron-up")
				.addClass("icon-chevron-down");
			if ($("body").hasClass("responsivo-menor-979")) {
				$("body").removeClass("responsivo-menor-979");
				$("#navigation nav:not(.closed) ul").show();
				$("#navigation nav:not(.closed) h2 i")
					.removeClass("icon-chevron-down")
					.addClass("icon-chevron-up");
				$("#navigation-section").fadeIn();
				$("#em-destaque").fadeIn();
				module_box_adjust();
			}
		}
		//fim ajustes responsividade
	}

	// ajustes de navegador
	function browser_adjusts() {
		if (
			navigator.appVersion.indexOf("MSIE 7.") != -1 ||
			navigator.appVersion.indexOf("MSIE 8.") != -1 ||
			navigator.appVersion.indexOf("MSIE 9.") != -1
		) {
			$("#portal-searchbox .searchField").val(
				$("#portal-searchbox .searchField").attr("title")
			);
			$("#portal-searchbox .searchField").focus(function() {
				if ($(this).val() == $("#portal-searchbox .searchField").attr("title"))
					$(this).val("");
			});
			$("#portal-searchbox .searchField").blur(function() {
				if ($(this).val() == "")
					$(this).val($("#portal-searchbox .searchField").attr("title"));
			});
		}
	}
	// fim ajustes de navegador

	//ajustes de tamanho dos itens para .module-box-01
	function module_box_adjust() {
		$(".module-box-01 .lista li").each(function(key) {
			limit = 3 * parseInt($(".module-box-01 .lista li").size() / 3);

			if ((key + 1) % 3 == 0 && key + 1 <= limit) {
				elm1 = $(".module-box-01 .lista li").eq(key - 2);
				elm2 = $(".module-box-01 .lista li").eq(key - 1);
				elm3 = $(".module-box-01 .lista li").eq(key);
				// alert(elm3.text());
				padding_vertical = 2;
				height = elm1.height();
				if (elm2.height() > height) height = elm2.height();
				if (elm3.height() > height) height = elm3.height();

				elm1.height(height + padding_vertical);
				elm2.height(height + padding_vertical);
				elm3.height(height + padding_vertical);
			} else if (key + 1 > limit) {
				if ($(".module-box-01 .lista li").size() - limit == 2) {
					elm1 = $(".module-box-01 .lista li").eq(key);
					elm2 = $(".module-box-01 .lista li").eq(key + 1);
					padding_vertical = 2;
					height = elm1.height();
					if (elm2.height() > height) height = elm2.height();
					elm1.height(height + padding_vertical);
					elm2.height(height + padding_vertical);
					return false;
				} else return false;
			}
		});
	}
	//fim ajustes de tamanho dos itens para .module-box-01

	//aparecimento de icones de redes sociais, paginas internas
	function delaySocialItems() {
		if ($(".btns-social-like").hasClass("hide")) {
			$(".btns-social-like").each(function() {
				$(this).hide();
				$(this).removeClass("hide");
				$(this).fadeIn(6000);
			});
		}
	}
	//fim aparecimento de icones de redes sociais, paginas internas

	//funcao de controle de player de audio
	function playAudio(element, urls, formats, basePath) {
		var audio = document.createElement("audio"),
			canPlayMP3 =
				typeof audio.canPlayType === "function" &&
				audio.canPlayType("audio/mpeg") !== "";
		if (formats.indexOf("mp3") != -1 && !canPlayMP3) {
			$("#" + element).jPlayer({
				ready: function(event) {
					$(this).jPlayer("setMedia", urls);
				},
				swfPath: basePath + "js/Jplayer.swf",
				supplied: formats,
				wmode: "window",
				solution: "flash",
				smoothPlayBar: true,
				keyEnabled: true,
				oggSupport: false,
				nativeSupport: false,
				cssSelectorAncestor: "#jp_container_" + element,
				preload: "none"
			});
		} else {
			$("#" + element).jPlayer({
				ready: function(event) {
					$(this).jPlayer("setMedia", urls);
				},
				swfPath: basePath + "js",
				supplied: formats,
				wmode: "window",
				smoothPlayBar: true,
				keyEnabled: true,
				cssSelectorAncestor: "#jp_container_" + element,
				preload: "none"
			});
		}
	}
	//fim funcao de controle de player de audio
	//funcao para controle de itens de videos, do listagem-box02-videos
	function setModuleBox02clicks() {
		$(".module-box-02-videos .video-list .link-video-item").click(function() {
			title = $(this)
				.parent()
				.children("h3")
				.text();
			description = $(this)
				.parent()
				.children(".info-description")
				.text();
			link = $(this)
				.parent()
				.children(".info-link")
				.text();
			container = $(this)
				.parent()
				.parent()
				.parent();
			container
				.children(".video-main")
				.children("h3")
				.children(".title")
				.text(title);
			container
				.children(".video-main")
				.children(".description")
				.text(description);
			container
				.children(".video-main")
				.children(".player-container")
				.children("iframe")
				.attr("src", link);
			return false;
		});
		$(".module-box-02-videos .video-list .link-video-item-title").click(
			function() {
				title = $(this)
					.parent()
					.parent()
					.children("h3")
					.text();
				description = $(this)
					.parent()
					.parent()
					.children(".info-description")
					.text();
				link = $(this)
					.parent()
					.parent()
					.children(".info-link")
					.text();
				container = $(this)
					.parent()
					.parent()
					.parent()
					.parent();
				container
					.children(".video-main")
					.children("h3")
					.children(".title")
					.text(title);
				container
					.children(".video-main")
					.children(".description")
					.text(description);
				container
					.children(".video-main")
					.children(".player-container")
					.children("iframe")
					.attr("src", link);
				return false;
			}
		);
	}

	//fim funcao para controle de itens de videos, do listagem-box02-videos
	//funcao addons de carrossel
	function carousel_addons() {
		var index = $(".gallery-pane .carousel-inner .active").index();

		$(".galeria-thumbs .galeria-image")
			.eq(index)
			.addClass("active");

		$(".galeria-thumbs .galeria-image")
			.children("a")
			.hover(
				function() {
					$(this)
						.children("img")
						.fadeTo("slow", 1);
				},
				function() {
					if (
						!$(this)
							.parent()
							.hasClass("active")
					)
						$(this)
							.children("img")
							.fadeTo("fast", 0.6);
				}
			);

		$(".galeria-thumbs .galeria-image a").click(function() {
			$(".galeria-thumbs .active img").fadeTo("fast", 0.6);
			$(".galeria-thumbs .active").removeClass("active");
			$(this)
				.parent()
				.addClass("active");
			index = $(".galeria-thumbs li.active").index();
			$(this)
				.parents(".gallery-pane")
				.children(".carousel")
				.carousel(index);
			return false;
		});

		$(".gallery-pane .carousel").bind("slid", function() {
			index = $(".gallery-pane .carousel-inner .active").index();
			if (
				$(".galeria-thumbs .galeria-image")
					.eq(index)
					.hasClass("active")
			)
				return true;
			$(".galeria-thumbs .active img").fadeTo("fast", 0.6);
			$(".galeria-thumbs .active").removeClass("active");
			$(".galeria-thumbs .galeria-image")
				.eq(index)
				.addClass("active");
			$(".galeria-thumbs .active img").fadeTo("fast", 1);
		});

		$(".galeria-thumbs").slideDown("slow");
	}
	//fim funcao addons de carrossel
});
