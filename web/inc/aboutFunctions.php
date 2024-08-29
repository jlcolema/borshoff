<?php
//Work-related stuff


function generate_about_info(){
	
}

###########################
// Menu for filtering
###########################
function generate_about_menu(){
	echo '<ul class="filter sf-menu">';
		// echo '<li class="no-arrow pad-top">ABOUT</li>';
		// echo '<li class="no-arrow pad-top">|</li>';
		echo '<li class="subpageMenu"><a href="#">Who We Are</a>';
			echo '<ul class="subpageMenuList about">';
				$args = array(
					'child_of'=>3499,
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
				echo '</div>';
				//echo '<a href="'.get_permalink(164).'" class="workLink">View Work &raquo;</a>';
			echo '</div>';
		echo '</li>';
		echo '<li class="subpageMenu"><a href="#">What We Do</a>';
			echo '<ul class="subpageMenuList">';
				$args = array(
					'child_of'=>3505,
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
				echo '</div>';
				//echo '<a href="'.get_permalink(164).'" class="workLink">View Work &raquo;</a>';
			echo '</div>';
		echo '</li>';
	echo '</ul>';
}

?>