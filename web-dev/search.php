<?php //template for search results pages ?>

<?php get_header(); ?>

<?php 
// this is supposed to group search results by type 
//	global $wp_query;
//	$args = array_merge( $wp_query->query, array( 'post_type' => array('work','post','clients') ) );
//	query_posts( $args );
?>

<?php include('menu.php'); ?>

<section id="content" class="container blog blogWrapper">

	<?php include('navigation.php'); ?>
	
<div class="info">	
	
<div class="blogContent">
<div id="blogHeader" class="searchResults"><div class="blogContentIndent">Search Results</div></div>

	<?php if (have_posts()) : ?>

		<div class="blogContentIndent blogPost searchResult searchDetails">
			<h1 class="center">Displaying search results for <strong>&ldquo;<?php the_search_query(); ?>&rdquo;</strong></h1>
			<form method="get" id="searchform" action="/">
			<h2>Refine Search:&nbsp;&nbsp;&nbsp;<input type="text" name="s" value="<?php the_search_query(); ?>" /> <input type="submit" value="Search"/><input type="hidden" name="type" value="<?php if( isset($_GET['type']) ) echo $_GET['type']; ?>" id="posttypeField"/><!--<input type="hidden" name="category" id="categoryField" />--></h2>
			<ul id="searchFilter">
				<li><strong>Content Type:</strong></li>
				<li><a href="#pages" data-posttype="page" class="page <?php if($_GET['type'] == 'page') echo 'active'; ?>">Pages</a></li>
				<li><a href="#people" data-posttype="people" class="people <?php if($_GET['type'] == 'people') echo 'active'; ?>">People</a></li>
				<li><a href="#work" data-posttype="work" class="work <?php if($_GET['type'] == 'work') echo 'active'; ?>">Work</a></li>
				<li><a href="#blog" data-posttype="post" class="post <?php if($_GET['type'] == 'post') echo 'active'; ?>">Blog</a></li>
<!--				<li><a href="#blog" class="blog">Client News</a></li>
				<li><a href="#blog" class="blog">Borshoff News</a></li>
-->				<li><a href="#videos" data-posttype="videos" class="videos <?php if($_GET['type'] == 'videos') echo 'active'; ?>">Videos</a></li>
			</ul>
			</form>
			
			<div class="clearfix"></div>
			<h2 id="resultsCount">Found <?php echo $wp_query->found_posts; ?> results</h2>
		</div>
		
		<?php if( has_pagination() ): ?>
