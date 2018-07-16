/*!
 * Masonry PACKAGED v4.1.1
 * Cascading grid layout library
 * http://masonry.desandro.com
 * MIT License
 * by David DeSandro
 */

/*
 * Masonry initialize
 * https://www.phoca.cz
 *
 * Copyright (C) 2016 Jan Pavelka www.phoca.cz
 *
 * Licensed under the MIT license
 */
 
function phMasGetMarginLeft(container, basePadding, columns) {
	
	var wI	= jQuery('.item').width();
	var mL	= parseInt(jQuery('.item').css("margin-left"));
	var mR	= parseInt(jQuery('.item').css("margin-right"));
	//var wC 	= jQuery('body').width();
	var wC 	= container.parent().width();
	var wCO = container.parent().outerWidth();
	
	wN = (wCO - ((columns * wI) + (columns * mL) + (columns * mR))) / 2;
	wN = Math.round(wN);
	
	
	if (wN < 0) {
		wN = parseInt(basePadding);
	} 
		
	//var l = "Item: " + wI + ", Columns:" + columns + ", Container: " + wC + ", ContainerOuter: " + wCO + ", Margin: " + wN + ", Base Width: "+ basePadding; 
	//console.log(l);
	wS = wN + 'px';
	
	return wS;
	
}

jQuery(window).load(function() {
	
	var $phMasContainer = jQuery('#pg-msnr-container');
	var $phMasBasePL	= $phMasContainer.parent().css( "padding-left");

	$phMasEvent = $phMasContainer.masonry({
		itemSelector: '.item',
		isAnimated: true,
		isFitWidth: false,
		columnWidth: '.pg-grid-sizer'
	});

	jQuery.extend( Masonry.prototype, {                  
		phMasGetCols : function() {
			return this.cols; 
		}
	});

	$phMasCols 		= $phMasContainer.masonry('phMasGetCols');
	$phMasNewMargin = phMasGetMarginLeft($phMasContainer, $phMasBasePL, $phMasCols);
	$phMasContainer.parent().css( "padding-left", $phMasNewMargin);


	/*jQuery(window).resize(function() {
		
		$phMasCols 		= $phMasContainer.masonry('phMasGetCols');
		$phMasNewMargin = phMasGetMarginLeft($phMasContainer, $phMasBasePL, $phMasCols);
		$phMasContainer.parent().css( "padding-left", $phMasNewMargin);
		
	});*/
	
	$phMasEvent.on( 'layoutComplete', function() {
		$phMasCols 		= $phMasContainer.masonry('phMasGetCols');
		$phMasNewMargin = phMasGetMarginLeft($phMasContainer, $phMasBasePL, $phMasCols);
		$phMasContainer.parent().css( "padding-left", $phMasNewMargin);
	});
});