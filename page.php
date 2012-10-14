
<?php get_header(); ?>

	<div class="left">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<article>

			<h1 class="blog-title"><?php the_title(); ?></h1>

			<?php the_content(); ?>

		</article>

	<?php endwhile; endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
