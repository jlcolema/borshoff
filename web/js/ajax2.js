//##############################################################################################	
//##############################################################################################	
								// GLOBAL NAMESPACE VARIABLES
//##############################################################################################	
//##############################################################################################	
var isTransitionPlaying = false;
var pageHistory = [];
var History = window.History;
var backEnabled = false;

var isHomePage = true;
var isSidebarInPlace = false;

var newContent;
var oldContent;
var currentContent;

var transitionSpeed = 800;

var sectionGutter = 5;
var mainNavSize = 245;

var activeClass = 'activeAJAX';

var currentPosition;
var activatedPosition;
var pagePositions = ['home','work','clients','people','insights','about','careers'];
//##############################################################################################	



//##############################################################################################	
//##############################################################################################	
								// SETUP FUNCTIONS
//##############################################################################################	
//##############################################################################################	

	//###############################################	
	// Setup AJAX enabled pages - add AJAX wrappers and initialize
	//###############################################	
		function initializeAjax(){
			
			if( $('body.home').length < 1 ){
				isHomePage = false;
			}

			if( isHomePage ){
				currentPosition = 0;
				$('#pageWrapper').append($('<div id="ajaxContentViewport"/>'));
				$('#ajaxContentViewport').append($('<div id="ajax1"/>'));
				$('#ajaxContentViewport').append($('<div id="ajax2"/>'));
				$('#ajaxContentViewport').append($('<div id="ajax3"/>'));
			} else {
				currentPosition = $.inArray($.url().segment(1),pagePositions);
				$('#initialContent').wrap($('<div id="content"/>'));
				$('#content').wrap($('<div id="ajax2" class="activeAJAX"/>'));
				$('#initialContent > section, #initialContent > div').unwrap();
				$('#ajax2').wrap($('<div id="ajaxContentViewport"/>'));
				$('#ajaxContentViewport').append($('<div id="ajax1"/>'));
				$('#ajaxContentViewport').append($('<div id="ajax3"/>'));
				documentReady();
			}
		
			oldContent = $('#ajax1');
			currentContent = $('#ajax2');
			newContent = $('#ajax3');
			
			resizeMiddleWidth();
			
			$('#ajaxContentViewport').width(middleWidth);
		
			oldContent.width(middleWidth);
			currentContent.width(middleWidth);
			newContent.width(middleWidth);
			
			oldContent.css('zIndex',300);
			currentContent.css('zIndex',400);
			newContent.css('zIndex',300);
			
			currentContent.css({'marginLeft':0});
			newContent.css({'marginLeft':middleWidth+sectionGutter});
			oldContent.css({'marginLeft':-middleWidth-sectionGutter});
		
			bindLinks();
			
			if( !isHomePage ){
				setupSidebar();
			}
			
		}
		
	//###############################################	
	// If it's the homepage, move the sidebar over first
	//###############################################	
		function setupSidebar(url){
			if( isHomePage ){ // deal with homepage content
				showLoader(currentContent);
				$('#initialContent').animate({
					'left':middleWidth + mainNavSize + sectionGutter
				},transitionSpeed,function(){
					isTransitionPlaying = false;
					$('#homeContent').remove();
					$('#initialContent').width( $('#sidebar').outerWidth() );
					$('#initialContent').css({'marginLeft':'auto','right':0});
					$('#sidebar').css({'position':'absolute','right':0});
					currentContent.load(url+'?ajax',function(){
						cleanupContainers();
					});
				});
			} else { // deal with other pages' content
				$('#sidebar').css({'position':'absolute','right':0});
//				$('#sidebar').css({'left':middleWidth + mainNavSize});
			}
			isSidebarInPlace = true;
		}



//##############################################################################################	
//##############################################################################################	
								// LINK HANDLING
