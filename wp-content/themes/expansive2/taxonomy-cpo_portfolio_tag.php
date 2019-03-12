<?php get_header(); ?>

<?php get_template_part('element', 'page-header'); ?>
	
<div id="main" class="main">
	<div class="container">
		<section id="content" class="content">
			<?php do_action('cpotheme_before_content'); ?>
			
			<?php $description = term_description(); ?>
			<?php if($description != ''): ?>
			<div class="page-content">
				<?php echo $description; ?>
			</div>
			<?php endif; ?>
			
			<?php do_action('cpotheme_after_content'); ?>
		</section>
		<?php get_sidebar(); ?>
		<div class="clear"></div>
	</div>
					
	<?php if(have_posts()): $feature_count = 0; ?>
	<div id="portfolio" class="portfolio">
		<?php cpotheme_grid(null, 'element', 'portfolio', 4, array('class' => 'column-fit')); ?>
	</div>
	<?php endif; ?>
	<?php cpotheme_numbered_pagination(); ?>
	
</div>

<?php get_footer(); ?>