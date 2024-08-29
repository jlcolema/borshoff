//###############################################	
// Oh, look, they're using a mobile device!
//###############################################	
var deviceIphone = "iphone";
var deviceIpod = "ipod";
var deviceIpad = "ipad";
var deviceAndroid = "android";
var isMobile = false;
 
//Initialize our user agent string to lower case.
var uagent = navigator.userAgent.toLowerCase();
 
// Detects if the current device is an iPhone.
function DetectIphone() {
	return (uagent.search(deviceIphone) > -1)
}
 
// Detects if the current device is an iPad.
function DetectIpad() {
	return (uagent.search(deviceIpad) > -1)
}
 
// Detects if the current device is an iPod Touch.
function DetectIpod() {
	return (uagent.search(deviceIpod) > -1)
}

// Detects if the current device is an Android.
function DetectAndroid() {
	return (uagent.search(deviceAndroid) > -1)
}
 
// Detects if the current device is mobile
function detectMobile() {
	if (DetectIphone())
		return true;
	else if (DetectIpod())
		return true;
	else if (DetectIpad())
		return true;
	else if (DetectAndroid())
		return true;
	else
		return false;
}


// this has to be in the global namespace since it is being called by stuff in both window.load and document.ready
function initializeScrollbars(){
	$('.scroll-pane').jScrollPane({contentWidth:'0px'}); // specificy a content width to eliminate horizontal scrollbars - via http://stackoverflow.com/questions/4404944/how-do-i-disable-horizontal-scrollbar-in-jscrollpane-jquery
}
	

var isBlogPage = false;



$(window).load(function(){
//###############################################	
// Make everything longer for the blog page
//###############################################	
	function equalizeBlogHeight(blogHeight){
		$('nav').height(blogHeight-80);
		$('#sidebar').height(blogHeight);
		$('aside').height(blogHeight);
		$('div.info').height(blogHeight);
	}
	
	if( isBlogPage ){
		var blogContentHeight = $('div.blogContent').height();
		if( blogContentHeight > 730 ){
			equalizeBlogHeight( blogContentHeight );
			initializeScrollbars();
		} else {
			equalizeBlogHeight(730);
		}
	} else {
		initializeScrollbars();
	}
	
	$('ul.slideshow').cycle('resume');
	
});



