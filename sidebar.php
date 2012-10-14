
<div class="right-sidebar">

	<?php if(is_page('about-me')): ?>
	<div class="skills-container">
		<section>
			<header>
				<h1>Skills at a glance</h1>
			</header>
			<ul class="skills-list">
				<li>HTML5</li>
				<li>CSS3</li>
				<li>JavaScript</li>
				<li>jQuery</li>
				<li>jQuery mobile</li>
				<li>PHP</li>
				<li>WordPress</li>
				<li>Drupal</li>
				<li>Git</li>
				<li>Photoshop</li>
			</ul>
		</section>
	</div>
	<?php endif; ?>

	<div class="instagram-container">
		<img class="instagram-logo" src="<?php echo IMG; ?>/instagram-icon.png" alt="instagram icon">
		<img class="instagram" src="<?php echo IMG; ?>/instagram.png" alt="instagram">
		<span class="instagram-loading">loading...</span>
	</div>

	<div class="twitter-container">
		<h3 class="twitter-header">Twitter feed: <a href="http://twitter.com/richardsweeney">@richardsweeney</a></h3>
		<span class="twitter-loading">loading...</span>
	</div>
	<script>
		jQuery(function ($) {
			$.ajaxSetup({ cache: false });
			$.getScript(ajaxGlobals.js + '/instagram.js').done(function () {
				$.getScript(ajaxGlobals.js + '/tweet.js');
			});
		});
	</script>

</div>

