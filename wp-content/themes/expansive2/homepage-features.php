<?php $query = new WP_Query('post_type=cpo_feature&posts_per_page=-1&order=ASC&orderby=menu_order'); ?>
<?php if($query->posts): $feature_count = 0; ?>
<div id="features" class="features">
	<?php cpotheme_grid($query->posts, 'element', 'feature', 2, array('class' => 'column-early column-fit')); ?>
</div>
<?php endif; ?>