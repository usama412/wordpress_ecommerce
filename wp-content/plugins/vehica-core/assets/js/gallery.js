"use strict"

jQuery(document).ready(function () {
    var pswpElement = document.querySelectorAll('.pswp')[0];

    var images = [];

    jQuery.each(jQuery('.vehica-gallery-main .vehica-swiper-slide:not(.vehica-swiper-slide-duplicate)'), (index, image) => {
        if (typeof jQuery(image).data('src') !== 'undefined') {
            images.push({
                src: jQuery(image).data('src'),
                w: jQuery(image).data('width'),
                h: jQuery(image).data('height'),
                title: jQuery(image).data('title'),
                index: jQuery(image).data('index')
            });
        }
    })

    jQuery('.vehica-gallery-main .vehica-swiper-slide').on('click', function () {
        var options = {
            index: jQuery(this).data('index'),
            showHideOpacity: true,
            closeOnScroll: false,
            shareEl: false,
            getThumbBoundsFn: function () {
                var pageYScroll = window.pageYOffset || document.documentElement.scrollTop;
                var rect = jQuery('.vehica-gallery-main').get(0).getBoundingClientRect();
                return {x: rect.left, y: rect.top + pageYScroll, w: rect.width, h: rect.height};
            }.bind(this)
        };

        var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, images, options);
        gallery.listen('afterChange', function () {
            window.VehicaEventBus.$emit('carGalleryChangeImage', gallery.currItem.index);
        });
        gallery.init();
    })
});