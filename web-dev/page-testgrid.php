<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="<?php bloginfo('template_directory'); ?>/styleIsotopeWorking.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/css/isotope.css">
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.isotope.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		
		$('#gridWrapper').isotope({
			layoutMode:'fitColumns'
		});
	});
</script>
</head>

<body>
<?php
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
echo '<ul id="gridWrapper">'.PHP_EOL;
foreach($items as $item):
	echo '<li id="work_'.$item->ID.'" class="element">'.PHP_EOL;
		echo '<a href="'.get_permalink($item->ID).'" class="grid_link">'.PHP_EOL;
		echo '<div class="hover_info">'.PHP_EOL;
			echo '<h2>'.get_the_title(get_post_meta($item->ID,'client',true)).'</h2>'.PHP_EOL;
			echo '<h1>'.$item->post_title.'</h1>'.PHP_EOL;
			$numCategories = get_categories_count($item->ID);
			if( $numCategories > 0 ){
				$categoriesList = get_categories_list($item->ID);
				echo '<p class="categories">'.implode('<br/>',$categoriesList).'</p>';
			}
		echo '</div>'.PHP_EOL;
		echo MultiPostThumbnails::get_the_post_thumbnail('work', 'square_crop', $item->ID, 'square_thumbnail' ).PHP_EOL;
		echo '</a>'.PHP_EOL;
	echo '</li>'.PHP_EOL;
endforeach;
echo '</ul>'.PHP_EOL;
?>
</body>
</html>
