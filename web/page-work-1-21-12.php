<?php include('menu.php'); ?>

<section id="content" class="container clearfix">

	<?php include('navigation.php'); ?>
	
	<?php generate_work_grid(); ?>

	<?php get_sidebar(); ?>
	
	<script> 
	 <?php /*
	   $(document).ready(function() { 
			$('ul.sf-menu').superfish({ 
				delay:       0,                            // one second delay on mouseout 
				animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
				speed:       'fast',                          // faster animation speed 
				autoArrows:  false,                           // disable generation of arrow mark-up 
				dropShadows: false                            // disable drop shadows 
			}); 
		});   */
		 ?>
		 
		 $(document).ready(function() { 
			demoShow = new Object();
			demoShow.height = 'show';
			
			demoHide = new Object();
			demoHide.height = 'hide';
			demoHide.opacity = 'hide';
			
			$('ul.sf-menu').sooperfish({
				sooperfishWidth: 150,
				dualColumn:     7,
				tripleColumn:     14,
				animationShow:   demoShow,
				speedShow:     300,
				easingShow:    'easeInSine',
				animationHide:  demoHide,
				speedHide:      300,
				easingHide:    'easeInSine',
				delay:0,
				autoArrows:  true
			});
		
		 });
	</script>
	<ul class="filter sf-menu">
		<li class="no-arrow">VIEW</li>
		<li class="no-arrow">|</li>
		<li><a href="">All Work</a>
			<ul>
				<li><a href="">test 1</a></li>
			</ul>
		</li>
		<li><a href="">Industry</a>
			<ul>
				<li><a href="">test 1</a></li>
			</ul>
		</li>
		<li><a href="">Service</a>
			<ul>
				<li><a href="">test 1</a></li>
			</ul>
		</li>
		<li><a href="">Client</a>
			<ul>
				<li><a href="">test 1</a></li>
			</ul>
		</li>
	</ul>

</section>

<?php get_footer(); ?>