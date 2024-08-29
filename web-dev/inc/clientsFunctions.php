<?php
//Clients-related stuff

###########################
// The Grid
###########################
function generate_client_grid(){
	$args = array(
		'numberposts'=>-1,
		'post_type'=>'clients',
		'orderby'=>'rand',
		'order'=>'ASC',
		'post_status'=>'publish'
	);
	$items = get_posts($args);
	echo '<section class="grid clients">';
	echo '<a class="slideGridLeftButton"></a>';
	echo '<a class="slideGridRightButton"></a>';
	echo '<ul id="gridWrapper">';
	foreach($items as $item):
		if( get_post_meta($item->ID,'isListedInGrid',true) == 1 ){
			echo '<li id="client_'.$item->ID.'">'.PHP_EOL;
				if( get_post_meta($item->ID,'isLinkedInGrid',true) == 1 && has_associated_work($item->ID) ){
					echo '<a href="'.get_permalink(164).'?client='.get_the_client_info($item->ID,'post_name').'" class="grid_link">'.PHP_EOL;
				} else { 
					echo '<a href="#" class="grid_link">'.PHP_EOL;
				}
				echo '<div class="hover_info">'.PHP_EOL;
					echo '<h1>'.$item->post_title.'</h1>'.PHP_EOL;
					$numCategories = get_categories_count($item->ID);
					if( $numCategories > 0 ){
						$categoriesList = get_categories_list($item->ID);
						echo '<p class="categories">'.implode('<br/>',$categoriesList).'</p>';
					}
					if( get_post_meta($item->ID,'isLinkedInGrid',true) == 1 && has_associated_work($item->ID) ) {
						echo '<h6 class="view_more_link">View Work &raquo;</h6>';
					}
				echo '</div>'.PHP_EOL;
				echo MultiPostThumbnails::get_the_post_thumbnail('clients', 'square_crop', $item->ID, 'square_thumbnail' ).PHP_EOL;
				echo '</a>'.PHP_EOL;
			echo '</li>'.PHP_EOL;
		}
	endforeach;
	echo '</ul>';
	echo '</section>';
}

###########################
// Menu for filtering
###########################
function generate_client_menu(){
	
}


?>