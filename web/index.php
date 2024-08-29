<?php //template for generic catch-all pages ?>

<?php get_header(); ?>

<?php include('menu.php'); ?>

<section id="content" class="container blog blogWrapper">

	<?php include('navigation.php'); ?>
	
	<div class="info">	
		
		<div class="blogContent">
			<div id="blogHeader" class="archiveListing">
				<?php
					$supplemental = 'Insights';
					if( is_date() ){
						$supplemental = 'Insights from '.get_the_date( 'F Y' );
					} elseif( is_tag() ) {
						$supplemental = 'Insights about &ldquo;'.single_tag_title('',false).'&rdquo;';
					} elseif( is_home() ) {
						$supplemental = 'All Insights';
					}
				?>
				<div class="blogContentIndent"><?php echo $supplemental; ?> <a id="blogRSS" href="<?php echo get_bloginfo('rss2_url'); ?>" title="Subscribe via RSS"></a></div>
			</div>
		
			<?php while( have_posts() ) : the_post(); ?>
				<?php generate_blog_content( get_the_ID() ); ?>
			<?php endwhile; ?>
			
			<?php if( !is_single() ){ ?>
				<div class="navigation clearfix">
					<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
					<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
				</div>
			<?php }  else { echo 'asdfasdfasdf'; }?>
		</div>
	
		<?php generate_blog_sidebar(); ?>
		
	</div>

	<?php get_sidebar(); ?>
	
	<?php //generate_item_info_menu_heading('History'); ?>
	
	<?php generate_blog_menu(); ?>

	<div class="clearfix"></div>

</section>

<?php get_footer(); ?>