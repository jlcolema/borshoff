<?php

add_theme_support( 'post-thumbnails' );
add_image_size( 'square_thumbnail', 240, 240, true );
add_image_size( 'large_featured', 1020, 730, true );


if (class_exists('MultiPostThumbnails')) {
	
	//thumbnails that are associated with every post type
		$types = array('clients','work','people');
		foreach($types as $type){
			new MultiPostThumbnails(array(
				'label' => 'Square Crop',
				'id' => 'square_crop',
				'post_type' => $type
			) );
		}
		
	//adds Featured Images 2-10 for work
		for($i = 2; $i < 11; $i++){
			new MultiPostThumbnails(array(
				'label' => 'Featured Image '.$i,
				'id' => 'featured_image_'.$i,
				'post_type' => 'work'
			) );
		}
		
	//adds Featured Images 2-10 for the homepage only
		global $post;
		//if( $post->ID == 11 ) {
			for($i = 2; $i < 11; $i++){
				new MultiPostThumbnails(array(
					'label' => 'Featured Image '.$i,
					'id' => 'featured_image_'.$i,
					'post_type' => 'page'
				) );
			}
		//}
}

// add a select box listing Clients to work post type
add_action( 'add_meta_boxes', 'add_client_select' );
add_action( 'save_post', 'save_client_select' );

function add_client_select() {
    add_meta_box( 
        'client_select',
        'Client',
        'print_client_select',
        'work' 
    );
}

function print_client_select() {
	global $post;
	wp_nonce_field( plugin_basename( __FILE__ ), 'client_select_noncename' );
	$currentClient = get_post_meta($post->ID,'client');
	$currentClient = $currentClient[0];
	$args = array(
		'numberposts'=>-1,
		'post_type'=>'clients',
		'orderby'=>'rand',
		'order'=>'ASC',
		'post_status'=>'publish'
	);
	$clients = get_posts($args);
	echo '<label>Select a Client: </label>&nbsp;&nbsp;&nbsp;';
	echo '<select id="client" name="client">';
	if( !isset($currentClient) || $currentClient == '' ) {
		echo '<option value="" selected="selected">No Client</option>';	
	} else {
		echo '<option value="">No Client</option>';		
	}
		foreach( $clients as $client ){
			$currentSelected = ( $currentClient == $client->ID ) ? ' selected' : '';
			echo '<option value="'.$client->ID.'"'.$currentSelected.'>'.$client->post_title.'</option>';	
		}
	echo '</select>';
}

function save_client_select($post_id) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( !wp_verify_nonce( $_POST['client_select_noncename'], plugin_basename( __FILE__ ) ) ) return;
	if ( !current_user_can( 'edit_post', $post->ID ) ) return;
	$client = $_POST['client'];
	update_post_meta($post_id,'client',$client);
}






###############################################################33
// Add Visibility Status to the Admin Manage Screens
###############################################################33
add_filter('manage_edit-work_columns', 'work_columns');
add_filter('manage_edit-people_columns', 'people_columns');
add_filter('manage_edit-clients_columns', 'clients_columns');
function work_columns($columns) {
    $columns['client'] = 'Client';
    $columns['visibility'] = 'Visible In Grid';
    $columns['linked'] = 'Linked in Grid';
    return $columns;
}
function people_columns($columns) {
    $columns['position'] = 'Position';
    $columns['visibility'] = 'Visible In Grid';
    $columns['linked'] = 'Linked in Grid';
    return $columns;
}
function clients_columns($columns) {
    $columns['visibility'] = 'Visible In Grid';
    $columns['linked'] = 'Linked in Grid';
    return $columns;
}


