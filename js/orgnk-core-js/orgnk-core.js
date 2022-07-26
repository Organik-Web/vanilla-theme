( function($) {

"use strict"

// =======================================================================================================================
// Global variables
// =======================================================================================================================

const pageBody = $('.page-body');
const header = $('.header');
const overlayPanel = $('.header .overlay-panel');
const mobileMenu = $('.header .mobile-menu');
const mobileMenuPanel = $('.header .mobile-menu-panel');
const mobileMenuPanelTrigger = $('.header .mobile-menu-panel-trigger');
const enquiryForm = $('.header .enquiry-form');
const enquiryPanel = $('.header .enquiry-panel');
const enquiryPanelTrigger = $('.header .enquiry-panel-trigger');
const searchPanel = $('.header .search-panel');
const searchPanelTrigger = $('.header .search-panel-trigger');
const closeSearchPanelTrigger = $('.header .search-panel .search-panel-trigger-close');
const searchPanelInput = $('.header .search-panel .search-input');
const desktopNav = $('.header .nav-has-sub-menu');
const headerSubNav = $('.header .header-sub-nav');
const sidebar = $('.sidebar');
const sidebarMenu = $('.sidebar-menu');
const resizeSelectElem = $('select.resize-select');
const bodyElem = $('body');
const windowElem = $(window);



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

// Callback function set offset CSS for certain elements based on the fixed header height
function setHeaderOffset() {

	const adminBarHeight = $('#wpadminbar').is(':visible') ? $('#wpadminbar').outerHeight() : 0;
	const headerTopHeight = header.find('.header-top').is(':visible') ? header.find('.header-top').outerHeight() : 0;
	const headerMiddleHeight = header.find('.header-middle').is(':visible') ? header.find('.header-middle').outerHeight() : 0;
	const headerBottomHeight = header.find('.header-bottom').is(':visible') ? header.find('.header-bottom').outerHeight() : 0;
	const fullHeaderHeight = headerTopHeight + headerMiddleHeight + headerBottomHeight;

	overlayPanel.css({ 'padding-top' : fullHeaderHeight + adminBarHeight + 'px' });
}

// Callback function to OPEN the desktop menu submenu
function openDesktopSubMenu( menuItem ) {

	const subMenu = $(menuItem).children('ul.sub-menu');
	const subMenuWidth = subMenu.width();
	const elemLeftPos = $(menuItem).offset().left;
	const windowRightPos = windowElem.width() - ( subMenuWidth + 50 );

	// Align the submenu right if it's too close to the right side of the window
	if ( elemLeftPos > windowRightPos ) {
		subMenu.addClass('align-right');
	}

	// If not, remove this class and reset the position
	else {
		subMenu.removeClass('align-right');
	}

	$(menuItem).addClass('sub-menu-open');
}

// Callback function to CLOSE the desktop menu submenu
function closeDesktopSubMenu( menuItem ) {
	$(menuItem).removeClass('sub-menu-open');
}

// Callback function to OPEN the overlay panel
function openOverlayPanel() {
	if ( bodyElem.hasClass('is-overlay') === false ) {
		bodyElem.addClass('is-overlay disable-scroll');
	}
}

// Callback function to CLOSE the overlay panel
function closeOverlayPanel() {

	if ( bodyElem.hasClass('is-overlay') === true ) {

		// Get any CSS transition delay set on the overlay panel to use as a delay
		const timeoutDuration = overlayPanel.attr('data-transition-delay') ? parseFloat(overlayPanel.attr('data-transition-delay'))  * 1000 : 0;

		setTimeout( function() {
			bodyElem.removeClass('is-overlay disable-scroll');
		}, timeoutDuration);
	}
}

// Callback function to OPEN the mobile menu
function openMobileMenu() {

	// Check if transitionDuration property exists on mobile .ul items, if it exists retrieve duration css property. If it doesn't exist set to 0
	// Check if transition delay increment is set in the mobile menu overlay panel, if it exists then retrieve the property, if not then set it to 0
	// Loop through mobile menu .ul items, add in-view class to them and set increment and duration properties as needed
	const timeoutDuration			= mobileMenu.find('ul.menu > li').css('transitionDuration') ? parseFloat(mobileMenu.find('ul.menu > li').css('transitionDuration')) : 0;
	const transitionDelayIncrement	= mobileMenu.attr('data-transition-delay') ? parseFloat(mobileMenu.attr('data-transition-delay')) : 0;

	mobileMenuPanelTrigger.addClass('open');
	mobileMenuPanelTrigger.find('.label-swapper').addClass('swap');
	bodyElem.addClass('is-mobile-menu disable-scroll');
	mobileMenu.addClass('in-view');

	mobileMenu.find('ul.menu > li').each( function(i) {
		$(this).addClass('in-view')
		if ( timeoutDuration && transitionDelayIncrement ) {
			$(this).css({'transition-delay' : ( transitionDelayIncrement * ( 1 + i ) + timeoutDuration ) + 's'});
		}
	});
}

// Callback function to CLOSE the mobile menu
function closeMobileMenu() {

	// Get the CSS transition duration set on the mobile menu to use as a delay for collapsing menu items
	const timeoutDuration = parseFloat( mobileMenuPanel.css('transitionDuration') ) * 1000;

	mobileMenuPanelTrigger.removeClass('open');
	mobileMenuPanelTrigger.find('.label-swapper').removeClass('swap');
	bodyElem.removeClass('is-mobile-menu disable-scroll');

	setTimeout( function() {
		mobileMenu.removeClass('in-view');
		mobileMenu.find('.menu-item-has-children').removeClass('sub-menu-open').find('.toggle-sub-menu').removeClass('open').find('.screen-reader-text').text('Expand sub menu');
		mobileMenu.find('.sub-menu').slideUp().attr('aria-hidden', 'true');

		mobileMenu.find('ul.menu > li').each( function() {
			$(this).removeClass('in-view').css({ 'transition-delay' : '' });
		});
	}, timeoutDuration);
}

// Callback function to OPEN the enquiry panel
function openEnquiryPanel() {
	enquiryPanelTrigger.addClass('open');
	enquiryPanelTrigger.find('.label-swapper').addClass('swap');
	bodyElem.addClass('is-enquiry disable-scroll');
	enquiryForm.addClass('in-view');

	// Get the CSS transition delay set on the enquiry form to use as a delay for focusing on the enquiry form
	// We're doing this after adding the classes above as they may add/change the transition delay value
	// We're also adding a little extra delay to ensure the element is fully visible so we can trigger focus properly
	const timeoutDuration = parseFloat( enquiryForm.css('transitionDelay') ) * 1000 + 50;

	// Wait until the transitions finish before we focus on the enquiry form
	setTimeout( function() {
		enquiryForm.attr('tabindex', '-1').trigger('focus').removeAttr('tabindex');
	}, timeoutDuration);

	$(document).trigger('orgnk.openEnquiryPanel');
}

// Callback function to CLOSE the enquiry panel
function closeEnquiryPanel() {

	// Get the CSS transition duration set on the enquiry panel to use as a delay for closing the enquiry panel
	const timeoutDuration = parseFloat( enquiryPanel.css('transitionDuration') ) * 1000;

	enquiryPanelTrigger.removeClass('open');
	enquiryPanelTrigger.find('.label-swapper').removeClass('swap');
	bodyElem.removeClass('is-enquiry disable-scroll');

	// Wait until the transitions finish before hide the enquiry form
	setTimeout( function() {
		enquiryForm.removeClass('in-view');
	}, timeoutDuration);
}

// Callback function to OPEN the search panel
function openSearchPanel() {
	searchPanelTrigger.addClass('open');
	searchPanelTrigger.find('.label-swapper').addClass('swap');
	bodyElem.addClass('is-search');

	// Wait until the transitions finish before we focus on the input
	setTimeout( function() {
		searchPanelInput.trigger('focus');
	}, 100);
}

// Callback function to CLOSE the search panel
function closeSearchPanel() {
	searchPanelTrigger.removeClass('open');
	searchPanelTrigger.find('.label-swapper').removeClass('swap');
	bodyElem.removeClass('is-search');
}

// Callback to setup the mobile menu on load
function setMobileMenu() {

	const parentListItem = '.menu-item-has-children';
	const subMenu = '.sub-menu';

	// Hide the submenus and assign aria attributes
	mobileMenu.find(parentListItem).children(subMenu).hide().attr('aria-hidden', 'true');

	// Add the aria attributes and toggle button to any menu items that have children
	mobileMenu.find(parentListItem).each(function(){
		$(this).children('a').attr('aria-haspopup', 'true').after(
			'<button class="toggle-sub-menu"><i class="icon" aria-hidden="true"></i><span class="screen-reader-text">Expand sub menu</span></button>'
		);
	});
}

// Callback to setup the sidebar menu on load
function setSidebarMenu() {

	const parentListItem = '.menu-item-has-children';
	const subMenu = '.sub-menu';

	// Hide the sub menus
	sidebarMenu.find(parentListItem).children(subMenu).hide().attr('aria-hidden', 'true');

	// Expand the sub menu if user is currently on a sub page when the page loads
	sidebarMenu.find('li').each( function() {
		if ( $(this).hasClass('current-page-ancestor') || $(this).hasClass('current-menu-item') && $(this).hasClass('menu-item-has-children') ) {
			$(this).addClass('sub-menu-open').children('.toggle-sub-menu').addClass('open').siblings(subMenu).show();
		}
	});
}

// Callback function to OPEN AND CLOSE the dropdown menus for mobile menu and sidebar menu
function toggleDropdownMenu( self ) {

	const parentListItem = '.menu-item-has-children';
	const subMenu = '.sub-menu';

	// If this item's sub menu is currently hidden
	if ( $(self).siblings(subMenu).is(':hidden') === true ) {

		// Change this trigger's toggle state and screen reader text
		$(self).addClass('open').children('.screen-reader-text').text('Collapse sub menu');

		// Add class to this item and open its direct sub menu
		$(self).parent(parentListItem).addClass('sub-menu-open').children(subMenu).slideDown().attr('aria-hidden', 'false');

		// If any neighboring items sub menu is open
		if ( $(self).parent(parentListItem).siblings().hasClass('sub-menu-open') === true ) {

			$(self).parent(parentListItem).siblings().each( function() {

				// Then remove the class from that item, and close any children that are open, reset their trigger button's state and screen reader text
				$(this).removeClass('sub-menu-open').children('.toggle-sub-menu').removeClass('open').find('.screen-reader-text').text('Expand sub menu');
				$(this).find(parentListItem).removeClass('sub-menu-open').children('.toggle-sub-menu').removeClass('open').find('.screen-reader-text').text('Expand sub menu');
				$(this).find(subMenu).slideUp().attr('aria-hidden', 'true');

			});
		}
	}

	// If this item's sub menu is currently visible
	else if ( $(self).siblings(subMenu).is(':hidden') === false ) {

		// Reset screen reader text for this item
		$(self).removeClass('open').children('.screen-reader-text').text('Expand sub menu');

		// Then remove the class from that item, and close any children that are open, reset their trigger button's state and screen reader text
		$(self).parent(parentListItem).removeClass('sub-menu-open').find(parentListItem).removeClass('sub-menu-open').find('.toggle-sub-menu').removeClass('open').find('.screen-reader-text').text('Expand sub menu');

		// Find any open sub menus within the current item and collapse them all
		$(self).parent(parentListItem).find(subMenu).slideUp().attr('aria-hidden', 'true');
	}
}

// Callback to reset the header sub nav on resize
// Mainly for use with the dropdown header sub nav style
function resetHeaderSubNav() {
	headerSubNav.find('.sub-menu').css('display', '');
	headerSubNav.find('.menu-item-has-children').removeClass('sub-menu-open');
	headerSubNav.find('.toggle-sub-menu').removeClass('open');
}

// Callback function to temporarily disable CSS transitions on selected objects for 1 millisecond while their state is changing
// Primarily used to prevent elements animating on page load
function disableTransitions() {

	// Disable all CSS transitions
	bodyElem.addClass('disable-transitions');

	// Reactivate CSS transitions after 0.1 seconds
	setTimeout( function() {
		bodyElem.removeClass('disable-transitions');
	}, 100);
}

// Resize select fields to match their chosen options width
function resizeSelect() {

	resizeSelectElem.each( function() {

		const fieldClasses = $(this).attr('class').replace('resize-select', '');
		const selectedText = $(this).find('option:selected').text();
		const selected = $('<span>').html(selectedText).addClass('temp-select-styles').addClass(fieldClasses);
		selected.appendTo('body');
		const selectedWidth = selected.width() + 4; // Extra pixels to compensate for Firefox font rendering on select elements adding extra space
		selected.remove();

		// Apply the width to the select element
		$(this).width(selectedWidth);

	});
}

// Make the sidebar sticky if it will fit comfortably between the header and the bottom of the screen
function stickySidebar() {

	// Get top and bottom offset values from inline HTML attributes
	// If the values aren't found, the offset amount defaults to 0
	let sidebarTopOffset		= sidebar.attr('data-offset-top') ? sidebar.attr('data-offset-top') : 0;
	let sidebarBottomOffset 	= sidebar.attr('data-offset-bottom') ?  sidebar.attr('data-offset-bottom') : 0;

	// Once we've retrieved the offset values, check to see if either one equals 0 and inherit the property from the other
	// If both offset values are not set, both of these checks will fall and this function will continue with it's calculations
	if ( sidebarTopOffset != 0 && sidebarBottomOffset == 0 ) {
		sidebarBottomOffset = sidebarTopOffset;
	}

	if ( sidebarBottomOffset != 0 && sidebarTopOffset == 0 ) {
		sidebarTopOffset = sidebarBottomOffset;
	}

	// Calculate the available area between the bottom of the header and the bottom of the window
	const adminBarHeight = $('#wpadminbar').is(':visible') ? $('#wpadminbar').outerHeight() : 0;
	const headerBottomPos = header.height() + parseFloat(sidebarTopOffset); // Add some pixels for top offset
	const windowBottomPos = windowElem.height() - parseFloat(sidebarBottomOffset); // Remove some pixels for bottom offset
	const areaToFit = windowBottomPos - headerBottomPos; // Find the space between bottom of header and bottom of screen with offsets
	const sidebarHeight = sidebar.outerHeight(); // Sidebar height
	const sidebarOffsetValue = headerBottomPos + adminBarHeight; // Total offset value for sidebar

	if ( sidebarHeight <= areaToFit ) {
		sidebar.addClass('is-sticky').css({ 'top' : sidebarOffsetValue + 'px' });
	} else {
		sidebar.removeClass('is-sticky').removeAttr('style');
	}
}

// Generate a table of contents for an entry
// Finds every H2 in an entries editor content, adds an ID tag to each and generates a list of corresponding link targets
// Must be the first callback on page load to ensure the scrollToTarget can work properly
function entryTableOfContents() {

	if ( $('.entry-has-contents-table').length ) {

		let headings = [];
		var contentsTable = $('<ul class="toc-list">');

		// First loop to append ID tags to each H2 element and store their text for generating the table of contents
		$('.editor-content').find('h2').each( function( index, element ) {
			index += 1;
			$(element).attr( 'id', 'toc-section-' + index );
			headings.push( $(element).html() );
		});

		// Second loop to prepare array of h2's into HTML output
		$(headings).each( function( index, element ) {
			index += 1;
			contentsTable.append('<li><a class="contents-link" href="#toc-section-' + index + '">' + element + '</a></li>');
		});

		$('.entry-contents-table').append( contentsTable[0] );

		$('.contents-link').on( 'click', function(event) {

			// Make sure this isn't a built-in skip to link
			if ( ! $(this).hasClass('skip-to') ) {
				scrollToTarget(this, true, event);
			}
		});
	}
}

// Add a class to the site header on scroll
function stickyHeader() {

	// First check if the disable scroll function has been activated and if the body has been given a top offset attribute
	// This prevents the header from losing its scroll class when the disable scroll function has been activated but the header should still be in its scrolled state
	const bodyOffsetTop = bodyElem.attr('data-offset-top');
	const scrollPos = bodyOffsetTop ? bodyOffsetTop : windowElem.scrollTop();
	const headerHeight = header.outerHeight();

	if ( scrollPos >= headerHeight ) {
		header.addClass('header-scrolled');
	} else {
		header.removeClass('header-scrolled');
	}
}

// Finds a supplied element's anchor hash, matches it to a target on the page and then scrolls to that target
function scrollToTarget( trigger, appendHash = false, event = false ) {

	if ( location.pathname.replace(/^\//, '') == trigger.pathname.replace(/^\//, '') && location.hostname == trigger.hostname ) {

		// Find the target to scroll to
		const hashString = trigger.hash.slice(1);
		const hash = $(trigger.hash);
		const target = hash.length ? hash : $('[name=' + hashString + ']');

		// Check that a scroll target exists inside the main page area - this prevents unwanted scrolling for targets that live outside the main page, like modals
		if ( target.length && pageBody.find(target).length ) {
			// Get various properties of the target
			const adminBarHeight = $('#wpadminbar').is(':visible') ? $('#wpadminbar').outerHeight() : 0;
			const targetMarginTop = parseFloat( target.css('marginTop') );
			const targetOffsetTop = ( target.offset().top ) - targetMarginTop;
			const headerHeight = header.outerHeight();
			const scrollOffset = Math.ceil( targetOffsetTop - headerHeight - adminBarHeight ); // Firefox likes to be a little precise here and use decimal values, so we're rounding the number up to the nearest whole pixel
			const scrollPos = windowElem.scrollTop();
			const scrollDistance = Math.abs( scrollOffset - scrollPos ); // Get the difference between the target and the current scroll position for calculating scroll speed
			let scrollSpeed = 500;

			// Dynamically set the scroll speed based on how far away the target is
			if ( scrollDistance > 2500 ) {
				scrollSpeed = Math.ceil( scrollDistance / 3 );
			} else if ( scrollDistance > 1200 ) {
				scrollSpeed = Math.ceil( scrollDistance / 2 );
			} else if ( scrollDistance == 0 ) {
				scrollSpeed = 0;
			}

			// Only prevent default if scroll is going to happen
			if ( event ) {
				event.preventDefault();
			}

			// 1. Disable the html element's scroll-behaviour property while the animation runs
			$('html').css({ 'scroll-behavior' : 'initial' });

			// 2. Unfocus the trigger
			$(trigger).trigger('blur');

			// 3. Run the scroll animation
			$('html,body').animate({
				scrollTop: scrollOffset
			}, scrollSpeed);

			// 4. After the main animation has completed, we need to do a bunch of things to finish the trip to the destination
			setTimeout( function() {

				// Shift focus to the target
				$(target).trigger('focus');

				// If the target didn't receive focus (i.e. it is not a naturally focusable element), add tabindex and then trigger focus again
				if ( ! $(target).is(':focus') ) {
					$(target).attr('tabindex', '-1').trigger('focus');
				}

				// Because we're triggering a focus change above as part of the scroll animation,
				// The browser will want to auto scroll AGAIN to the new focused element, causing the scroll to jump
				// So, at this point, we force the window scroll top position to the target offset without any animation,
				// Which occurs instantaneously and effectively hides the jump
				$('html,body').scrollTop(scrollOffset);

				// Next, remove tab index so any non-focusable elements can't be focused again later
				// Note: we cannot blur the element at this point otherwise Safari will automatically reset the focus to the top of the document
				// Hiding the focus ring on non-focusable elements must be done in CSS
				$(target).removeAttr('tabindex');

				// Finally, reset the html element's scroll-behaviour property after the animation is complete
				$('html').css({ 'scroll-behavior' : '' });

				// If preserving the hash is enabled, append the trigger's href hash to the URL
				if ( appendHash === true ) {
					history.replaceState(undefined, undefined, '#' + hashString); // replaceState() replaces the current page state, use pushState() instead to create a new state
				}

			}, scrollSpeed + 10); // Scroll speed plus a tiny bit extra to avoid conflicts with the main animation
		}
	}
}

// Callback function to toggle tab content and adjust the tab content area's height dynamically
// Supports the option to have tabs display as an accordion below a specified breakpoint, with the parameters are passed via HTML attributes
// This function runs on page load/show/resize and when a tab item is clicked
function toggleOrgnkTabs( newTab = null, tabGroup = null, transition = 0 ) {

	// If no new tab and tab group has been supplied, then we are initialising or reformatting the tabs
	// This first condition runs on page load, page show and resize
	if ( ! newTab && ! tabGroup ) {

		// Loop through each instance of the tabs to initialise
		$('.orgnk-tabs').each( function() {

			const tabs = $(this).find('.orgnk-tab-trigger');
			const targets = $(this).find('.orgnk-tab-target');
			const contentArea = $(this).find('.orgnk-tabs-content-area');
			const activeTab = $(this).find('.orgnk-tab-trigger.active');
			const tab = activeTab.length ? activeTab : tabs.first(); // If no active tab can be found on page load, show the first tab automatically
			const targetID = tab.attr('tab-target');
			const target = contentArea.find('#' + targetID);
			const defaultOpen = $(this).attr('data-tabs-default-open') === 'false' ? false : true;
			const isAccordion = $(this).attr('data-tabs-accordion') === 'true' ? true : false;
			const breakpoint = $(this).attr('data-tabs-accordion-breakpoint') ? parseFloat($(this).attr('data-tabs-accordion-breakpoint')) : 1024;

			if ( defaultOpen ) {
				tab.addClass('active').attr('aria-expanded', 'true').attr('disabled', false);
				target.addClass('active').attr('aria-hidden', 'false');
			}

			// For the accordion display style below the breakpoint, show the active tab's content
			if ( defaultOpen && isAccordion && windowElem.width() < breakpoint ) {
				target.show();
			} else {
				// If above the breakpoint, remove all inline display styles as they are no longer necessary
				targets.css({ 'display' : '' });
			}

			setOrgnkTabsHeight( this, target, breakpoint, transition );

			if ( ! $(this).hasClass('tabs-initialized') ) {
				$(this).addClass('tabs-initialized');
			}
		});
	}

	// If a new tab and a tab group has been supplied
	// This condition runs when each tab is clicked
	else if ( newTab && tabGroup ) {

		const tabs = tabGroup.find('.orgnk-tab-trigger');
		const targets = tabGroup.find('.orgnk-tab-target');
		const contentArea = tabGroup.find('.orgnk-tabs-content-area');
		const tab = $(newTab);
		const targetID = tab.attr('tab-target');
		const target = tabGroup.find(contentArea).find('#' + targetID);
		const isAccordion = tabGroup.attr('data-tabs-accordion') === 'true' ? true : false;
		const breakpoint = tabGroup.attr('data-tabs-accordion-breakpoint') ? parseFloat(tabGroup.attr('data-tabs-accordion-breakpoint')) : 1024;

		// If the target tab's content is hidden - we're using CSS visibility here instead of .is(':hidden') so we can perform CSS fades
		if ( target.css('visibility') === 'hidden' ) {

			// Deactivate all tabs
			tabs.removeClass('active').attr('aria-expanded', 'false').attr('disabled', false);

			// Then activate the current tab
			tab.addClass('active').attr('aria-expanded', 'true').attr('disabled', false);

			// Hide all content
			targets.removeClass('active').attr('aria-hidden', 'true');

			// Then active the current tab's target content
			target.addClass('active').attr('aria-hidden', 'false');

			// For the accordion display style below the breakpoint, use jQuery slide animations
			if ( isAccordion && windowElem.width() < breakpoint ) {

				// Slide up all targets except the current target
				targets.each( function() {
					$(this).slideUp(transition, function() {

						// When the slide up animation is complete, remove the inline display CSS property after the transition time has passed
						setTimeout( function() {
							$(this).css({ 'display' : '' });
						}, transition);
					});
				});

				target.slideDown(transition);

			} else {
				// If above the breakpoint, remove all inline display styles as they are no longer necessary
				targets.not(target).css({ 'display' : '' });
			}
			setOrgnkTabsHeight( tabGroup, target, breakpoint, transition );

		// Else if the tab is visible we are animating it out
		} else {

			if ( isAccordion && windowElem.width() < breakpoint ) {

				// Deactivate all tabs
				tabs.removeClass('active').attr('aria-expanded', 'false').attr('disabled', false);

				// Then activate the current tab
				tab.removeClass('active').attr('aria-expanded', 'true').attr('disabled', false);

				// Hide all content
				targets.removeClass('active').attr('aria-hidden', 'true');

				// Then active the current tab's target content
				target.removeClass('active').attr('aria-hidden', 'true');

				// Slide up all targets except the current target
				targets.each( function() {
					$(this).slideUp(transition, function() {

						// When the slide up animation is complete, remove the inline display CSS property after the transition time has passed
						setTimeout( function() {
							$(this).css({ 'display' : '' });
						}, transition);
					});
				});
			}
			setOrgnkTabsHeight( tabGroup, target, breakpoint, transition );
		}
		setOrgnkTabsHeight( tabGroup, target, breakpoint, transition );
	}
}

// Callback function to set the height of Organik tabs based on the content size and display style
// Uses the CSS min-height property instead of height to prevent cropping or breaking the tabs visually if a tab's content is shorter than the tabs (triggers) list
function setOrgnkTabsHeight( tabGroup, target, breakpoint, transition ) {

	const targets = $(tabGroup).find('.orgnk-tab-target');
	const contentArea = $(tabGroup).find('.orgnk-tabs-content-area');
	const isAccordion = $(tabGroup).attr('data-tabs-accordion') === 'true' ? true : false;
	let contentHeight = 0;

	// If using the accordion tabs layout, find the tallest tab target and set the content area's height
	if ( isAccordion ) {

		// Loop through all targets to find the tallest
		targets.each( function() {
			if ( $(this).height() > contentHeight ) {
				contentHeight = $(this).outerHeight();
			}
		});

		// Set the content area's height to the tallest target, if above the breakpoint
		// We are using min-height because if the tallest target's height is less than the height of the tabs (triggers) list,
		// the content area will still use the natural height of the tabs list
		if ( contentHeight > 0 && windowElem.width() >= breakpoint ) {
			contentArea.css({ 'min-height' : contentHeight + 'px' });
		} else {
			contentArea.css({ 'min-height' : '' });
		}

	} else {

		contentHeight = target.outerHeight();

		if ( transition > 0 ) {
			contentArea.animate({ 'min-height' : contentHeight + 'px' }, transition);
		} else {
			contentArea.css({ 'min-height' : contentHeight + 'px' });
		}
	}
}



// =======================================================================================================================
// Element events
// =======================================================================================================================

// Resize select elements whenever their option value is changed
resizeSelectElem.on( 'change', resizeSelect );

// Mobile menu show/hide on trigger click
mobileMenuPanelTrigger.on( 'click touch', function(e) {

	e.preventDefault();

	if ( $(this).hasClass('open') === false ) {

		if ( searchPanelTrigger.hasClass('open') === true ) {
			closeSearchPanel();
		}

		if ( enquiryPanelTrigger.hasClass('open') === true ) {
			closeEnquiryPanel();
		}

		openMobileMenu();
		openOverlayPanel();

	} else if ( $(this).hasClass('open') === true ) {
		closeMobileMenu();
		closeOverlayPanel();
	}
	disableScroll();
});

// Enquiry panel show on trigger click
enquiryPanelTrigger.add($('a[href="#enquiry-form"]')).on('click touchstart', function(e) {

	e.preventDefault();

	if ( $(this).hasClass('open') === false ) {

		if ( mobileMenuPanelTrigger.hasClass('open') === true ) {
			closeMobileMenu();
		}

		if ( searchPanelTrigger.hasClass('open') === true ) {
			closeSearchPanel();
		}

		openEnquiryPanel();
		openOverlayPanel();

	} else if ( $(this).hasClass('open') === true ) {
		closeEnquiryPanel();
		closeOverlayPanel();
	}
	disableScroll();
});

// Search panel show on trigger click
searchPanelTrigger.on('click touchstart', function(e) {

	e.preventDefault();

	if ( $(this).hasClass('open') === false ) {

		if ( mobileMenuPanelTrigger.hasClass('open') === true ) {
			closeMobileMenu();
			closeOverlayPanel();
		}

		if ( enquiryPanelTrigger.hasClass('open') === true ) {
			closeEnquiryPanel();
			closeOverlayPanel();
		}

		openSearchPanel();

	} else if ( $(this).hasClass('open') === true ) {
		closeSearchPanel();
	}
	disableScroll();
});

// Search panel hide on trigger click
closeSearchPanelTrigger.on('click touchstart', function(e) {

	e.preventDefault();

	if ( searchPanelTrigger.hasClass('open') === true ) {
		closeSearchPanel();
	}
});

// Desktop submenu show/hide on hover
desktopNav.each( function() {

	$(this).find('li.menu-item-has-children').on( 'mouseenter', function() {

		// Store 'this' as a variable so we can pass it through a timeout function
		var self = this;

		setTimeout( function() {
			openDesktopSubMenu(self);
		}, 10);

	}).on( 'mouseleave', function() {
		closeDesktopSubMenu(this);
	});

	// Desktop submenu show/hide with keyboard/tabs
	$(this).find('li.menu-item-has-children a').on( 'focus', function() {

		$(this).parents('li.menu-item-has-children').last().each( function() {
			openDesktopSubMenu(this);
		});
	}).on( 'blur', function() {

		// Store 'this' as a variable so we can pass it through a timeout function
		var self = this;

		// Add a short delay so the blur and focus functions don't fire simultaneously
		setTimeout( function() {
			$(self).parents('li.menu-item-has-children').last().each( function() {
				// Check if any child element has focus
				if ( $(this).find(':focus').length == 0 ) {
					closeDesktopSubMenu(this);
				}
			});
		}, 10);
	});
});

// Mobile submenu show/hide on toggle click
mobileMenu.on('click', '.toggle-sub-menu', function() {
	toggleDropdownMenu(this);
});

// Sidebar submenu show/hide on toggle click
sidebarMenu.on('click', '.toggle-sub-menu', function() {
	toggleDropdownMenu(this);
});

// Header sub nav submenu show/hide on toggle click
headerSubNav.on('click', '.toggle-sub-menu', function() {
	toggleDropdownMenu(this);
});

// Skip to buttons
$('a.skip-to').on( 'click', function(event) {
	scrollToTarget(this, false, event);
});

// Links with an anchor target set, checking first that they actually have a link set
$('a[href*="#"]').not('[href="#"]').not('[href="#0"]').on( 'click', function(event) {

	// Make sure this isn't a built-in skip to link
	if ( ! $(this).hasClass('skip-to') ) {
		scrollToTarget(this, true, event);
	}
});

// Organik tabs triggers
$('.orgnk-tabs .orgnk-tab-trigger').on( 'click', function() {

	// Find the transition attribute to pass to the toggleOrgnkTabs() function
	const tabGroup = $(this).parents('.orgnk-tabs');
	const transition = tabGroup.attr('data-tabs-transition') ? parseFloat(tabGroup.attr('data-tabs-transition')) : 300;

	toggleOrgnkTabs(this, tabGroup, transition);
});

// Video Modaal setup
$('.video-modal-trigger').modaal({
	type: 'video',
	overlay_opacity: 1,
	background: '',
	custom_class: 'video-modal'
});



// =======================================================================================================================
// Window events
// =======================================================================================================================

// On document click
$(document).on('click touchstart', function (e) {

	e.stopPropagation();
	const target = $(e.target);

	// Close search box if user clicks anwyhere outside of it or the search trigger button
	if ( target.closest(searchPanel).length || target.closest(searchPanelTrigger).length ) {
		return;
	} else {
		if ( searchPanelTrigger.hasClass('open') ){
			closeSearchPanel();
		}
	}
});

// Window key press
windowElem.on( 'keydown', function(e) {

	// Close overlays if user presses esc key while it is open
	if ( bodyElem.hasClass('is-mobile-menu') && e.key == 'Escape' ) {
		closeMobileMenu();
		closeOverlayPanel();
		disableScroll();
	}

	if ( bodyElem.hasClass('is-enquiry') && e.key == 'Escape' ) {
		closeEnquiryPanel();
		closeOverlayPanel();
		disableScroll();
	}

	if ( bodyElem.hasClass('is-search') && e.key == 'Escape' ) {
		closeSearchPanel();
	}
});

// Window resize
windowElem.on( 'resize', function() {

	// Get the breakpoint value from the inline HTML attribute
	const headerBreakpoint = ( header.attr( 'data-header-breakpoint' ) ) ? header.attr( 'data-header-breakpoint' ) : 1200;

	// If user has the mobile menu open and resizes the browser to desktop size, then hide it and reset it
	if ( ( windowElem.width() > headerBreakpoint ) && ( mobileMenuPanelTrigger.hasClass('open') === true ) ) {
		closeMobileMenu();
		closeOverlayPanel();
		disableScroll();
	}

	// Get the breakpoint value from the inline HTML attribute
	const subNavBreakpoint = ( headerSubNav.attr( 'data-sub-nav-breakpoint' ) ) ? headerSubNav.attr( 'data-sub-nav-breakpoint' ) : 768;

	// Reset the header sub nav dropdown on resize
	if ( ( windowElem.width() > subNavBreakpoint ) && ( headerSubNav.hasClass('mobile-dropdown') === true ) ) {
		resetHeaderSubNav();
	}

	setHeaderOffset();
	resizeSelect();
	stickySidebar();
	toggleOrgnkTabs();
});

// Window scroll
windowElem.on( 'scroll', function() {
	stickyHeader();
});

// Fire callbacks if the user uses the back/forwards arrows
// Mainly concerns safari/firefox which cache pages
windowElem.on( 'pageshow', function(e) {

	if ( e.originalEvent.persisted ) {
		disableTransitions();
		setHeaderOffset();
		closeMobileMenu();
		closeEnquiryPanel();
		closeSearchPanel();
		closeOverlayPanel();
		disableScroll();
		resizeSelect();
		stickyHeader();
		toggleOrgnkTabs();
	}
});

// Window load
$(document).ready( function() {

	// Remove empty paragraph tags
	$('p:empty').remove();

	// Callbacks
	entryTableOfContents();
	disableTransitions();
	setHeaderOffset();
	setMobileMenu();
	setSidebarMenu();
	resizeSelect();
	stickySidebar();
	stickyHeader();
	toggleOrgnkTabs();


	// If the window URL contains a hash, then scroll to the target
	if ( window.location.hash ) {
		scrollToTarget(window.location, true);
	}
});

})( jQuery );
