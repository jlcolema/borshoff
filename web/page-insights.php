<?php //template for the people page - displays a grid of people ?>

<?php if( !isset($_GET['ajax']) ) : ?>

<?php get_header(); ?>

<?php include('menu.php'); ?>

<section id="pageWrapper" class="container">

	<?php include('navigation.php'); ?>
	
	<div id="initialContent">
		<?php generate_insights_grid(); ?>
	</div>

	<?php get_sidebar(); ?>

	<div class="clearfix"></div>

</section>

<?php get_footer(); ?>

<?php else : // load only the content for AJAX ?>
	<div id="content" class="insights" data-title="<?php echo get_ajax_title(); ?>">

		<?php generate_insights_grid(); ?>
	
		<div class="clearfix"></div>

	</div>
<?php endif; ?>