add_action('manage_posts_custom_column',  'column_data');
function column_data($name) {
    global $post;
	switch($name){
		case 'client':
			if( get_post_meta($post->ID, 'client', true) ){
				$data = get_the_title(get_post_meta($post->ID, 'client', true));
			} else {
				$data = '';
			}
			break;
		case 'visibility':
			$data = (get_post_meta($post->ID, 'isListedInGrid', true)) ? "Visible" : "Disabled";
			break;
		case 'linked':
			$data = (get_post_meta($post->ID, 'isLinkedInGrid', true)) ? "Linked" : "Disabled";
			break;
		case 'position':
			$numCategories = get_categories_count($post->ID,'employee_position');
			if( $numCategories > 0 ){
				$categoriesList = get_categories_list($post->ID,'employee_position');
				$data = $categoriesList[0];
			} else {
				$data = '';
			}
			break;
	}
	echo $data;
}


###############################################################33
// BEGIN Functions for content generation
###############################################################33
// GENERIC
function generate_submenu($title,$id,$args){
	echo '<h3>'.$title.'</h3>';
	$items = get_posts($args);
	echo '<ul>';
	foreach($items as $item):
		echo '<li id="'.$item->ID.'">'.$item->post_title.'</li>';
	endforeach;
	echo '</ul>';
}

function get_categories_count($id, $tax = 'service_category'){
	$categories = wp_get_object_terms( $id, $tax );
	return count($categories);
}

function get_categories_list($id, $tax = 'service_category'){
	$categories = wp_get_object_terms( $id, $tax );
	//get_taxonomies();
	$categoriesList = array();
	foreach($categories as $category){
		$categoriesList[] = $category->name;
	}
	return $categoriesList;
}

function get_excerpt($id){
	$thePost = get_post($id);
	print_r($thePost);
	$excerpt = $thePost->post_excerpt;
	if( $excerpt != '' ){
		return $excerpt;
	} else {
		return get_summary($thePost->post_content,10).'&#8230;';
	}
}

function get_summary($text,$length){
	$summary = explode(' ',$text);
	$summary = array_slice($summary,0,$length);
	$summary = implode(' ',$summary);
	return $summary;
}

function generate_page_slideshow($id){
	$preloader = "";
	echo '<div class="info">';
		echo '<ul class="slideshow">';
		$image_id = get_post_thumbnail_id($id);
		$image_url = wp_get_attachment_image_src($image_id,'large_featured'); 
		$image_url = $image_url[0];
			echo '<li style="background:url('.$image_url.'); height:730px;"></li>';
			if (class_exists('MultiPostThumbnails')) {
				$mpt_images = array(
					'2',
					'3',
					'4',
					'5',
					'6',
					'7',
					'8',
					'9',
					'10',
				);
			
				foreach ($mpt_images as $mpt_image) {
					if (MultiPostThumbnails::has_post_thumbnail('page', "featured_image_{$mpt_image}", $id)) {
						$post_thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id('page', "featured_image_{$mpt_image}", $id);
						echo '<li style="background:url('.wp_get_attachment_url($post_thumbnail_id).'); height:730px;"></li>';
						$preloader .= MultiPostThumbnails::get_the_post_thumbnail('page', "featured_image_{$mpt_image}", $id);
					}
				}
				echo '</ul>';
			echo '</div>';
			echo '<div class="preloader">' . $preloader . '</div>';
	}
	
}

//-----------------------------------
// WORK PAGE
function generate_work_grid($taxonomy = null, $category = null){
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
	$items = get_posts($args);
	echo '<section class="grid work">'.PHP_EOL;
	echo '<a id="slideGridLeftButton"></a>'.PHP_EOL;
	echo '<a id="slideGridRightButton"></a>'.PHP_EOL;
	echo '<div id="gridWrapper">'.PHP_EOL;
	$i=0;
	foreach($items as $item):
		if( get_post_meta($item->ID,'isListedInGrid',true) == 1 ){
			if( $i == 0 ) echo '<ul>'.PHP_EOL;
			echo '<li id="work_'.$item->ID.'">'.PHP_EOL;
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
			if( $i == 2 ) {
				echo '</ul>'.PHP_EOL;
				$i=0;
			} else {
				$i++;	
			}
		}
	endforeach;
	if($i != 0) echo '</ul>';
	echo '</div>'.PHP_EOL;
	echo '</section>'.PHP_EOL;
}

