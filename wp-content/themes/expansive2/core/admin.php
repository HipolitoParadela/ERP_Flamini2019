<?php

//Define custom columns for each custom post type page
if(!function_exists('cpocore_admin_columns')){
	add_action('manage_posts_custom_column', 'cpocore_admin_columns', 2);
	function cpocore_admin_columns($column){
		global $post;
		switch($column){
			case 'cpo-image': echo get_the_post_thumbnail($post->ID, array(60,60)); break;
			//Portfolio
			case 'cpo-portfolio-cats': echo get_the_term_list($post->ID, 'cpo_portfolio_category', '', ', ', ''); break;
			case 'cpo-portfolio-tags': echo get_the_term_list($post->ID, 'cpo_portfolio_tag', '', ', ', ''); break;
			//Services
			case 'cpo-service-cats': echo get_the_term_list($post->ID, 'cpo_service_category', '', ', ', ''); break;
			case 'cpo-service-tags': echo get_the_term_list($post->ID, 'cpo_service_tag', '', ', ', ''); break;
			//Team
			case 'cpo-team-cats': echo get_the_term_list($post->ID, 'cpo_team_category', '', ', ', ''); break;
			//Products
			case 'cpo-product-cats': echo get_the_term_list($post->ID, 'cpo_product_category', '', ', ', ''); break;
			case 'cpo-product-tags': echo get_the_term_list($post->ID, 'cpo_product_tag', '', ', ', ''); break;
				
			default:break;
		}
	}
}


