jQuery(function () {
	//init
	init();
	
	//acao botao de alto contraste
	jQuery('a.toggle-contraste').click(function(){
		if(!jQuery('div.layout').hasClass('contraste'))
		{
			jQuery('div.layout').addClass('contraste');	
			layout_classes = jQuery.cookie('layout_classes');
			if( layout_classes != 'undefined' )
				layout_classes = layout_classes + ' contraste';
			else
				layout_classes = 'contraste';
			jQuery.cookie('layout_classes', layout_classes );
		}
		else
		{
			jQuery('div.layout').removeClass('contraste');
			layout_classes = jQuery.cookie('layout_classes');
			layout_classes = layout_classes.replace('contraste', '');			
			jQuery.cookie('layout_classes', layout_classes );		
		}
	});
	//fim acao botao de alto contraste

	// botao de menu para resolucoes menores ou iguais a 800 x 1280
	jQuery('a.mainmenu-toggle').click(function(){		
		
		if( !jQuery('#navigation-section').is(':visible') )
		{
			jQuery('#navigation-section').slideDown();
			if( jQuery(document).width() > 767 )
				jQuery('#em-destaque').fadeOut();
		}
		else
		{
			jQuery('#navigation-section').slideUp();
			if( jQuery(document).width() > 767 )
				jQuery('#em-destaque').fadeIn();
		}

		return false;
	});
	// fim botao de menu para resolucoes menores ou iguais a 800 x 1280

	//botao de acao de voltar para o topo
	jQuery('.voltar-ao-topo a').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
		  var target = jQuery(this.hash);
		  target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
		  if (target.length) {
		    jQuery('html,body').animate({
		      scrollTop: target.offset().top
		    }, 1000);
		    return false;
		  }
		}
	});
	//fim botao de acao de voltar para o topo

	//menus fechados e interacoes de menu principal
	jQuery('#navigation-section > nav.closed > ul').hide();	
	jQuery('#navigation-section > nav > h2').click(function(){		
		jQuery(this).next().slideToggle();
		jQuery(this).find('i').toggleClass('icon-chevron-down');
		jQuery(this).find('i').toggleClass('icon-chevron-up');
		jQuery(this).parent().toggleClass('closed');
	});	
	jQuery('#navigation-section > nav > h2 > i:not(.visible-tablet)').parent().css('cursor','pointer');
	URL = document.URL;
	URL = URL.replace('http://', '');
	URL = URL.replace('https://', '');
	URL = URL.substring(URL.indexOf('/'));
	jQuery('#navigation-section > nav.closed > ul a').each(function(i){
		link = jQuery(this).attr('href');
		if(URL == link)
			jQuery(this).parents('nav.closed > ul').slideToggle();
	});
	//fim menus fechados e interacoes de menu principal
});
jQuery(window).resize(function(){
	resize();
});
if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
	window.addEventListener("orientationchange", function() {
	    resize();
	}, false);
}

function init() {
	//classes de layout
	jQuery('div.layout').addClass( jQuery.cookie('layout_classes') );
	
	//ajustes conforme navegador
	browser_adjusts();
	
	//inicializacao de carrossel
	jQuery('.gallery-pane .carousel').carousel();
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
	jQuery('.hasTooltip').mouseout(function(){
		// jQuery(this).tooltip('disable');
		jQuery(this).show();
	});

}

function resize() {
	//ajustes de responsividade
	if( jQuery(document).width() < 979 )
	{
		if( ! jQuery('body').hasClass('responsivo-menor-979') )
		{
			jQuery('body').addClass('responsivo-menor-979');
			jQuery('#navigation h2').next().hide();
			jQuery('#navigation h2').find('i').removeClass('icon-chevron-up');
			jQuery('#navigation h2').find('i').addClass('icon-chevron-down');
		}
		if( jQuery('#navigation-section').is(':visible') )
			jQuery('#navigation-section').hide();

		if( ! jQuery('#em-destaque').is(':visible') )
			jQuery('#em-destaque').fadeIn();
	}	
	else
	{
		jQuery('#navigation-section nav.closed h2 i').removeClass('icon-chevron-up').addClass('icon-chevron-down');
		if( jQuery('body').hasClass('responsivo-menor-979') )
		{
			jQuery('body').removeClass('responsivo-menor-979');
			jQuery('#navigation nav:not(.closed) ul').show();
			jQuery('#navigation nav:not(.closed) h2 i').removeClass('icon-chevron-down').addClass('icon-chevron-up');
			jQuery('#navigation-section').fadeIn();
			jQuery('#em-destaque').fadeIn();
			module_box_adjust();
		}
	}
	//fim ajustes responsividade
}
// ajustes de navegador
function browser_adjusts() {
	if(navigator.appVersion.indexOf("MSIE 7.")!=-1 || navigator.appVersion.indexOf("MSIE 8.")!=-1 || navigator.appVersion.indexOf("MSIE 9.")!=-1)
	{
		jQuery('#portal-searchbox .searchField').val( jQuery('#portal-searchbox .searchField').attr('title') );
		jQuery('#portal-searchbox .searchField').focus(function(){
			if(jQuery(this).val()==jQuery('#portal-searchbox .searchField').attr('title')) jQuery(this).val('');
		});
		jQuery('#portal-searchbox .searchField').blur(function(){
			if(jQuery(this).val()=='') jQuery(this).val(jQuery('#portal-searchbox .searchField').attr('title'));
		});
	}
}
// fim ajustes de navegador

