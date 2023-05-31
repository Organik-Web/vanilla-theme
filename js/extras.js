( function($) {

"use strict"

// =======================================================================================================================
// Callback functions
// =======================================================================================================================

// Gallery slider
function splideGallery() {

	// Check Splide exists
	if ( typeof Splide != 'undefined' ) {

		const gallery = $('.orgnk-gallery');
		const galleryMain = gallery.find('.gallery-main');
		const galleryThumbs = gallery.find('.gallery-thumbs');

		gallery.each( function(i) {

			const galleryTop = new Splide( galleryMain[i], {
				pagination		: false,
				type			: 'fade',
				slideFocus		: false,
				keyboard        : 'focused'
			} );

			const galleryBottom = new Splide( galleryThumbs[i], {
				pagination 		: false,
				arrows			: false,
				fixedWidth 		: 120,
				isNavigation	: true,
				focus      		: 'center',
				keyboard        : 'focused'
			} ).mount();

			galleryTop.sync( galleryBottom ).mount();
		});
	}
}

// Testimonials slider
function splideTestimonials() {

	// Check Splide exists
	if ( typeof Splide != 'undefined' ) {

		const testimonialSlider = $('.orgnk-testimonials.type-slider');
		const testimonialSliderMain = testimonialSlider.find('.testimonials-list');

		testimonialSlider.each( function(i) {

			const slider = new Splide( testimonialSliderMain[i], {
				type     		: 'loop',
				pagination 		: true,
				focus      		: 'center',
				speed			: 300,
				slideFocus		: false,
				keyboard        : 'focused'
			});
			slider.mount();

			const paginationDots = $(this).find('.splide__pagination').children('li');
			const paginationHeight = $(this).find('.splide__pagination').outerHeight();
			let paginationWidth = 0;

			// Loop through each pagination dot and add it's width
			paginationDots.each( function() {
				paginationWidth += $(this).width();
			});

			const arrowHeight = $(this).find('.splide__arrows').outerHeight();
			const arrowOffset = ( arrowHeight - paginationHeight ) / 2;

			// $(this).css({ 'padding-bottom' : arrowHeight - arrowOffset + 'px'});
			$(this).find('.splide__arrows').css({ 'bottom' : -arrowOffset + 'px' });
			$(this).find('.splide__arrow--prev').css({ 'margin-right' : paginationWidth + 'px' });
		});
	}
}

// Google Reviews slider
function splideGoogleReviews() {

	// Check Splide exists
	if ( typeof Splide != 'undefined' ) {

		const reviewsSlider = $('.orgnk-greviews.type-slider');
		const reviewsSliderMain = reviewsSlider.find('.reviews-list');

		reviewsSlider.each( function(i) {

			const slider = new Splide( reviewsSliderMain[i], {
				type     		: 'loop',
				pagination 		: true,
				focus      		: 'center',
				speed			: 300,
				slideFocus		: false,
				keyboard        : 'focused',
				perPage			: 3,
				breakpoints		: {
					1023: {
						perPage: 1
					},
					1399: {
						perPage: 2
					}
				}
			});
			slider.mount();

			const paginationDots = $(this).find('.splide__pagination').children('li');
			const paginationHeight = $(this).find('.splide__pagination').outerHeight();
			let paginationWidth = 0;

			// Loop through each pagination dot and add it's width
			paginationDots.each( function() {
				paginationWidth += $(this).width();
			});

			const arrowHeight = $(this).find('.splide__arrows').outerHeight();
			const arrowOffset = ( arrowHeight - paginationHeight ) / 2;

			// $(this).css({ 'padding-bottom' : arrowHeight - arrowOffset + 'px'});
			$(this).find('.splide__arrows').css({ 'bottom' : -arrowOffset + 'px' });
			$(this).find('.splide__arrow--prev').css({ 'margin-right' : paginationWidth + 'px' });
		});
	}
}
// =======================================================================================================================
// Window events
// =======================================================================================================================

// Window load
$(document).ready( function() {
	splideGallery();
	splideTestimonials();
	splideGoogleReviews();
});

})( jQuery );
