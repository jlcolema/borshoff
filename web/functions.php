<?php

###############################################################33
// add various image sizes
###############################################################33
add_theme_support( 'post-thumbnails' );
add_image_size( 'square_thumbnail', 240, 240, true );
add_image_size( 'retina_square_thumbnail', 480, 480, true );
add_image_size( 'large_featured', 1020, 730, true );
add_image_size( 'medium_featured', 640, 458, true );
add_image_size( 'mobile_feature', 640, 340, true );


###############################################################33
// add various image sizes
###############################################################33
add_theme_support( 'menus' );


###############################################################33
// Setup Multiple post thumbnails for specific pages
###############################################################33
if (class_exists('MultiPostThumbnails')) {
	
	//thumbnails that are associated with every post type
		$types = array('clients','work','people','post');
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



###############################################################33
// add a select box listing Clients to work post type
###############################################################33
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
		'orderby'=>'title',
		'order'=>'ASC',
		'post_status'=>'publish'
	);
	$clients = get_posts($args);
	echo '<label>Select a Client: </label>&nbsp;&nbsp;&nbsp;';
	echo '<select id="client" name="client" style="width:100%">';
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
	global $wpdb;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( !wp_verify_nonce( $_POST['client_select_noncename'], plugin_basename( __FILE__ ) ) ) return;
	if ( !current_user_can( 'edit_post', $post->ID ) ) return;
	$client = $_POST['client'];
	update_post_meta($post_id,'client',$client);
	if($client != '' && $client != '-1'){
		update_post_meta($post_id,'client',$client);
	}
}



###############################################################33
// add a select box listing People to default post type
###############################################################33
add_action( 'add_meta_boxes', 'add_person_select' );
add_action( 'save_post', 'save_person_select' );

function add_person_select() {
    add_meta_box( 
        'person_select',
        'Choose a Borshoff Employee Author',
        'print_person_select',
        'post' 
    );
}

function print_person_select() {
	global $post;
	wp_nonce_field( plugin_basename( __FILE__ ), 'person_select_noncename' );
	$currentPerson = get_post_meta($post->ID,'person');
	$currentPerson = $currentPerson[0];
	$args = array(
		'numberposts'=>-1,
		'post_type'=>'people',
		'orderby'=>'title',
		'order'=>'ASC',
		'post_status'=>'publish'
	);
	$people = get_posts($args);
	echo '<label>Select an Author: </label>&nbsp;&nbsp;&nbsp;';
	echo '<select id="person" name="person" style="width:100%">';
	if( !isset($currentPerson) || $currentPerson == '' ) {
		echo '<option value="-1" selected="selected">No Author (Borshoff)</option>';	
	} else {
		echo '<option value="-1">No Author (Borshoff)</option>';		
	}
		foreach( $people as $person ){
			$currentSelected = ( $currentPerson == $person->ID ) ? ' selected' : '';
			echo '<option value="'.$person->ID.'"'.$currentSelected.'>'.$person->post_title.'</option>';	
		}
	echo '</select>';
}

function save_person_select($post_id) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( !wp_verify_nonce( $_POST['person_select_noncename'], plugin_basename( __FILE__ ) ) ) return;
	if ( !current_user_can( 'edit_post', $post->ID ) ) return;
	$person = $_POST['person'];
	update_post_meta($post_id,'person',$person);
	if($person != '' && $person != '-1'){
		update_post_meta($post_id,'person_name',get_the_title($person));
	}
}





###############################################################33
// Add Additional detials to the Admin Manage Screens
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
// Sitewide generic content generation utilitiy functions
###############################################################33
function get_categories_count($id, $tax = 'service_category'){
	$categories = wp_get_object_terms( $id, $tax );
	return count($categories);
}

function get_categories_list($id, $tax = 'service_category', $property = 'name'){
	$categories = wp_get_object_terms( $id, $tax );
	//get_taxonomies();
	$categoriesList = array();
	foreach($categories as $category){
		$categoriesList[] = $category->$property;
	}
	return $categoriesList;
}

function get_excerpt($id){
	$thePost = get_post($id);
	$excerpt = $thePost->post_excerpt;
	if( $excerpt != '' ){
		return $excerpt;
	} else {
		$summary = get_summary($thePost->post_content,10);
		if( $summary != '' ){
			return strip_tags($summary).'&#8230;';
		} else {
			return '';	
		}
	}
}

function get_summary($text,$length){
	$summary = explode(' ',$text);
	$summary = array_slice($summary,0,$length);
	$summary = implode(' ',$summary);
	return $summary;
}

