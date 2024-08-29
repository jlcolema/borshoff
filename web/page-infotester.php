<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
echo '<pre>';
//	$taxs = get_taxonomies();
//	//print_r($taxs);
//	
//	$terms = get_terms('industry_category');
//	//print_r($terms);
//
//	$args = array(
//		'numberposts'=>-1,
//		'post_type'=>'clients',
//		'post_status'=>'publish'
//	);
//	$clients = get_posts($args);
//	//print_r($clients);
//	
//	$id = 291;
//	$clientPost = get_post($id);
//	//print_r($clientPost);
//	//echo $clientPost->post_name;
//	
//	$args = array(
//		'child_of'=>3505,
//		'post_status'=>'publish'
//	);
//	$items = get_pages($args);
//	print_r($items);
	
	
/*$taxonomy = 'industry_category';
$terms = get_terms($taxonomy);
foreach($terms as $term){
	//$termCounter = get_term_by('slug', $term->slug, $taxonomy);
	//if( $termCounter->count > 0 ) {
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
			echo $term->slug .' has work #'.$thePost->ID.'<br>';
		} else {
			echo $term->slug . ' has no work<br>';
		}
	}
}*/


	global $wpdb;
//	echo htmlspecialchars("'").'<br>';
//	echo htmlspecialchars_decode('&#8217;');
//	$postsWithAuthors = $wpdb->get_results("SELECT * FROM {$wpdb->base_prefix}postmeta WHERE meta_key='person' AND meta_value!='' AND meta_value!='-1'");
//	//print_r($postsWithAuthors);
//	foreach($postsWithAuthors as $work){
//		echo $work->post_id.'person_name'.get_the_title($work->meta_value).'<br/>';
//		//update_post_meta($work->post_id,'person_name',get_the_title($work->meta_value));
//	}
	
echo '</pre>';

?>