( function($) {

"use strict"

// =======================================================================================================================
// Callback functions
// =======================================================================================================================

// Disable scroll function
function disableScroll() {

	const adminBarHeight = $('#wpadminbar').is(':visible') ? $('#wpadminbar').outerHeight() : 0;

	// Temporarily disable the html element's scroll-behaviour property while the function runs
	// This prevents scroll jumping and re-animating when body scrolling is re-activated
	$('html').css({ 'scroll-behavior' : 'initial' });

	if ( ( bodyElem.hasClass('disable-scroll') ) && ( ( bodyElem.attr('data-offset-top') == undefined ) ) && ! bodyElem.hasClass('dont-disable-scroll') )  {

		let scrollPos = windowElem.scrollTop() - adminBarHeight;

		bodyElem.attr('data-offset-top', scrollPos).css({
			'position' : 'fixed',
			'width' : '100%',
			'top' : -scrollPos + 'px'
		});

	} else if ( ( ! bodyElem.hasClass('disable-scroll') ) && ( ( bodyElem.attr('data-offset-top') != undefined ) ) ) {

		let scrollPos = bodyElem.attr('data-offset-top') ? bodyElem.attr('data-offset-top') : 0;

		bodyElem.removeAttr('data-offset-top').removeAttr('style');
		windowElem.scrollTop(scrollPos);

	}

	// Finally, reset the html element's scroll-behaviour property after the function is complete
	$('html').css({ 'scroll-behavior' : '' });
}

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

const enquiryForm = $('.header .mobile-enquiry-form');
const enquiryPanel = $('.header .section-contact-form');
const sectionContent = $('.header .section-content');
const openEnquiryPanelTrigger = $('.header .mobile-enquiry-panel-trigger-open');
const closeEnquiryPanelTrigger = $('.header .mobile-enquiry-panel-trigger-close');
const phoneButton = $('.header #mobile-phone-button');
const panelScroll = $('.mobile-panel-scroll');
const resizeSelectElem = $('select.resize-select');
const bodyElem = $('body');
const windowElem = $(window);

// Callback function to OPEN the enquiry panel
function openMobileEnquiryPanel() {

	sectionContent.addClass('hidden');
	closeEnquiryPanelTrigger.removeClass('hidden')
	phoneButton.addClass('hidden')
	// Get the CSS transition delay set on the enquiry form to use as a delay for focusing on the enquiry form
	// We're doing this after adding the classes above as they may add/change the transition delay value
	// We're also adding a little extra delay to ensure the element is fully visible so we can trigger focus properly
	const timeoutDuration = .7 * 1000 + 50;

	// Wait until the transitions finish before we focus on the enquiry form
	setTimeout( function() {
		enquiryPanel.attr('tabindex', '-1').trigger('focus').removeAttr('tabindex');
		enquiryPanel.addClass('open');
	}, timeoutDuration);

	openEnquiryPanelTrigger.addClass('hidden')
}

// Callback function to CLOSE the enquiry panel
function closeMobilEnquiryPanel() {
	enquiryPanel.removeClass('open');

	// Get the CSS transition duration set on the enquiry panel to use as a delay for closing the enquiry panel
	const timeoutDuration = .7 * 1000;

	// Wait until the transitions finish before hide the enquiry form
	setTimeout( function() {
		sectionContent.removeClass('hidden');
		// sectionWrap.css('min-height', sectionWrap.outerHeight())
	}, timeoutDuration);

	closeEnquiryPanelTrigger.addClass('hidden')
	openEnquiryPanelTrigger.removeClass('hidden')
	phoneButton.removeClass('hidden')

}

// Enquiry panel show on trigger click
openEnquiryPanelTrigger.add($('a[href="#mobile-enquiry-form-open"]')).on('click touchstart', function(e) {

	e.preventDefault();

	if ( $(this).hasClass('open') === false ) {

		openMobileEnquiryPanel();

	}
	disableScroll();

});

// Enquiry panel show on trigger click
closeEnquiryPanelTrigger.add($('a[href="#mobile-enquiry-form-close"]')).on('click touchstart', function(e) {

	e.preventDefault();

	if ( $(this).hasClass('open') === false ) {

		closeMobilEnquiryPanel();

	}
	disableScroll();

});

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
