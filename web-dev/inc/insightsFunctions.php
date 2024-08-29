<?php
//Insights-related stuff

###########################
// The Grid
###########################
function generate_insights_grid(){
	echo '<section class="grid insights">';
	echo '<a class="slideGridLeftButton"></a>';
	echo '<a class="slideGridRightButton"></a>';
	echo '<ul id="gridWrapper">';
		//Twitter
			$tweets = display_latest_tweets(
				'@Borshoff',
				3,
				true,
				'',
				'',
				'<li class="tweets"><div class="insightsHover"></div><h3>Tweets</h3><span class="status">',
				'',
				'',
				'</span></li>',
				'g:i A M jS',
				true,
				'grid'
			);
		
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
			foreach($items as $item):
				if( $i < 2 ){
					echo '<li class="blog twoRow"><div class="insightsHover"></div>';
					$i++;
				} else {
					echo '<li class="blog"><div class="insightsHover"></div>';
				}
					$thumb = MultiPostThumbnails::get_the_post_thumbnail('post', 'square_crop', $item->ID, 'square_thumbnail',array('title'=>$item->post_title,'alt'=>$item->post_title) );
					echo '<a class="blogThumb" href="'.get_permalink($item->ID).'">'.$thumb.'</a>';
					echo '<a href="'.get_permalink($item->ID).'">';
						echo '<h3>Blog</h3>';
						echo '<p class="date">'.mysql2date('m / d / Y', $item->post_date).'</p>';
						echo '<h2>'.$item->post_title.'</h2>';
						
						
					//display the author byline: <p class="byline>By Author <br />Title</p>
					if( get_post_meta($item->ID,'person',true) != '' && get_post_meta($item->ID,'person',true) != '-1' ){
						
						$personID = get_post_meta($item->ID,'person',true);
		
						$numCategories = get_categories_count($personID,'employee_position');
						if( $numCategories > 0 ){
							$categoriesList = get_categories_list($personID,'employee_position');
							$position = $categoriesList[0];
						} else {
							$position = '';
						}
						
						$byline = '<p class="by">By '.get_the_title($personID).'<br />'.$position.'</p>';
					} elseif( get_post_meta($item->ID,'author_name',true) != '' ) {
						$byline = get_post_meta($item->ID,'author_name',true);
						if( get_post_meta($item->ID,'author_title',true) != '' ) $byline .= '<br/>'.get_post_meta($item->ID,'author_title',true);
						$byline = '<p class="by">By '.$byline.'</p>';
					} else {
						$byline = '';	
					}
		
								
					echo $byline;
						
					echo '</a>';
				echo '</li>';
			endforeach;
		
		//Client Coverage
		$args = array(
			'numberposts'=>3,
			'post_type'=>'post',
			'orderby'=>'post_date',
			'order'=>'DESC',
			'post_status'=>'publish',
			'category_name'=>'Client News'
		);
		$items = get_posts($args);
		$i=0;
			foreach($items as $item):
				if( $i < 2 ){
					echo '<li class="coverage twoRow"><div class="insightsHover"></div>';
					$i++;
				} else {
					echo '<li class="coverage"><div class="insightsHover"></div>';
				}
					$thumb = MultiPostThumbnails::get_the_post_thumbnail('post', 'square_crop', $item->ID, 'square_thumbnail',array('title'=>$item->post_title,'alt'=>$item->post_title) );
					echo '<a class="blogThumb" href="'.get_permalink($item->ID).'">'.$thumb.'</a>';
					echo '<a href="'.get_permalink($item->ID).'">';
						echo '<h3>Client News</h3>';
						echo '<p class="date">'.mysql2date('m / d / Y', $item->post_date).'</p>';
						echo '<h2>'.$item->post_title.'</h2>';
					echo '</a>';
				echo '</li>';
			endforeach;
		
		//Borshoff News
		$args = array(
			'numberposts'=>3,
			'post_type'=>'post',
			'orderby'=>'post_date',
			'order'=>'DESC',
			'post_status'=>'publish',
			'category_name'=>'Borshoff News'
		);
		$items = get_posts($args);
		$i=0;
			foreach($items as $item):
				if( $i < 2 ){
					echo '<li class="news twoRow"><div class="insightsHover"></div>';
					$i++;
				} else {
					echo '<li class="news"><div class="insightsHover"></div>';
				}
					$thumb = MultiPostThumbnails::get_the_post_thumbnail('post', 'square_crop', $item->ID, 'square_thumbnail',array('title'=>$item->post_title,'alt'=>$item->post_title) );
					echo '<a class="blogThumb" href="'.get_permalink($item->ID).'">'.$thumb.'</a>';
					echo '<a href="'.get_permalink($item->ID).'">';
						echo '<h3>Borshoff News</h3>';
						echo '<p class="date">'.mysql2date('m / d / Y', $item->post_date).'</p>';
						echo '<h2>'.$item->post_title.'</h2>';
					echo '</a>';
				echo '</li>';
			endforeach;
		
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
			foreach($items as $item):
				if( $i < 2 ){
					echo '<li class="video twoRow"><div class="insightsHover"></div>';
					$i++;
				} else {
					echo '<li class="video"><div class="insightsHover"></div>';
				}
					$thumb = get_the_post_thumbnail($item->ID,'square_thumbnail',array('title'=>$item->post_title,'alt'=>$item->post_title));
					echo '<a class="vidThumb" href="'.get_permalink($item->ID).'">'.$thumb.'</a>';
					echo '<a href="'.get_permalink($item->ID).'">';
						echo '<h3>Videos</h3>';
						echo '<p class="date">'.mysql2date('m / d / Y', $item->post_date).'</p>';
						echo '<h2>'.$item->post_title.'</h2>';
					echo '</a>';
				echo '</li>';
			endforeach;
	echo '</ul>';
	echo '</section>';
}


