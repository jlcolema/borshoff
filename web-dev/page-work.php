<?php //template for page "Work" - displays a grid of items ?>

<?php if( !isset($_GET['ajax']) ) : ?>

<?php get_header(); ?>

<?php include('menu.php'); ?>

<section id="pageWrapper" class="container clearfix">

	<?php include('navigation.php'); ?>
	
	<div id="initialContent">
		<?php generate_work_grid(); ?>

		<?php generate_work_menu(); ?>
	</div>

	<?php get_sidebar(); ?>
	
</section>

<?php get_footer(); ?>

<?php else : // load only the content for AJAX ?>
	<div id="content" class="work" data-title="<?php echo get_ajax_title(); ?>">

		<?php generate_work_grid(); ?>
	
		<div class="clearfix"></div>
	
		<?php generate_work_menu(); ?>

	</div>
<?php endif; ?>