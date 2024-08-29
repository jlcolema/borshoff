if (typeof(console) === 'undefined') {
    console = {
		log : function(data){}
	};
}

//##############################################################################################	
//##############################################################################################	
								// GLOBAL NAMESPACE VARIABLES
//##############################################################################################	
//##############################################################################################	

// page layout
	var isBlogPage = false;
	var usingTabletCSS = false;
	var isMobile = false;

// grid related
	var gridWrapper;
	var grid;
	var gridWrapperLeft = 0;
	var gridWrapperWidth;
	var gridWidth;
	var gridOverlap;
	var amountToSlide = 490;
	var slideSpeed = 600;
	var incrementAmount;

// giant text checks
	var homeSlideCounter = 0;
	var isHomeTextFadable = true;
	var initialResizeComplete = false;
	var nuberOfSlidesHomeTextIsVisible = 3;
	var resizeCounter = 0;
	
// parameters to setup Cycle
	var cycleOptions  = {};
	var slideshowWrapper;

// sidebar email link
	var sendEmailLink;

// sitewide dropdown menus
	var menus;

// about & careers pages
	var isCareersPage;
	var isAboutPage;
	var flyoutPage;
	var pageInfoContainer;
	var currentScrollPaneElement;
	var currentScrollPaneAPI;

// custom grids
var customShowcaseURL;



//##############################################################################################	
//##############################################################################################	
								// GLOBAL NAMESPACE FUNCTIONS