//##############################################################################################	
//##############################################################################################	

	//###############################################	
	// Switch most links to AJAX
	//###############################################	
		function bindLinks(){
			$('nav a').on('click',mainMenulinkCheck);
			$('a.grid_link').not('[href="#"]').on('click',gridLinkSetup);
			$('a.closeLink').on('click',closeLinkSetup);
			if( currentContent.find('.info').length > 0 ) {
				if( $(this).attr('href') != '' ) {
					$('li.no-arrow a').on('click',linkCheck);
				}
			}
			$('.isotopeFilterItem').on('click',filterLinkSetup);
			$(".pageInfoContainer a[target!='_blank']").not(".pageInfoContainer a[href^='mailto']").not('.contactFormLink').on('click',subpageLinkSetup);
		
			$('.slideGridLeftButton').on('click',slideGridLeft);
			$('.slideGridRightButton').on('click',slideGridRight);
		}
		
	//###############################################	
	// Clear the initial isotope filter
	//###############################################	
		function clearInitialFilter(){
			initialFilter = '*';
		}
		
	//###############################################	
	// Ensure that close links for individual work/people go back one step
	//###############################################	
		function closeLinkSetup(e){ //close x's on single items
			e.preventDefault();
			////console.log(pageHistory);
			if(	pageHistory.length > 1 ){
				////console.log(pageHistory[pageHistory.length-2]);
				goToLink(pageHistory[pageHistory.length-2]);
			} else {
				goToLink($(this).attr('href'));		
			}
		}
	
	//###############################################	
	// Capture clicks on grid tiles
	//###############################################	
		function gridLinkSetup(e){ //grid squares
			e.preventDefault();
			var url = $(this).attr('href');
			
			if( currentContent.find($('.clients')).length > 0 ){ // if it's the clients page, do the following
			
				$('nav li').removeClass('current_page_item');
				$('nav li').eq(0).addClass('current_page_item');

				if( url != '#' ){ // detect unclickable clients
					if( url.indexOf('?') != -1 ){ 
						var equalPos = url.indexOf('=');
						initialFilter = '.'+url.substr(equalPos+1);
						var questionPos = url.indexOf('?');
						url = url.substring(0,questionPos);
					}
					////console.log(url);
					goToLink(url);
				} else { // for unclickable clients, do nothing
					return false;	
				}
				
			} else { // for all other grid links (work, people) just load the url of the grid square
				////console.log('normal grid link');
				goToLink(url);
			}
		}
		
	//###############################################	
	// Enable filtering with Isotope
	//###############################################	
		function filterLinkSetup(e){ // isotope filters
			// handle the principals page separately
			if( $(this).text().indexOf('Principals') > -1 ){
				if( $(this).attr('href') == '' ){ // run the isotope filter
					//console.log('run isotope to filter principals');
					initialFilter = $(this).attr('data-filter');
					return true;
				} else { // load the principals page
					//console.log('load people page');
					var url = $(this).attr('href');
					var equalPos = url.indexOf('=');
					initialFilter = '.'+url.substr(equalPos+1);
					var questionPos = url.indexOf('?');
					//console.log('questionPosition',questionPos);
					//console.log('initial url',url);
					url = url.substring(0,questionPos);
					goToLink(url);
					return false;
				}
			} else {
				if( $(this).text().indexOf('All') == -1 ) {  // this is a filter link, not the "All" reset link
					//console.log('not all not principals');
					if( $(this).attr('href') != '' ){ // a filter link on a single work/personel page
						var url = $(this).attr('href');
						e.preventDefault();
						if( url.indexOf('?') ){ // if there's a query parameter (i.e. if it's a filter instead of the all)
							var equalPos = url.indexOf('=');
							initialFilter = '.'+url.substr(equalPos+1);
							var questionPos = url.indexOf('?');
							url = url.substring(0,questionPos);
							////console.log('------');
							////console.log(url);
						}
						////console.log(url);
						goToLink(url);
						return false;
					} else {
						//console.log('hi');
						initialFilter = $(this).attr('data-filter');
						return true;
					}
				} else { // handle links to everything
				//console.log('everything');
					var url = $(this).attr('href');
					//console.log(url);
					if( url.indexOf('?') > -1 ){ // if there's a query parameter (i.e. if it's a filter instead of the all)
						var equalPos = url.indexOf('=');
						initialFilter = '.'+url.substr(equalPos+1);
						var questionPos = url.indexOf('?');
						url = url.substring(0,questionPos);
						//console.log('------');
						//console.log(url);
						goToLink(url);
						return false;
					} else {
						initialFilter = $(this).attr('data-filter');
						return true;	
					}
				}
			}
		}
		
	//###############################################	
	// Handle Main Menu items
	//###############################################	
		function mainMenulinkCheck(e){ // check main nav links
			e.preventDefault();
			activatedPosition = $.inArray($(this).url().segment(1),pagePositions);
			if( !isTransitionPlaying && $(this).attr('href') != pageHistory[pageHistory.length-1] ){
				clearInitialFilter();
				var url = $(this).attr('href');
				$('nav li').removeClass('current_page_item');
				$(this).parent('li').addClass('current_page_item');
				goToLink(url);
			}
		}
		
	//###############################################	
	// Handle links in the subpage flyout menus
	//###############################################	
		function subpageLinkSetup(e){ // check flyout menu links
			e.preventDefault();
			////console.log('hi');
			if( !isTransitionPlaying && $(this).attr('href') != pageHistory[pageHistory.length-1] ){
				var url = $(this).attr('href');
				////console.log(url);
				if( $(this).attr('target') != "_blank" && url.indexOf('mailto') < 0 ){
					////console.log('slide load');
					goToLink(url);
				} else {
					////console.log('proceed as normal');
				}
			} else {
				return false;	
			}
		}


	//###############################################	
	// Check generic links
	//###############################################	
		function linkCheck(e){ // check generic links
			if( $(this).text().indexOf('Principals') == -1 ){
				e.preventDefault();
				if( !isTransitionPlaying && $(this).attr('href') != pageHistory[pageHistory.length-1] ){
					var url = $(this).attr('href');
					goToLink(url);
				}
			}
		}


	//###############################################	
	// 'Load a new link' utility
	//###############################################	
		function goToLink(url){
				//console.log('headed to: ',url);
			if( !isTransitionPlaying ){
								
				////console.log(pageHistory);
				
				// "_trackEvent" is the pageview event, 
				if (typeof _gaq !== "undefined" && _gaq !== null) {
					_gaq.push(['_trackPageview', url]);
				}
				
				stateObj = {address:url};
				if( History != null && typeof History.pushState == 'function' ){
					History.pushState(stateObj, "", url);
				}
				
				
				oldContent.css('overflow','inherit');
				newContent.css('overflow','inherit');
		
				if( url == pageHistory[pageHistory.length-2] && backEnabled ){
					//condition for pageHistory back
					//console.log(activatedPosition,currentPosition,'pageHistory back');
					pageHistoryBack();
					backEnabled = false;
				} else if( activatedPosition < currentPosition ) {
					//condition for load back
					//console.log(activatedPosition,currentPosition,'slide back');
					goBackward(url);
					backEnabled = false;
				} else {
					//else for load forward
					//console.log(activatedPosition,currentPosition,'slide forward');
					goForward(url);
					backEnabled = true;
				}
				
				if( url == pageHistory[pageHistory.length-1] ){
					pageHistory.pop();	
				} else {
					pageHistory.push(url);
				}
			}
		
		}
		
		




