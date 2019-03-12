<article <?php post_class(); ?> id="post-<?php the_ID(); ?>"> 
	<div class="post-image">
		<?php cpotheme_postpage_image('portfolio_large'); ?>		
	</div>
	<div class="post-meta secondary-color-bg">
		<div class="primary-color-bg dark">
			<?php cpotheme_postpage_date(); ?>
		</div>
		<?php cpotheme_postpage_comments(); ?>
	</div>
	
	<div class="post-body">
		<?php cpotheme_postpage_title(); ?>
		<div class="post-byline">
			<?php cpotheme_postpage_author(); ?>
			<?php cpotheme_postpage_categories(); ?>
			<?php cpotheme_edit(); ?>
		</div>
		<div class="post-content">
			<?php cpotheme_postpage_content(); ?>
		</div>
		<?php if(is_singular('post')) cpotheme_postpage_tags(false, '', '', ''); ?>
		<?php cpotheme_postpage_readmore('button'); ?>
	</div>
	<div class="clear"></div>
</article>