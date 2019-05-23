function pasteTag(tag, closingTag, prependText, appendText) {
	var pe 			= document.getElementById( 'phocagallery-comments-editor' );
	var startTag 	= '[' + tag + ']';
	var endTag 		= '[/' + tag + ']';
	
	if (typeof pe.selectionStart != 'undefined') {
		var tagText = pe.value.substring(pe.selectionStart, pe.selectionEnd);
	} else if (typeof document.selection != 'undefined') {
		var tagText = document.selection.createRange().text;
	} else {
	}
	
	if (typeof closingTag == 'undefined') {
		var closingTag	= true;
	}
	if (typeof prependText == 'undefined') {
		var prependText	= '';
	}
	if (typeof appendText == 'undefined') {
		var appendText	= '';
	}
	if (!closingTag) {
		endTag 			= '';
	}	
	var totalText 		= prependText + startTag + tagText + endTag + appendText;
	pe.focus();
	
	if (typeof pe.selectionStart != 'undefined') {
		var start	= pe.selectionStart;
		var end 	= pe.selectionEnd;
		pe.value 	= pe.value.substr(0, start) + totalText + pe.value.substr(end);
		
		if (typeof selectionStart != 'undefined' && typeof selectionEnd != 'undefined') {
			pe.selectionStart 	= start + selectionStart;
			pe.selectionEnd 	= start + selectionEnd;
		} else {
			if (tagText == '') {
				pe.selectionStart 	= start + prependText.length + startTag.length;
				pe.selectionEnd 	= start + prependText.length + startTag.length;
			} else {
				pe.selectionStart 	= start + totalText.length;
				pe.selectionEnd 	= start + totalText.length;
			}
		}
	} else if (typeof document.selection != 'undefined') {
		var range 	= document.selection.createRange();
		range.text 	= totalText;
		
		if (typeof selectionStart != 'undefined' && typeof selectionEnd != 'undefined') {
			range.moveStart('character', -totalText.length + selectionStart);
			range.moveEnd('character', -totalText.length + selectionEnd);
		} else {
			if (tagText == '') {
				range.move('character', -(endTag.length + appendText.length));
			} else {
			}
		}
		range.select();
	}
	countChars();
	delete selectionStart;
	delete selectionEnd;
}

function pasteSmiley( smiley ) {
	var pe = document.getElementById( 'phocagallery-comments-editor' );
	if ( typeof pe.selectionStart != 'undefined' ) {
		var start	= pe.selectionStart;
		var end 	= pe.selectionEnd;
		pe.value 	= pe.value.substring( 0, start ) + smiley + pe.value.substring( end );
		
		newPosition	= start + smiley.length;
		
		pe.selectionStart	= newPosition;
		pe.selectionEnd		= newPosition;
		
	} else if (typeof document.selection != 'undefined') {
		pe.focus();
		range = document.selection.createRange();
		range.text = smiley;
	} else {
		pe.value += smiley;
	}
	countChars();
	pe.focus();
}



