
<?php get_header(); ?>

	<div class="left about-me">

	<?php while (have_posts()) : the_post(); ?>

		<div class="english-cv">
			<article>
				<header>
					<h1>Who I am and what I do</h1>
				</header>
				<?php the_content(); ?>
			</article>
		</div>

	<?php endwhile; ?>

		<script>
			jQuery(function ($) {
				var $svCV = $('.swedish-cv').hide(),
						$ukCV = $('.english-cv'),
						$link = $('<p class="post-meta"><a href="#" id="visa-cv">Kolla mitt svenska CV</a></p>');
				$link
					.appendTo($('.about-me'))
					.click(function (e){
						$ukCV.hide();
						$svCV.fadeIn(300)
						e.preventDefault();
						$(this).remove();
						$('html, body').animate({
							scrollTop: 0
						}, 400);
					});
			});
		</script>

		<div class="swedish-cv">
			<article>
				<header>
					<h1>Vem jag är och det jag gör</h1>
				</header>
				<p><span class="lift">Jag är en glad och social frontendutvecklare som brinner för den moderna webben.</span></p>
				<p>Jag har mycket bra kompetens i HTML, CSS, JavaScript och PHP. Jag har jobbat mycket med WordPress och en hel del med Drupal och har byggt ett 20-tal sajter samt utvecklat ett antal WordPress plugins. Jag tycker väldigt mycket om JavaScript och använder mig gärna av jQuery. Jag har även byggt en HTML5 webbapp på jQuery mobile som finns på stringcalc.com.</p>
				<p>Jag har jobbat som frilans utvecklare/webbdesigner i 5 år. Jag är ingen grafisk designer, men kan en hel del om design, linjering, typografi och hur man ska använda färg. Jag är duktig i Photoshop, använder Git och skriver gärna lite MySQL när det behövs.</p>
				<p>Mellan Januari och Juli 2012 jobbade jag som frontend-utvecklare hos Good Old i Malmö.</p>
				<p>Jag är irlänsk och har bott i Malmö med min (svenska) fru sedan 2007. Jag pratar och skriver flytande på svenska (och givetvis på engelska!).</p>
				<p>Jag har min bakgrund i musik. Jag studerade luta på Royal Academy of Music i London (i år blev jag en Associate på RAM). Jag jobbade som frilans musiker i dryggt 10 år. Under den tiden spelade jag hundratals konserter, spelade in drygt ett dussin skivor och reste jorden runt.</p>
			</article>
		</div>

	</div>
<?php get_sidebar(); ?>

<?php get_footer(); ?>
