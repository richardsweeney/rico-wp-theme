
		<?php get_header(); ?>

		<?php get_sidebar(); ?>

		<div id="right">

			<h3>Archive for
		
 	  <?php
 	  	
 	  	if (is_day()) {
 	  		the_time('F jS, Y');
 	  	}	elseif (is_month()) {
		 		the_time('F, Y');
			} elseif (is_year()) {
				the_time('Y');
			}
		
		?>
		
			</h3>

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
				<div class="post">

					<h4 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					
					<?php the_content(); comments_template(); ?>
				
					<p class="post-meta">Posted on <?php the_date(); ?> in <?php the_category(' '); ?> : <?php comments_popup_link(__('No comments yet'), __('1 comment so far'), __('% Comments')); ?> : <a href="<?php comments_link(); ?>">leave a comment</a></p>
				
				</div>
			
			<?php endwhile;
			
			 	next_posts_link('next page &raquo;');
				previous_posts_link('&laquo; previous page');
			
			 endif; ?>

		</div>

		<?php get_footer(); ?>
		
		
		

