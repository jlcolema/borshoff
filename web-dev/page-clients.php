<?php //template for the work page - displays a grid of work ?>

<?php if( !isset($_GET['ajax']) ) : ?>

<?php get_header(); ?>

<?php include('menu.php'); ?>

<section id="pageWrapper" class="container">

	<?php include('navigation.php'); ?>
	
	<div id="initialContent">
		<?php generate_client_grid(); ?>
	</div>

	<?php get_sidebar(); ?>

	<div class="clearfix"></div>

</section>

<?php get_footer(); ?>

<?php else : // load only the content for AJAX ?>
	<div id="content" class="clients" data-title="<?php echo get_ajax_title(); ?>">

		<?php generate_client_grid(); ?>
	
		<div class="clearfix"></div>

	</div>
<?php endif; ?>