<footer>
	&copy; 2012 BORSHOFF &nbsp;| &nbsp; strategic {creative} communication
</footer>
<?php wp_footer(); ?>

<!--loads all of the js in one file-->
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/combinedJS.js"></script>

<script type="text/javascript">
	<?php
		// JS indicator for the front page
		if( is_front_page() ) {
			echo 'var isFrontPage=true;'.PHP_EOL;
		} else {
			echo 'var isFrontPage=false;'.PHP_EOL;
		}
	?>
</script>

<!--enable cross-browser history support-->
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.history.js"></script>


<!--url access through JS-->
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.url.js"></script>


<!--Sitewide Custom Scripts-->
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/mobileDetection.js"></script>

<?php if( get_post_type() != 'videos' && get_post_type() != 'post' && !is_search() && !is_template('template_hidden_page.php') ): ?>
<!--Look at all the AJAX fanciness-->
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/ajax2.js"></script>
<?php endif; ?>

<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/global.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.ba-hashchange.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.ba-bbq.min.js"></script>


<script type="text/javascript">
	<?php
		// Capture the specified Isotope filter from the query string
		if( isset($_GET['client']) ) $initialFilter = $_GET['client'];
		if( isset($_GET['service']) ) $initialFilter = $_GET['service'];
		if( isset($_GET['industry']) ) $initialFilter = $_GET['industry'];
		if( isset($_GET['principals']) ) $initialFilter = $_GET['principals'];
		
		if( isset($initialFilter) ) echo 'var initialFilter = ".' . $initialFilter . '";'.PHP_EOL;
	?>
</script>



</body>
</html>