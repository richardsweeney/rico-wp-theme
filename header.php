<!DOCTYPE html">
<html <?php language_attributes(); ?>>
<head>

	<title>Richard Sweeney <?php wp_title(':'); ?></title>
	<meta name="description" content="I'm a web developer that loves WordPress, HTML5, CSS3, PHP, JavaScript, jQuery.">
	<meta name="author" content="Richard Sweeney">
	<meta name ="viewport" content ="initial-scale=1.0 width=device-width">
	<link rel="shortcut icon" href="<?php echo IMG; ?>/favicon.ico">
	<script src="http://use.typekit.com/qsh5wak.js"></script>
	<script>
		try {
			Typekit.load();
		} catch(e) {
			console.log(e);
		}
	</script>

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

	<div class="header clearfix">

		<div class="header-container center-layout">

			<header>

				<hgroup>
					<h1 class="site-header"><a href="<?php echo URL; ?>">Richard Sweeney</a></h1>
					<h3 class="sub-header">front-end developer</h3>
				</hgroup>

				<nav role="navigation">
					<ul class="main-nav">
						<li class="page_item<?php if(get_post_type() == 'post'): ?> current_page_item<?php endif; ?>"><a href="<?php echo URL; ?>/blog/">blog</a></li>
						<li class="page_item<?php if(is_page('about-me')): ?> current_page_item<?php endif; ?>"><a href="<?php echo URL; ?>/about-me/">about me</a></li>
						<li class="page_item<?php if(is_page('portfolio') || get_post_type() == 'portfolio_item') : ?> current_page_item<?php endif; ?>"><a href="<?php echo URL; ?>/portfolio/">portfolio</a></li>
						<li class="page_item<?php if(is_page('contact')) : ?> current_page_item<?php endif; ?>"><a href="<?php echo URL; ?>/contact/">get in touch</a></li>
					</ul>
				</nav>

			</header>

		</div>

	</div>

	<div class="site-container">

		<div class="main-content">
