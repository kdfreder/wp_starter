//jquery initialize : applies functions to dynamically created elements
!function(i){var t=function(i,t){this.selector=i,this.callback=t},e=[];e.initialize=function(e,n){var c=[],a=function(){-1==c.indexOf(this)&&(c.push(this),i(this).each(n))};i(e).each(a),this.push(new t(e,a))};var n=new MutationObserver(function(){for(var t=0;t<e.length;t++)i(e[t].selector).each(e[t].callback)});n.observe(document.documentElement,{childList:!0,subtree:!0,attributes:!0}),i.fn.initialize=function(i){e.initialize(this.selector,i)},i.initialize=function(i,t){e.initialize(i,t)}}(jQuery);

//checks where element is in relation to the viewport
//if($(".element:above-the-top").length) if you know your element or if($(this).filter(":above-the-top").length) if you're in an each loop and don't know the element
(function($){$.belowthefold=function(element,settings){var fold=$(window).height()+$(window).scrollTop();return fold<=$(element).offset().top-settings.threshold;};$.abovethetop=function(element,settings){var top=$(window).scrollTop();return top>=$(element).offset().top+$(element).height()-settings.threshold;};$.rightofscreen=function(element,settings){var fold=$(window).width()+$(window).scrollLeft();return fold<=$(element).offset().left-settings.threshold;};$.leftofscreen=function(element,settings){var left=$(window).scrollLeft();return left>=$(element).offset().left+$(element).width()-settings.threshold;};$.inviewport=function(element,settings){return!$.rightofscreen(element,settings)&&!$.leftofscreen(element,settings)&&!$.belowthefold(element,settings)&&!$.abovethetop(element,settings);};$.extend($.expr[':'],{"below-the-fold":function(a,i,m){return $.belowthefold(a,{threshold:0});},"above-the-top":function(a,i,m){return $.abovethetop(a,{threshold:0});},"left-of-screen":function(a,i,m){return $.leftofscreen(a,{threshold:0});},"right-of-screen":function(a,i,m){return $.rightofscreen(a,{threshold:0});},"in-viewport":function(a,i,m){return $.inviewport(a,{threshold:0});}});})(jQuery);

//if for some reason we need a popup window, usually with a facebook share link
function popupOnCurrentScreenCenter(url, w, h) {
	var dualScreenLeft = typeof window.screenLeft !== "undefined" ? window.screenLeft : screen.left;
	var dualScreenTop = typeof window.screenTop !== "undefined" ? window.screenTop : screen.top;
	var width = window.innerWidth ? window.innerWidth :
		document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
	var height = window.innerHeight ? window.innerHeight :
		document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
	var left = ((width / 2) - (w / 2)) + dualScreenLeft;
	var top = ((height / 2) - (h / 2)) + dualScreenTop;
	var newWindow =
		window.open(url, '_blank', 'toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=0, resizable=0, copyhistory=0, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
	// Puts focus on the newWindow
	if (window.focus) {
		newWindow.focus();
	}
};

//gets us a single parameter from a url as a string, or from the current url
function getParameterByName(name, url) {
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, "\\$&");
	var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
		results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, " "));
}

//sets a timeout to do multiple functions, good to use to throttle resize events
var waitForFinalEvent = (function () {
	var timers = {};
	return function (callback, ms, uniqueId) {
		if (!uniqueId) { uniqueId = "Don't call this twice without a uniqueId"; }
		if (timers[uniqueId]) { clearTimeout (timers[uniqueId]); }
		timers[uniqueId] = setTimeout(callback, ms);
	};
})();

//this can capitalize the first letter of each word
function ucwords(str,force){
    str=force ? str.toLowerCase() : str;
    return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
    function(firstLetter){
    	return firstLetter.toUpperCase();
    });
}

//sets a variable for all of the current url parameters
function getUrlVars(){
    var vars = {}, hash;
    if(window.location.href.indexOf('?') <= 0){
	    return vars;
    }    
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++){
        hash = hashes[i].split('=');
        vars[hash[0]] = hash[1];
    }
    return vars;
}

//check if your obj is empty
function isEmpty(obj) {
    if (obj == null) return true;
    if (obj.length > 0)    return false;
    if (obj.length === 0)  return true;
    for (var key in obj) {
        if (hasOwnProperty.call(obj, key)) return false;
    }
    return true;
}

//get the parameters from a string of a search query, can be used like: var $_GET = getQueryParams(document.location.search);
function getQueryParams(qs) {
    qs = qs.split("+").join(" ");
    var params = {},
        tokens,
        re = /[?&]?([^=]+)=([^&]*)/g;

    while (tokens = re.exec(qs)) {
        params[decodeURIComponent(tokens[1])]
            = decodeURIComponent(tokens[2]);
    }
    return params;
}