//ajustes de tamanho dos itens para .module-box-01
function module_box_adjust() {
	jQuery('.module-box-01 .lista li').each(function(key){
		limit = 3 * parseInt(jQuery('.module-box-01 .lista li').size()/3);

		if((key+1)%3==0 && (key+1)<=limit)
		{
			elm1 = jQuery('.module-box-01 .lista li').eq(key-2);
			elm2 = jQuery('.module-box-01 .lista li').eq(key-1);
			elm3 = jQuery('.module-box-01 .lista li').eq(key);
			// alert(elm3.text());
			padding_vertical = 2;
			height = elm1.height();
			if(elm2.height() > height)
				height = elm2.height();
			if(elm3.height() > height)
				height = elm3.height();
			
			elm1.height(height+padding_vertical);
			elm2.height(height+padding_vertical);
			elm3.height(height+padding_vertical);
		}
		else if((key+1)>limit)
		{
			if(jQuery('.module-box-01 .lista li').size()-limit==2)
			{
				elm1 = jQuery('.module-box-01 .lista li').eq(key);
				elm2 = jQuery('.module-box-01 .lista li').eq(key+1);
				padding_vertical = 2;
				height = elm1.height();
				if(elm2.height() > height)
					height = elm2.height();
				elm1.height(height+padding_vertical);
				elm2.height(height+padding_vertical);
				return false;
			}
			else
				return false;
		}
	});
}
//fim ajustes de tamanho dos itens para .module-box-01

//aparecimento de icones de redes sociais, paginas internas
function delaySocialItems()
{
	if(jQuery('.btns-social-like').hasClass('hide'))
	{
		jQuery('.btns-social-like').each(function(){
		    jQuery(this).hide();
		    jQuery(this).removeClass('hide');
		    jQuery(this).fadeIn(6000);
		});	
	}
}
//fim aparecimento de icones de redes sociais, paginas internas


//funcao de controle de player de audio
function playAudio(element, urls, formats, basePath)
{
	var audio  = document.createElement("audio"),
	canPlayMP3 = (typeof audio.canPlayType === "function" && audio.canPlayType("audio/mpeg") !== "");	
	if(formats.indexOf('mp3')!=-1 && !canPlayMP3)
	{
		jQuery('#'+element).jPlayer({
			ready: function (event) {
				jQuery(this).jPlayer("setMedia", urls);
			},
			swfPath: basePath+"js/Jplayer.swf",
			supplied: formats,
			wmode: "window",
			solution:"flash",
			smoothPlayBar: true,
			keyEnabled: true,
			oggSupport: false,
			nativeSupport: false,
			cssSelectorAncestor: "#jp_container_"+element,
			preload:"none"
		});
	}
	else
	{		
		jQuery('#'+element).jPlayer({
			ready: function (event) {				
				jQuery(this).jPlayer("setMedia", urls);
			},
			swfPath: basePath+"js",
			supplied: formats,
			wmode: "window",
			smoothPlayBar: true,
			keyEnabled: true,
			cssSelectorAncestor: "#jp_container_"+element,
			preload:"none"
		});
	}
}
//fim funcao de controle de player de audio
//funcao para controle de itens de videos, do listagem-box02-videos
function setModuleBox02clicks()
{
	jQuery('.module-box-02-videos .video-list .link-video-item').click(function(){
		title = jQuery(this).parent().children('h3').text();		
		description = jQuery(this).parent().children('.info-description').text();
		link = jQuery(this).parent().children('.info-link').text();		
		container = jQuery(this).parent().parent().parent();
		container.children('.video-main').children('h3').children('.title').text( title );
		container.children('.video-main').children('.description').text( description );
		container.children('.video-main').children('.player-container').children('iframe').attr( 'src', link );
		return false;
	});
	jQuery('.module-box-02-videos .video-list .link-video-item-title').click(function(){
		title = jQuery(this).parent().parent().children('h3').text();		
		description = jQuery(this).parent().parent().children('.info-description').text();
		link = jQuery(this).parent().parent().children('.info-link').text();		
		container = jQuery(this).parent().parent().parent().parent();
		container.children('.video-main').children('h3').children('.title').text( title );
		container.children('.video-main').children('.description').text( description );
		container.children('.video-main').children('.player-container').children('iframe').attr( 'src', link );
		return false;
	});
}
//fim funcao para controle de itens de videos, do listagem-box02-videos
//funcao addons de carrossel
function carousel_addons()
{	
	index = jQuery('.gallery-pane .carousel-inner .active').index();
	jQuery('.galeria-thumbs .galeria-image').eq( index ).addClass('active');
	jQuery('.galeria-thumbs .galeria-image').children('a').hover(function(){
		jQuery(this).children('img').fadeTo('slow', 1);		
	},function(){
		if(!jQuery(this).parent().hasClass('active'))
			jQuery(this).children('img').fadeTo('fast', 0.6);	
	});
	jQuery('.galeria-thumbs .galeria-image a').click(function(){		
		jQuery('.galeria-thumbs .active img').fadeTo('fast', 0.6);
		jQuery('.galeria-thumbs .active').removeClass('active');
		jQuery(this).parent().addClass('active');
		index = jQuery('.galeria-thumbs li.active').index();
		jQuery(this).parents('.gallery-pane').children('.carousel').carousel( index );
		return false;
	});
	jQuery('.gallery-pane .carousel').bind('slid', function(){
		index = jQuery('.gallery-pane .carousel-inner .active').index();		
		if(jQuery('.galeria-thumbs .galeria-image').eq( index ).hasClass('active'))
			return true;
		jQuery('.galeria-thumbs .active img').fadeTo('fast', 0.6);
		jQuery('.galeria-thumbs .active').removeClass('active');
		jQuery('.galeria-thumbs .galeria-image').eq( index ).addClass('active');
		jQuery('.galeria-thumbs .active img').fadeTo('fast', 1);
	});
	jQuery('.galeria-thumbs').slideDown('slow');
}
//fim funcao addons de carrossel