###########################
// Single item content
###########################
function generate_insights_info(){
	
}



###############################################################33
// get feed using cURL
###############################################################33
function feedMe($feed) {
	// Use cURL to fetch text
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $feed);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$rss = curl_exec($ch);
	curl_close($ch);

	return $rss;
}
	

###############################################################33
// TWITTER Feed Stuff
###############################################################33

/**
 * TWITTER FEED PARSER
 * 
 * @version	1.1.1
 * @author	Jonathan Nicol
 * @link	http://f6design.com/journal/2010/10/07/display-recent-twitter-tweets-using-php/
 * 
 * Notes:
 * We employ caching because Twitter only allows their RSS feeds to be accesssed 150
 * times an hour per user client.
 * --
 * Dates can be displayed in Twitter style (e.g. "1 hour ago") by setting the 
 * $twitter_style_dates param to true.
 * 
 * Credits:
 * Hashtag/username parsing based on: http://snipplr.com/view/16221/get-twitter-tweets/
 * Feed caching: http://www.addedbytes.com/articles/caching-output-in-php/
 * Feed parsing: http://boagworld.com/forum/comments.php?DiscussionID=4639
 */
 
function display_latest_tweets(
	$twitter_user_id,
	$tweets_to_display = 100,
	$ignore_replies = false,
	$twitter_wrap_open = '<h2>Latest tweets</h2><ul id="twitter">',
	$twitter_wrap_close = '</ul>',
	$tweet_wrap_open = '<li><div class="insightsHover"></div>',
	$meta_wrap_open = '',
	$meta_wrap_close = '',
	$tweet_wrap_close = '</li>',
	$date_format = 'm / d / Y',
	$twitter_style_dates = false,
	$file_suffix = 'grid'){
	
	$uploads = wp_upload_dir();
	$uploads_dir = ( $uploads['basedir'] );
	$filename = ('/twitter/twitter-cache'.$file_suffix.'.txt');
	$cache_file = ( $uploads_dir . $filename );
 
	// Seconds to cache feed (1 hour).
	$cachetime = 60*60*2;
	// Time that the cache was last filled.
	$cache_file_created = ((@file_exists($cache_file))) ? @filemtime($cache_file) : 0;
 
	// A flag so we know if the feed was successfully parsed.
	$tweet_found = false;
 
	// Show file from cache if still valid.
	$thisurl = $_SERVER["REQUEST_URI"];
	if ((time() - $cachetime < $cache_file_created) && ( strstr( $thisurl, "?reset" ) == false )) {
 
		$tweet_found = true;
		// Display tweets from the cache.
		@readfile($cache_file);	
		
 
	} else {
	
		// Cache file not found, or old. Fetch the RSS feed from Twitter.
		$rss_address = 'http://twitter.com/statuses/user_timeline/'.$twitter_user_id.'.rss';
		
		// $rss = feedMe($rss_address);
		$rss = @file_get_contents('http://twitter.com/statuses/user_timeline/'.$twitter_user_id.'.rss');
 
		if($rss)
		
		
		 {
 
			// Parse the RSS feed to an XML object.
			$xml = @simplexml_load_string($rss);
 
			if($xml !== false) {
 
				// Error check: Make sure there is at least one item.
				if (count($xml->channel->item)) {
 
					$tweet_count = 0;
 
					// Start output buffering.
					ob_start();
 
					// Open the twitter wrapping element.
					$twitter_html = $twitter_wrap_open;
 
					// Iterate over tweets.
					foreach($xml->channel->item as $tweet) {
 
						// Twitter feeds begin with the username, "e.g. User name: Blah"
						// so we need to strip that from the front of our tweet.
						$tweet_desc = substr($tweet->description,strpos($tweet->description,":")+2);
						$tweet_desc = htmlspecialchars($tweet_desc);
						$tweet_desc = preg_replace('/<[^>]*>/', '', $tweet_desc);

						$tweet_first_char = substr($tweet_desc,0,1);
 
						// If we are not gnoring replies, or tweet is not a reply, process it.
						if ($tweet_first_char!='@' || $ignore_replies==false){
 
							$tweet_found = true;
							$tweet_count++;
 
							// Add hyperlink html tags to any urls, twitter ids or hashtags in the tweet.
							/*
							$tweet_desc = preg_replace('/(https?:\/\/[^\s"<>]+)/','<a href="$1">$1</a>',$tweet_desc);
							$tweet_desc = preg_replace('/(^|[\n\s])@([^\s"\t\n\r<:]*)/is', '$1<a href="http://twitter.com/$2">@$2</a>', $tweet_desc);
							$tweet_desc = preg_replace('/(^|[\n\s])#([^\s"\t\n\r<:]*)/is', '$1<a href="http://twitter.com/search?q=%23$2">#$2</a>', $tweet_desc);
							*/
 
 							// Convert Tweet display time to a UNIX timestamp. Twitter timestamps are in UTC/GMT time.
							$tweet_time = strtotime($tweet->pubDate);	
 							if ($twitter_style_dates){
								// Current UNIX timestamp.
								$current_time = time();
								$time_diff = abs($current_time - $tweet_time);
								switch ($time_diff) 
								{
									case ($time_diff < 60):
										$display_time = $time_diff.' seconds ago';                  
										break;      
									case ($time_diff >= 60 && $time_diff < 3600):
										$min = floor($time_diff/60);
										$display_time = $min.' minutes ago';                  
										break;      
									case ($time_diff >= 3600 && $time_diff < 86400):
										$hour = floor($time_diff/3600);
										$display_time = 'about '.$hour.' hour';
										if ($hour > 1){ $display_time .= 's'; }
										$display_time .= ' ago';
										break;          
									default:
										$display_time = date('m / d / Y',$tweet_time);
										break;
								}
 							} else {
 								$display_time = date('m / d / Y',$tweet_time);
 							}
 
							// Render the tweet.
							$twitter_html .= $tweet_wrap_open.'<a href="http://twitter.com/'.$twitter_user_id.'" class="date time" target="borshoff_twitter">'.$display_time.'</a><a href="http://twitter.com/'.$twitter_user_id.'" target="borshoff_twitter">'.$tweet_desc.'</a>'.$tweet_wrap_close;
 
						}
 
						// If we have processed enough tweets, stop.
						if ($tweet_count >= $tweets_to_display){
							break;
						}
 
					}
 
					// Close the twitter wrapping element.
					$twitter_html .= $twitter_wrap_close;
					echo $twitter_html;
 
					// Generate a new cache file.
					$file = @fopen($cache_file, 'w');
 
					// Save the contents of output buffer to the file, and flush the buffer. 
					@fwrite($file, ob_get_contents()); 
					@fclose($file); 
					ob_end_flush();
 
				}
			}
		}
	} 
	// In case the RSS feed did not parse or load correctly, show a link to the Twitter account.
	if (!$tweet_found){
		echo $twitter_wrap_open.$tweet_wrap_open.'Oops, our twitter feed is unavailable right now. '.$meta_wrap_open.'<a href="http://twitter.com/'.$twitter_user_id.'">Follow us on Twitter</a>'.$meta_wrap_close.$tweet_wrap_close.$twitter_wrap_close;
	}
}
 

?>