//Display welcome wizard-- it should help new users get going
if(!function_exists('cpotheme_admin_wizard')){
	add_action('admin_notices', 'cpotheme_admin_wizard');
	function cpotheme_admin_wizard(){
		$screen = get_current_screen();
		$wizard_dismissed = trim(cpotheme_get_option(CPOTHEME_ID.'_wizard', 'cpotheme_settings', false));
		
		//Display only on the dashboard and custom post listings
		$display = true;
		if(isset($_GET['action']) && $_GET['action'] == 'edit' || $screen->action == 'add' || $screen->base == 'plugins' || $screen->base == 'widgets') 
			$display = false;
		
		//If notice hasn't been explicitly dismissed, display it
		if(current_user_can('manage_options') && $wizard_dismissed != 'dismissed' && $display){
			
			$core_path = defined('CPO_CORELITE_URL') ? CPO_CORELITE_URL : get_template_directory_uri().'/core/';
			
			echo '<div id="message" class="updated">';
			echo '<div class="cpotheme-notice">';
			echo '<a href="'.add_query_arg('ctdismiss', CPOTHEME_ID.'_wizard').'" class="cpothemes-notice-dismiss">'.__('Dismiss This Notice', 'cpocore').'</a>';
			echo '<img class="cpotheme-wizard-image" src="'.$core_path.'images/ct-icon.png">';
			echo '<h3 class="cpotheme-wizard-title">'.sprintf(__('%s Is Ready', 'cpocore'), CPOTHEME_NAME).'</h3>';
			echo '<p class="cpotheme-wizard-description">'.__('Your new theme has been activated! To get started, check the following list:', 'cpocore').'</p>';
			
			echo '<div class="cpotheme-wizard-clear"></div>';

			
			//RECOMMENDED PLUGINS
			echo '<div class="cpotheme-wizard-column">';
			echo '<h3>1. '.__('Install Recommended Plugins', 'cpocore').'</h3>';
			echo __('This theme works well with a number of free plugins to unlock its full potential. It is highly recommended that you install them.', 'cpocore');
			//CPO Content Types
			$plugin_url = add_query_arg(array('tab' => 'plugin-information', 'plugin' => 'cpo-content-types', 'TB_iframe' => 'true', 'width' => '640', 'height' => '500'), admin_url('plugin-install.php'));
			echo '<a class="cpotheme-wizard-task thickbox" href="'.$plugin_url.'"><span class="cpotheme-wizard-icon dashicons-before dashicons-admin-plugins"></span> <strong>'.__('Install CPO Content Types', 'cpocore').'</strong></a>';
			//CPO Shortcodes
			$plugin_url = add_query_arg(array('tab' => 'plugin-information', 'plugin' => 'cpo-shortcodes', 'TB_iframe' => 'true', 'width' => '640', 'height' => '500'), admin_url('plugin-install.php'));
			echo '<a class="cpotheme-wizard-task thickbox" href="'.$plugin_url.'"><span class="cpotheme-wizard-icon dashicons-before dashicons-admin-plugins"></span> '.__('Install CPO Shortcodes', 'cpocore').'</a>';
			//CPO Widgets
			$plugin_url = add_query_arg(array('tab' => 'plugin-information', 'plugin' => 'cpo-widgets', 'TB_iframe' => 'true', 'width' => '640', 'height' => '500'), admin_url('plugin-install.php'));
			echo '<a class="cpotheme-wizard-task thickbox" href="'.$plugin_url.'"><span class="cpotheme-wizard-icon dashicons-before dashicons-admin-plugins"></span> '.__('Install CPO Widgets', 'cpocore').'</a>';
			echo '</div>';
			
			//CONTENT TYPES
			echo '<div class="cpotheme-wizard-column">';
			echo '<h3>2. '.__('Add Custom Content Types', 'cpocore').'</h3>';
			echo __('This theme supports special content types. Populate the following and your site will start taking shape.', 'cpocore');
			echo ' ';
			$plugin_url = add_query_arg(array('tab' => 'plugin-information', 'plugin' => 'cpo-content-types', 'TB_iframe' => 'true', 'width' => '640', 'height' => '500'), admin_url('plugin-install.php'));
			echo sprintf(__('You will need the %s plugin.', 'cpocore'), '<strong><a class="thickbox" href="'.$plugin_url.'">CPO Content Types</a></strong>');
			//Posts and Pages - In case there are no custom post types
			if(!defined('CPOTHEME_USE_SLIDES') && !defined('CPOTHEME_USE_FEATURES') && !defined('CPOTHEME_USE_PORTFOLIO') && !defined('CPOTHEME_USE_PRODUCTS') && !defined('CPOTHEME_USE_SERVICES') && !defined('CPOTHEME_USE_TESTIMONIALS') && !defined('CPOTHEME_USE_TEAM'))
				echo '<a class="cpotheme-wizard-task" href="edit.php?post_type=cpo_slide"><span class="cpotheme-wizard-icon dashicons-before dashicons-admin-post"></span> '.__('Start creating posts', 'cpocore').'</a>';
			//Homepage Slides
			if(defined('CPOTHEME_USE_SLIDES') && CPOTHEME_USE_SLIDES == true)
				echo '<a class="cpotheme-wizard-task" href="edit.php?post_type=cpo_slide"><span class="cpotheme-wizard-icon dashicons-before dashicons-images-alt2"></span> '.__('Add slides to the homepage', 'cpocore').'</a>';
			//Homepage features
			if(defined('CPOTHEME_USE_FEATURES') && CPOTHEME_USE_FEATURES == true)
				echo '<a class="cpotheme-wizard-task" href="edit.php?post_type=cpo_feature"><span class="cpotheme-wizard-icon dashicons-before dashicons-star-filled"></span> '.__('Add feature blocks to the homepage', 'cpocore').'</a>';
			//Portfolio
			if(defined('CPOTHEME_USE_PORTFOLIO') && CPOTHEME_USE_PORTFOLIO == true)
				echo '<a class="cpotheme-wizard-task" href="edit.php?post_type=cpo_portfolio"><span class="cpotheme-wizard-icon dashicons-before dashicons-portfolio"></span> '.__('Create your portfolio items', 'cpocore').'</a>';			
			//Products
			if(defined('CPOTHEME_USE_PRODUCTS') && CPOTHEME_USE_PRODUCTS == true)
				echo '<a class="cpotheme-wizard-task" href="edit.php?post_type=cpo_product"><span class="cpotheme-wizard-icon dashicons-before dashicons-cart"></span> '.__('Showcase your products', 'cpocore').'</a>';
			//Services
			if(defined('CPOTHEME_USE_SERVICES') && CPOTHEME_USE_SERVICES == true)
				echo '<a class="cpotheme-wizard-task" href="edit.php?post_type=cpo_service"><span class="cpotheme-wizard-icon dashicons-before dashicons-archive"></span> '.__('List your services', 'cpocore').'</a>';			
			//Testimonials
			if(defined('CPOTHEME_USE_TESTIMONIALS') && CPOTHEME_USE_TESTIMONIALS == true)
				echo '<a class="cpotheme-wizard-task" href="edit.php?post_type=cpo_testimonial"><span class="cpotheme-wizard-icon dashicons-before dashicons-format-chat"></span> '.__('Add some testimonials', 'cpocore').'</a>';
			//Team
			if(defined('CPOTHEME_USE_TEAM') && CPOTHEME_USE_TEAM == true)
				echo '<a class="cpotheme-wizard-task" href="edit.php?post_type=cpo_team"><span class="cpotheme-wizard-icon dashicons-before dashicons-universal-access"></span> '.__('Add your team members', 'cpocore').'</a>';
			//Portfolio listing
			if(defined('CPOTHEME_USE_PORTFOLIO') && CPOTHEME_USE_PORTFOLIO == true)
				echo '<a class="cpotheme-wizard-task" href="post-new.php?post_type=page"><span class="cpotheme-wizard-icon dashicons-before dashicons-welcome-add-page"></span> '.__('Create a page with the Portfolio template', 'cpocore').'</a>';
			//Products listing
			if(defined('CPOTHEME_USE_PRODUCTS') && CPOTHEME_USE_PRODUCTS == true)
				echo '<a class="cpotheme-wizard-task" href="post-new.php?post_type=page"><span class="cpotheme-wizard-icon dashicons-before dashicons-welcome-add-page"></span> '.__('Create a page with the Products template', 'cpocore').'</a>';
			//Services listing
			if(defined('CPOTHEME_USE_SERVICES') && CPOTHEME_USE_SERVICES == true)
				echo '<a class="cpotheme-wizard-task" href="post-new.php?post_type=page"><span class="cpotheme-wizard-icon dashicons-before dashicons-welcome-add-page"></span> '.__('Create a page with the Services template', 'cpocore').'</a>';
			//Team listing
			if(defined('CPOTHEME_USE_TEAM') && CPOTHEME_USE_TEAM == true)
				echo '<a class="cpotheme-wizard-task" href="post-new.php?post_type=page"><span class="cpotheme-wizard-icon dashicons-before dashicons-welcome-add-page"></span> '.__('Create a page with the Team template', 'cpocore').'</a>';			
			echo '</div>';
			
			
			//THEME OPTIONS
			echo '<div class="cpotheme-wizard-column cpotheme-wizard-column-last">';
			echo '<h3>3. '.__('Configure The Theme', 'cpocore').'</h3>';
			echo __('Add the finishing touch. Customize your theme using the theme options page, add your menus, and create your sidebar widgets.', 'cpocore');
			echo '<a class="cpotheme-wizard-task" href="customize.php"><span class="cpotheme-wizard-icon dashicons-before dashicons-admin-appearance"></span> '.__('Customize the appearance of your site', 'cpocore').'</a>';
			echo '<a class="cpotheme-wizard-task" href="nav-menus.php"><span class="cpotheme-wizard-icon dashicons-before dashicons-menu"></span> '.__('Set up the main navigation menu', 'cpocore').'</a>';
			echo '<a class="cpotheme-wizard-task" href="widgets.php"><span class="cpotheme-wizard-icon dashicons-before dashicons-welcome-widgets-menus"></span> '.__('Add some widgets to your sidebar', 'cpocore').'</a>';
			echo '</div>';
			
			echo '<div class="cpotheme-wizard-clear"></div>';
			echo '</div>';			
			echo '</div>';
		}
	}
}


