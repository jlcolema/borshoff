<?php get_header(); ?>

<?php
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
	$taxonomy = $term['taxonomy'];
	$category = $term['slug'];
?>

<?php generate_work_grid( $taxonomy, $category ); ?>

<?php get_footer(); ?>