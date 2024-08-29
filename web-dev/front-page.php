<?php //template for the front page - displays a slideshow ?>

<?php get_header(); ?>

<?php include('menu.php'); ?>

<section id="pageWrapper" class="container clearfix left">

	<?php include('navigation.php'); ?>
	
	<div id="initialContent">
		<?php get_sidebar(); ?>
		<div id="homeContent">
		<?php while( have_posts() ) : the_post();
			generate_home_slideshow( );
		endwhile; ?>
		</div>
	</div>

</section>

<?php get_footer(); ?>