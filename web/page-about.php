<?php //template for the careers page - displays a slideshow and menu ?>

<?php if( !isset($_GET['ajax']) ) : ?>

<?php get_header(); ?>

<?php include('menu.php'); ?>

<section id="pageWrapper" class="container about flyoutPage">

	<?php include('navigation.php'); ?>
	
	<div id="initialContent">
		<?php while( have_posts() ) : the_post();
			generate_page_slideshow( get_topmost_ancestor(get_the_ID()) );
		endwhile; ?>
	
		<?php generate_about_menu(); ?>
	</div>
	
	<?php get_sidebar(); ?>

	<div class="clearfix"></div>

</section>

<?php get_footer(); ?>

<?php else : // load only the content for AJAX ?>
	<div id="content" class="about flyoutPage" data-title="<?php echo get_ajax_title(); ?>">

		<?php while( have_posts() ) : the_post();
			generate_page_slideshow( get_the_ID() );
		endwhile; ?>
	
		<?php generate_about_menu(); ?>
	
		<div class="clearfix"></div>

	</div>
<?php endif; ?>