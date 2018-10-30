var initPhotoSwipeFromDOM = function(gallerySelector) {

    // parse slide data (url, title, size ...) from DOM elements 
    // (children of gallerySelector)
    var parseThumbnailElements = function(el) {
       /* NOT DIRECT PARENT var thumbElements = el.childNodes,*/
	   var thumbElements = jQuery(el).find('figure'),
            numNodes = thumbElements.length,
            items = [],
            figureEl,
            linkEl,
            size,
            item;

        for(var i = 0; i < numNodes; i++) {

            figureEl = thumbElements[i]; // <figure> element

            // include only element nodes 
            if(figureEl.nodeType !== 1) {
                continue;
            }

            linkEl = figureEl.children[0]; // <a> element
		

            size = linkEl.getAttribute('data-size').split('x');

            // create slide object
          /*  item = {
                src: linkEl.getAttribute('href'),
                w: parseInt(size[0], 10),
                h: parseInt(size[1], 10)
            };*/
			
			// create slide object
			if (jQuery(linkEl).data('type') == 'video') {
				item = {
					html: jQuery(linkEl).data('video')
				};
			} else {
				item = {
					src: linkEl.getAttribute('href'),
					w: parseInt(size[0], 10),
					h: parseInt(size[1], 10)
				};
			} 



            if(figureEl.children.length > 1) {
                // <figcaption> content
                item.title = figureEl.children[1].innerHTML; 
            }

            if(linkEl.children.length > 0) {
                // <img> thumbnail element, retrieving thumbnail url
              //  item.msrc = linkEl.children[0].getAttribute('src');
            } 

            item.el = figureEl; // save link to element for getThumbBoundsFn
            items.push(item);
        }

        return items;
    };

    // find nearest parent element
    var closest = function closest(el, fn) {
        return el && ( fn(el) ? el : closest(el.parentNode, fn) );
    };

    // triggers when user clicks on thumbnail
    //var onThumbnailsClick = function(e) {
		var onThumbnailsClick = jQuery('.photoswipe-button').on('click', function(e) {
        e = e || window.event;
        e.preventDefault ? e.preventDefault() : e.returnValue = false;

        var eTarget = e.target || e.srcElement;

        // find root element of slide
        var clickedListItem = closest(eTarget, function(el) {
            return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
        });

        if(!clickedListItem) {
            return;
        }

        // find index of clicked item by looping through all child nodes
        // alternatively, you may define index via data- attribute
       /* NOT DIRECT PARENT var clickedGallery = clickedListItem.parentNode,*/
		//var clickedGallery = clickedListItem.parentNode.parentNode.parentNode.parentNode.parentNode,
		var clickedGalleryObject = jQuery(clickedListItem).closest('div.pg-photoswipe'),
            clickedGallery = clickedGalleryObject[0],
			/* NOT DIRECT PARENT childNodes = clickedListItem.parentNode.childNodes,*/
			childNodes = jQuery(clickedGallery).find('figure'),
            numChildNodes = childNodes.length,
            nodeIndex = 0,
            index;


        for (var i = 0; i < numChildNodes; i++) {
			
            if(childNodes[i].nodeType !== 1) { 
                continue; 
            }

            if(childNodes[i] === clickedListItem) {
                index = nodeIndex;
                break;
            }
            nodeIndex++;
        }



        if(index >= 0) {
			
            // open PhotoSwipe if valid index found
            openPhotoSwipe( index, clickedGallery );
        }
        return false;
    });

    // parse picture index and gallery index from URL (#&pid=1&gid=2)
    var photoswipeParseHash = function() {
        var hash = window.location.hash.substring(1),
        params = {};

        if(hash.length < 5) {
            return params;
        }

        var vars = hash.split('&');
        for (var i = 0; i < vars.length; i++) {
            if(!vars[i]) {
                continue;
            }
            var pair = vars[i].split('=');  
            if(pair.length < 2) {
                continue;
            }           
            params[pair[0]] = pair[1];
        }

        if(params.gid) {
            params.gid = parseInt(params.gid, 10);
        }

        return params;
    };

    var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
        var pswpElement = document.querySelectorAll('.pswp')[0],
            gallery,
            options,
            items;

        items = parseThumbnailElements(galleryElement);

        // define options (if needed)
       /* options = {

            // define gallery index (for URL)
            galleryUID: galleryElement.getAttribute('data-pswp-uid'),

            getThumbBoundsFn: function(index) {
                // See Options -> getThumbBoundsFn section of documentation for more info
                var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                    rect = thumbnail.getBoundingClientRect(); 

                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
            }

        };*/
		
		options = {

            // define gallery index (for URL)
            galleryUID: galleryElement.getAttribute('data-pswp-uid'),

            getThumbBoundsFn: function(index) {
                // See Options -> getThumbBoundsFn section of documentation for more info
                var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                    rect = thumbnail.getBoundingClientRect(); 

                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
            },
			showHideOpacity: true, 
			getThumbBoundsFn: false

        };

        // PhotoSwipe opened from URL
        if(fromURL) {
            if(options.galleryPIDs) {
                // parse real index when custom PIDs are used 
                // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
                for(var j = 0; j < items.length; j++) {
                    if(items[j].pid == index) {
                        options.index = j;
                        break;
                    }
                }
            } else {
                // in URL indexes start from 1
                options.index = parseInt(index, 10) - 1;
            }
        } else {
            options.index = parseInt(index, 10);
        }

        // exit if index not found
        if( isNaN(options.index) ) {
            return;
        }

        if(disableAnimation) {
            options.showAnimationDuration = 0;
        }

        // Pass data to PhotoSwipe and initialize it
        gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
		
		
		/* SLIDESHOW */
		setSlideshowState(ssButtonClass, true /* not running from the start */);

		// start timer for the next slide in slideshow after prior image has loaded
		gallery.listen('afterChange', function() { 
			if (ssRunning && ssOnce) {
				ssOnce = false;
				setTimeout(gotoNextSlide, ssDelay);
			}
		}); 
		gallery.listen('destroy', function() { gallery = null; });
		/* END SLIDESHOW */
		
        gallery.init();
		
		/* YouTube */
		gallery.listen('beforeChange', function() {
			var currItem = jQuery(gallery.currItem.container);
			jQuery('.ph-pswp-video-wrapper iframe').removeClass('active');
			var currItemIframe = currItem.find('.ph-pswp-video-wrapper iframe').addClass('active');
			jQuery('.ph-pswp-video-wrapper iframe').each(function() {
				if (!jQuery(this).hasClass('active')) {
					jQuery(this).attr('src', jQuery(this).attr('src'));
				}
			});
		});
		
		gallery.listen('close', function() {
			jQuery('.ph-pswp-video-wrapper iframe').each(function() {
				jQuery(this).attr('src', jQuery(this).attr('src'));
			});
		});
		
		
		/* SLIDESHOW FUNCTIONS */
	
		// slideshow vars:
		var ssRunning = false, 
			ssOnce = false,
			ssDelay = 2500 /*ms*/,
			ssButtonClass = '.pswp__button--playpause';

		/* slideshow management */
		jQuery(ssButtonClass).on('click touchstart', function(e) {
			// toggle slideshow on/off
			setSlideshowState(this, !ssRunning);
			//gallery.next();
		});
		


		function setSlideshowState(el, running) {
			
			if (running) {
				setTimeout(gotoNextSlide, ssDelay / 2.0 /* first time wait less */);
			}
			var title = running ? jQuery('#phTxtPauseSlideshow').text() : jQuery('#phTxtPlaySlideshow').text();
			jQuery(el).removeClass(running ? "play" : "pause") // change icons defined in css
				.addClass(running ? "pause" : "play")
				.prop('title', title);
			ssRunning = running;
		}

		function gotoNextSlide() {
			if (ssRunning && !!gallery) {
				ssOnce = true;
				gallery.next();
				// start counter for next slide in 'afterChange' listener
			}
		}

		/* override handling of Esc key to stop slideshow on first esc (note Esc to leave fullscreen never gets here) */
		jQuery(document).keydown(function(e) {
			if (e.altKey || e.ctrlKey || e.shiftKey || e.metaKey) return;
			if ((e.key === "Escape" || e.which == 27 /*esc*/) && !!gallery) {
				if (e.preventDefault)  e.preventDefault();
				else  e.returnValue = false;
				if (ssRunning) {
					setSlideshowState(ssButtonClass, false);
				} else {
					gallery.close();
				}
			}
		});
		
		
		/* END SLIDESHOW FUNCTIONS */
		
    };

    // loop through all gallery elements and bind events
    var galleryElements = document.querySelectorAll( gallerySelector );

    for(var i = 0, l = galleryElements.length; i < l; i++) {
        galleryElements[i].setAttribute('data-pswp-uid', i+1);
        galleryElements[i].onclick = onThumbnailsClick;
    }

    // Parse URL and open gallery if it contains #&pid=3&gid=1
    var hashData = photoswipeParseHash();
    if(hashData.pid && hashData.gid) {
        openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
    }
};

// execute above function
initPhotoSwipeFromDOM('.pg-photoswipe');