function get_the_client_name( $id ){
	$client = get_post($id);
	return $client->post_title;
}

function generate_work_info($id){
	echo '<div class="info">';
		echo '<ul class="slideshow">';
		$image_id = get_post_thumbnail_id();
		$image_url = wp_get_attachment_image_src($image_id,'large_featured'); 
		$image_url = $image_url[0];
			echo '<li style="background:url('.$image_url.'); height:730px; width:500px;"></li>';
			for($i=2; $i < 11; $i++){
				$imageName = 'featured_image_'.$i;
				if( MultiPostThumbnails::has_post_thumbnail('work', $imageName, $id) ){
					$image_id = MultiPostThumbnails::get_post_thumbnail_id('work',$imageName,$id);
					$image_url = wp_get_attachment_url($image_id);
					//$image_url = $image_url[0];
					echo '<li style="background:url('.$image_url.'); height:730px; width:500px;"></li>';
				}
			}
		echo '</ul>';
		
		/* create preload div */
		echo '<div class="preloader">';
		for($i=2; $i < 11; $i++){
			$imageName = 'featured_image_'.$i;
			if( MultiPostThumbnails::has_post_thumbnail('work', $imageName, $id) ){
				$image_id = MultiPostThumbnails::get_post_thumbnail_id('work',$imageName,$id);
				$image_url = wp_get_attachment_url($image_id);
				//$image_url = $image_url[0];
				echo '<img src="'.$image_url.'" />';
			}
		}
		echo '</div>';
		echo '<article class="scroll-pane">';
			echo '<a href="#" id="prevPhoto"></a>';
			echo '<span> 1 of 3 </span>';
			echo '<a href="#" id="nextPhoto"></a>';
			echo '<h3>Client</h3>';
			echo '<h2>'.get_the_client_name( get_post_meta($id,'client',true) ).'</h2>';
			echo '<h3>Project</h3>';
			echo '<h1>'.get_the_title().'</h1>';
			$numCategories = get_categories_count($id);
			if( $numCategories > 0 ){
				if( $numCategories == 1 ){
					echo '<h3>Service</h3>';
				} else {
					echo '<h3>Services</h3>';
				}
				$categoriesList = get_categories_list($id);
				echo '<p class="categories">'.implode(', ',$categoriesList).'</p>';
			}
			the_content();
			echo '<a href="'.get_permalink(164).'" class="closeLink">Close project</a>';
		echo '</article>';
	echo '</div>';
}
function generate_work_menu(){
	echo '<menu>';
		echo '<h3>All Work</h3>';
		$args = array(
			'numberposts'=>-1,
			'post_type'=>'work',
			'orderby'=>'rand',
			'order'=>'ASC',
			'post_status'=>'publish'
		);
		generate_submenu('Industry','industry',$args);
		echo '<h3>Service</h3>';
		echo '<h3>Client</h3>';
	echo '</menu>';
}
//-----------------------------------
// CLIENT PAGE
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
	echo '<a id="slideGridLeftButton"></a>';
	echo '<a id="slideGridRightButton"></a>';
	echo '<div id="gridWrapper">';
	$i=0;
	foreach($items as $item):
		if( get_post_meta($item->ID,'isListedInGrid',true) == 1 ){
			if( $i == 0 ) echo '<ul>'.PHP_EOL;
			echo '<li id="client_'.$item->ID.'">'.PHP_EOL;
				if( get_post_meta($item->ID,'isLinkedInGrid',true) == 1 ){
					echo '<a href="'.get_permalink($item->ID).'" class="grid_link">'.PHP_EOL;
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
					if( has_associated_work($item->ID) ) {
						echo '<h6 class="view_more_link">View Work &raquo;</h6>';
					}
				echo '</div>'.PHP_EOL;
				echo MultiPostThumbnails::get_the_post_thumbnail('clients', 'square_crop', $item->ID, 'square_thumbnail' ).PHP_EOL;
				echo '</a>'.PHP_EOL;
			echo '</li>'.PHP_EOL;
			if( $i == 2 ) {
				echo '</ul>'.PHP_EOL;
				$i=0;
			} else {
				$i++;	
			}
		}
	endforeach;
	if($i != 0) echo '</ul>';
	echo '</div>';
	echo '</section>';
}

