/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */
var tst = tst || {};

jQuery(document).on('ready', function () {
    var $ = jQuery;
    tst.adjustBackgroundImages = function (tstLoadedImages) {
        // Index background images.
        var imageElements = [];
        $('*').each(function () {
            var $this = $(this);
            var backgroundImage = $this.css('backgroundImage');

            // Fix for avia-slideshow.
            if (!backgroundImage || backgroundImage === 'none') {
                backgroundImage = $this.attr('data-img-url');
            }

            // Only applies to cover images.
            if (!backgroundImage || $this.css('backgroundSize') !== 'cover') {
                return;
            }

            imageElements.push({
                element: $this,
                url: backgroundImage,
                type: 'background',
            });
        });

        // Index image tags.
        $('img').each(function () {
            var $this = $(this);
            var src = $this.attr('src');

            // Only applies to object-fit images.
            if (!src || $(this).css('objectFit') !== 'cover') {
                return;
            }

            imageElements.push({
                element: $this,
                url: src,
                type: 'image',
            });
        });

        // Browse through all image IDs.
        for (var id in tstLoadedImages) {
            var image = tstLoadedImages[id];

            // Browse through all URLs.
            for (var i = 0; i < image.urls.length; i++) {
                var url = image.urls[i];

                // Find elements using this URL.
                for (var j = 0; j < imageElements.length; j++) {
                    var imageElement = imageElements[j];

                    // Search for URL directly.
                    var found = imageElement.url.indexOf(url) !== -1;

                    // Try to decode URL.
                    if (!found) {
                        try {
                            if (decodeURIComponent(imageElement.url).indexOf(url) !== -1) {
                                found = true;
                            }
                        } catch (e) {
                        }
                    }

                    if (found) {
                        var x = image.focusPointX * 100 + '%';
                        var y = image.focusPointY * 100 + '%';

                        if (imageElement.type === 'background') {
                            imageElement.element
                                .css('background-position-x', x)
                                .css('background-position-y', y);
                        } else if (imageElement.type === 'image') {
                            imageElement.element.css('object-position', x + ' ' + y);
                        }
                    }
                }
            }
        }
    };

    if (typeof tstLoadedImages !== 'undefined') {
        tst.adjustBackgroundImages(tstLoadedImages);
    }
});