function has_associated_work($id) {
	global $wpdb;
	$work = $wpdb->get_results("SELECT * FROM {$wpdb->base_prefix}postmeta WHERE meta_key='client' AND meta_value='$id'");
	if( count($work) > 0 ){
		foreach( $work as $workPost ){
			if( get_post_status($workPost->post_id) == 'publish' && get_post_meta($workPost->post_id,'isListedInGrid',true) ){
				return true;
			}
		}
		return false;
	} else {
		return false;	
	}
}

function generate_item_info_menu_heading($heading = ''){
	echo '<ul class="filter sf-menu info-heading">';
	echo '<li><a href="#">'.$heading.'</a></li>';
	echo '</ul>';
}

function generate_page_slideshow($id){
	$preloader = "";
	echo '<div class="info">';
		echo '<ul class="slideshow">';
		$image_id = get_post_thumbnail_id($id);
		$image_url = wp_get_attachment_image_src($image_id,'large_featured'); 
		$image_url = $image_url[0];
		
		$imagePosition = parseImageAlignment(get_post_meta($id,'work_imageAlignment_1',true));
		$x = ( count($imagePosition) == 2 ) ? $imagePosition[0]: 'center';
		$y = ( count($imagePosition) == 2 ) ? $imagePosition[1]: 'center';


        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $ub = '';
        if(preg_match('/MSIE/i',$u_agent))
        {
            $ub = "Internet Explorer";
        }
		
		$IE_bgStretch = '';		
		if( $ub == "Internet Explorer" ){
			$IE_bgStretch = ' filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'.'.$image_url.'\', sizingMethod=\'scale\'); -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''.$image_url.'\', sizingMethod=\'scale\')";';
		}

			echo '<li style="background-image:url('.$image_url.'); background-position:'.$x.' '.$y.';'.$IE_bgStretch.'"></li>';
			if (class_exists('MultiPostThumbnails')) {
				
				for($i = 2; $i < 11; $i++){
					if (MultiPostThumbnails::has_post_thumbnail('page', "featured_image_{$i}", $id)) {
						$post_thumbnail_id = MultiPostThumbnails::get_post_thumbnail_id('page', "featured_image_{$i}", $id);
						$image_url = wp_get_attachment_url($post_thumbnail_id,'large_featured');
						
						$imagePosition = parseImageAlignment(get_post_meta($id,'work_imageAlignment_'.$i,true));
						$x = ( count($imagePosition) == 2 ) ? $imagePosition[0]: 'center';
						$y = ( count($imagePosition) == 2 ) ? $imagePosition[1]: 'center';
						
						if( $ub == "Internet Explorer" ){
							$IE_bgStretch = ' filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'.'.$image_url.'\', sizingMethod=\'scale\'); -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''.$image_url.'\', sizingMethod=\'scale\')";';
						}

						echo '<li style="background-image:url('.$image_url.'); background-position:'.$x.' '.$y.';'.$IE_bgStretch.'"></li>';
						//$preloader .= MultiPostThumbnails::get_the_post_thumbnail('page', "featured_image_{$i}", $id);
					}
				}
				
				echo '</ul>';
			echo '</div>';
			//echo '<div class="preloader">' . $preloader . '</div>';
	}
	
}


function generate_home_slideshow(){
	$preloader = "";
	echo '<div class="info">';

	$customFields = get_post_custom(3923);
		echo '<div id="textWrapper">';
			echo '<div><span class="dyntextval">'.$customFields['homeslide_headline'][0].'</span></div>';
			echo '<p>'.$customFields['homeslide_message'][0].'</p>';
		echo '</div>';

		echo '<ul class="slideshow">';
				
		$args = array(
			'numberposts'=>-1,
			'post_type'=>'homeslides',
			'orderby'=>'post_date',
			'order'=>'ASC',
			'post_status'=>'publish'
		);
		$items = get_posts($args);
				
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $ub = '';
        if(preg_match('/MSIE/i',$u_agent))
        {
            $ub = "Internet Explorer";
        }
		
		foreach($items as $item):
			$imagePosition = parseImageAlignment(get_post_meta($item->ID,'homeslide_imageAlignment',true));
			$x = ( count($imagePosition) == 2 ) ? $imagePosition[0]: 'center';
			$y = ( count($imagePosition) == 2 ) ? $imagePosition[1]: 'center';
			
			$image_url = wp_get_attachment_url(get_post_thumbnail_id($item->ID),'large_featured');
			
			$IE_bgStretch = '';
			
			if( $ub == "Internet Explorer" ){
				$IE_bgStretch = ' filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'.'.$image_url.'\', sizingMethod=\'scale\'); -ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''.$image_url.'\', sizingMethod=\'scale\')";';
			}
			
			echo '<li style="background-image:url('.$image_url.'); background-position:'.$x.' '.$y.';'.$IE_bgStretch.'" id="'.$item->ID.'">';
			echo '</li>';
			//$preloader .= get_the_post_thumbnail($item->ID);

		endforeach;

		echo '</ul>';
	echo '</div>';
	//echo '<div class="preloader">' . $preloader . '</div>';
	
}