//can get a part of a url string like: pageN = getPathPart(href, 0, true);
getPathPart = function(path, index, getLastIndex){
	if (index === undefined){
	    index = 0;
	}
	parts = path.split('/');
	if (parts && parts.length > 1){
	    parts = (parts || []).splice(1);
	}
	if(getLastIndex){
	    if(parts[parts.length - 1] == ''){
		    return parts[parts.length - 2]
	    } else {
		    return parts[parts.length - 1]
	    }
	}  
	return parts.length > index ? parts[index] : null;
}

//simple way to determine if on ie
function detectIE() {
	var ua = window.navigator.userAgent;
	var msie = ua.indexOf('MSIE ');
	if (msie > 0) {
		return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
	}
	var trident = ua.indexOf('Trident/');
	if (trident > 0) {
		// IE 11 => return version number
		var rv = ua.indexOf('rv:');
		return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
	}
	var edge = ua.indexOf('Edge/');
	if (edge > 0) {
		// Edge (IE 12+) => return version number
		return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
	}
	// other browser
	return false;
}

jQuery(document).ready(function($) {
	var version = detectIE();
	if (version === false) {
		$("html").addClass("notIE");
	} else {
		$("html").addClass("IE");
	}
	
	if ("ontouchstart" in window || "ontouch" in window) { 
		$('html').addClass('touch'); 
	} else {
		$('html').addClass('no-touch'); 
	}
	
	//finds if there is an offest between an element and the text inside it
	$.fn.textOffset = function(){
		var html_org = $(this).html();
		var html_calc = '<span>' + html_org + '</span>';
		$(this).html(html_calc);
		var left = $(this).find('span:first').position().left;
		$(this).html(html_org);
		return left;
	};
	
	//finds the width of the text inside of an element
	$.fn.textWidth = function(){
		var html_org = $(this).html();
		var html_calc = '<span>' + html_org + '</span>';
		$(this).html(html_calc);
		var width = $(this).find('span:first').width();
		$(this).html(html_org);
		return width;
	};
	
	//checks if the element is after another element
	$.fn.isAfter = function(sel){
		return this.prevAll(sel).length !== 0;
	}
	
	//checkes if the element is before another element
	$.fn.isBefore = function(sel){
		return this.nextAll(sel).length !== 0;
	}
	
	//changes the order of the elements with selector
	$.fn.shuffle = function() {
	    var allElems = this.get(),
	        getRandom = function(max) {
	            return Math.floor(Math.random() * max);
	        },
	        shuffled = $.map(allElems, function(){
	            var random = getRandom(allElems.length),
	                randEl = $(allElems[random]).clone(true)[0];
	            allElems.splice(random, 1);
	            return randEl;
	       });
	    this.each(function(i){
	        $(this).replaceWith($(shuffled[i]));
	    });
	    return $(shuffled);
	};
	
	//easy way to subscribe to mailchimp lists
	//the form action would be below. you can git it by viewing one of the embeddable forms mailchimp provides. (MERGE0 , the email field, is required)
	//https://[mailchimp usser].us12.list-manage.com/subscribe/post-json?u=[mailchil user id]&id=[list id]
	function submitMailchimpSubscribeForm($form, $resultElement) {
		$resultElement.html("Subscribing...");
	    $.ajax({
	        type: "GET",
	        url: $form.attr("action"),
	        data: $form.serialize(),
	        cache: false,
	        dataType: "jsonp",
	        jsonp: "c", // trigger MailChimp to return a JSONP response
	        contentType: "application/json; charset=utf-8",
	        success: function(data){
                if (data.result != "success") {
                    var message = data.msg || "Sorry. Unable to subscribe. Please try again later.";
                    if (data.msg && data.msg.indexOf("already subscribed") >= 0) {
                        message = "You're already subscribed. Thank you.";
                    }
                    $resultElement.html(message);
                } else {
                    $resultElement.html("Thank you for subscribing!");
                }
            }
	    });
	}
	
	//lightbox.addClass("active needs-overflow");
	var overflowCheck = function(action){
		if(action == 'remove'){
			if(!$(".needs-overflow").length){
				$("html, body").removeClass("overflow");
			}
		}
		if(action == 'add'){
			$("html, body").addClass("overflow");
		}
	}
	
	//the lightbox functions
	$("[data-action='lightbox']").initialize(function(){
		$(this).click(function(e){
			e.preventDefault();
			//if we just want to show one image
			if($(this).data('rel') == 'image'){
				//element also needs data-url for image you want to open
				$("#lightbox .lightbox-content").html("<img src='"+$(this).data('url')+"' />");
				var expander = $(this);
				expander.addClass("load"); //here we can show the user we are working on it
				$("#lightbox .lightbox-content img").load(function(){
					$("#lightbox").addClass("active needs-overflow");
					expander.removeClass("load");
					overflowCheck('add');
					$("#lightbox .inner").addClass("image");
				});
			}
			//if we want to show an image from a slick slider, without having to make another slick slider, but still want to be able to advance through the slides
			if($(this).data('rel') == 'slider-image'){
				//element also needs data-url for image you want to open
				$("#lightbox .lightbox-content").html("<img src='"+$(this).data('url')+"' class='main-img' />");
				var expander = $(this);
				expander.addClass("load");
				if(expander.hasClass("slick-arrow")){
					// this will get called when using one of the arrows added below
					var slider = $(".current-lightbox-subject");
					//let's advance the slider
					slider.slick('slickGoTo', parseInt(expander.data("slick-index")), true);
				} else {
					//this is called with the initial true expaner button click
					var slider = expander.parents(".slick-slider");
					slider.addClass("current-lightbox-subject");
				}
				var current = slider.find(".slick-current");
				var prev = current.prev(".slick-slide").find(".expander").data('url');
				var next = current.next(".slick-slide").find(".expander").data('url');
				var prevIndex = current.prev(".slick-slide").data('slick-index');
				var nextIndex = current.next(".slick-slide").data('slick-index');
				$("#lightbox .lightbox-content").prepend("<button class='slick-prev slick-arrow' data-action='lightbox' data-rel='slider-image' data-slick-index='"+prevIndex+"' data-url='"+prev+"'>Previous</button>");
				$("#lightbox .lightbox-content").append("<button class='slick-next slick-arrow' data-action='lightbox' data-rel='slider-image' data-slick-index='"+nextIndex+"' data-url='"+next+"'>Next</button>");
				//let's lazy load the next and previous images too so they are ready
				$("#lightbox .lightbox-content").append("<img src='"+prev+"' style='display:none;' /><img src='"+next+"' style='display:none;' />");
				$("#lightbox .lightbox-content .main-img").load(function(){
					$("#lightbox").addClass("active needs-overflow");
					expander.removeClass("load");
					overflowCheck('add');
					$("#lightbox .inner").addClass("image slider-image");
				});
			}
			if($(this).data('rel') == 'video'){
				//element also needs data-platform for youtube or vimeo and data-id for the video
				if($(this).data('platform') == 'vimeo'){
					$("#lightbox .lightbox-content").html('<div class="vid-box"><div class="vid-holder"><iframe src="https://player.vimeo.com/video/'+$(this).data("id")+'?title=0&byline=0&portrait=0&autoplay=1" tabindex="0" frameborder="0" allowfullscree></iframe></div></div>');
				}
				if($(this).data('platform') == 'youtube'){
					$("#lightbox .lightbox-content").html('<div class="vid-box"><div class="vid-holder"><iframe src="https://www.youtube.com/embed/'+$(this).data("id")+'?autoplay=1&autohide=1&rel=0&enablejsapi=1" tabindex="0" frameborder="0" allowfullscreen></iframe></div></div>');
				}
				$("#lightbox").addClass("active needs-overflow");
				overflowCheck('add');
				$("#lightbox .inner").addClass("video");
			}
			if($(this).data('rel') == 'content'){
				//this is used for content that's hard or unnecessary to make completely dynamic
				if($(this).data('type') == 'info'){
					//this element will need a sibling .content-for-lightbox to hold the html you want in the lightbox
					var content = $(this).parent().find(".content-for-lightbox").html();
					$("#lightbox .lightbox-content").html(content);
				}
				if($(this).data('type') == 'slider'){
					//this element will need a sibling .slides-for-lightbox that holds the elements you want to make into slides.
					//or a data-slider to associate the elements
					//it can't be a slider already though because the navigation wouldn't work
					var slide = $(this).data('slide');
					if(typeof $(this).data('slider') !== 'undefined'){
						//association
						//the .parent().parent() helps to localize it, but you can remove it if you need
						var slides = $(this).parent().parent().find(".slides-for-lightbox[data-slider='"+$(this).data('slider')+"']").html();
					} else {
						//sibling
						var slides = $(this).parent().find(".slides-for-lightbox").html();
					}
					$("#lightbox .inner").addClass("slider");
					$("#lightbox .lightbox-content").html(slides);
					$("#lightbox .lightbox-content").slick();
					$("#lightbox .lightbox-content").slick('slickGoTo', slide, true);
					$("#lightbox .lightbox-content").on('afterChange', function(event, slick, currentSlide){
						$(".slick-slide[data-slick-index='"+currentSlide+"'] .inner-scroll").css("height", "100%");
					});
				}
				$("#lightbox .inner").addClass("content");
				$("#lightbox").addClass("active needs-overflow");
				overflowCheck('add');
				if($(this).data('type') == 'slider'){
					$("#lightbox .lightbox-content").slick('setPosition');
					$("#lightbox .inner").addClass("set");
				}
			}
			if($(this).data('rel') == 'ajax-post'){
				//needs a data-rel for the post type you want and a data-id for the post id
				$.ajax({
					url: scripts.ajaxurl,
					type: 'get',
					data: {
						action: 'get_my_post',
						posttype: $(this).data('rel'),
						postid: $(this).data('id'),
					},
					success: function( response ) {
						if(response != 'none'){
							$("#lightbox .lightbox-content").html(response);
						}
					}
				}).done(function(){
					$("#lightbox .inner").addClass("ajax-"+$(this).data('rel'));
					$("#lightbox").addClass("active needs-overflow");
					overflowCheck('add');
				});
			}
			//for future: need to add something to handle things that cannot be cloned or dynamically created into the lightbox-content, like gravity forms
			//would need to be able to add the content in the footer template and hide it until needed, and not remove it while on the page-
			//there could still be other lightboxes on the page
		});
	});
	$(".lightbox-closer").initialize(function(){
		$(this).click(function(){
			$("#lightbox").removeClass("active needs-overflow");
			overflowCheck('remove');
			$("#lightbox .inner").attr("class", "inner");
			$("#lightbox .lightbox-content").attr("class", "lightbox-content");
			$(".slick-slider").removeClass("current-lightbox-subject");
			if($("#lightbox .lightbox-content.slick-initialized").length){
				$("#lightbox .lightbox-content").slick('unslick');
			}
			$("#lightbox .lightbox-content").delay(300).html("");
		});
	});
	
	//if somehow, somewhere a video embed doesn't have our containers, let's add them
	$("iframe[src*='vimeo'], frame[src*='youtube']").each(function(){
		if(!$(this).parent().hasClass("vid-holder")){
			$(this).wrap( "<div class='vid-box'><div class='vid-holder'></div></div>" );
		}
	});
	
	//will apply a class to every link on the page to let us know if it's the current page, because wordpress doesn't always
	$("[href]").each(function() {
		if (this.href == window.location.href) {
			$(this).addClass("current-page");
		}
	});
	
	//a quick way to make mobile only slick sliders for things like recent posts or something
	//current limitation that the element can only have this one class with digits in it
	//e.g. .slick-this-to-768
	$("*[class*=slick-this-to-]").each(function(){
		var num = $(this).attr('class').match(/\d+/)[0];
		if($(window).width() < num){
			if(!$(this).hasClass("slick-initialized")){
				$(this).slick({
					arrows:false,
					dots: true,
				});
			}
		} else {
			if($(this).hasClass("slick-initialized")){
				$(this).slick("unslick");
			}
		}
	});
	$(window).resize(function(){
		$("*[class*=slick-this-to-]").each(function(){
			var num = $(this).attr('class').match(/\d+/)[0];
			if($(window).width() < num){
				if(!$(this).hasClass("slick-initialized")){
					$(this).slick({
						arrows:false,
						dots: true,
					});
				}
			} else {
				if($(this).hasClass("slick-initialized")){
					$(this).slick("unslick");
				}
			}
		});
	});
	
	
	//animate the anchors on the page
	$("a[href*='#']").click(function(e){
		var myString = $(this).attr("href").split("#").pop();
		if($("#"+myString).length){
			e.preventDefault();
			$("#mobile-menu-close").click();
			$("html, body").animate({
				scrollTop: $("#"+myString).offset().top,
			});
		}
	});
	
	//we usually have a back to top button
	if($('html').height() < $(window).height()*1.5){
		$("#scroll-top, #scroll-top-catch").hide();
	}
	$("#scroll-top").click(function(){
		$("html, body").animate({
			scrollTop: 0,
		}, 300);
		return false;
	});
	$(window).scroll(function(){
		if($("#scroll-top-catch:in-viewport").length || $("#scroll-top-catch:above-the-top").length){
			$("#scroll-top").addClass("active");
		} else {
			$("#scroll-top").removeClass("active");
		}
	});
	
	//object-fit is really nice, but sometimes we need it to work on IE
	if($("html").hasClass("IE")){
		$(".object-fit-cover, .object-fit-contain, .object-fit-scale-down").each(function(){
			var src = $(this).find("img").attr("src");
			$(this).css({
				"background-image": "url('"+src+"')"
			});
			$(this).find("img").css({
				"opacity": 0,
			});
		});
	}
});