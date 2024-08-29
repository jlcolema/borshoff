<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers 3.0 
 * 
 * Template Name: Hidden Page
 */

get_header(); ?>

<?php get_header(); ?>

<?php include('menu.php'); ?>

<section id="content" class="container blog blogWrapper">

	<?php include('navigation.php'); ?>
	
<div class="info">	
	
	<div class="blogContent">
		<?php while( have_posts() ) : the_post(); ?>
			<div id="blogHeader" class="hiddenTemplate"><div class="blogContentIndent"><?php the_title(); ?></div></div>
			<div class="blogContentIndent blogPost">
				<?php the_content(); ?>
			</div>
		<?php endwhile; ?>
	</div>
	
	</div>

	<?php get_sidebar(); ?>
	
	<div class="clearfix"></div>

</section>

<?php get_footer(); ?>