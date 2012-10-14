<?php get_header(); ?>

	<div class="left blog-container">

	<?php while (have_posts()) : the_post(); ?>

		<article>
			<header>
				<h1><?php the_title(); ?></h1>
			</header>
			<?php if(has_post_thumbnail()): ?>
				<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
				<img class="image-border full-post-thumbnail" src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>">
			<?php endif; ?>
			<?php the_content(); ?>
			<footer>
				<p class="post-meta">Posted : <?php the_time('F jS, Y'); ?> in <?php the_category(' '); ?></a></p>
			</footer>

		</article>

		<?php comments_template(); ?>

		<ul class="next-prev-posts">
			<?php previous_post_link('<li>&laquo; Previous post: %link</li>'); ?>
	 		<?php next_post_link('<li>&raquo; Next post: %link</li>'); ?>
	 	</ul>

	<?php endwhile; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
