<?php
//People-related stuff

###########################
// The Grid
###########################
function generate_people_grid(){
	$args = array(
		'numberposts'=>-1,
		'post_type'=>'people',
		'orderby'=>'rand',
		'order'=>'ASC',
		'post_status'=>'publish'
	);
	$items = get_posts($args);
	echo '<section class="grid people">';
	echo '<a class="slideGridLeftButton"></a>';
	echo '<a class="slideGridRightButton"></a>';
	echo '<ul id="gridWrapper">';
	foreach($items as $item):
		if( get_post_meta($item->ID,'isListedInGrid',true) == 1 ){
			$isLeadership = get_post_meta($item->ID,'isLeadership',true);
			$classes =  ( $isLeadership == 1 ) ? ' class="leadership"' : '';
			echo '<li id="people_'.$item->ID.'"'.$classes.'>';
				if( get_post_meta($item->ID,'isLinkedInGrid',true) == 1 ){
					echo '<a href="'.get_permalink($item->ID).'" class="grid_link">'.PHP_EOL;
				} else { 
					echo '<a href="#" class="grid_link">'.PHP_EOL;
				}
				echo '<div class="hover_info">';
					echo '<h1>'.$item->post_title.'</h1>';
					$numCategories = get_categories_count($item->ID,'employee_position');
					if( $numCategories > 0 ){
						$categoriesList = get_categories_list($item->ID,'employee_position');
						echo '<p>';
						echo implode('<br />',$categoriesList);
						echo '</p>';
					}
				echo '</div>';
				echo MultiPostThumbnails::get_the_post_thumbnail('people', 'square_crop', $item->ID, 'square_thumbnail' );
				echo '</a>';
			echo '</li>';
		}
	endforeach;
	echo '</ul>';
	echo '</section>';
}



###########################
// Single item content
###########################
function generate_people_info($id){
	echo '<div class="info">';
		echo '<div class="infoHover">';
			//$closeLink = ( isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],'borshoff.biz' && $_SERVER['HTTP_REFERER'] != get_permalink(11) ) ) ? $_SERVER['HTTP_REFERER'] : get_permalink(166);
			//this new version always points back to the main "work" page rather than using the referrer
			$closeLink = get_permalink(166);
			echo '<a href="'.$closeLink.'" class="closeLink"></a>';
		echo '</div>';
		echo '<ul class="slideshow">';
			$image_id = get_post_thumbnail_id();
			$image_url = wp_get_attachment_image_src($image_id,'large_featured');
			$image_url = $image_url[0];

			$imagePosition = parseImageAlignment(get_post_meta(get_the_ID(),'people_imageAlignment',true));
			$x = ( count($imagePosition) == 2 ) ? $imagePosition[0]: 'center';
			$y = ( count($imagePosition) == 2 ) ? $imagePosition[1]: 'center';
			
			$u_agent = $_SERVER['HTTP_USER_AGENT'];
			$ub = '';
			$ub = (preg_match('/MSIE/i',$u_agent)) ? "Internet Explorer" : '';
			$version = (preg_match('/MSIE 9/i',$u_agent)) ? '9' : '';
			$version = (preg_match('/MSIE 10/i',$u_agent)) ? '10' : $version;
			$version = (preg_match('/MSIE 11/i',$u_agent)) ? '11' : $version;

			$IE_bgStretch = '';		
			if( $ub == "Internet Explorer" && $version < 9 ){
				$IE_bgStretch = ' filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'.'.$image_url.'\', sizingMethod=\'scale\'); -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''.$image_url.'\', sizingMethod=\'scale\')";';
			}

			echo '<li class="people' . $image_id . '" style="background-image:url('.$image_url.'); background-position:'.$x.' '.$y.';'.$IE_bgStretch.'"></li>';
		echo '</ul>';
		echo '<article class="personInfo scroll-pane">';
			echo '<div class="personInfoWrapper">';
				echo '<h3>'.get_the_title().'</h3>';
				$numCategories = get_categories_count(get_the_ID(),'employee_position');
				if( $numCategories > 0 ){
					$categoriesList = get_categories_list(get_the_ID(),'employee_position');
					echo '<p class="categories">';
					echo implode('<br />',$categoriesList);
					echo '</p>';
				}
				the_content();
				echo '<!-- AddThis Button BEGIN -->';
				echo '<div class="addthis_toolbox addthis_default_style">';
				echo '<a href="http://www.addthis.com/bookmark.php?v=250" class="addthis_button_compact shareLink" addthis:url="'.get_permalink().'"
       addthis:title="'.get_the_title().'" 
       addthis:description="'.get_excerpt(get_the_id()).'"></a>';
				echo '</div>';
				echo '<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js?domready=1"></script>';
				echo '<!-- AddThis Button END -->';
			echo '</div>';
		echo '</article>';
	echo '</div>';
}





###########################
// Menu for filtering
###########################
function generate_people_menu(){
	echo '<ul class="filter sf-menu isotopeFilterMenu">';
		// echo '<li class="no-arrow pad-top">VIEW</li>';
		// echo '<li class="no-arrow pad-top">|</li>';
		echo '<li class="no-arrow"><a href="'.get_permalink(166).'" class="isotopeFilterItem" data-filter="*">All People</a></li>';
		if ( is_single() ) {
			$href = get_permalink(166).'?principals=leadership';
		} else {
			$href = '';
		}
		echo '<li class="no-arrow"><a href="'.$href.'" class="isotopeFilterItem" data-filter=".leadership">Principals</a></li>';
	echo '</ul>';
}


?>