//##############################################################################################	
//##############################################################################################	
								// ANIMATION & LOADING
//##############################################################################################	
//##############################################################################################	

	//###############################################	
	// show the appropriate loader on command
	//###############################################	
		function showLoader(box){
			box.html('<div class="ajaxLoader"></div>');
			$('.ajaxLoader').width(middleWidth);
		}
		
	//###############################################	
	// hide all active loaders
	//###############################################	
		function hideAllLoaders(){
			$('.ajaxLoader').fadeOut(300,function(){$(this).remove();});
		}
		


	//###############################################	
	// advance to a new page (slide all to the left)
	//###############################################	
		function goForward(url){
			if( !isSidebarInPlace ){
				// load initial;
				isTransitionPlaying = true;
				setupSidebar(url);
			} else {
				// load forward
				isTransitionPlaying = true;
				showLoader(newContent);
				currentContent.animate({'marginLeft': (-middleWidth-sectionGutter)},transitionSpeed);		
				newContent.animate({
					'marginLeft':0
				},transitionSpeed,function(){
					newContent.load(url+'?ajax',function(){
						oldContent.css('marginLeft',middleWidth);
						
						var limboContent = oldContent;
						oldContent = currentContent;
						currentContent = newContent;
						newContent = limboContent;
						limboContent = null;
						
						cleanupContainers();
		
						//newContent.find('#content').remove();
		
					});
				});
			}
		}


	//###############################################	
	// *load* to an old page from pageHistory (slide all to the right)
	//###############################################	
		function goBackward(url){
			////console.log('load back');
			isTransitionPlaying = true;
			showLoader(oldContent);
			currentContent.animate({'marginLeft':middleWidth+sectionGutter},transitionSpeed);		
			oldContent.animate({
				'marginLeft':0
			},transitionSpeed,function(){
				oldContent.load(url+'?ajax',function(){
					
					newContent.css('marginLeft',-middleWidth);
					
					var limboContent = newContent;
					newContent = currentContent;
					currentContent = oldContent;
					oldContent = limboContent;
					limboContent = null;
					
					cleanupContainers();
		
					//oldContent.find('#content').remove();
				});
			});
		}

	//###############################################	
	// use existing content to go back one page (slide all to the right)
	// use the content from oldContent to reduce hits on the server
	//###############################################	
		function pageHistoryBack(){
			////console.log('pageHistory back');
			isTransitionPlaying = true;
			currentContent.animate({'marginLeft':middleWidth+sectionGutter},transitionSpeed);		
			oldContent.animate({
				'marginLeft':0
			},transitionSpeed,function(){
				newContent.css('marginLeft',-middleWidth);
				
				var limboContent = newContent;
				newContent = currentContent;
				currentContent = oldContent;
				oldContent = limboContent;
				limboContent = null;
						
				cleanupContainers();
				
				//oldContent.find('#content').remove();
		
			});
		}

	//###############################################	
	// executed after every transition
	//###############################################	
		function cleanupContainers(){
			
			var script = 'http://s7.addthis.com/js/250/addthis_widget.js?domready=1';
			if (window.addthis){
				window.addthis = null;
			}
			$.getScript( script );

			currentPosition = activatedPosition;
			//console.log('new current position: ',currentPosition);
			
			hideAllLoaders();
		
			isTransitionPlaying = false;
		
			newContent.removeClass(activeClass);
			oldContent.removeClass(activeClass);
			currentContent.addClass(activeClass);
		
			currentContent.css('zIndex',300);
			oldContent.css('zIndex',400);
			newContent.css('zIndex',400);
		
			isHomePage = false;
		
			oldContent.css('overflow','hidden');
			newContent.css('overflow','hidden');
			
			newContent.find('#content').remove();
			
			bindLinks();
		
			documentReady();

			//console.log('about to change the title to',currentContent.find('#content').attr('data-title'));
			document.title = currentContent.find('#content').attr('data-title');
		
		}
		
		