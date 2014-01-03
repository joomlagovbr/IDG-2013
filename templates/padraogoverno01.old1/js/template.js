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
});
jQuery(window).resize(function(){
	resize();
});

function init() {
	//classes de layout
	jQuery('div.layout').addClass( jQuery.cookie('layout_classes') );
	browser_adjusts();
	jQuery('.gallery-pane .carousel').carousel();
	resize();
	module_box_adjust(null);
}

function resize() {

	//ajustes de responsividade
	if( jQuery(document).width() < 979 )
	{
		if( jQuery('#navigation h2').css('cursor') != 'pointer' )
		{
			jQuery('#navigation h2').css('cursor', 'pointer');
			jQuery('#navigation h2').click(function(){
				if( !jQuery(this).next().is(':visible') )
				{
					jQuery(this).next().slideDown();
					jQuery(this).find('i').removeClass('icon-chevron-down');
					jQuery(this).find('i').addClass('icon-chevron-up');
				}
				else
				{
					jQuery(this).next().slideUp();
					jQuery(this).find('i').addClass('icon-chevron-down');
					jQuery(this).find('i').removeClass('icon-chevron-up');
				}
			});			
		}
		if( jQuery('#navigation-section').is(':visible') )
			jQuery('#navigation-section').hide();

		if( ! jQuery('#em-destaque').is(':visible') )
			jQuery('#em-destaque').fadeIn();

		module_box_adjust('auto');
		module_box_adjust(null);
	}	
	else
	{
		if( jQuery('#navigation h2').css('cursor') != 'default' )
		{
			jQuery('#navigation h2').css('cursor', 'default');
			jQuery('#navigation h2').click(function(){ return false; });
			jQuery('#navigation h2').next().show();				
			jQuery('#navigation-section').fadeIn();
			jQuery('#em-destaque').fadeIn();
			module_box_adjust(null);
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
function module_box_adjust( val ) {
	jQuery('.module-box-01 .lista li').each(function(key){
		if(val == null) {
			if(key==0) max_height = 0;
			if(key==0) size = jQuery(this).size();
			padding_vertical = 8;
			if(jQuery(this).height() > max_height) max_height = jQuery(this).height() + padding_vertical;
			if(key+1 == size) jQuery('.module-box-01 .lista li').height( max_height );			
		}
		else
			jQuery('.module-box-01 .lista li').height( val );
	});
}
//fim ajustes de tamanho dos itens para .module-box-01