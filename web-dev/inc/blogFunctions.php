<?php
//Blog-related stuff


###########################
// Blog Content
###########################
function generate_blog_content($id){
		echo '<div class="blogContentIndent blogPost">';
			echo '<p class="date">'.get_the_date('n / d / Y').'</p>';
			echo '<h1><a href="'.get_permalink().'">'.get_the_title().'</a></h1>';
			if( get_post_meta($id,'person',true) != '' && get_post_meta($id,'person',true) != '-1' ){
				
				$personID = get_post_meta($id,'person',true);

				$numCategories = get_categories_count($personID,'employee_position');
				if( $numCategories > 0 ){
					$categoriesList = get_categories_list($personID,'employee_position');
					$position = ', '.$categoriesList[0];
				} else {
					$position = '';
				}
				
				$byline = '<p class="byline">By <a href="'.get_permalink($personID).'">'.get_the_title($personID).'</a>'.$position.'</p>';
			} elseif( get_post_meta($id,'author_name',true) != '' ) {
				$byline = get_post_meta($id,'author_name',true);
				if( get_post_meta($id,'author_title',true) != '' ) $byline .= ', '.get_post_meta($id,'author_title',true); 
				$byline = '<p class="byline">'.$byline.'</p>';
			} else {
				$byline = '';	
			}
			echo $byline;
			//echo get_post_meta($id, 'vimeo_link', true);
			the_content();
			the_tags('<div class="tags">tags: ',', ','</div>');
		echo '</div>';
}


###########################
// History Sidebar
###########################
function generate_blog_sidebar(){
	echo '<aside class="scroll-pane">';
		echo '<div class="blogSidebarWrapper">';
			echo '<h3>Recent Posts</h3>';
			$cat = '';
			$type = '';
			if( in_category('blog') ) $cat = 58;
			if( in_category('borshoff-news') ) $cat = 381;
			if( in_category('client-news') ) $cat = 11;
			if( get_post_type() == 'videos' ) $type = 'videos';
			$args = array(
				'numberposts'=>5,
				'post_type'=>'post',
				'publish_status'=>'publish'
			);
			if( $cat != '' ) $args['category'] = $cat;
			if( $type != '' ) $args['post_type'] = $type;
			$recentPosts = get_posts($args);
			foreach($recentPosts as $recentPost){
				echo '<h2><a href="'.get_permalink($recentPost->ID).'">'.$recentPost->post_title.'</a></h2>';
			}
			
			echo '<h3>Archives</h3>';
			$args = array(
				'limit'=>5
			);
			echo '<ul id="archiveList">';
				wp_get_archives( $args );
			echo '</ul>';
			echo '<h3>Tags</h3>';
			$args = array(
				'orderby'=>'count',
				'order'=>'DESC',
				'number'=>'10'
			);
			$tags = get_tags($args);
			echo '<ul>';
			foreach($tags as $tag){
				$tagLink = get_tag_link($tag->term_id);
				echo '<li><a href="'.$tagLink.'">'.$tag->name.' ('.$tag->count.')</a></li>';
			}
			echo '</ul>';
			echo '<!-- AddThis Button BEGIN -->';
			echo '<div class="addthis_toolbox addthis_default_style">';
			echo '<a href="http://www.addthis.com/bookmark.php?v=250" class="addthis_button_compact shareLink" addthis:url="'.get_permalink($post->ID).'"
   addthis:title="'.get_the_title($post->ID).'" 
   addthis:description="'.get_excerpt($post->ID).'"></a>';
			echo '</div>';
			echo '<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js?domready=1"></script>';
			echo '<!-- AddThis Button END -->';
		echo '</div>';
	echo '</aside>';
}



###########################
// Menu for filtering
###########################
function generate_blog_menu(){
	echo '<ul class="filter sf-menu isotopeFilterMenu">';
		//echo '<li class="no-arrow pad-top first">VIEW</li>';
		//echo '<li class="no-arrow pad-top">|</li>';
		echo '<li class="no-arrow"><a href="'.get_permalink(13).'" class="isotopeFilterItem no-arrow">All Insights</a></li>';
		//echo '<li><a href="#">Blog</a>';
//			echo '<ul>';
//				$taxonomy = 'industry_category';
//				$terms = get_terms($taxonomy);
//				foreach($terms as $term){
//					$termCounter = get_term_by('slug', $term->slug, $taxonomy);
//					if( $termCounter->count > 0 ) {
//						if ( is_single() ) {
//							$href = '/work/?industry='.$term->slug;
//						} else {
//							$href = '';
//						}
//						echo '<li><a href="'.$href.'" class="isotopeFilterItem" data-filter=".'.$term->slug.'">'.$term->name.'</a></li>';
//					}
//				}
//			echo '</ul>';
//		echo '</li>';
	echo '</ul>';	
}