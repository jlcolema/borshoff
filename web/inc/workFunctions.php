<?php
//Work-related stuff

###########################
// Utility Functions
###########################
function get_the_client_info( $id, $key = 'post_title' ){
	$client = get_post($id);
	return $client->$key;
}

function industry_has_associated_work($taxonomy,$term){
			$args = array(
			'numberposts'=>-1,
			'post_type'=>'clients',
			'post_status'=>'publish',
			'tax_query' => array(
				array(
				  'taxonomy' => $taxonomy,
				  'field' => 'slug',
				  'terms' => $term->slug // Where term_id of Term 1 is "1".
				)
			)
		);
	//print_r($args);
	$posts = get_posts($args);
	foreach($posts as $thePost){
		if( has_associated_work($thePost->ID) ){
			return true;
		}
	}
	return false;
}



###########################
// The Grid
###########################
function generate_work_grid($taxonomy = null, $category = null, $customGrid = null){
	$args = array(
		'numberposts'=>-1,
		'post_type'=>'work',
		'orderby'=>'rand',
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
	if( $customGrid ){
		$args['include'] = $customGrid;
	}
	$items = get_posts($args);
	echo '<section class="grid work">'.PHP_EOL;
	echo '<a class="slideGridLeftButton"></a>'.PHP_EOL;
	echo '<a class="slideGridRightButton"></a>'.PHP_EOL;
	echo '<ul id="gridWrapper">'.PHP_EOL;
	foreach($items as $item):
	
		$serviceFilterNames = get_categories_list($item->ID,'service_category','slug');
		$industryFilterNames = get_categories_list($item->ID,'industry_category','slug');
		$clientID = get_post_meta($item->ID,'client',true);
		$clientIndustryFilterNames = get_categories_list($clientID,'industry_category','slug');
		$clientPost = get_post($clientID);
		$clientName = $clientPost->post_name;
		$clientFilterNames = array($clientName);
		$filterItems = array_merge($serviceFilterNames,$industryFilterNames,$clientIndustryFilterNames,$clientFilterNames);
	
		if( get_post_meta($item->ID,'isListedInGrid',true) == 1 || !empty($customGrid) ){
			echo '<li id="work_'.$item->ID.'" class="'.implode(' ',$filterItems).'">'.PHP_EOL;
				if( get_post_meta($item->ID,'isLinkedInGrid',true) == 1 ){
					echo '<a href="'.get_permalink($item->ID).'" class="grid_link">'.PHP_EOL;
				} else { 
					echo '<a href="#" class="grid_link">'.PHP_EOL;
				}
				echo '<div class="hover_info">'.PHP_EOL;
					echo '<h1>'.$item->post_title.'</h1>'.PHP_EOL;
					if( get_post_meta($item->ID,'client',true) ){
						echo '<h2>'.get_the_title(get_post_meta($item->ID,'client',true)).'</h2>'.PHP_EOL;
					}
					/*
					$numCategories = get_categories_count($item->ID);
					if( $numCategories > 0 ){
						$categoriesList = get_categories_list($item->ID);
						echo '<p class="categories">'.implode('<br/>',$categoriesList).'</p>';
					}
					*/
				echo '</div>'.PHP_EOL;
				echo MultiPostThumbnails::get_the_post_thumbnail('work', 'square_crop', $item->ID, 'square_thumbnail' ).PHP_EOL;
				echo '</a>'.PHP_EOL;
			echo '</li>'.PHP_EOL;
		}
	endforeach;
	echo '</ul>'.PHP_EOL;
	echo '</section>'.PHP_EOL;
}



###########################
// Single item content
###########################
function generate_work_info($id){
	$counter = 0;
	
	$imageCount = 0;
	if( has_post_thumbnail() ) $imageCount++;
	for($i=2; $i < 11; $i++){
		$imageName = 'featured_image_'.$i;
		if( MultiPostThumbnails::has_post_thumbnail('work', $imageName, $id) ){
			$imageCount++;
		}
	}
	$videoArray = json_decode(get_post_meta($id, 'vimeo_link', true));
	if( !is_array($videoArray) ) $videoArray = array($videoArray); // uses an array structure if there is only one video
	if ( !empty($videoArray) && $videoArray[0] != '' ) {
		foreach($videoArray as $video){
			$imageCount++;
		}
	}
	
	echo '<div class="info">';
		echo '<div class="infoHover">';
			//$closeLink = ( isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],'borshoff.biz') && $_SERVER['HTTP_REFERER'] != get_permalink(11) ) ? $_SERVER['HTTP_REFERER'] : get_permalink(164);
			//this new version always points back to the main "work" page rather than using the referrer
			$closeLink = get_permalink(164);
			echo '<a href="'.$closeLink.'" class="closeLink"></a>';
			if( $imageCount > 1 ){
				echo '<a class="slideWorkLeftButton"></a>'.PHP_EOL;
				echo '<a class="slideWorkRightButton"></a>'.PHP_EOL;
			}
		echo '</div>';
		echo '<ul class="slideshow">';
		
		if( has_post_thumbnail() ){
		
			$image_id = get_post_thumbnail_id();
			$image_metadata = get_posts(array('p' => $image_id, 'post_type' => 'attachment'));
			$firstImageClient = $image_metadata[0]->post_excerpt;
			$firstImageServices = $image_metadata[0]->post_content;
			$image_url = wp_get_attachment_image_src($image_id,'large_featured'); 
			$image_url = $image_url[0];
			
			$imagePosition = parseImageAlignment(get_post_meta(get_the_ID(),'work_imageAlignment_1',true));
			$x = ( count($imagePosition) == 2 ) ? $imagePosition[0]: 'center';
			$y = ( count($imagePosition) == 2 ) ? $imagePosition[1]: 'center';
			
			$u_agent = $_SERVER['HTTP_USER_AGENT'];
			$ub = '';
			$ub = (preg_match('/MSIE/i',$u_agent)) ? "Internet Explorer" : '';
			$version = (preg_match('/MSIE 9/i',$u_agent)) ? '9' : '';
			$version = (preg_match('/MSIE 10/i',$u_agent)) ? '10' : $version;
			$version = (preg_match('/MSIE 11/i',$u_agent)) ? '11' : $version;
			
			$IE_bgStretch = '';		
			if( $ub == "Internet Explorer" && $version < 8){
				$IE_bgStretch = ' filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'.'.$image_url.'\', sizingMethod=\'scale\'); -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''.$image_url.'\', sizingMethod=\'scale\')";';
			}
			
				echo '<li data-caption="'.$image_metadata[0]->post_excerpt.'" data-description="'.$image_metadata[0]->post_content.'" style="background-image:url('.$image_url.'); background-position:'.$x.' '.$y.';'.$IE_bgStretch.'"></li>';
				$counter++;
				
		}
			for($i=2; $i < 11; $i++){
				$imageName = 'featured_image_'.$i;
				if( MultiPostThumbnails::has_post_thumbnail('work', $imageName, $id) ){
					$image_id = MultiPostThumbnails::get_post_thumbnail_id('work',$imageName,$id);
					$image_metadata = get_posts(array('p' => $image_id, 'post_type' => 'attachment'));
					$image_url = wp_get_attachment_url($image_id, 'large_featured');
										
					$imagePosition = parseImageAlignment(get_post_meta(get_the_ID(),'work_imageAlignment_'.$i,true));
					$x = ( count($imagePosition) == 2 ) ? $imagePosition[0]: 'center';
					$y = ( count($imagePosition) == 2 ) ? $imagePosition[1]: 'center';
					
					if( $ub == "Internet Explorer" && $version < 8 ){
						$IE_bgStretch = ' filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'.'.$image_url.'\', sizingMethod=\'scale\'); -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''.$image_url.'\', sizingMethod=\'scale\')";';
					}
					
					if ( $image_url != '' ) {
						$counter++;
						//$image_url = $image_url[0];
						echo '<li data-caption="'.$image_metadata[0]->post_excerpt.'" data-description="'.$image_metadata[0]->post_content.'" style="background-image:url('.$image_url.'); background-position:'.$x.' '.$y.';'.$IE_bgStretch.'"></li>';
					}
				}
			}
			// assemble vimeo link
			$videoArray = json_decode(get_post_meta($id, 'vimeo_link', true));
			if( !is_array($videoArray) ) $videoArray = array($videoArray); // uses an array structure if there is only one video
			if ( !empty($videoArray) && $videoArray[0] != '' ) {
				//echo '<li>' . get_post_meta($id, 'vimeo_link', true) . '</li>';
				foreach($videoArray as $video){
					echo '<li><iframe src="http://player.vimeo.com/video/'.$video.'?title=0&byline=0&portrait=0&color=9dc8ba" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></li>'.PHP_EOL;
					$counter++;
				}
			}
		echo '</ul>';
		
		/* create preload div */
//		echo '<div class="preloader">';
//		for($i=2; $i < 11; $i++){
//			$imageName = 'featured_image_'.$i;
//			if( MultiPostThumbnails::has_post_thumbnail('work', $imageName, $id) ){
//				$image_id = MultiPostThumbnails::get_post_thumbnail_id('work',$imageName,$id);
//				$image_url = wp_get_attachment_url($image_id);
//				//$image_url = $image_url[0];
//				echo '<img src="'.$image_url.'" />';
//			}
//		}
//		echo '</div>';
		echo '<article class="scroll-pane">';
			echo '<div class="workDetailsWrapper">';
				if( $counter > 2 ){
					echo '<a href="#" id="prevPhoto"></a>';
				} else {
					echo '<a class="hidden" href="#" id="prevPhoto"></a>';
				}
				echo '<span> 1 of ' . $counter . ' </span>';
				echo '<div class="pager"></div>';
				if( $counter > 2 ){
				echo '<a href="#" id="nextPhoto"></a>';
				} else {
					echo '<a class="hidden" href="#" id="nextPhoto"></a>';
				}
				echo '<h3>Client</h3>';
				echo '<h2><a href="'.get_permalink(164).'?client='.get_the_client_info(get_post_meta($id,'client',true),'post_name').'">'.get_the_client_info( get_post_meta($id,'client',true) ).'</a></h2>';
				echo '<h3>Project</h3>';
				echo '<h1>'.get_the_title().'</h1>';
//				if( !empty($firstImageClient) ){
//					echo '<h2>'.$firstImageClient.'</h2>';
//					echo '<h3>Services</h3>';
//					echo '<h1>'.$firstImageServices.'</h1>';
//				} else {
//				}
	//			$numCategories = get_categories_count($id);
	//			if( $numCategories > 0 ){
	//				if( $numCategories == 1 ){
	//					echo '<h3>Service</h3>';
	//				} else {
	//					echo '<h3>Services</h3>';
	//				}
	//				$categoriesList = get_categories_list($id);
	//				echo '<p class="categories">'.implode(', ',$categoriesList).'</p>';
	//			}
				the_content();
				// echo '<a href="'.get_permalink(164).'" class="shareLink">Share</a>';
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
function generate_work_menu(){
	echo '<ul class="filter sf-menu isotopeFilterMenu">';
		// echo '<li class="no-arrow pad-top">VIEW</li>';
		// echo '<li class="no-arrow pad-top">|</li>';
		echo '<li class="no-arrow"><a href="'.get_permalink(164).'" class="isotopeFilterItem" data-filter="*">All Work</a></li>';
		echo '<li><a href="#">Industry</a>';
			echo '<ul>';
				$taxonomy = 'industry_category';
				$terms = get_terms($taxonomy);
				foreach($terms as $term){
					if( strtolower(trim($term->description)) != 'hidden' && industry_has_associated_work($taxonomy,$term) ){
						if ( is_single() ) {
							$href = get_permalink(164).'?industry='.$term->slug;
						} else {
							$href = '';
						}
						echo '<li><a href="'.$href.'" class="isotopeFilterItem" data-filter=".'.$term->slug.'">'.$term->name.'</a></li>';
					}
				}
			echo '</ul>';
		echo '</li>';
		echo '<li><a href="#">Service</a>';
			echo '<ul>';
				$terms = get_terms('service_category');
				foreach($terms as $term){
					if( strtolower(trim($term->description)) != 'hidden' ){
						if ( is_single() ) {
							$href = get_permalink(164).'?service='.$term->slug;
						} else {
							$href = '';
						}
						echo '<li><a href="'.$href.'" class="isotopeFilterItem" data-filter=".'.$term->slug.'">'.$term->name.'</a></li>';
					}
				}
			echo '</ul>';
		echo '</li>';
// clients are now hidden from the menu
//		echo '<li><a href="#">Client</a>';
//			echo '<ul>';
//				$args = array(
//					'numberposts'=>-1,
//					'post_type'=>'clients',
//					'post_status'=>'publish',
//					'orderby'=>'title',
//					'order'=>'ASC'
//				);
//				$clients = get_posts($args);
//				foreach($clients as $client){
//					if( get_post_meta($client->ID,'isIncludedInDropdown',true) && has_associated_work($client->ID) ){
//						if ( is_single() ) {
//							$href = get_permalink(164).'?client='.$client->post_name;
//						} else {
//							$href = '';
//						}
//						echo '<li><a href="'.$href.'" class="isotopeFilterItem" data-filter=".'.$client->post_name.'">'.$client->post_title.'</a></li>';
//					}
//				}
//			echo '</ul>';
//		echo '</li>';
	echo '</ul>';
}


?>