function parseImageAlignment($alignment){
	$array = explode('_',$alignment);
	return $array;	
}


// ######################################################
// Grab the highest parent based on a provided id
// ######################################################
function get_topmost_ancestor($id){
	$ancestors = get_post_ancestors($id);
	if(!empty($ancestors)){
		$ancestors = array_reverse($ancestors);
		return $ancestors[0];
	} else {
		return $id;	
	}
}



// ######################################################
// Contact form shortcode
// ######################################################
add_shortcode("contactform", "contactform");

function contactform( $atts, $content = null ){ // shortcode
	return '<a class="contactFormLink" href="#">'.$content.'</a>';
}



###############################################################33
// Grab the title for ajax pages
###############################################################33
function get_ajax_title(){
	global $post;
	if( get_post_type() == 'work' ){
		$title = get_the_title(get_post_meta($post->ID,'client',true)) . ' | ' . wp_title('',false);
		return $title;
	} elseif( is_search() ) {
		return 'Search for ' . $title .' | '. get_bloginfo('name');
	} else {
		$title = trim(( is_home() || is_front_page() ) ? get_bloginfo('description') : wp_title('',false));
		return $title .' | '. get_bloginfo('name');
	}
}


###############################################################33
// Add CPT to search
###############################################################33
function filter_search($query) {
    if ($query->is_search) {
	$query->set('post_type', array('post', 'work', 'people', 'video', 'page', 'news' ));
    };
    return $query;
};
add_filter('pre_get_posts', 'filter_search');







###############################################################33
// LOOK HERE FUTURE SQL GOD
// order search results alphabetically by post type and by date within each post type
// how can this be done manually?
// i.e. order should probably be done like so:	pages,people,work,clients,posts,videos
###############################################################33
//add_filter('posts_orderby','my_sort_custom',10,2);
//function my_sort_custom( $orderby, $query ){
//    global $wpdb;
//
//    if( is_search() )
//        $orderby =  $wpdb->prefix."posts.post_type ASC, {$wpdb->prefix}posts.post_date DESC";
//
//    return  $orderby;
//}



###############################################################33
// if a search filter is set, limit the search to the specified custom post type
###############################################################33
function mySearchFilter($query) {
	if( isset($_GET['type']) ){
		$post_type = $_GET['type'];
		if (!$post_type) {
			$post_type = 'any';
		}
		if ($query->is_search) {
			$query->set('post_type', $post_type);
		};
		return $query;
	}
};

add_filter('pre_get_posts','mySearchFilter');


###############################################################33
// check if a listing page has previous/next posts
###############################################################33
# Will return true if there is a next page
function has_next_page() {
    global $paged, $max_page;
    return $paged < $max_page;
}

# Will return true if there is a previous page
function has_previous_page() {
    global $paged;
    return $paged > 1;
}

# Will return true if there is more than one page (either before or after).
function has_pagination() {
    return has_next_page() or has_previous_page();
}

###############################################################33
// Logic checks
###############################################################33
function is_template($templateName){
	global $post;
	if( is_page() && get_post_meta($post->ID, '_wp_page_template', true) == $templateName ){
		return true;
	} else {
		return false;	
	}
}


###############################################################33
// Content Type-Specific Genration Functions
###############################################################33
require_once('inc/workFunctions.php');
require_once('inc/clientsFunctions.php');
require_once('inc/peopleFunctions.php');
require_once('inc/insightsFunctions.php');
require_once('inc/aboutFunctions.php');
require_once('inc/careersFunctions.php');
require_once('inc/blogFunctions.php');
//-----------------------------------


###############################################################33
// QUEUE JQUERY
###############################################################33
function my_scripts_method() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', '/wp-content/themes/borshoff/js/jquery.min.js');
    wp_enqueue_script( 'jquery' );
}    
 
add_action('wp_enqueue_scripts', 'my_scripts_method');





###############################################################
// dump
###############################################################
function dump($data) {
    if(is_array($data)) { //If the given variable is an array, print using the print_r function.
        print "<pre>-----------------------\n";
        print_r($data);
        print "-----------------------</pre>";
    } elseif (is_object($data)) {
        print "<pre>==========================\n";
        var_dump($data);
        print "===========================</pre>";
    } else {
        print "=========&gt; ";
        var_dump($data);
        print " &lt;=========";
    }
} 