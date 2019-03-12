<?php get_header(); ?>

<?php get_template_part('element', 'page-header'); ?>

<div id="main" class="main">
	<div class="container">
		<?php cpotheme_post_media(get_the_ID(), 'image'); ?>
		<section id="content" class="content">
			<?php do_action('cpotheme_before_content'); ?>
			<?php if(have_posts()) while(have_posts()): the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="page-content">
					<?php the_content(); ?>
				</div>
				<?php cpotheme_post_pagination(); ?>
				<div class="clear"></div>
			</div>
			<?php endwhile; ?>
			
			<?php do_action('cpotheme_after_content'); ?>
		</section>
		<?php get_sidebar(); ?>
		<div class="clear"></div>
	</div>
	<div id="navigation-portfolio" class="navigation-portfolio secondary-color-bg dark">
		<?php previous_post_link('%link', '<div id="nav-prev" class="nav-prev primary-color-bg">%title</div>'); ?>
		<?php next_post_link('%link', '<div id="nav-next" class="nav-next primary-color-bg">%title</div>'); ?>
	</div>
</div>

<?php get_footer(); ?>