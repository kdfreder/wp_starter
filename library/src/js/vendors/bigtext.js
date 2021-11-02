/*! @preserve plugins/jquery-bigtext.js */

//big text example
/*if(!$(".big-text span").length){
	$(".big-text").wrapInner("<span/>");
}
$(".big-text > span").bigText({
	maximumFontSize: 80,
	limitingDimension: 'width',
});*/
(function($){
    "use strict";
    var defaultOptions= {
        fontSizeFactor: 1.15,
        maximumFontSize: null,
        limitingDimension: "both",
    };

    $.fn.bigText= function(options) {
        return this.each(function() {
            options= $.extend({}, defaultOptions, options);
            var $this= $(this);
            var $parent= $this.parent();

            $this.css("visibility", "hidden");

            $this.css({
	            'display': 'inline',
                'font-size': (1000 * options.fontSizeFactor) + "px",
                'line-height': "1000px",
            });
			
            var parentPadding= {
                left: parseInt($parent.css('padding-left')),
                top: parseInt($parent.css('padding-top')),
                right: parseInt($parent.css('padding-right')),
                bottom: parseInt($parent.css('padding-bottom'))
            };

            var box= {
                width: $this.outerWidth(),
                height: $this.outerHeight()
            };

            var widthFactor= ($parent.innerWidth() - parentPadding.left - parentPadding.right) / box.width;
            var heightFactor= ($parent.innerHeight() - parentPadding.top - parentPadding.bottom) / box.height;
            var lineHeight;

            if (options.limitingDimension.toLowerCase() === "width") {
                lineHeight= Math.floor(widthFactor * 1000);
                $parent.height(lineHeight);
            } else if (options.limitingDimension.toLowerCase() === "height") {
                lineHeight= Math.floor(heightFactor * 1000);
            } else if (widthFactor < heightFactor)
                lineHeight= Math.floor(widthFactor * 1000);
            else if (widthFactor >= heightFactor)
                lineHeight= Math.floor(heightFactor * 1000);

            var fontSize= lineHeight * options.fontSizeFactor;
            if (options.maximumFontSize !== null && fontSize > options.maximumFontSize) {
                fontSize= options.maximumFontSize;
                lineHeight= fontSize / options.fontSizeFactor;
            }

            $this.css({
                'font-size': Math.floor(fontSize)  + "px",
                'line-height': Math.ceil(lineHeight)  + "px",
                'display': 'block'
            });

            $this.css("visibility", "");
            
        });
    }
})(jQuery);
