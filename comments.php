<?php
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])):
		wp_die('Burn');
	endif;
	if (have_comments()):
?>
	<h2 class="comments"><?php comments_number('No comments', 'One comment', '% comments' );?></h2>
	<ol class="commentlist">
		<?php wp_list_comments('type=comment&callback=newrico_comment'); ?>
	</ol>
<?php else: ?>
	<?php if (!comments_open()): ?>
		<p class="nocomments">Comments are closed.</p>
	<?php endif; ?>
<?php endif; ?>


<?php if (comments_open()): ?>

<div class="comments-container">

	<h4><?php comment_form_title( 'Add a comment', 'Add a comment to %s' ); ?></h4>

	<div class="cancel-comment-reply">
		<small><?php cancel_comment_reply_link(); ?></small>
	</div>

	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="comment-form" class="comment-form">

		<?php if (is_user_logged_in()) : ?>

		<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>

		<?php else: ?>

		<div class="wrap">
			<label for="author">Name</label>
				<input type="text" name="author" id="author" placeholder="Your name" value="<?php echo esc_attr($comment_author); ?>" />
		</div>

		<div class="wrap">
			<label for="email">Email</label>
				<input type="email" name="email" id="email" placeholder="Your email" value="<?php echo esc_attr($comment_author_email); ?>" />
		</div>

		<?php endif; ?>

		<div class="wrap">
			<label for="comment">Your comment</label>
				<textarea name="comment" id="comment" placeholder="Your comment"></textarea>
		</div>

		<input name="submit" type="submit" id="submit" class="submit" value="Submit Comment" />
		<?php comment_id_fields(); ?>
		<?php do_action('comment_form', $post->ID); ?>

	</form>

</div>

<?php endif; // coments open ?>
