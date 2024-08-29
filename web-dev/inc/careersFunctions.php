<?php
//Work-related stuff


function generate_careers_info(){
	
}


###########################
// Menu for filtering
###########################
function generate_careers_menu(){
	echo '<ul class="filter sf-menu">';
		// echo '<li class="no-arrow pad-top">CAREERS</li>';
		// echo '<li class="no-arrow pad-top">|</li>';
		echo '<li class="subpageMenu"><a href="#">Opportunities</a>';
			echo '<ul class="subpageMenuList careers">';
				$args = array(
					'child_of'=>18,
					'post_status'=>'publish',
					'sort_column'=>'menu_order'
				);
				$items = get_pages($args);
				foreach($items as $item){
					echo '<li id="flyout_'.rawurlencode(strtolower(str_replace(' ','-',$item->post_name))).'"><a href="'.get_permalink($item->ID).'" class="infoItem">'.$item->post_title.'</a><div class="pageInfo">'.apply_filters('the_content',$item->post_content).'</div></li>';
				}
			echo '</ul>';
			echo '<div class="pageInfoContainer"><div class="pageInfoClose"></div>';
				echo '<div class="pageInfoWrapper">';
				echo '<h1></h1>';
				echo '<p></p>';
				//echo '<a href="#" class="workLink">Apply Now &raquo;</a>';
				//echo '<article class="scroll-pane">';
				//echo '</article>';
				echo '</div>';
			echo '</div>';
		echo '</li>';
	echo '</ul>';
}


?>