( function($) {

	"use strict"

	const enquiryPanel = $('.header .section-contact-form');
	const sectionContent = $('.header .section-content');
	const openEnquiryPanelTrigger = $('.header .mobile-enquiry-panel-trigger-open');
	const closeEnquiryPanelTrigger = $('.header .mobile-enquiry-panel-trigger-close');
	const enquirySubmitTrigger	=$('.gform_footer');
	const phoneButton = $('.header #mobile-phone-button');
	const windowElem = $(window);
	const contactFormContent = $('.section-contact-content');
	const header = $('.header');
	const mobileMenuPanel = $('.gform_body');
	const mobileMenuActions = $('.mobile-contact-header')
	const enquiryPanelTrigger = $('.header .enquiry-panel-trigger');
	const headerEnquiryForm = $('.header .enquiry-form');

	// Callback function set offset CSS for certain elements based on the fixed header height
	function setHeaderOffset() {
		const adminBarHeight = $('#wpadminbar').is(':visible') ? $('#wpadminbar').outerHeight() : 0;
		const headerTopHeight = header.find('.header-top').is(':visible') ? header.find('.header-top').outerHeight() : 0;
		const headerMiddleHeight = header.find('.header-middle').is(':visible') ? header.find('.header-middle').outerHeight() : 0;
		const headerBottomHeight = header.find('.header-bottom').is(':visible') ? header.find('.header-bottom').outerHeight() : 0;
		const fullHeaderHeight = headerTopHeight + headerMiddleHeight + headerBottomHeight;

		return fullHeaderHeight + adminBarHeight;
	}

	function setDeviceDimensions() {

		if ( windowElem.width() < 1199  && headerEnquiryForm.hasClass('in-view')) {
			let vh = window.innerHeight;
			const headerOffset = setHeaderOffset();
			const actionsHeight = mobileMenuActions.outerHeight() * 3;
			contactFormContent.find('.content-wrap').css({"min-height": 'calc( ' + vh + 'px - ' + (headerOffset + actionsHeight) + 'px)',"max-height": 'calc( ' + vh + 'px - ' + (headerOffset + actionsHeight) + 'px)'})
			mobileMenuPanel.css({"min-height": 'calc( ' + vh + 'px - ' + (headerOffset + actionsHeight) + 'px)',"max-height": 'calc( ' + vh + 'px - ' + (headerOffset + actionsHeight) + 'px)'});
		} else {
			contactFormContent.css({"min-height": 'initial',"max-height": 'initial'});
			contactFormContent.find('.content-wrap').css({"min-height": 'initial',"max-height": 'initial'});
			mobileMenuPanel.css({"min-height": 'initial',"max-height": 'initial'});
		}

	}

	// Callback function to OPEN the enquiry panel
	function openMobileEnquiryPanel() {
		openEnquiryPanelTrigger.attr('disabled', true);
		closeEnquiryPanelTrigger.attr('disabled', true);
		openEnquiryPanelTrigger.addClass('hidden')
		phoneButton.addClass('hidden')
		closeEnquiryPanelTrigger.removeClass('hidden')
		sectionContent.addClass('hidden');

		// Get the CSS transition delay set on the enquiry form to use as a delay for focusing on the enquiry form
		// We're doing this after adding the classes above as they may add/change the transition delay value
		// We're also adding a little extra delay to ensure the element is fully visible so we can trigger focus properly
		const timeoutDuration = .7 * 1000 + 50;

		// Wait until the transitions finish before we focus on the enquiry form
		setTimeout( function() {
			enquiryPanel.attr('tabindex', '-1').trigger('focus').removeAttr('tabindex');
			enquiryPanel.addClass('open');
			closeEnquiryPanelTrigger.attr('disabled', false);
		}, timeoutDuration);

		$(document).trigger('orgnk.openMobileEnquiryPanel');

	}

	// Callback function to CLOSE the enquiry panel
	function closeMobilEnquiryPanel() {
		openEnquiryPanelTrigger.attr('disabled', true);
		closeEnquiryPanelTrigger.attr('disabled', true);
		enquiryPanel.removeClass('open');
		closeEnquiryPanelTrigger.addClass('hidden')
		openEnquiryPanelTrigger.removeClass('hidden')
		// Get the CSS transition duration set on the enquiry panel to use as a delay for closing the enquiry panel
		const timeoutDuration = .7 * 1000;

		// Wait until the transitions finish before hide the enquiry form
		setTimeout( function() {
			sectionContent.removeClass('hidden');
			openEnquiryPanelTrigger.attr('disabled', false);
			phoneButton.removeClass('hidden')
		}, timeoutDuration);


		$(document).trigger('orgnk.closeMobilEnquiryPanel');
	}

	// Enquiry panel show on trigger click
	openEnquiryPanelTrigger.add($('a[href="#mobile-enquiry-form-open"]')).on('click touchstart', function(e) {

		if ( $(this).hasClass('open') === false ) {
			openMobileEnquiryPanel();
		}

	});

	// Enquiry panel show on trigger click
	closeEnquiryPanelTrigger.add($('a[href="#mobile-enquiry-form-close"]')).on('click touchstart', function(e) {


		if ( $(this).hasClass('open') === false ) {
			closeMobilEnquiryPanel();
		}

	});


	enquiryPanelTrigger.on('click touchstart', function(e) {

		if ( $(this).hasClass('open') === true ) {
			setDeviceDimensions();
		}

		if ( $(this).hasClass('open') === false ) {
			contactFormContent.css({"min-height": 'initial',"max-height": 'initial'});
			mobileMenuPanel.css({"min-height": 'initial',"max-height": 'initial'});
		}
	});

	function onKeyboardOnOff(isOpen) {
		// Write down your handling code
		if (isOpen) {
			closeEnquiryPanelTrigger.addClass('fixfixed')
			openEnquiryPanelTrigger.addClass('fixfixed')
			enquirySubmitTrigger.addClass('fixfixed')
		} else {
			closeEnquiryPanelTrigger.removeClass('fixfixed')
			openEnquiryPanelTrigger.removeClass('fixfixed')
			enquirySubmitTrigger.removeClass('fixfixed')
		}
	}

	var originalPotion = false;
	$(document).ready(function(){
		if (originalPotion === false) originalPotion = $(window).width() + $(window).height();
	});

	/**
	 * Determine the mobile operating system.
	 * This function returns one of 'iOS', 'Android', 'Windows Phone', or 'unknown'.
	 *
	 * @returns {String}
	 */
	function getMobileOperatingSystem() {
		var userAgent = navigator.userAgent || navigator.vendor || window.opera;

		  // Windows Phone must come first because its UA also contains "Android"
		if (/windows phone/i.test(userAgent)) {
			return "winphone";
		}

		if (/android/i.test(userAgent)) {
			return "android";
		}

		// iOS detection from: http://stackoverflow.com/a/9039885/177710
		if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
			return "ios";
		}

		return "";
	}

	function applyAfterResize() {

		if (getMobileOperatingSystem() != 'ios') {
			if (originalPotion !== false) {
				var wasWithKeyboard = $('body').hasClass('view-withKeyboard');
				var nowWithKeyboard = false;

					var diff = Math.abs(originalPotion - ($(window).width() + $(window).height()));
					if (diff > 100) nowWithKeyboard = true;

				$('body').toggleClass('view-withKeyboard', nowWithKeyboard);
				if (wasWithKeyboard != nowWithKeyboard) {
					onKeyboardOnOff(nowWithKeyboard);
				}
			}
		}
	}

	$(document).on('focus blur', 'select, textarea, input[type=text], input[type=date], input[type=password], input[type=email], input[type=number]', function(e){
		var $obj = $(this);
		var nowWithKeyboard = (e.type == 'focusin');
		$('body').toggleClass('view-withKeyboard', nowWithKeyboard);
		onKeyboardOnOff(nowWithKeyboard);
	});

	$(window).on('resize orientationchange', function(){
		applyAfterResize();
	});


	windowElem.on( 'resize', function() {
		setDeviceDimensions();
		applyAfterResize();
	});

	$(window).on('resize orientationchange', function(){
		setDeviceDimensions();
		applyAfterResize();
	});
	})( jQuery );