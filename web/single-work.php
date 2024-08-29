<?php //template for single work/project info - displays image slideshow of work with project details ?>

<?php if( !isset($_GET['ajax']) ) : ?>

<?php get_header(); ?>

<?php include('menu.php'); ?>

<section id="pageWrapper" class="container">

	<?php include('navigation.php'); ?>
	
	<div id="initialContent">
		<?php while( have_posts() ) : the_post(); ?>
			<?php generate_work_info( get_the_ID() ); ?>
		<?php endwhile; ?>
	
		<?php generate_work_menu(); ?>
		
	</div>

	<?php get_sidebar(); ?>
	
	<?php /* not needed anymore
		generate_item_info_menu_heading('Details')
	*/ ?>

	<div class="clearfix"></div>

</section>

<?php get_footer(); ?>

<?php else : // load only the content for AJAX ?>
	<div id="content" class="work" data-title="<?php echo get_ajax_title(); ?>">

		<?php while( have_posts() ) : the_post(); ?>
			<?php generate_work_info( get_the_ID() ); ?>
		<?php endwhile; ?>

		<div class="clearfix"></div>
	
		<?php generate_work_menu(); ?>

	</div>
<?php endif; ?>