//##############################################################################################	
//##############################################################################################	


	//###############################################	
	// Define indexOf to compensate for the IE lack of support
	//###############################################	
		if (!Array.prototype.indexOf) { 
			Array.prototype.indexOf = function(obj, start) {
				 for (var i = (start || 0), j = this.length; i < j; i++) {
					 if (this[i] === obj) { return i; }
				 }
				 return -1;
			}
		}


	//###############################################	
	// Setup items after the page loads
	//###############################################	
		function loadComplete(){
			// Make everything longer for the blog page
			if( isBlogPage ){
				var blogContentHeight = $('div.blogContent').height();
				if( blogContentHeight > 730 ){
					equalizeBlogHeight( blogContentHeight );
					initializeScrollbars();
				} else {
					equalizeBlogHeight(730);
					// removes extra padding on the sidebars for when the blog is short
					$('aside').css('paddingBottom',0);
				}
				
				$('#searchFilter a').click(function(e){
					e.preventDefault();
					var postType = $(this).attr('data-posttype');
					$('#posttypeField').val(postType);
					$(this).parents('form').submit();
				});
				
			} else {
				initializeScrollbars();
			}
		
			// Slideshow is paused in document.ready to prevent sliding before page load is complete
			slideshowWrapper.cycle('resume');
		}
		
	
	//###############################################	
	// Checks on pageload to see if you are at a custom grid, if so, setup the single-work page's close button to go back to the custom grid instead of the main Work page
	//###############################################	
	function checkForCustomPageURL(){
		if( customShowcaseURL == null && $('body').hasClass('single-showcase') ){
			customShowcaseURL = window.location.href;
			pageHistory.push(customShowcaseURL);
		}
		if( customShowcaseURL != null ){
			$('.infoHover .closeLink').attr('href',customShowcaseURL);
		}
	}
	
	
	
	//###############################################	
	// Setup scrollbars on all elements that need them
	//###############################################	
		function initializeScrollbars(){
			$('.scroll-pane').jScrollPane({contentWidth:'0px'}); // contentWidth is set to 0 to avoid horizontal scrollbars http://bit.ly/x5EF7h
		}
		
	//###############################################	
	// Hide the address bar in iOS
	//###############################################	
		function hideiOSAddressBar(){
			setTimeout(function(){
				window.scrollTo(0,0);
				window.removeEventListener('load', hideiOSAddressBar);
			}, 100);
		}


	//###############################################	
	// Extend the height of the page if a blog page has loaded
	//###############################################	
		function equalizeBlogHeight(blogHeight){
			$('nav').height(blogHeight-80);
			$('#sidebar').height(blogHeight);
			$('aside').height(blogHeight);
			$('div.info').height(blogHeight);
		}



	//###############################################	
	// Adjust elements as needed for mobile
	//###############################################	
		function adjustForMobile(){
			if( isMobile ) {
				window.addEventListener('load', hideiOSAddressBar);
				// show grid and slideshow arrows by default since mobile doesn't have a hover state
				$('.slideGridLeftButton').css({left:0});
				$('.slideGridRightButton').css({right:0});
			
				if( slideshowWrapper.find('li').length > 1 ){ // only for pages with more than one sample though...
					$('.slideWorkLeftButton').css({left:0});
					$('.slideWorkRightButton').css({right:0});
				} else {
					$('.slideWorkLeftButton').remove();
					$('.slideWorkRightButton').remove();
				}
				
				// enable swiping on mobile!
				var swipeThreshold = {x:30,y:80};
					
				if( slideshowWrapper.find('li').length > 1 ){
					
					//enable swiping for single work pages
					$('div.activeAJAX div.infoHover').swipe({
						threshold:swipeThreshold,
						swipeLeft: slideshowAdvanceNext,
						swipeRight: slideshowAdvancePrev
					});
					
					// enable swiping for traditional "pages"
					slideshowWrapper.swipe({
						threshold:swipeThreshold,
						swipeLeft: slideshowAdvanceNext,
						swipeRight: slideshowAdvancePrev
					});
				}
				
				$('div.activeAJAX #gridWrapper .hover_info img').swipe({
					threshold:{x:30,y:10},
					swipeLeft: slideGridLeft,
					swipeRight: slideGridLeft
				});
			}
			
			// hide dropdown menus when tapping something else on mobile
			$('div.activeAJAX div.infoHover').on('click',hideSooperfishMobile);
			$('div.activeAJAX section.grid_link').on('click',hideSooperfishMobile);
	
		}
	
	
	//###############################################	
	// Remove the 3rd row of insights items when the page shrinks
	//###############################################	
		function toggleThirdRow() {
			if( usingTabletCSS ) {
				gridWrapper.isotope({filter:'.twoRow'});
			} else {
				gridWrapper.isotope({filter:'*'});
			}
		}


	//###############################################	
	// Figure out how wide the middle section should be
	// based on the window width minus the nav bar and sidebar
	//###############################################	
		function calculateMiddleWidth(){
			middleWidth = $(window).width() - $('nav').outerWidth() - $('#sidebar').outerWidth() - 10;
			return middleWidth;
		}


	//###############################################	
	// Make the middle column elastic to deal with the browser width changes
	//###############################################	
		function resizeMiddleWidth(){
			var articleWidth = 0;
			
			var middleWidth = calculateMiddleWidth();
			var divInfo = $('div.activeAJAX div.info');
	
			divInfo.width( middleWidth );
			$('section.blogWrapper .info').width( middleWidth );
			$('section.blogWrapper .blogContent').width( middleWidth - $('aside').outerWidth() );
			
			articleWidth = divInfo.find('article').outerWidth();
			var newMiddleWidth = middleWidth - articleWidth;
			
			//console.log(middleWidth,articleWidth,newMiddleWidth);
			
			divInfo.find('ul.slideshow, ul.slideshow li, div.infoHover').width( newMiddleWidth  );
			$('#textWrapper').width( newMiddleWidth );
			$('#ajaxContentViewport, #ajaxContentCurrent, #ajaxContentOld, #ajaxContentNew').width( newMiddleWidth );
			slideshowWrapper.find('li').width( newMiddleWidth );
			slideshowWrapper.width( newMiddleWidth );
			$('div.activeAJAX section.grid').width( newMiddleWidth );
			if( $('div.activeAJAX .insights').length ) {
				if( $('div.activeAJAX #gridWrapper').height() != 735 ){
					usingTabletCSS = true;
				} else {
					usingTabletCSS = false;	
				}
				toggleThirdRow();
			}
			initializeHomepageFontSize();
			
			if( isBlogPage ) {
				loadComplete();
			}
			
		}
	
	
	//###############################################	
	// Stores the current grid for reference
	//###############################################	
		function determineCurrentGrid(){
			gridWrapper = $('div.activeAJAX #gridWrapper');
			grid = $('div.activeAJAX section.grid');
		}
	
	
	
	//###############################################	
	// Enable arrow keys for the grid
	//###############################################	
		function toggleGridKeyboardShortcuts(isEnabled){
			if( isEnabled == true ){
				//console.log('grid keyboard on');
				toggleSlideshowKeyboardShortcuts(false);
				$(document.documentElement).on('keyup',handleGridArrowKeys);
			} else {
				//console.log('grid keyboard on');
				$(document.documentElement)	.off('keyup',handleGridArrowKeys);
			}
		}
		
		function handleGridArrowKeys(e){
			//console.log('key captured');
			switch(e.keyCode){
				case 39:
					//console.log('right key captured');
					slideGridRight();
					break;
				case 37:
					//console.log('left key captured');
					slideGridLeft();
					break;	
			}
		}
	
	
	//###############################################	
	// Move the grid
	//###############################################	
		function slideGridLeft(){
			//console.log('left slide activated');
			refreshSizing();
			if( gridWrapperLeft != 0 ){
				//console.log('inside');
				if( gridWrapperLeft <= -amountToSlide ){ // move the grid to the left
					incrementAmount = '+=' + amountToSlide.toString();
					grid.css('background-position',gridWrapperLeft+amountToSlide + 'px top');
					//console.log(grid.css('background-position-x'));
					gridWrapper.stop().animate({
						'left':incrementAmount
					}, slideSpeed, function(){
						toggleGridArrows(false,false);
						if( gridWrapperLeft > -amountToSlide ) { // if the resulting position is past 0, go to 0
							scrollGridToFarLeft();
						}
					});
				} else { // if it goes too far, just go back to 0
					scrollGridToFarLeft();
				}
			}
		}
		
		function slideGridRight(){
			//console.log('right slide activated');
			refreshSizing();
			if( gridWrapperLeft >= -(gridOverlap - amountToSlide + 20) ){ // move the wrapper to the right
				//console.log('inside');
				incrementAmount = '-=' + amountToSlide.toString();
				grid.css('background-position',gridWrapperLeft-amountToSlide + 'px top');
				//console.log(grid.css('background-position-x'));
				gridWrapper.stop().animate({
					'left':incrementAmount
				}, slideSpeed, function(){
					toggleGridArrows(false,false);
					if( gridWrapperLeft <= -(gridOverlap - amountToSlide + 20) ) { // if the resulting position is past the end, go to the end
						scrollGridToFarRight();
					}
				});
			} else { // if it goes to far, just go to the end
				scrollGridToFarRight();
			}
		}
		
		function scrollGridToFarLeft(){
			refreshSizing();
			grid.css('background-position',0 + 'px top');
			//console.log(grid.css('background-position-x'));
			gridWrapper.stop().animate({
				'left':0
			}, slideSpeed, function(){
				toggleGridArrows(true,false);
			});
		}
	
		function scrollGridToFarRight(){
			refreshSizing();
			grid.css('background-position',-(gridWrapperWidth - gridWidth - 5) + 'px top');
			//console.log(grid.css('background-position-x'));
			gridWrapper.stop().animate({
				'left':-( gridWrapperWidth - gridWidth - 5 )
			}, slideSpeed, function(){
				toggleGridArrows(false,true);
			});
		}
	
	//###############################################	
	// check the grid sizing after each slide
	//###############################################	
		function refreshSizing(){
			if( $('div.activeAJAX .grid').length > 0 ){
				gridWrapperLeft = parseInt(gridWrapper.css('left').replace('px',''),10);
				gridWrapperWidth = gridWrapper.width();
				gridWidth = grid.width();
				gridOverlap = Math.abs( gridWrapperWidth - gridWidth );
			}
		}


	
	//###############################################	
	// Hide/Show the control arrows for the grid
	//###############################################	
		function toggleGridArrows(hideLeft, hideRight){
			hideLeft = ( typeof hideLeft != 'undefined' ) ? hideLeft : false;
			hideRight = ( typeof hideRight != 'undefined' ) ? hideRight : false;
			if( hideLeft ){
				$('.slideGridLeftButton').addClass('hidden');
			} else {
				$('.slideGridLeftButton').removeClass('hidden');
			}
			if( hideRight ){
				$('.slideGridRightButton').addClass('hidden');
			} else {
				$('.slideGridRightButton').removeClass('hidden');
			}
		}
	
	
	//###############################################	
	// Hide/Show the control arrows for work slideshows
	//###############################################	
		function toggleWorkArrows(hideLeft, hideRight){
			hideLeft = ( typeof hideLeft != 'undefined' ) ? hideLeft : false;
			hideRight = ( typeof hideRight != 'undefined' ) ? hideRight : false;
			if( hideLeft ){
				$('.slideWorkLeftButton').addClass('hidden');
			} else {
				$('.slideWorkLeftButton').removeClass('hidden');
			}
			if( hideRight ){
				$('.slideWorkRightButton').addClass('hidden');
			} else {
				$('.slideWorkRightButton').removeClass('hidden');
			}
		}
		
		function removeWorkArrows(){
			$('.slideWorkLeftButton').remove();
			$('.slideWorkRightButton').remove();
		}
	
	
	//###############################################	
	// Actions triggered before/after slide transition
	//###############################################	
		function afterSlide(curr, next, opts){
			console.log('!!!!!!!!!!!!! After slide');
			currentSlide = $("div.activeAJAX .pager a.activeSlide").html();
			$slides = $('div.activeAJAX ul.slideshow li');
			console.log('after slide',currentSlide);
			if( $slides.eq(parseInt(currentSlide)-1).attr('data-description') ){
				console.log('custom text');
				setCustomSlideDescription($slides.eq(parseInt(currentSlide)-1).attr('data-caption'), $slides.eq(parseInt(currentSlide)-1).attr('data-description'));
			} else {
				if( currentSlide ) clearCustomSlideDescription();				
			}
			if (!currentSlide) {
				currentSlide = "1";
			}
			$('div.activeAJAX article .jspPane span').html(' ' + currentSlide + ' of ' + opts.slideCount + ' ');
			if( $('div.activeAJAX ul.slideshow li').length > 1 ){
				window.location.hash = '#'+currentSlide;
			}
		}
		
		function beforeHomeSlide(curr, next, opts){
			if( homeSlideCounter == nuberOfSlidesHomeTextIsVisible ) {
				isHomeTextFadable = false;
				hideHomeText();
			}
			homeSlideCounter++;
		}
		
		
		
		var defaultTopText;
		var defaultBottomText;
		
		
		function setupCustomSlideshow(){
			defaultTopText = defaultTopText = $('.workDetailsWrapper h2').html();
			defaultBottomText = defaultBottomText = $('.workDetailsWrapper h1').html();
			if( $('div.activeAJAX ul.slideshow li').eq(0).attr('data-description') ){
				$slides = $('div.activeAJAX ul.slideshow li');
				setCustomSlideDescription($slides.eq(0).attr('data-caption'), $slides.eq(0).attr('data-description'));
			}
		}
		
		function setCustomSlideDescription(top,bottom){
//			console.log('set custom',top,bottom);
			$('.workDetailsWrapper h2').html( top ); // the client name
			$('.workDetailsWrapper h3').eq(1).html( 'SERVICES' ); // the second heading
			$('.workDetailsWrapper h1').html( bottom ); //the thing listed under second heading
		}
		
		function clearCustomSlideDescription(){
			$('.workDetailsWrapper h2').html( defaultTopText ); // the client name
			$('.workDetailsWrapper h3').eq(1).html( 'PROJECT' ); // the second heading
			$('.workDetailsWrapper h1').html( defaultBottomText ); //the thing listed under second heading
		}
	


	//###############################################	
	// Specify different options for slideshow based on page type
	//###############################################	
		function setupSlideshow(){
			if( isFrontPage ){
				cycleOptions.fx = 'fade';
				cycleOptions.before = beforeHomeSlide;
				cycleOptions.speed = 1200;
				cycleOptions.timeout = 6000;
			} else {
				cycleOptions.fx = 'scrollHorz';
				cycleOptions.after = afterSlide;
				cycleOptions.prev = '#prevPhoto';
				cycleOptions.next = '#nextPhoto';
				cycleOptions.speed = 700;
				cycleOptions.timeout = 0;
				cycleOptions.manualTrump = false;
				cycleOptions.pager = '.pager';
				cycleOptions.slideResize = 0;
				cycleOptions.containerResize = 0;
			}
			
			if( isFrontPage ){
				slideshowWrapper = $('#initialContent ul.slideshow');
			} else {
				slideshowWrapper = $('div.activeAJAX ul.slideshow');
			}
			
			isFrontPage = false;

			if( $("div.activeAJAX .pager a.activeSlide").length && slideshowWrapper.find('li').length < 2 ){
				removeWorkArrows();
			}
			
			slideshowWrapper.cycle(cycleOptions);
			slideshowWrapper.cycle('pause');
			
			if( slideshowWrapper.length > 0 ){
				toggleGridKeyboardShortcuts(false);
				toggleSlideshowKeyboardShortcuts(true);
			} else {
				toggleSlideshowKeyboardShortcuts(false);
			}
		}
	
	

	//###############################################	
	// Enable arrow keys for the slideshow
	//###############################################	
		function toggleSlideshowKeyboardShortcuts(isEnabled){
			if(isEnabled){
				toggleGridKeyboardShortcuts(false);
				$(document.documentElement).on('keyup',handleSlideshowArrowKeys);
			} else {
				$(document.documentElement).off('keyup',handleSlideshowArrowKeys);
			}
		}
	
		
		function handleSlideshowArrowKeys(e){
			switch(e.keyCode){
				case 39:
					slideshowAdvanceNext();
					break;
				case 37:
					slideshowAdvancePrev();
					break;	
			}
		}
	
	//###############################################	
	// Manually trigger the slideshow change
	//###############################################	
		function slideshowAdvancePrev(){
			slideshowWrapper.cycle('prev',function(){
				//afterSlide();
			});
		}
		
		function slideshowAdvanceNext(){
			slideshowWrapper.cycle('next',function(){
				//afterSlide();
			});
		}
		


	//###############################################	
	// Setup the giant font size
	//###############################################	
		function initializeHomepageFontSize(){
			var textwrapperDiv = $('#textWrapper div');
			textwrapperDiv.height( textwrapperDiv.outerHeight() + 400 )	;
			textwrapperDiv.textfill({maxFontPixels: 350, callback: doneTextSize});
			if( !initialResizeComplete ){
				initialResizeComplete = true;
				initializeHomepageFontSize();
			}
		}
	
	//###############################################	
	// Quickly add extra height to act as the bottom margin for the headline
	//###############################################	
		function doneTextSize(){
			var spanHeight = $('#textWrapper div span').outerHeight();
			if( navigator.userAgent.indexOf('Firefox') != -1 && navigator.userAgent.indexOf('Macintosh') != -1 && resizeCounter < 4 ) {
				$('#textWrapper div').height( spanHeight + 20 );
			} else {
				$('#textWrapper div').height( Math.floor(spanHeight * 1.05) - Math.floor(spanHeight / 10) );
			}
			resizeCounter++;
		}
	
	
	//###############################################	
	// Hide/show giant text & setup event listeners
	//###############################################	
		function showHomeText(){
			$('div.info').off('mouseover',showHomeText);
			initializeHomepageFontSize();
			$('#textWrapper').stop().fadeTo(800,1);
			$('#textWrapper').on('mouseout',hideHomeText);
		}
	
		function hideHomeText(){
			$('#textWrapper').off('mouseout',hideHomeText);
			$('#textWrapper').stop().fadeTo(800,0);
			$('div.info').on('mouseover',showHomeText);			
		}


	//###############################################	
	// Hide/show animate contact form
	//###############################################	
		function showContactForm(e){
			if( e != null ) e.preventDefault();
			swapContactClasses();
			$('#contact').addClass('visible');	
			sendEmailLink.off('click',showContactForm);
			sendEmailLink.on('click',hideContactForm);
			$('#closeContactForm').on('click',hideContactForm);
		}
		function hideContactForm(e){
			e.preventDefault();
			$('#contact').removeClass('visible');
			sendEmailLink.off('click',hideContactForm);
			sendEmailLink.on('click',showContactForm);
			$('#closeContactForm').off('click',hideContactForm);
		}



	//###############################################	
	// Run after a page is initialized
	//###############################################
		function swapContactClasses(){
			if( !isBlogPage && $('#homeContent').length > 0 ){
				$('#contact').removeClass('insideContact');	
				$('#contact').addClass('homeContact');	
			} else {
				$('#contact').removeClass('homeContact');	
				$('#contact').addClass('insideContact');	
			}
		}


	//###############################################	
	// Enlarge/Reduce search form on focus/blur
	//###############################################	
		function enlargeSearchForm(){
			$( "#s" ).animate({ "width": "196" }, 500 );
			$( ".wrapper-search" ).animate({ "width": "196" }, 500 );
		}
		
		function reduceSearchForm(){
			$( "#s" ).animate({ "width": "26" }, 500 );
			$( ".wrapper-search" ).animate({ "width": "26" }, 500 );
		}


	//###############################################	
	// Setup the dropdown menus
	//###############################################	
		function initializeDropdownMenus(){
			//console.log('menus initialized');
			menus = $('ul.sf-menu').sooperfish({
				sooperfishWidth:150,
				dualColumn:		50,
				tripleColumn:	100,
				animationShow:	{height:'show'},
				speedShow:		0,
				easingShow:		'easeInSine',
				animationHide:	{height:'hide',opacity:'hide'},
				speedHide:		300,
				easingHide:		'easeOutSine',
				delay:			0,
				autoArrows:		false
			});
			//console.log(menus);
		}	



	//###############################################	
	// Hide dropdown menus when tapping out on mobile
	//###############################################	
		function hideSooperfishMobile(){
			//this isn't ready yet
			//menus.hideSooperfishUl();
		}


	//###############################################	
	// Hack to add classes to the first two tweets on the Insights grid
	//###############################################
		function addClassesToInsightsTweets(){
			if( $('div.activeAJAX .insights').length > 0 ){
				var insightsTweets = $('div.activeAJAX .insights .tweets');
				insightsTweets.eq(0).addClass('twoRow');
				insightsTweets.eq(1).addClass('twoRow');
			}
		}


	//###############################################	
	// Filter items using Isotope
	//###############################################
		function setupIsotopeFilter(){
			if( $('div.activeAJAX .grid').length > 0 ){
				//console.log('hi');
				var selector = $(this).attr('data-filter');
				filterIsotope(selector);
				return false;
			} else {
				//console.log('no grid');
				return true;	
			}
		}
		
		function filterIsotope(selector){
			refreshSizing();
			scrollGridToFarLeft();
			gridWrapper.isotope({filter:selector},function(){
				if( gridWrapperWidth <= gridWidth ){
					toggleGridArrows(true,true);
					toggleSlideshowKeyboardShortcuts(false);
					toggleGridKeyboardShortcuts(true);
				} else {
					toggleGridArrows(true,false);
					toggleGridKeyboardShortcuts(false);
				}
			});
		}


	//###############################################	
	// Show/hide flyout menus for About & Careers
	//###############################################
		function showSubpageMenu(e){
			e.preventDefault();
			if(( isAboutPage || isCareersPage ) && pageInfoContainer && typeof currentScrollPaneAPI.destroy === 'function' ){
				currentScrollPaneAPI.destroy();
			}
			if( pageInfoContainer != $(this).parents('.subpageMenu').children('.pageInfoContainer') ) {
				hidePageInfoContainer();
			}
			pageInfoContainer = $(this).parents('.subpageMenu').children('.pageInfoContainer');
			pageInfoContainer.find('.pageInfoWrapper').children('h1').text($(this).text());
			pageInfoContainer.find('.pageInfoWrapper').children('p').html($(this).next('.pageInfo').html());
			$(".pageInfoContainer a[target!='_blank']").not(".pageInfoContainer a[href^='mailto']").not('.pageInfoContainer .contactFormLink').on('click',subpageLinkSetup); // function defined in 'ajax2.js'
			$('.contactFormLink').on('click',showContactForm);
			pageInfoContainer.show();
			
			if( isAboutPage || isCareersPage ){
				currentScrollPaneElement = pageInfoContainer.jScrollPane({contentWidth:'0px'});
				currentScrollPaneAPI = currentScrollPaneElement.data('jsp');
			}
			window.location.hash = $(this).parents('li').attr('id').replace(' ','-').replace('flyout_','');
		}
		
		function hidePageInfoContainer(){
			if( pageInfoContainer ){
				pageInfoContainer.hide();
				pageInfoContainer = null;
				window.location.hash = '';
			}
		}


	//###############################################	
	// Hide the first menu; shown by default on About & Careers
	//###############################################
		function hideFirstFlyout(){
			//console.log('hide flyout');
			$('.activeAJAX li.sf-parent').eq(0).trigger('mouseout');
		}


	//###############################################	
	// Grab the has string of the url
	//###############################################
		function hideFirstFlyout(){
			//console.log('hide flyout');
			$('.activeAJAX li.sf-parent').eq(0).trigger('mouseout');
		}



	//###############################################	
	// Run after a page is initialized
	//###############################################
		function documentReady(){
			isMobile = detectMobile();
			
			if( $('section.blog').length > 0 ) {
				isBlogPage = true; // sets the global variable
			}
				
			//adjustForMobile();
			initializeScrollbars();
		
			$(window).on('resize',resizeMiddleWidth);
		
		
			setupCustomSlideshow();
		
			// Setup the grid sliding
			determineCurrentGrid();
			$('.slideGridLeftButton').on('click',slideGridLeft);
			$('.slideGridRightButton').on('click',slideGridRight);
			if( gridWrapper ){
				toggleSlideshowKeyboardShortcuts(false);
				toggleGridKeyboardShortcuts(true);
				toggleGridArrows(true,false);
				gridWrapper.isotope({
					layoutMode:'fitColumns',
					resizable:true
				});
			}
			$('.isotopeFilterItem').on('click',setupIsotopeFilter);
		
		
			// Setup the slideshow
			console.log('ready, setting up');
			setupSlideshow();
			$('.slideWorkRightButton').on('click',slideshowAdvanceNext);
			$('.slideWorkLeftButton').on('click',slideshowAdvancePrev);
			
			
			sendEmailLink = $('#send_email_link, #heading_send_email_link, .contactFormLink');
			sendEmailLink.on('click',showContactForm);

			swapContactClasses();
		
		
			$( "#s" ).on('focus',enlargeSearchForm);
			$( "#s" ).on('blur',reduceSearchForm);
			
			
			initializeDropdownMenus();
			
			addClassesToInsightsTweets();
			
		
			if( typeof initialFilter != 'undefined' ) {
				refreshSizing();
				filterIsotope(initialFilter);
			}
			
			var url =  $.url();
			if( url.attr('fragment').search('contact-us') > -1 ){
				showContactForm(null);
			}
		
		
			flyoutPage = $('div.activeAJAX .flyoutPage');
			isCareersPage = ( $('div.activeAJAX ul.careers').length > 0 ) ? true : false;
			isAboutPage = ( $('div.activeAJAX ul.about').length > 0 ) ? true : false;
		
		
			if( isCareersPage || isAboutPage ){
				var theMenu = $('div.activeAJAX ul.sf-menu');
				
				$('.pageInfoClose').on('click',hidePageInfoContainer);
				theMenu.find('.subpageMenu > a').on('mouseover',hidePageInfoContainer);
				theMenu.find('a.infoItem').on('mouseover',hidePageInfoContainer);
				theMenu.find('a.infoItem').on('mouseover click',showSubpageMenu);	

				var url =  $.url();
				if( url.attr('fragment') != '' || typeof url.segment(2) != 'undefined' ){
					//console.log('permalink');
					// capture the initial hash tag and fly out the correct "page" on load
						if( url.attr('fragment') != '' ){
							fragment = url.attr('fragment');
						} else {
							if( url.segment(-1) != '' ){
								fragment = url.segment(-1);
							}
						}
						//console.log(fragment);
						if( fragment != '' ){ // handle subpages of the about/careers url
							//console.log(escape(fragment));
							var id = '#flyout_'+escape(fragment);
							//console.log(id);
							var theCurrentFlyout = theMenu.find('#flyout_'+fragment);
							//console.log(theCurrentFlyout);
							theCurrentFlyout.parents('li.sf-parent').trigger('mouseover');
							theCurrentFlyout.find('a').trigger('click');
						} 
				} else {
					//console.log('no permalink');
					theMenu.find('li.sf-parent').eq(0).trigger('mouseover');
					if( theMenu.find('li.sf-parent').length > 1 ){
						theMenu.find('li.sf-parent').eq(1).one('mouseover',hideFirstFlyout);
					}
				}
		
			}
		
		
			resizeMiddleWidth();
			var initialSpanHeight = $('#textWrapper div span').height();
			$('#textWrapper div').height( Math.floor(initialSpanHeight * 1.05) - Math.floor(initialSpanHeight / 10) );
			initializeHomepageFontSize();
			
			checkForCustomPageURL();
						
		}


//##############################################################################################	
//##############################################################################################	
								// Window Load
//##############################################################################################	
//##############################################################################################	

$(window).load(function(){
	loadComplete();
});





//##############################################################################################	
//##############################################################################################	
								// Document Ready
//##############################################################################################	
//##############################################################################################	


$(document).ready(function(){
	
	documentReady();
	if( typeof initializeAjax == 'function' && !isBlogPage ){
		initializeAjax();
	}

});


