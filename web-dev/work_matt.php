<?php 
/**
 *
 * Template Name: Work - Matt
 *
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');


function generate_work_grid2($taxonomy = null, $category = null){
	$args = array(
		'numberposts'=>-1,
		'post_type'=>'work',
		'orderby'=>'menu_order',
		'order'=>'ASC',
		'post_status'=>'publish'
	);
	if( !is_null($taxonomy) && !is_null($category) ){
		$args[tax_query] = array(
			'taxonomy' => $taxonomy,
			'field' => 'slug',
			'terms' => $category
		);
	}
	$items = get_posts($args);
	$i=0;
	$grid='';
	foreach($items as $item):
		if( $i == 0 ) 
		$grid .= '<ul>'.PHP_EOL;
		$grid .= '<li id="work_'.$item->ID.'">'.PHP_EOL;
			$grid .= '<a href="'.get_permalink($item->ID).'" class="grid_link">'.PHP_EOL;
			$grid .= '<div class="hover_info">'.PHP_EOL;
				$grid .= '<h2>'.get_the_title(get_post_meta($item->ID,'client',true)).'</h2>'.PHP_EOL;
				$grid .= '<h1>'.$item->post_title.'</h1>'.PHP_EOL;
				$grid .= '<p>Service Cat, Service Cat2</p>'.PHP_EOL;
			$grid .= '</div>'.PHP_EOL;
			$grid .= MultiPostThumbnails::get_the_post_thumbnail('work', 'square_crop', $item->ID, 'square_thumbnail' ).PHP_EOL;
			$grid .= '</a>'.PHP_EOL;
		$grid .= '</li>'.PHP_EOL;
		if( $i == 2 ) {
			$grid .= '</ul>'.PHP_EOL;
			$i=0;
		} else {
			$i++;	
		}
	endforeach;
	$grid .+ $grid.+ $grid;
	$grid .+ $grid.+ $grid;
	$grid = '<div id="gridWrapper">'.$grid.'</div>';
	echo $grid;
	echo '<script>var grid_3row = '.json_encode($grid).'</script>';
	
	
	$i=0;
	$grid2='';
	foreach($items as $item):
		if( $i == 0 ) 
		$grid2 .= '<ul>'.PHP_EOL;
		$grid2 .= '<li id="work_'.$item->ID.'">'.PHP_EOL;
			$grid2 .= '<a href="'.get_permalink($item->ID).'" class="grid_link">'.PHP_EOL;
			$grid2 .= '<div class="hover_info">'.PHP_EOL;
				$grid2 .= '<h2>'.get_the_title(get_post_meta($item->ID,'client',true)).'</h2>'.PHP_EOL;
				$grid2 .= '<h1>'.$item->post_title.'</h1>'.PHP_EOL;
				$grid2 .= '<p>Service Cat, Service Cat2</p>'.PHP_EOL;
			$grid2 .= '</div>'.PHP_EOL;
			$grid2 .= MultiPostThumbnails::get_the_post_thumbnail('work', 'square_crop', $item->ID, 'square_thumbnail' ).PHP_EOL;
			$grid2 .= '</a>'.PHP_EOL;
		$grid2 .= '</li>'.PHP_EOL;
		if( $i == 1 ) {
			$grid2 .= '</ul>'.PHP_EOL;
			$i=0;
		} else {
			$i++;	
		}
	endforeach;
	$grid2 .+ $grid2;
	$grid2 = '<div id="gridWrapper">'.$grid2.'</div>';
	echo '<script>var grid_2row = '.json_encode($grid2).'</script>';
}


?>


<?php //template for the work page - displays a grid of work ?>

<?php get_header(); ?>

<?php include('menu.php'); ?>

<script>

$(window).resize(function() {
	if ( $(window).height() < 730) {
		$('#gridWrapper').replaceWith(grid_2row);
	} else {
		$('#gridWrapper').replaceWith(grid_3row);
	}
});

</script>

<section id="content" class="container">

	<?php include('navigation.php'); ?>
	
	<section class="grid">
		<a id="slideGridLeftButton"></a>
		<a id="slideGridRightButton"></a>
		<?php generate_work_grid2(); ?>
	</section>

	<?php get_sidebar(); ?>

	<div class="clearfix"></div>

</section>

<?php get_footer(); ?>