function has_associated_work($id) {
	global $wpdb;
	$work = $wpdb->get_results("SELECT * FROM wp_postmeta WHERE meta_key='client' AND meta_value='$id'");
	if( $work ){
		return true;
	} else {
		return false;	
	}
}


function generate_client_menu(){
	
}
//-----------------------------------
// PEOPLE PAGE
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
	echo '<a id="slideGridLeftButton"></a>';
	echo '<a id="slideGridRightButton"></a>';
	echo '<div id="gridWrapper">';
	$i=0;
	foreach($items as $item):
		if( get_post_meta($item->ID,'isListedInGrid',true) == 1 ){
			if( $i == 0 ) echo '<ul>';
			echo '<li id="people_'.$item->ID.'">';
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
			if( $i == 2 ) {
				echo '</ul>';
				$i=0;
			} else {
				$i++;	
			}
		}
	endforeach;
	if($i != 0) echo '</ul>';
	echo '</div>';
	echo '</section>';
}


function generate_people_info($id){
	echo '<div class="info">';
		echo '<ul class="slideshow">';
		$image_id = get_post_thumbnail_id();
		$image_url = wp_get_attachment_image_src($image_id,'large_featured'); 
		$image_url = $image_url[0];
			echo '<li class="people' . $image_id . '" style="background:url('.$image_url.'); height:730px; width:500px;"></li>';
		echo '</ul>';
		echo '<article class="scroll-pane">';
			echo '<h3>'.get_the_title().'</h3>';
			$numCategories = get_categories_count(get_the_ID(),'employee_position');
			if( $numCategories > 0 ){
				$categoriesList = get_categories_list(get_the_ID(),'employee_position');
				echo '<p class="categories">';
				echo implode('<br />',$categoriesList);
				echo '</p>';
			}
			the_content();
			echo '<a href="'.get_permalink(166).'" class="closeLink">Close bio</a>';
		echo '</article>';
	echo '</div>';
}
//-----------------------------------
// INSIGHTS PAGE
function generate_insights_grid(){
	echo '<section class="grid insights">';
	echo '<a id="slideGridLeftButton"></a>';
	echo '<a id="slideGridRightButton"></a>';
	echo '<div id="gridWrapper">';
	
		//Twitter
		echo '<ul class="tweets">';
			echo '<li>';
				echo '<a href="'.get_permalink($item->ID).'">';
					echo '<h1>'.$item->post_title.'</h1>';
					echo '<p class="date">Service Cat, Service Cat2</p>';
					echo '<a href="">View Work &raquo;</a>';
				echo '</a>';
			echo '</li>';
		echo '</ul>';
		
		//Blog
		$args = array(
			'numberposts'=>3,
			'post_type'=>'post',
			'orderby'=>'post_date',
			'order'=>'DESC',
			'post_status'=>'publish',
			'category_name'=>'Blog'
		);
		$items = get_posts($args);
		$i=0;
		echo '<ul class="blog">';
			foreach($items as $item):
				echo '<li>';
					echo '<a href="'.get_permalink($item->ID).'">';
						echo '<h3>Blog</h3>';
						echo '<p class="date">'.mysql2date('m/d/Y', $item->post_date).'</p>';
						echo '<h2>'.$item->post_title.'</h3>';
						echo '<a href="" class="attribution">By</a>';
					echo '</a>';
				echo '</li>';
			endforeach;
		echo '</ul>';
		
		//Client Coverage
		$args = array(
			'numberposts'=>3,
			'post_type'=>'post',
			'orderby'=>'menu_order',
			'order'=>'ASC',
			'post_status'=>'publish',
			'category_name'=>'Client News'
		);
		$items = get_posts($args);
		$i=0;
		echo '<ul class="coverage">';
			foreach($items as $item):
				echo '<li>';
					echo '<a href="'.get_permalink($item->ID).'">';
						echo '<h3>Client News</h3>';
						echo '<p class="date">'.mysql2date('m/d/Y', $item->post_date).'</p>';
						echo '<h2>'.$item->post_title.'</h3>';
						echo '<a href="" class="attribution">By</a>';
					echo '</a>';
				echo '</li>';
			endforeach;
		echo '</ul>';
		
		//Borshoff News
		$args = array(
			'numberposts'=>3,
			'post_type'=>'post',
			'orderby'=>'menu_order',
			'order'=>'ASC',
			'post_status'=>'publish',
			'category_name'=>'Borshoff News'
		);
		$items = get_posts($args);
		$i=0;
		echo '<ul class="news">';
			foreach($items as $item):
				echo '<li>';
					echo '<a href="'.get_permalink($item->ID).'">';
						echo '<h3>Borshoff News</h3>';
						echo '<p class="date">'.mysql2date('m/d/Y', $item->post_date).'</p>';
						echo '<h2>'.$item->post_title.'</h3>';
						echo '<a href="" class="attribution">By</a>';
					echo '</a>';
				echo '</li>';
			endforeach;
		echo '</ul>';
		
		//Video
		$args = array(
			'numberposts'=>3,
			'post_type'=>'videos',
			'orderby'=>'post_date',
			'order'=>'DESC',
			'post_status'=>'publish'
		);
		$items = get_posts($args);
		$i=0;
		echo '<ul class="video">';
			foreach($items as $item):
				echo '<li>';
					echo get_the_post_thumbnail($item->ID);
					echo '<a href="'.get_permalink($item->ID).'">';
						echo '<h3>Videos</h3>';
						echo '<p class="date">'.mysql2date('m/d/Y', $item->post_date).'</p>';
						echo '<h2>'.$item->post_title.'</h3>';
						echo '<a href="" class="attribution">By</a>';
					echo '</a>';
				echo '</li>';
			endforeach;
		echo '</ul>';
	echo '</div>';
	echo '</section>';
}