$(document).ready(function(){
	
	
if( $('section.blog').length > 0 ) {
	isBlogPage = true; // sets the global variable
}
	
//###############################################	
// Adjust elements as needed for mobile
//###############################################	
isMobile = detectMobile();
if( isMobile ) {
	
	// show grid and slideshow arrows by default since mobile doesn't have a hover state
	$('#slideGridLeftButton').css({left:0});
	$('#slideGridRightButton').css({right:0});

	if( $('ul.slideshow li').length > 1 ){ // only for pages with more than one sample though...
		$('#slideWorkLeftButton').css({left:0});
		$('#slideWorkRightButton').css({right:0});
	} else {
		$('#slideWorkLeftButton').remove();
		$('#slideWorkRightButton').remove();
	}
	
	// enable swiping on mobile!
	var swipeThreshold = {x:30,y:80};
		
	if( $('ul.slideshow li').length > 1 ){
		
		//enable swiping for single work pages
		$('div.infoHover').swipe({
			threshold:swipeThreshold,
			swipeLeft: slideshowAdvanceNext,
			swipeRight: slideshowAdvancePrev
		})
		
		// enable swiping for traditional "pages"
		$('ul.slideshow').swipe({
			threshold:swipeThreshold,
			swipeLeft: slideshowAdvanceNext,
			swipeRight: slideshowAdvancePrev
		})
	}
	
	$('#gridWrapper .hover_info img').swipe({
		threshold:{x:30,y:10},
		swipeLeft: slideGridLeft,
		swipeRight: slideGridLeft
	})

}




//###############################################	
// Custom scrollbars
//###############################################	
	if( !isBlogPage ){
		initializeScrollbars();
	}
		
	if( isBlogPage ){ // removes extra padding on the sidebars for when the blog is short
		if(  $('div.blogContent').height() < 730 ){
			$('#sidebar').css('paddingBottom',0);
			$('aside').css('paddingBottom',0);
		}
	}

	
//###############################################	
// Deal with the browser width changes
// make the middle column elastic based on the window width minus the nav bar and sidebar
//###############################################	
	function calculateMiddleWidth(){
		return middleWidth = $(window).width() - $('nav').outerWidth() - $('#sidebar').outerWidth() - 10;
	}
	
	var usingTabletCSS = false;
	
	$(window).on('resize',resizeMiddleWidth);
	
	function resizeMiddleWidth(){
		var middleWidth = calculateMiddleWidth();
		var divInfo = $('div.info');

		divInfo.width( middleWidth );
		divInfo.find('div.blogContent').width( middleWidth - divInfo.find('aside').outerWidth() );
		
		var articleWidth = divInfo.find('article').outerWidth();
		var newMiddleWidth = middleWidth - articleWidth;
		
		divInfo.find('ul.slideshow').width( newMiddleWidth  );
		divInfo.find('ul.slideshow li').width( newMiddleWidth );
		divInfo.find('div.infoHover').width( newMiddleWidth );
		$('section.grid').width( middleWidth );
		if( $('.insights').length ) {
			if( $('#gridWrapper').height() != 735 ){
				usingTabletCSS = true;
			} else {
				usingTabletCSS = false;	
			}
			toggleThirdRow();
		}
		initializeHomepageFontSize()
	}
	
	function toggleThirdRow() {
		if( usingTabletCSS ) {
			theGrid.isotope({filter:'.twoRow'});
		} else {
			theGrid.isotope({filter:'*'});
		}
	}
		

//###############################################	
// Animate the grid sliding
//###############################################

	$('#slideGridLeftButton').on('click',slideGridLeft);
	$('#slideGridRightButton').on('click',slideGridRight);
	
	if( $('#gridWrapper').length > 0 ){
		toggleGridKeyboardShortcuts(true);
	}
	
	function toggleGridKeyboardShortcuts(isEnabled){
		if( isEnabled == true ){
			$(document.documentElement).on('keyup',handleGridArrowKeys);
		} else {
			$(document.documentElement).off('keyup',handleGridArrowKeys);
		}
	}
	
	function handleGridArrowKeys(e){
		switch(e.keyCode){
			case 39:
				slideGridRight();
				break;
			case 37:
				slideGridLeft();
				break;	
		}
	}

	var gridWrapper = $('#gridWrapper');
	var grid = $('section.grid');
	var gridWrapperLeft;
	var gridWrapperWidth;
	var gridWidth;
	var gridOverlap;
	var amountToSlide = 490;
	var slideSpeed = 600;
	var incrementAmount;
	
	function slideGridLeft(){
		refreshSizing();
		if( gridWrapperLeft != 0 ){
			if( gridWrapperLeft <= -amountToSlide ){ // move the grid to the left
				incrementAmount = '+=' + amountToSlide.toString();
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
		refreshSizing();
		if( gridWrapperLeft >= -(gridOverlap - amountToSlide + 20) ){ // move the wrapper to the right
			incrementAmount = '-=' + amountToSlide.toString();
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
	
	function refreshSizing(){
		gridWrapperLeft = parseInt(gridWrapper.css('left').replace('px',''));
		gridWrapperWidth = gridWrapper.width();
		gridWidth = grid.width();
		gridOverlap = Math.abs( gridWrapperWidth - gridWidth );
	}
	
	function scrollGridToFarLeft(){
		refreshSizing();
		gridWrapper.stop().animate({
			'left':0
		}, slideSpeed, function(){
			toggleGridArrows(true,false);
		});
	}
	
	function scrollGridToFarRight(){
		refreshSizing();
		gridWrapper.stop().animate({
			'left':-( gridWrapperWidth - gridWidth - 5 )
		}, slideSpeed, function(){
			toggleGridArrows(false,true);
		});
	}
	
	function toggleGridArrows(hideLeft, hideRight){
		hideLeft = ( hideLeft != undefined ) ? hideLeft : false;
		hideRight = ( hideRight != undefined ) ? hideRight : false;
		if( hideLeft ){
			$('#slideGridLeftButton').addClass('hidden');
		} else {
			$('#slideGridLeftButton').removeClass('hidden');
		}
		if( hideRight ){
			$('#slideGridRightButton').addClass('hidden');
		} else {
			$('#slideGridRightButton').removeClass('hidden');
		}
	}
	
	toggleGridArrows(true,false);

	

//###############################################	
// Slideshow for a single post content
//###############################################
	var cycleOptions  = new Object();
	if( isFrontPage ){
		cycleOptions.fx = 'fade';
		cycleOptions.before = beforeHomeSlide;
		cycleOptions.prev = '#prevPhoto';
		cycleOptions.next = '#nextPhoto';
		cycleOptions.speed = 1200;
		cycleOptions.timeout = 6000;
		cycleOptions.pager = '.pager';
	} else {
		cycleOptions.fx = 'scrollHorz';
		cycleOptions.after = afterSlide;
		cycleOptions.prev = '#prevPhoto';
		cycleOptions.next = '#nextPhoto';
		cycleOptions.speed = 700;
		cycleOptions.timeout = 0;
		cycleOptions.pager = '.pager';
		cycleOptions.slideResize = 0;
		cycleOptions.containerResize = 0;
	}
		
	$('ul.slideshow').cycle(cycleOptions);
	$('ul.slideshow').cycle('pause');
	
	function afterSlide(curr, next, opts){
		currentSlide = $(".pager a.activeSlide").html();
		if (!currentSlide) currentSlide = "1";
		$('article .jspPane span').html(' ' + currentSlide + ' of ' + opts.slideCount + ' ');
		if( $('ul.slideshow li').length > 1 ){
			window.location.hash = '#'+currentSlide;
		}
	}
	
	$('#slideWorkRightButton').click(function(){
		slideshowAdvanceNext();
	});
	
	$('#slideWorkLeftButton').click(function(){
		slideshowAdvancePrev()
	});
	
	
	if( $('ul.slideshow').length > 0 ){
		toggleSlideshowKeyboardShortcuts(true);
	} else {
		toggleSlideshowKeyboardShortcuts(false);
	}
	
	
	function toggleSlideshowKeyboardShortcuts(isEnabled){
		if(isEnabled){
			$(document.documentElement).on('keyup',handleSlideshowArrowKeys);
		} else {
			$(document.documentElement).on('keyup',handleSlideshowArrowKeys);
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
	
	function slideshowAdvancePrev(){
		$('ul.slideshow').cycle('prev',function(){afterSlide()})
	}
	
	function slideshowAdvanceNext(){
		$('ul.slideshow').cycle('next',function(){afterSlide()})
	}
		
	

//###############################################	
// homepage text
//###############################################
	
	
	var isHomeTextFadable = true;
	
	var initialResizeComplete = false;
	
	function beforeHomeSlide(curr, next, opts){
		console.log('started beforeHomeSlide()');
		if( isHomeTextFadable ) {
			isHomeTextFadable = false;
			console.log('hideHomeText()');
			hideHomeText();
			//toggleHomeTextHover();
		}
		console.log('completed beforeHomeSlide()');
	}
	
	function doneTextSize(){
		console.log('started doneTextSize()');
		var spanHeight = $('#textWrapper div span').height();
		$('#textWrapper div').height( Math.floor(spanHeight * 1.05) - Math.floor(spanHeight / 10) );
		console.log('completed doneTextSize()');
	}


	function initializeHomepageFontSize(){
		console.log('started initializeHomepageFontSize()');
		var textwrapperDiv = $('#textWrapper div');
		textwrapperDiv.height( textwrapperDiv.height() + 400 )	
		textwrapperDiv.textfill({maxFontPixels: 300, callback: doneTextSize});
		if( !initialResizeComplete ){
			initialResizeComplete = true;
			initializeHomepageFontSize();
		}
		console.log('completed initializeHomepageFontSize()');
	}
	
	function toggleHomeTextHover(on){
		$('div.info').on('mouseover',showHomeText);			
	}
	
	function showHomeText(){
		console.log('started showHomeText()');
		$('div.info').off('mouseover',showHomeText);
		initializeHomepageFontSize();
		$('#textWrapper').stop().fadeTo(800,1);
		$('#textWrapper').on('mouseout',hideHomeText);
		console.log('completed showHomeText()');
	}

	function hideHomeText(){
		console.log('started hideHomeText()');
		$('#textWrapper').off('mouseout',hideHomeText);
		$('#textWrapper').stop().fadeTo(800,0);
		$('div.info').on('mouseover',showHomeText);
		console.log('completed hideHomeText()');			
	}



	
//###############################################	
// Hack to add classes to the first two tweets on the Insights grid
//###############################################
if( $('.insights').length ){
	var insightsTweets = $('.insights .tweets');
	insightsTweets.eq(0).addClass('twoRow');
	insightsTweets.eq(1).addClass('twoRow');
}



//###############################################	
// Isotope
//###############################################		
	var theGrid = $('#gridWrapper');
	theGrid.isotope({
		layoutMode:'fitColumns',
		resizable:true
	}, function(){
		//initializeScrollbars();
	});
	
	
	$('.isotopeFilterItem').click(function(){
		refreshSizing();
		scrollGridToFarLeft();
		var selector = $(this).attr('data-filter');
		theGrid.isotope({filter:selector},function(){
			if( gridWrapperWidth <= gridWidth ){
				toggleGridArrows(true,true);
				toggleGridKeyboardShortcuts(true);
			} else {
				toggleGridArrows(true,false);
				toggleGridKeyboardShortcuts(false);
			}
		});
		return false;
	});
	
	if( typeof initialFilter != 'undefined' ) {
		refreshSizing();
		scrollGridToFarLeft();
		theGrid.isotope({filter:initialFilter},function(){
			if( gridWrapperWidth <= gridWidth ){
				toggleGridArrows(true,true);
				toggleGridKeyboardShortcuts(true);
			} else {
				toggleGridArrows(true,false);
				toggleGridKeyboardShortcuts(false);
			}
		});
	}






//###############################################	
// Sooperfish Dropdowns
//###############################################		
	demoShow = new Object();
	demoShow.height = 'show';
	//demoShow.opacity = 'show';
	
	demoHide = new Object();
	demoHide.height = 'hide';
	demoHide.opacity = 'hide';
	
	var menus = $('ul.sf-menu').sooperfish({
		sooperfishWidth: 	150,
		dualColumn:     	50,
		tripleColumn:     	100,
		animationShow:   	demoShow,
		speedShow:     		0,
		easingShow:    		'easeInSine',
		animationHide:  	demoHide,
		speedHide:      	300,
		easingHide:   	 	'easeOutSine',
		delay:				0,
		autoArrows:  		false
	});
	
	// hide the sooperfish menus by tapping on a background item on mobile
	if( isMobile ) {
		$('div.infoHover').click(function(){menus.hideSooperfishUl});
		$('section.grid_link').click(function(){menus.hideSooperfishUl});
	}




//###############################################	
// Click on the subpage items (about, employment)
//###############################################
	var flyoutPage = $('section.flyoutPage');
	if( flyoutPage.length > 0 ){
		
		function hideFirstFlyout(){
			flyoutPage.find('li.sf-parent').eq(0).trigger('mouseout');
		}

		flyoutPage.find('li.sf-parent').eq(0).trigger('mouseover');
		flyoutPage.find('li.sf-parent').eq(1).one('mouseover',hideFirstFlyout);
		
		
		
		var pageInfoContainer;
		
		function showSubpageMenu(e){
			pageInfoContainer = $(this).parents('.subpageMenu').children('.pageInfoContainer');
			pageInfoContainer.children('h1').text($(this).text());
			pageInfoContainer.children('p').html($(this).next('.pageInfo').html());
			window.location.hash = escape($(this).text());
			pageInfoContainer.show();
			return false;	
		}
		
		/*
			$url=$_SERVER['REQUEST_URI'];
			if  ( strpos( $url, "/careers/"  ) == 0 ) {
				pageInfoContainer = $(this).parents('.subpageMenu').children('.pageInfoContainer');
			else {
				pageInfoContainer = $(this).parents('.subpageMenu').children('.pageInfoContainer');
			}
		*/
		
		function hidePageInfoContainer(){
			if( pageInfoContainer ){
				pageInfoContainer.hide();
				window.location.hash = '';
			}
		}

		$('.pageInfoClose').on('click',hidePageInfoContainer);
		flyoutPage.find('.subpageMenu > a').on('mouseover',hidePageInfoContainer);
		flyoutPage.find('a.infoItem').on('mouseover',hidePageInfoContainer);
		flyoutPage.find('a.infoItem').on('mouseover',showSubpageMenu);

	}





//###############################################	
// Search
//###############################################
	
	$( "#s" ).focus(function(){
		$( "#s" ).animate({
			"width": "196"
		}, 500 );
		$( ".wrapper-search" ).animate({
			"width": "196"
		}, 500 );
	});
	
	$( "#s" ).blur(function(){
		$( "#s" ).animate({
			"width": "26"
		}, 500 );
		$( ".wrapper-search" ).animate({
			"width": "26"
		}, 500 );
	});
	
	

//###############################################	
// Contact Form
//###############################################
var sendEmailLink = $('#send_email_link');
sendEmailLink.on('click',showContactForm);
function showContactForm(e){
	e.preventDefault();
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
// Initialize
//###############################################
	resizeMiddleWidth();
	//var initialSpanHeight = $('#textWrapper div span').height();
	//$('#textWrapper div').height( Math.floor(initialSpanHeight * 1.05) - Math.floor(initialSpanHeight / 10) );
	if ( isFrontPage ) {
		doneTextSize();
		initializeHomepageFontSize();
	}



	// Auto scroll/hide the address bar on iPhone
	if( isMobile ){
		window.addEventListener('load', hideiOSAddressBar);
	}

});

function hideiOSAddressBar(){
	setTimeout(slideUpiOS, 100);;
}
function slideUpiOS(){
	window.scrollTo(0,0);
	window.removeEventListener('load', hideiOSAddressBar);
}