//Notice display and dismissal
if(!function_exists('cpotheme_admin_notice_control')){
	add_action('admin_init', 'cpotheme_admin_notice_control');
	function cpotheme_admin_notice_control(){
		//Display a notice
		if(isset($_GET['ctdisplay']) && $_GET['ctdisplay'] != ''){
			cpotheme_update_option(htmlentities($_GET['ctdisplay']), 'display');
			wp_redirect(remove_query_arg('ctdisplay'));
		}
		//Dismiss a notice
		if(isset($_GET['ctdismiss']) && $_GET['ctdismiss'] != ''){
			cpotheme_update_option(htmlentities($_GET['ctdismiss']), 'dismissed');
			wp_redirect(remove_query_arg('ctdismiss'));
		}
	}
}

//Add a help link next to the Screen Options tab
if(!function_exists('cpotheme_admin_help')){
	//add_action('admin_footer', 'cpotheme_admin_help');
	function cpotheme_admin_help(){
		$core_path = defined('CPO_CORELITE_URL') ? CPO_CORELITE_URL : get_template_directory_uri().'/core/';
		echo '<div id="cpotheme-help" style="display:none;">';
		echo '<a class="cpotheme-help-link" href="http://cpothemes.com/documentation" target="_blank">';
		echo '<img class="cpotheme-help-link-image" src="'.$core_path.'images/ct-icon.png">';
		echo __('Theme Documentation', 'cpocore');
		echo '</a>';	
		echo '</div>';
	}
}