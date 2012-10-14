
<?php get_header(); ?>

<div class="blog-page">

	<div class="posts-container">
	<?php global $category_name, $cat; ?>
	<?php if(!empty($cat)): ?>
		<header>
			<h1 class="category-header">Writings on the subject of <em><?php echo $category_name; ?></em></h1>
		</header>
	<?php endif; ?>

	<?php
		global $posts_per_page;
		if(isset($_GET['page'])):
			$currentPage = (int) $_GET['page'];
			$offset = ($currentPage - 1) * $posts_per_page;
		else:
			$currentPage = 1;
		endif;
		$args = array(
			'offset' => $offset,
			'cat' => $cat
		);
		query_posts($args);
		while (have_posts()): the_post();
	?>

	<?php if (has_post_thumbnail()): ?>

		<div class="blog-page-excerpt has-thumbnail">

			<article>

				<div class="header-excerpt-meta-container">

					<header>
						<h1 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					</header>
					<footer>
						<p class="post-meta"><?php the_time('F jS, Y'); ?> in <?php the_category(' '); ?> <?php comments_number( '', '- 1 comment', '- % comments' ); ?></p>
					</footer>

					<div class="excerpt-container">
						<?php rps_nicer_excerpt(array('words' => 60)); ?>
					</div>

				</div>

				<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium'); ?>
				<div class="thumbnail-container">
					<a href="<?php the_permalink(); ?>">
						<img class="post-thumbnail" src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>">
					</a>
				</div>

			</article>

		</div>

	<?php else: //No thumbnail ?>

		<div class="blog-page-excerpt no-thumbnail">

			<article>

				<div class="header-meta-container">
					<header>
						<h1 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					</header>
					<footer>
						<p class="post-meta"><?php the_time('F jS, Y'); ?> in <?php the_category(' '); ?> <?php comments_number( '', ' - 1 comment', ' - % comments' ); ?></p>
					</footer>
				</div>

				<div class="excerpt-container">
					<?php rps_nicer_excerpt(array('words' => 40)); ?>
				</div>

			</article>

		</div>

	<?php endif; endwhile; ?>

	</div>

	<?php rps_pagination(); ?>

</div>

<?php get_footer(); ?>




