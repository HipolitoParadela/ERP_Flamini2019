<div class="feature">
	<?php if(has_post_thumbnail()): ?>
	<div class="feature-image secondary-color-bg dark">
		<?php the_post_thumbnail('portfolio'); ?>
		<?php cpotheme_icon(get_post_meta(get_the_ID(), 'feature_icon', true), 'feature-icon'); ?>
	</div>
	<?php endif; ?>
	
	<div class="feature-body">
		<h3 class="feature-title">
			<?php the_title(); ?>
		</h3>
		<div class="feature-content"><?php the_content(); ?><?php cpotheme_edit(); ?></div>
	</div>
</div>