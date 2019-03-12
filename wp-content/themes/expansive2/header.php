<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div class="outer" id="top">
		<?php do_action('cpotheme_before_wrapper'); ?>
		<div class="wrapper">
			<header id="header" class="header">
				<div id="topbar" class="topbar dark">
					<?php do_action('cpotheme_top'); ?>
					<div class="clear"></div>
				</div>
				<?php do_action('cpotheme_header'); ?>
				<div class='clear'></div>
			</header>
			<?php do_action('cpotheme_before_main'); ?>
			<div class="clear"></div>