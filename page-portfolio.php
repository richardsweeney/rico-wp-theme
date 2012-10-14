
<?php get_header(); ?>

	<div class="clearfix portfolio-container">

		<div class="portfolio left websites eq-height">

			<header>
				<h1>Websites (selected).</h1>
			</header>

		<?php
			$args = array(
				'post_type' => 'portfolio_item',
				'type' => 'website',
				'posts_per_page' => -1
			);
			$query = new WP_Query($args);
			while($query->have_posts()): $query->the_post();
				$url = get_post_meta($post->ID, '_url', true);
				$tagline = get_post_meta($post->ID, '_tagline', true);
				$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'portfolio-image');
			?>
			<div class="portfolio-item">
				<article>
					<header>
						<h1 class="site-url"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					</header>
					<div class="image-container image-border">
						<a href="<?php the_permalink(); ?>">
							<span class="tagline"><?php echo $tagline; ?></span>
							<img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>">
						</a>
					</div>
				</article>
			</div>

		<?php endwhile; wp_reset_query(); ?>

		</div>

		<div class="portfolio right applications eq-height">

			<header>
				<h1>Applications.</h1>
			</header>

		<?php
			$args['type'] = 'application';
			$query = null;
			$query = new WP_Query($args);
			while($query->have_posts()): $query->the_post();
				$url = get_post_meta($post->ID, '_url', true);
				$tagline = get_post_meta($post->ID, '_tagline', true);
				$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'portfolio-image');
				?>

			<div class="portfolio-item">
				<article>
					<header>
						<h1 class="site-url"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					</header>
					<div class="image-container image-border">
						<a href="<?php the_permalink(); ?>">
							<span class="tagline"><?php echo $tagline; ?></span>
							<img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>">
						</a>
					</div>
				</article>
			</div>

		<?php endwhile; wp_reset_query(); ?>

		</div>

	</div>

<?php get_footer(); ?>