<!--		<div class="navigation clearfix">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>	
-->		<?php endif; ?>

		<?php while (have_posts()) : the_post(); ?>
		<?php
			if(get_post_type() == 'work') $isVisible = (get_post_meta(get_the_id(),'isListedInGrid',true) == 1) ? true : false;
			elseif(get_post_type() == 'clients') $isVisible = (get_post_meta(get_the_id(),'isListedInGrid',true) == 1) ? true : false;
			elseif(get_post_type() == 'people') $isVisible = (get_post_meta(get_the_id(),'isListedInGrid',true) == 1) ? true : false;
			else $isVisible = true;
		?>
		<?php if($isVisible): ?>

		<div class="blogContentIndent blogPost searchResult">
			<?php 	
				wp_cache_delete($post->ID, 'posts');

				$displayDate = true;

				if( get_post_type() == 'work' ){ // work
					$resultType = 'resultTypeWork';
					$link = get_permalink(164);
					$linkText = 'View Work';
					$displayDate = false;
				} elseif( get_post_type() == 'videos' ) { // video
					$resultType = 'resultTypeVideo';
					global $post;
					$latest_posts = get_posts('numberposts=1&post_type=videos');
					foreach($latest_posts as $latest) {
						$link = get_permalink($latest->ID);
					}
					$linkText = 'Watch Video';
				} elseif( false ) { // twitter | can't search tweets, sorry
					$resultType = 'resultTypeTwitter';
					$link = '';
					$linkText = 'Read Tweet';
				} elseif( get_post_type() == 'people' ) { // people
					$resultType = 'resultTypePeople';
					$link = get_permalink(166);
					$linkText = 'View Bio';
					$displayDate = false;
				} elseif( in_category( 11 ) ) { // clientnews
					$resultType = 'resultTypeClientnews';
					$link = get_category_link(11);
					$linkText = 'Read Client News';
				} elseif( is_page(18) || get_topmost_ancestor(get_the_id()) == 18 ) { // careers
					$resultType = 'resultTypeCareers';
					$link = get_permalink(18);
					$linkText = 'See Career Opportunity';
				} elseif( in_category( 381 ) ) { // borshoff news
					$resultType = 'resultTypeBorshoffnews';
					$link = get_category_link(381);
					$linkText = 'Read Borshoff News';
				} elseif( is_page(14) || get_topmost_ancestor(get_the_id()) == 14 ) { // about
					$resultType = 'resultTypeAbout';
					$link = get_permalink(14);
					$linkText = 'Learn about Borshoff';
					$displayDate = false;
				} else { // blog
					$resultType = 'resultTypeBlog';
					$link = get_permalink(4108);
					$linkText = 'Read Blog Post';
				}
				
				echo '<a href="'.$link.'" class="resultTypeIcon '.$resultType.'"></a>';
					
				if( $displayDate ){
					echo '<p class="date">' . get_the_date('F j, Y') . '</p>';
				}
				if( get_post_type() == 'work' ){
					echo '<h2><a href="'.get_permalink().'">' . get_post_meta(get_the_id(),'client_name',true) . ' | ' . get_the_title() . '</a></h2>';
				} else {
					echo '<h2><a href="'.get_permalink().'">' . get_the_title() . '</a></h2>';
				}
				echo '<p>' . get_the_excerpt() . '</p>';
				echo '<a class="readMore" href="' . get_permalink() . '">'.$linkText.' &raquo;</a>';			
				
			?>
		</div>
		
		<?php endif; ?>

		<?php endwhile; ?>

		<div class="navigation clearfix">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
	<?php else : ?>
		<div class="blogContentIndent blogPost searchResult">
			<h1 class="center">Looks like we couldn&rsquo;t find anything for <strong>&ldquo;<?php the_search_query(); ?>&rdquo;</strong></h1>
			<br />
			<form method="get" id="searchform" action="/">
			<h2>Refine Search:&nbsp;&nbsp;&nbsp;<input type="text" name="s" value="<?php the_search_query(); ?>" /> <input type="submit" value="Search"/><input type="hidden" name="type" value="<?php if( isset($_GET['type']) ) echo $_GET['type']; ?>" id="posttypeField"/><!--<input type="hidden" name="category" id="categoryField" />--></h2>
			<ul id="searchFilter">
				<li><strong>Content Type:</strong></li>
				<li><a href="#pages" data-posttype="page" class="page <?php if($_GET['type'] == 'page') echo 'active'; ?>">Pages</a></li>
				<li><a href="#people" data-posttype="people" class="people <?php if($_GET['type'] == 'people') echo 'active'; ?>">People</a></li>
				<li><a href="#work" data-posttype="work" class="work <?php if($_GET['type'] == 'work') echo 'active'; ?>">Work</a></li>
				<li><a href="#blog" data-posttype="post" class="post <?php if($_GET['type'] == 'post') echo 'active'; ?>">Blog</a></li>
<!--				<li><a href="#blog" class="blog">Client News</a></li>
				<li><a href="#blog" class="blog">Borshoff News</a></li>
-->				<li><a href="#videos" data-posttype="videos" class="videos <?php if($_GET['type'] == 'videos') echo 'active'; ?>">Videos</a></li>
			</ul>
			</form>
			<?php //get_search_form(); ?>
		</div>
	<?php endif; ?>
					
</div>

	<?php //generate_blog_sidebar(); ?>
	
	</div>

	<?php get_sidebar(); ?>
	
	<?php //generate_item_info_menu_heading('History'); ?>
	
	<?php //generate_blog_menu(); ?>

	<div class="clearfix"></div>

</section>

<?php get_footer(); ?>