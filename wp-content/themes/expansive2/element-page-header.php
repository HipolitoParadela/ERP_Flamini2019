<?php wp_reset_query(); ?>

<?php $image_url = cpotheme_header_image(); ?>
<?php if($image_url != '') $image_url = 'style="background-image:url('.esc_url($image_url).');"'; ?>
<section id="pagetitle" class="pagetitle primary-color-bg dark" <?php echo $image_url; ?>>
	<div class="container">
		<?php do_action('cpotheme_title'); ?>
	</div>
</section>