function generate_insights_info(){
	
}
//-----------------------------------
// ABOUT PAGE
function generate_about_info(){
	
}
function generate_about_menu(){
	
}
//-----------------------------------
// CAREERS PAGE
function generate_careers_info(){
	
}
function generate_careers_menu(){
	
}


//-----------------------------------
// QUEUE JQUERY
function my_scripts_method() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', '/wp-content/themes/borshoff/js/jquery.min.js');
    wp_enqueue_script( 'jquery' );
}    
 
add_action('wp_enqueue_scripts', 'my_scripts_method');






/*
    Relative Time Function
    based on code from http://stackoverflow.com/questions/11/how-do-i-calculate-relative-time/501415#501415
    For use in the "Parse Twitter Feeds" code below
*/
define("SECOND", 1);
define("MINUTE", 60 * SECOND);
define("HOUR", 60 * MINUTE);
define("DAY", 24 * HOUR);
define("MONTH", 30 * DAY);
function relativeTime($time)
{
    $delta = strtotime('+2 hours') - $time;
    if ($delta < 2 * MINUTE) {
        return "1 min ago";
    }
    if ($delta < 45 * MINUTE) {
        return floor($delta / MINUTE) . " min ago";
    }
    if ($delta < 90 * MINUTE) {
        return "1 hour ago";
    }
    if ($delta < 24 * HOUR) {
        return floor($delta / HOUR) . " hours ago";
    }
    if ($delta < 48 * HOUR) {
        return "yesterday";
    }
    if ($delta < 30 * DAY) {
        return floor($delta / DAY) . " days ago";
    }
    if ($delta < 12 * MONTH) {
        $months = floor($delta / DAY / 30);
        return $months <= 1 ? "1 month ago" : $months . " months ago";
    } else {
        $years = floor($delta / DAY / 365);
        return $years <= 1 ? "1 year ago" : $years . " years ago";
    }
}



