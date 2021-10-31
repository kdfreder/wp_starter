<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
		<?php // go here http://www.favicomatic.com/ and get every damn size, add them to library/src/img/favicons/
			$atip = array("57x57", "114x114", "72x72", "144x144", "60x60", "120x120", "76x76", "152x152");
			foreach($atip as $a){
				echo '<link rel="apple-touch-icon-precomposed" sizes="'.$a.'" href="'.get_template_directory_uri().'/library/src/img/favicons/apple-touch-icon-'.$a.'.png" />';
			}
			$icon = array("196x196", "96x96", "32x32", "16x16", "128x128"); // favicomatic names it just '128', remember to change it to '128x128'
			foreach($icon as $i){
				echo '<link rel="icon" type="image/png" href="'.get_template_directory_uri().'/library/src/img/favicons/favicon-'.$i.'.png" sizes="'.$i.'" />';
			}
		?>
		<!--[if IE]><link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/library/src/img/favicons//favicon.ico"><![endif]-->
		<meta name="application-name" content="&nbsp;"/>
		<meta name="msapplication-TileColor" content="#FFFFFF" />
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/src/img/favicons/mstile-144x144.png" />
		<meta name="msapplication-square70x70logo" content="<?php echo get_template_directory_uri(); ?>/library/src/img/favicons/mstile-70x70.png" />
		<meta name="msapplication-square150x150logo" content="<?php echo get_template_directory_uri(); ?>/library/src/img/favicons/mstile-150x150.png" />
		<meta name="msapplication-wide310x150logo" content="<?php echo get_template_directory_uri(); ?>/library/src/img/favicons/mstile-310x150.png" />
		<meta name="msapplication-square310x310logo" content="<?php echo get_template_directory_uri(); ?>/library/src/img/favicons/mstile-310x310.png" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		<?php wp_head(); ?>
		
		<?php the_field('header_scripts', 'option'); ?>
	</head>
	<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
		<?php the_field('body_open_scripts', 'option'); ?>
		<div id="container">
			<header class="header" role="banner" itemscope itemtype="http://schema.org/WPHeader">
				<div class="wrap">
					<a href="<?php echo home_url(); ?>" class="img-holder logo" rel="nofollow">
						<img src="<?php echo get_field('header_logo','option')['sizes']['lo-res'];?>" alt="<?php bloginfo( 'name' ); ?>" />
					</a>
					<nav itemscope itemtype="http://schema.org/SiteNavigationElement">
						<?php wp_nav_menu(array(
							'container' => false,
							'menu' => 'Header Menu',
							'menu_class' => 'nav top-nav',
							'depth' => 0,
						)); ?>
					</nav>
					<div id="mobile-menu-toggle"><i class="fa fa-bars"></i></div>
				</div>
			</header>
