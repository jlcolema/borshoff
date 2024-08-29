<?php //template for single work/project info - displays image slideshow of work with project details ?>

<?php get_header(); ?>

<?php include('menu.php'); ?>

<section id="content" class="container blog blogWrapper">

	<?php include('navigation.php'); ?>
	
<div class="info">	
	
<div class="blogContent">
<?php
	if( in_category(381) ){ ?>
		<div id="blogHeader" class="borshoffnews"><div class="blogContentIndent">Borshoff News <a id="blogRSS" href="<?php echo get_bloginfo('rss2_url'); ?>" title="Subscribe via RSS"></a></div></div>
	<?php } elseif( in_category(11) ) { ?>
		<div id="blogHeader" class="clientnews"><div class="blogContentIndent">Client News <a id="blogRSS" href="<?php echo get_bloginfo('rss2_url'); ?>" title="Subscribe via RSS"></a></div></div>
	<?php } elseif( get_post_type() == 'videos' ) { ?>
		<div id="blogHeader" class="videos"><div class="blogContentIndent">Videos <a id="blogRSS" href="<?php echo get_bloginfo('rss2_url'); ?>" title="Subscribe via RSS"></a></div></div>
	<?php } else { ?>
		<div id="blogHeader" class="blog"><div class="blogContentIndent">Blog <a id="blogRSS" href="<?php echo get_bloginfo('rss2_url'); ?>" title="Subscribe via RSS"></a></div></div>
	<?php }
?>

<?php while( have_posts() ) : the_post(); ?>
<?php
	if( get_post_type() == 'post' ) { // arrow links point to the next/prev post in the same category
		$prevPostLink = get_permalink(get_adjacent_post(true,'',false));
		$nextPostLink = get_permalink(get_adjacent_post(true,'',true));
		if( $prevPostLink != '' && $prevPostLink != null ) echo '<a href="'.$prevPostLink.'" id="prevPostButton"></a>'.PHP_EOL;
		if( $prevPostLink != '' && $prevPostLink != null ) echo '<a href="'.$nextPostLink.'" id="nextPostButton"></a>'.PHP_EOL;
	} else { // this would be any custom post types, who's arrows should work similarly, but not with the category parameter
		$prevPostLink = get_permalink(get_adjacent_post(false,'',false));
		$nextPostLink = get_permalink(get_adjacent_post(false,'',true));
		if( $prevPostLink != '' && $prevPostLink != null ) echo '<a href="'.$prevPostLink.'" id="prevPostButton"></a>'.PHP_EOL;
		if( $prevPostLink != '' && $prevPostLink != null ) echo '<a href="'.$nextPostLink.'" id="nextPostButton"></a>'.PHP_EOL;
	}
?>
	<?php generate_blog_content( get_the_ID() ); ?>
<?php endwhile; ?>
</div>

	<?php generate_blog_sidebar(); ?>
	
	</div>

	<?php get_sidebar(); ?>
	
	<?php //generate_item_info_menu_heading('History'); ?>
	
	<?php generate_blog_menu(); ?>

	<div class="clearfix"></div>

</section>

<?php get_footer(); ?>