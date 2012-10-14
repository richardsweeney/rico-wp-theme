
<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>

	<article>

		<div class="left left-50">
			<header>
				<h1><?php the_title(); ?></h1>
			</header>
			<?php the_content(); ?>
		</div>

		<div class="right-50">

			<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large'); ?>
			<img class="image-border" src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>">

		</div>

	</article>

	<?php endwhile; ?>

<?php get_footer(); ?>
