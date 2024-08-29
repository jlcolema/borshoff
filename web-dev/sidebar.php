<section id="sidebar" class="scroll-pane animationEnabled">
	<div id="sidebarContentWrapper">
		<article>		
			<?php
				$args = array(
					'numberposts'=>1,
					'post_type'=>'post',
					'category_name'=>'Blog',
					'orderby'=>'post_date',
					'order'=>'DESC',
					'post_status'=>'publish'
				);
				$items = get_posts($args);
				foreach($items as $item):
				echo '<a href="'.get_permalink(4108).'">';
				echo '<h3 id="insights_heading">Insights&nbsp;&nbsp;<img src="' . get_bloginfo('template_directory') . '/images/insights_heading.png" width="13" height="13" alt="" /></h3>';
				echo '</a>';
				echo '<a href="' . 	get_permalink($item->ID) . '">';
				echo '<h6>'.get_the_time('m / d / Y',$item->ID).'</h6>';
				echo '<p>'.get_the_title($item->ID).'</p>';
				echo '<span class="sidebar_link">read &raquo;</span>';
				echo '</a>';
				endforeach;
			?>
		</article>
		
		<article class="tweets">
			<a href="http://twitter.com/#!/@Borshoff" target="_blank">
				<h3 id="tweets_heading">Tweets&nbsp;&nbsp;<img src="<?php bloginfo('template_directory'); ?>/images/tweets_heading.png" width="13" height="13" alt="" /></h3>
			</a>
			<div class="clearfix">
				<ul class="sidebar-tweets">
					<?php /*
						tweet_blender_widget(array(
							'unique_div_id' => 'tweetblender-t1',
							'sources' => '@Borshoff',
							'refresh_rate' => 0,
							'tweets_num' => 1
						));
						*/
						
						$tweets = display_latest_tweets(
							'@Borshoff',
							1,
							true,
							'',
							'',
							'<li class="tweets"><h3>Tweets</h3>',
							'',
							'',
							'</li>',
							'g:i A M jS',
							true,
							'sidebar'
						);
					?>
				</ul>
			</div>
		</article>
	
			<article>
				<h3 id="heading_send_email_link">Contact</h3>
				<address class="vcard">
					<span class="branch">Headquarters</span>
				  <span class="adr">
					<span class="street-address">47 S Pennsylvania St<br>Suite 500</span><br/>
					<span class="locality">Indianapolis</span>, <abbr class="region" title="Indiana">IN</abbr>&nbsp;<span class="postal-code">46204</span>
				  <span class="phone">
					  P&nbsp;&nbsp;/&nbsp; <a href="tel:3176316400">317.631.6400</a>
				  </span>
				  </span>
				</address>
				<address class="vcard">
					<span class="branch">Evansville Office</span>
				  <span class="adr">
					<span class="street-address">PO Box 8334</span><br/>
					<span class="locality">Evansville</span>, <abbr class="region" title="Indiana">IN</abbr>&nbsp;<span class="postal-code">47716</span>
					  <span class="phone">
						  P&nbsp;&nbsp;/&nbsp; <a href="tel:8124906848">812.490.6848</a>
					  </span>
				  </span>
				</address>
				<p><a href="sendEmail" id="send_email_link" class="sidebar_link">Send us an email &raquo;</a></p>
				<ul id="icon_links" class="clearfix">
					<li id="facebook_icon"><a href="http://facebook.com/borshofftalks" target="_blank" title="Find us on Facebook">Find us on Facebook</a></li>
					<li id="twitter_icon"><a href="http://twitter.com/#!/@Borshoff" target="_blank" title="Follow us on Twitter">Follow us on Twitter</a></li>
					<li id="rss_icon"><a href="/category/blog/feed/" target="_blank" title="Subscribe via RSS">Subscribe via RSS</a></li>
				</ul>
	
			
		</article>
	</div>		
</section>

<div id="contact">
	<a href="#" id="closeContactForm"></a>
	<h3>Have a Question?</h3>
	<?php /* echo do_shortcode('[contact-form-7 id="4085" title="Contact form 1"]'); */ ?>
	<?php echo do_shortcode('[gravityform id=1 title=false description=false ajax=true]'); ?>
	
</div>
