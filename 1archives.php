
		<?php get_header(); ?>

		<?php get_sidebar(); ?>

		<div id="right">
		
			<h1>Archives</h1>

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
				<div class="post">

					<h2><?php the_title(); ?></h2>
	
					<?php the_content(); ?>
			
					<?php next_post_link('%link', 'Read the next post : <span class="pt">%title</span>', TRUE); ?> <br /><br />
					<?php previous_post_link('%link', 'Read the previous post : <span class="pt">%title</span>', TRUE); ?>
				
				</div>
			
			<?php endwhile; endif; ?>

		</div>

		<?php get_footer(); ?>
		
		
		

