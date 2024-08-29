<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
?>
<!DOCTYPE HTML>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo get_ajax_title(); ?></title>

<!--[if IE]><![endif]--> <!--blank comment enables parallel downloads/requests in IE-->

<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>">
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/css/fontsAndPlugins.css">

<!--Media Queries-->
<meta name="viewport" content="width=1200">
<!-- Big -->
<link href="<?php bloginfo('stylesheet_directory'); ?>/css/desktop.css" rel="stylesheet" type="text/css" media="only screen and (min-height:800px)">
<!-- medium -->
<link href="<?php bloginfo('stylesheet_directory'); ?>/css/tablet.css" rel="stylesheet" type="text/css" media="only screen and (max-height:799px)">

<!-- so it begins... the great AJAX of our time -->
<?php if( get_post_type() != 'videos' && get_post_type() != 'post' && !is_search() && !is_template('template_hidden_page.php') ): ?>
<link href="<?php bloginfo('stylesheet_directory'); ?>/css/ajax.css" rel="stylesheet" type="text/css">
<?php endif; ?>

<!--[if lt IE 9]>
<link href="<?php bloginfo('stylesheet_directory'); ?>/css/desktop.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/html5.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/modernizr.custom.38274.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/selectivizr.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/css3-mediaqueries.js"></script>
<link href="<?php bloginfo('stylesheet_directory'); ?>/css/ie8.css" rel="stylesheet" type="text/css" media="screen">
<![endif]-->

<!--[if lt IE 8]>
<link href="<?php bloginfo('stylesheet_directory'); ?>/css/ie7.css" rel="stylesheet" type="text/css" media="screen">
<![endif]-->


<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>