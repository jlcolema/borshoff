<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php bloginfo('name'); ?> : <?php ( is_home() || is_front_page() ) ? bloginfo('description') : wp_title(''); ?></title>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>">
<?php /* <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/css/fontface/stylesheet.css"> */ ?>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/css/isotope.css">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato:400,300,700);" />


<!--[if lt IE 9]>
<link href="<?php bloginfo('template_directory'); ?>/css/ie.css" rel="stylesheet" type="text/css">
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<script src="https://raw.github.com/keithclark/selectivizr/master/selectivizr.js"></script>
<![endif]-->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.cycle.lite.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.isotope.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		
		$('#gridWrapper').isotope({
			layoutMode:'fitColumns'
		});
		
		function calculateMiddleWidth(){
			return middleWidth = $(window).width() - $('nav').outerWidth() - $('#sidebar').outerWidth() - 10;
		}
		
		$(window).bind('resize',resizeMiddleWidth);
		
		function resizeMiddleWidth(){
			$('.info').width( calculateMiddleWidth() );
			$('.info .slideshow').width( calculateMiddleWidth() - $('.info article').outerWidth() );
			$('.info .slideshow li').width( calculateMiddleWidth() - $('.info article').outerWidth() );
			$('.grid').width( calculateMiddleWidth() );
		}
		
		resizeMiddleWidth();

		$('#slideGridLeftButton').bind('click',slideGridLeft);
		$('#slideGridRightButton').bind('click',slideGridRight);


		function slideGridLeft(){
			//console.log( parseInt($('#gridWrapper').css('left').replace('px','')) );
			if( parseInt($('#gridWrapper').css('left').replace('px','')) <= -490 ){
				$('#gridWrapper').stop().animate({
					'left':'+=490'
				}, 600, function(){
					if( parseInt($('#gridWrapper').css('left').replace('px','')) > -490 ) {
						$('#gridWrapper').stop().animate({
							'left':0
						}, 600);
					}
				});
			} else {
				$('#gridWrapper').stop().animate({
					'left':0
				}, 600);
			}
		}
		function slideGridRight(){
			//console.log( 'current left:', parseInt($('#gridWrapper').css('left').replace('px','')) );
			if( parseInt($('#gridWrapper').css('left').replace('px','')) > -($('#gridWrapper').width() - $('.grid').width() ) ){
				$('#gridWrapper').stop().animate({
					'left':'-=490'
				}, 600, function(){
					if( parseInt($('#gridWrapper').css('left').replace('px','')) <= -($('#gridWrapper').width() - $('.grid').width()) ) {
						$('#gridWrapper').stop().animate({
							'left': -($('#gridWrapper').width() - $('.grid').width() )
						}, 600);
					}
				});
			} else {
				$('#gridWrapper').stop().animate({
					'left':-($('#gridWrapper').width() - $('.grid').width() )
				}, 600);
			}
		}
		
		$('.slideshow').cycle({
			after:afterSlide,
			prev:'#prevPhoto',
			next:'#nextPhoto',
			speed:700,
			timeout:0
		});
		
		function afterSlide(curr, next, opts){
			$('article > span').text(' '+ (opts.currSlide + 1) + ' of ' + opts.slideCount + ' ');
		}

	});
</script>
<script type="text/javascript">
  WebFontConfig = {
    google: { families: [ 'Lato:400,300,700:latin' ] }
  };
  (function() {
    var wf = document.createElement('script');
    wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
      '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
    wf.type = 'text/javascript';
    wf.async = 'true';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(wf, s);
  })(); </script>


<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>