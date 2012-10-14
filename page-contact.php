
<?php get_header(); ?>

	<div class="left">

		<section>

			<header>
				<h1>Get in touch</h1>
			</header>

			<p class="contact-ramble">Feel free to drop me an email about anything you fancy.<br>WordPress, websites, lutes, gut strings etc. I'll do my best to get back to you as soon as possible.</p>

			<form data-id="<?php echo $post->ID; ?>" action="<?php echo get_permalink($post->ID); ?>" method="post" class="rps-contact-form">

				<div class="wrap">
					<label for="contact-name">Name</label>
						<input type="text" value="" name="contact-name" placeholder="Your name">
				</div>

				<div class="wrap">
					<label for="contact-email">Email</label>
						<input type="email" value="" name="contact-email" placeholder="Your email address">
				</div>

				<input type="text" value="" class="gomda-falt" name="contact-url">

				<div class="wrap">
					<label for="contact-message">Message</label>
						<textarea name="contact-message" placeholder="Your message"></textarea>
				</div>

				<input type="submit" value="Send your message" class="button go">

			</form>

			<div class="contact-details">
				<p><strong>Tel:</strong> +46 705 939 050<br><strong>Address:</strong> Torupsgatan 1D, 217 73 Malmö, Sweden</p>

		</section>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
