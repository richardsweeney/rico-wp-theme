
<?php get_header(); ?>

	<div id="left">

		<form action="<?php the_permalink(); ?>" method="post" class="rico-form">
			
			<div class="wrap">
				<label for="contact-name">Name</label>
					<input type="text" value="<?php echo $_POST['contact-name']; ?>" name="contact-name" placeholder="name">
			</div>

			<div class="wrap">
				<label for="contact-email">Email</label>
					<input type="email" value="<?php echo $_POST['contact-email']; ?>" name="contact-email" placeholder="email">
			</div>

			<div class="wrap">
				<label for="contact-url">Website</label>
					<input type="url" value="<?php echo $_POST['contact-url']; ?>" name="contact-url" placeholder="url">
			</div>

			<div class="wrap">
				<label for="contact-message">Message</label>
					<textarea name="contact-message" placeholder="yourmessage">
						<?php echo $_POST['contact-message']; ?>
					</textarea>
			</div>

			<input type="submit" value="send" class="go">

		</form>

	</div>

<?php get_footer(); ?>
