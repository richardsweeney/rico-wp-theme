
<?php get_header(); ?>

	<div class="landing-page-container">

		<div class="lading-page-rubrik">
			<p class="welcome">Hi. I'm a social, creative front-end developer.</p>
			<p class="likes">I like a lot of things about my job, but I really love JavaScript, PHP, HTML5, CSS3, jQuery &amp; WordPress.</p>
		</div>

		<section>

			<div class="recent-projects clearfix">

				<header>
					<h1 class="section-header">Recent projects</h1>
				</header>

				<div class="portfolio left websites landing-page">

				<?php
					$args = array(
						'post_type' => 'portfolio_item',
						'type' => 'website',
						'posts_per_page' => 1
					);
					$query = new WP_Query($args);
					while($query->have_posts()): $query->the_post();
						$url = get_post_meta($post->ID, '_url', true);
						$tagline = get_post_meta($post->ID, '_tagline', true);
						$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'portfolio-image');
					?>

					<article>
						<header>
							<h1 class="site-url"><a href="<?php echo URL; ?>/portfolio/"><?php the_title(); ?></a></h1>
						</header>
						<div class="image-border">
							<a href="<?php echo URL; ?>/portfolio/">
								<span class="tagline"><?php echo $tagline; ?></span>
								<img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>">
							</a>
						</div>
					</article>

				<?php endwhile; wp_reset_query(); ?>

				</div>

				<div class="portfolio right applications landing-page">
				<?php
					$args['type'] = 'application';
					$new_query = new WP_Query($args);
					while($new_query->have_posts()): $new_query->the_post();
						$url = get_post_meta($post->ID, '_url', true);
						$tagline = get_post_meta($post->ID, '_tagline', true);
						$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'portfolio-image');
					?>

					<article>
						<header>
							<h1 class="site-url"><a href="<?php echo URL; ?>/portfolio/"><?php the_title(); ?></a></h1>
						</header>
						<div class="image-border">
							<a href="<?php echo URL; ?>/portfolio/">
								<span class="tagline"><?php echo $tagline; ?></span>
								<img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>">
							</a>
						</div>
					</article>

				<?php endwhile; wp_reset_query(); ?>

			</div>

		</section>

		<section>

			<div class="latest-blog clearfix">

				<header>
					<h1 class="section-header">Latest Blog</h1>
				</header>
				<?php
					$args['post_type'] = 'post';
					$args['posts_per_page'] = 1;
					unset($args['type']);
					$query = new WP_Query($args);
					while ($query->have_posts()): $query->the_post();
						if (has_post_thumbnail()):
						?>

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

					<?php else: ?>

						<div class="blog-page-excerpt no-thumbnail">

							<article>

								<div class="header-meta-container">
									<header>
										<h1 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
									</header>
									<footer>
										<p class="post-meta">Posted : <?php the_time('F jS, Y'); ?> in <?php the_category(' '); ?><br><?php comments_number( '', '1 comment', '% comments' ); ?></p>
									</footer>
								</div>

								<div class="excerpt-container">
									<?php rps_nicer_excerpt(array('words' => 40)); ?>
								</div>

							</article>

						</div>

					<?php endif; endwhile; wp_reset_query(); ?>

			</div>

		</section>

	</div>

<?php get_footer(); ?>
