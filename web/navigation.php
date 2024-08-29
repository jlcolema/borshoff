<nav>
		<?php
		
			if( current_theme_supports('menus') ){
				
				$args = array(
					'menu'=>'Main Navigation'
				);
				wp_nav_menu( $args );
				
			} else {
				$args = array(
					'title_li'=>'',
					'exclude'=> '11',
					'depth'=>1
				);
				wp_list_pages( $args );
			}
		
		?>
</nav>