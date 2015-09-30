jQuery(function () {
	jQuery('.banner-carousel').carousel({'interval':10000});
	pos = jQuery('.banner-carousel .banneritem img').eq(0).height() - jQuery('.banner-carousel .banneritem .faixa').eq(0).height();
	jQuery('.banner-carousel .carousel-indicators').css('top', pos + 'px');
	jQuery('.banner-carousel .carousel-indicators li').mouseover(function(){
		jQuery(this).click();
	});
	jQuery('.banner-carousel').on('slide.bs.carousel', function () {		
		action = window.setTimeout(function(){
			jQuery('.banner-carousel .banneritem.item.next.left').mouseover();
			jQuery('.banner-carousel .banneritem.item.prev.right').mouseover();
			jQuery('.banner-carousel').carousel('cycle');
		}, 1);		
	});	
	jQuery(window).load(function(){
		pos = jQuery('.banner-carousel .banneritem img').eq(0).height() - jQuery('.banner-carousel .banneritem .faixa').eq(0).height();
		jQuery('.banner-carousel .carousel-indicators').css('top', pos + 'px');
	});
});