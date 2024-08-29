// JavaScript Document

var isTransitioning = false;
var history = new Array();
var isHomePage = true;
var isSidebarInPlace = false;

var newContent;
var oldContent;
var currentContent;


function initializeAjax(){
	$('#pageWrapper').append($('<div id="ajaxContentViewport"/>'));
	$('#ajaxContentViewport').append($('<div id="ajax1"/>'));
	$('#ajaxContentViewport').append($('<div id="ajax2"/>'));
	$('#ajaxContentViewport').append($('<div id="ajax3"/>'));

	oldContent = $('#ajax1');
	currentContent = $('#ajax2');
	newContent = $('#ajax3');
	
	$('nav a').on('click',loadMainMenuLinks);
	$('a.grid_link').on('click',loadMainMenuLinks);

	setupAjaxSizes();
	
	if( !isHomePage ){
		situateSidebar();
	}
		
}

function situateSidebar(url){
	var newPageURL = $(this).attr('href');
	if( isHomePage ){
		$('#initialContent').animate({
			'left':middleWidth + 245 + 5
		},800,function(){
			$('#homeContent').remove();
			$('#initialContent').width( $('#sidebar').outerWidth() )
			$('#initialContent').css({'left':'auto','right':0});
			getNewContent( newPageURL,true);
		});
	} else {
		slideContent(newPageURL,true);
	}
}

function loadMainMenuLinks(e){
	e.preventDefault();
	var url = $(this).attr('href');
	if( !isSidebarInPlace ){
		situateSidebar(url);
	} else {
		slideContent(url,true);
	}
}

function setupAjaxSizes(){
	$('#ajaxContentViewport').width(middleWidth);

	oldContent.width(middleWidth);
	currentContent.width(middleWidth);
	newContent.width(middleWidth);

	oldContent.height(500);
	currentContent.height(500);
	newContent.height(500);
	
	oldContent.css('zIndex',300);
	currentContent.css('zIndex',400);
	newContent.css('zIndex',500);
	
	currentContent.css({'left':0});
	newContent.css({'left':middleWidth});
	oldContent.css({'left':-middleWidth});
}


function getNewContent(url,isForward){
	if( !isTransitioning ) { //sorry, can't load 2 at a time
		history.push(url); // store the url for recordkeeping
		if( isHomePage ) {
			var loadingZone = currentContent;
		} else {
			var loadingZone = ( isForward ) ? newContent : oldContent;
		}
		loadingZone.load(url + '?ajax',function(){ // use the provided ID for the section of content to grab
			
		if( !isHomePage ){
			swapZones(isForward);				
		} else {
			isHomePage = false;	
		}
			
		// setup all the stuff from global.js
		documentReady();

		});
	}
}

function slideContent(url,isForward){
	if( isForward ){
		currentContent.animate({'left': (-middleWidth)},800);		
		newContent.animate({
			'left':0
		},800,function(){
			getNewContent( url,true);
		});
	} else {
		currentContent.animate({'left':middleWidth},800);		
		oldContent.animate({
			'left':0
		},800,function(){

		});
	}
}

function swapZones(isForward){
	oldContent.css('left',middleWidth);
	oldContent.find('#content').remove();

	var limboContent = oldContent;
	
	oldContent = currentContent;
	oldContent.attr('title','old');
	
	currentContent = newContent;
	currentContent.attr('title','current');
	
	newContent = limboContent;
	newContent.attr('title','new');
	
	limboContent = null;
	
	
	currentContent.css('zIndex',300);
	oldContent.css('zIndex',400);
	newContent.css('zIndex',400);
}