/*
    Parse Twitter Feeds
    based on code from http://spookyismy.name/old-entries/2009/1/25/latest-twitter-update-with-phprss-part-three.html
    and cache code from http://snipplr.com/view/8156/twitter-cache/
    and other cache code from http://wiki.kientran.com/doku.php?id=projects:twitterbadge
*/
function parse_cache_feed($usernames, $limit) {

error_reporting(E_ALL);
ini_set('display_errors', '1');
    $username_for_feed = str_replace(" ", "+OR+from%3A", $usernames);
    $feed = "http://search.twitter.com/search.atom?q=from%3A" . $username_for_feed . "&rpp=" . $limit;
    $usernames_for_file = str_replace(" ", "-", $usernames);
    $cache_file = dirname(__FILE__).'/wp-content/uploads/cache/' . $usernames_for_file . '-twitter-cache';
    $last = filemtime($cache_file);
    $now = time();
    $interval = 600; // ten minutes
    // check the cache file
    if ( !$last || (( $now - $last ) > $interval) ) {
        // cache file doesn't exist, or is old, so refresh it
        $cache_rss = file_get_contents($feed);
        if (!$cache_rss) {
            // we didn't get anything back from twitter
            echo "<!-- ERROR: Twitter feed was blank! Using cache file. -->";
        } else {
            // we got good results from twitter
            echo "<!-- SUCCESS: Twitter feed used to update cache file -->";
            $cache_static = fopen($cache_file, 'wb');
            fwrite($cache_static, serialize($cache_rss));
            fclose($cache_static);
        }
        // read from the cache file
        $rss = @unserialize(file_get_contents($cache_file));
    }
    else {
        // cache file is fresh enough, so read from it
        echo "<!-- SUCCESS: Cache file was recent enough to read from -->";
        $rss = @unserialize(file_get_contents($cache_file));
    }
    // clean up and output the twitter feed
    $feed = str_replace("&amp;", "&", $rss);
    $feed = str_replace("&lt;", "< ", $feed);
    $feed = str_replace("&gt;", ">", $feed);
    $clean = explode("<entry>", $feed);
    $amount = count($clean) - 1;
    if ($amount) { // are there any tweets?
	/*
        for ($i = 1; $i < = $amount; $i++) {
            $entry_close = explode("</entry>", $clean[$i]);
            $clean_content_1 = explode("<content type=\"html\">", $entry_close[0]);
            $clean_content = explode("</content>", $clean_content_1[1]);
            $clean_name_2 = explode("<name>", $entry_close[0]);
            $clean_name_1 = explode("(", $clean_name_2[1]);
            $clean_name = explode(")</name>", $clean_name_1[1]);
            $clean_user = explode(" (", $clean_name_2[1]);
            $clean_lower_user = strtolower($clean_user[0]);
            $clean_uri_1 = explode("<uri>", $entry_close[0]);
            $clean_uri = explode("</uri>", $clean_uri_1[1]);
            $clean_time_1 = explode("<published>", $entry_close[0]);
            $clean_time = explode("</published>", $clean_time_1[1]);
            $unix_time = strtotime($clean_time[0]);
            $pretty_time = relativeTime($unix_time);
			echo '<li>';
				echo '<p class="tweet">';
					echo $clean_content[0]; 
					echo '<small>' . $pretty_time . '</small>';
				echo '</p>';
			echo '</li>';
		}
		*/
	} else { // if there aren't any tweets
		echo '<li>';
			echo '<p class="tweet">no tweets</p>';
		echo '</li>';
	}
}

?>