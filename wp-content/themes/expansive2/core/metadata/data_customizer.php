<?php 

//Define customizer sections
if(!function_exists('cpotheme_metadata_panels')){
	function cpotheme_metadata_panels(){
		$data = array();
		
		$data['cpotheme_layout'] = array(
		'title' => __('Layout', 'cpocore'),
		'description' => __('Here you can find settings that control the structure and positioning of specific elements within your website.', 'cpocore'),
		'priority' => 25);
		
		$data['cpotheme_content'] = array(
		'title' => __('Content Areas', 'cpocore'),
		'description' => __('This theme includes a few areas where you can insert cutom content.', 'cpocore'),
		'capability' => 'edit_theme_options',
		'priority' => 26);
		
		return apply_filters('cpotheme_customizer_panels', $data);
	}
}


//Define customizer sections
if(!function_exists('cpotheme_metadata_sections')){
	function cpotheme_metadata_sections(){
		$data = array();
		
		$data['cpotheme_management'] = array(
		'title' => __('General Theme Options', 'cpocore'),
		'description' => __('Options that help you manage your theme better.', 'cpocore'),
		'capability' => 'edit_theme_options',
		'priority' => 15);
		
		$data['cpotheme_layout_general'] = array(
		'title' => __('Site Wide Structure', 'cpocore'),
		'description' => sprintf(__('Upgrade to %s to control the layout of your sidebars and other global elements.', 'cpocore'), '<a target="_blank" href="'.CPOTHEME_PREMIUM_URL.'">'.CPOTHEME_PREMIUM_NAME.'</a>'),
		'capability' => 'edit_theme_options',
		'panel' => 'cpotheme_layout',
		'priority' => 25);
		
		$data['cpotheme_layout_home'] = array(
		'title' => __('Homepage', 'cpocore'),
		'description' => sprintf(__('Upgrade to %s to customize the appearance and behavior of the homepage.', 'cpocore'), '<a target="_blank" href="'.CPOTHEME_PREMIUM_URL.'">'.CPOTHEME_PREMIUM_NAME.'</a>'),
		'capability' => 'edit_theme_options',
		'panel' => 'cpotheme_layout',
		'priority' => 50);
		
		if(defined('CPOTHEME_USE_PORTFOLIO') && CPOTHEME_USE_PORTFOLIO == true){
			$data['cpotheme_layout_portfolio'] = array(
			'title' => __('Portfolio', 'cpocore'),
			'description' => sprintf(__('Upgrade to %s to customize the appearance of the portfolio.', 'cpocore'), '<a target="_blank" href="'.CPOTHEME_PREMIUM_URL.'">'.CPOTHEME_PREMIUM_NAME.'</a>'),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		if(defined('CPOTHEME_USE_SERVICES') && CPOTHEME_USE_SERVICES == true){
			$data['cpotheme_layout_services'] = array(
			'title' => __('Services', 'cpocore'),
			'description' => sprintf(__('Upgrade to %s to customize the appearance of services.', 'cpocore'), '<a target="_blank" href="'.CPOTHEME_PREMIUM_URL.'">'.CPOTHEME_PREMIUM_NAME.'</a>'),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		if(defined('CPOTHEME_USE_TEAM') && CPOTHEME_USE_TEAM == true){
			$data['cpotheme_layout_team'] = array(
			'title' => __('Team Members', 'cpocore'),
			'description' => sprintf(__('Upgrade to %s to customize the appearance of the team section.', 'cpocore'), '<a target="_blank" href="'.CPOTHEME_PREMIUM_URL.'">'.CPOTHEME_PREMIUM_NAME.'</a>'),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		$data['cpotheme_typography'] = array(
		'title' => __('Typography', 'cpocore'),
		'description' => __('Custom typefaces for the entire site.', 'cpocore'),
		'capability' => 'edit_theme_options',
		'priority' => 45);

		$data['cpotheme_layout_posts'] = array(
		'title' => __('Blog Posts', 'cpocore'),
		'description' => sprintf(__('Upgrade to %s to modify the appearance and behavior of your blog posts.', 'cpocore'), '<a target="_blank" href="'.CPOTHEME_PREMIUM_URL.'">'.CPOTHEME_PREMIUM_NAME.'</a>'),
		'capability' => 'edit_theme_options',
		'panel' => 'cpotheme_layout',
		'priority' => 50);
		
		$data['cpotheme_typography'] = array(
		'title' => __('Typography', 'cpocore'),
		'description' => sprintf(__('Upgrade to %s to gain full control over the typography of your site.', 'cpocore'), '<a target="_blank" href="'.CPOTHEME_PREMIUM_URL.'">'.CPOTHEME_PREMIUM_NAME.'</a>'),
		'capability' => 'edit_theme_options',
		'priority' => 45);
		
		$data['cpotheme_content_general'] = array(
		'title' => __('Site Wide Content', 'cpocore'),
		'description' => __('Content areas located in common areas throughout the site. You can use HTML and shortcodes here.', 'cpocore'),
		'capability' => 'edit_theme_options',
		'panel' => 'cpotheme_content',
		'priority' => 50);
		
		$data['cpotheme_content_home'] = array(
		'title' => __('Homepage', 'cpocore'),
		'description' => __('Add custom content to specific areas of the homepage. You can use HTML and shortcodes here.', 'cpocore'),
		'capability' => 'edit_theme_options',
		'panel' => 'cpotheme_content',
		'priority' => 50);

		return apply_filters('cpotheme_customizer_sections', $data);
	}
}


if(!function_exists('cpotheme_metadata_customizer')){
	function cpotheme_metadata_customizer($std = null){
		$data = array();
		
		$data['general_logo'] = array(
		'label' => __('Custom Logo', 'cpocore'),
		'description' => __('Insert the URL of an image to be used as a custom logo.', 'cpocore'),
		'section' => 'title_tagline',
		'sanitize' => 'esc_url',
		'type' => 'image');

		$data['general_favicon'] = array(
		'label' => __('Custom Favicon', 'cpocore'),
		'description' => __('Recommended sizes are 16x16 pixels.', 'cpocore'),
		'section' => 'title_tagline',
		'sanitize' => 'esc_url',
		'type' => 'image');
		
		$data['general_logo_width'] = array(
		'label' => __('Logo Width (px)', 'cpocore'),
		'description' => __('Forces the logo to have a specified width.', 'cpocore'),
		'section' => 'title_tagline',
		'type' => 'text',
		'placeholder' => '(none)',
		'sanitize' => 'absint',
		'width' => '100px');
		
		$data['general_texttitle'] = array(
		'label' => __('Enable Text Title?', 'cpocore'),
		'description' => __('Activate this to display the site title as text.', 'cpocore'),
		'section' => 'title_tagline',
		'type' => 'checkbox',
		'std' => false);
		
		$data['general_editlinks'] = array(
		'label' => __('Show Edit Links', 'cpocore'),
		'description' => __('Display edit links on the site layout for logged in users.', 'cpocore'),
		'section' => 'cpotheme_management',
		'type' => 'checkbox',
		'std' => '1');
		
		//Layout
		$data['general_css'] = array(
		'label' => __('Custom CSS', 'cpocore'),
		'description' => __('Add custom CSS styling for the entire site, overriding the default stylesheet.', 'cpocore'),
		'section' => 'cpotheme_management',
		'type' => 'textarea',
		'sanitize' => 'wp_filter_nohtml_kses',
		'format' => 'css');
		
		$data['general_credit'] = array(
		'label' => __('Show Credit Link', 'cpocore'),
		'section' => 'cpotheme_layout_general',
		'type' => 'checkbox',
		'default' => '1');
		
		$data['homepage_settings'] = array(
		'label' => __('Layout Options', 'cpocore'),
		'description' => __('Control the appearance and layout of specific elements, such as the language switcher, breadcrumbs, or shopping cart.', 'cpocore'),
		'section' => 'cpotheme_layout_general',
		'type' => 'label');
		
		$data['home_posts'] = array(
		'label' => __('Enable Posts On Homepage', 'cpocore'),
		'section' => 'cpotheme_layout_home',
		'type' => 'checkbox',
		'default' => false);
		
		//Homepage Slider
		if(defined('CPOTHEME_USE_SLIDES') && CPOTHEME_USE_SLIDES == true){
			$data['slider_settings'] = array(
			'label' => __('Slider Options', 'cpocore'),
			'description' => __('Customize the speed, timeout and effects of the homepage slider.', 'cpocore'),
			'section' => 'cpotheme_layout_home',
			'type' => 'label');
		}
		
		//Homepage Features
		if(defined('CPOTHEME_USE_FEATURES') && CPOTHEME_USE_FEATURES == true){
			$data['features_settings'] = array(
			'label' => __('Features Options', 'cpocore'),
			'description' => __('Customize the layout of the feature blocks in the homepage.', 'cpocore'),
			'section' => 'cpotheme_layout_home',
			'type' => 'label');
		}
		
		//Portfolio layout
		if(defined('CPOTHEME_USE_PORTFOLIO') && CPOTHEME_USE_PORTFOLIO == true){			
			$data['portfolio_settings'] = array(
			'label' => __('Portfolio Options', 'cpocore'),
			'description' => __('Customize the layout, columns and appearance of the portfolio.', 'cpocore'),
			'section' => 'cpotheme_layout_portfolio',
			'type' => 'label');
		}
		
		//Services layout
		if(defined('CPOTHEME_USE_SERVICES') && CPOTHEME_USE_SERVICES == true){
			$data['services_settings'] = array(
			'label' => __('Service Options', 'cpocore'),
			'description' => __('Customize the layout of services.', 'cpocore'),
			'section' => 'cpotheme_layout_services',
			'type' => 'label');
		}
		
		//Services layout
		if(defined('CPOTHEME_USE_TEAM') && CPOTHEME_USE_TEAM == true){
			$data['team_settings'] = array(
			'label' => __('Team Options', 'cpocore'),
			'description' => __('Customize the layout of the team listing.', 'cpocore'),
			'section' => 'cpotheme_layout_team',
			'type' => 'label');
		}
		
		//Blog Posts
		$data['postpage_settings'] = array(
		'label' => __('Post Options', 'cpocore'),
		'description' => __('Control the appearance of specific elements in your blog posts such as dates, authors, or comments.', 'cpocore'),
		'section' => 'cpotheme_layout_posts',
		'type' => 'label');
		
		//Typography
		$data['type_settings'] = array(
		'label' => __('Typography Options', 'cpocore'),
		'description' => __('Select custom fonts for the headings, navigation, and body text of your site.', 'cpocore'),
		'section' => 'cpotheme_typography',
		'type' => 'label');
		
		//Colors		
		$data['color_settings'] = array(
		'label' => __('Color Options', 'cpocore'),
		'description' => __('Customize the colors of primary and secondary elements, as well as headings, navigation, and text.', 'cpocore'),
		'section' => 'colors',
		'type' => 'label');
		
		$data['content_settings'] = array(
		'label' => __('Content Options', 'cpocore'),
		'description' => __('Add custom text on the footer and link your preferred social media profiles.', 'cpocore'),
		'section' => 'cpotheme_content_general',
		'type' => 'label');
		
		//Homepage Content
		$data['home_tagline'] = array(
		'label' => __('Tagline Title', 'cpocore'),
		'section' => 'cpotheme_content_home',
		'empty' => true,
		'default' => __('Add your custom tagline here.', 'cpotheme'),
		'sanitize' => 'esc_html',
		'type' => 'textarea');
		
		if(defined('CPOTHEME_USE_FEATURES') && CPOTHEME_USE_FEATURES == true){
			$data['home_features'] = array(
			'label' => __('Features Description', 'cpotheme'),
			'section' => 'cpotheme_content_home',
			'empty' => true,
			'default' => __('Our core features', 'cpotheme'),
			'sanitize' => 'esc_html',
			'type' => 'textarea');
		}
		
		if(defined('CPOTHEME_USE_PORTFOLIO') && CPOTHEME_USE_PORTFOLIO == true){
			$data['home_portfolio'] = array(
			'label' => __('Portfolio Description', 'cpotheme'),
			'section' => 'cpotheme_content_home',
			'empty' => true,
			'default' => __('Take a look at our work', 'cpotheme'),
			'sanitize' => 'esc_html',
			'type' => 'textarea');
		}
		
		if(defined('CPOTHEME_USE_SERVICES') && CPOTHEME_USE_SERVICES == true){
			$data['home_services'] = array(
			'label' => __('Services Description', 'cpotheme'),
			'section' => 'cpotheme_content_home',
			'empty' => true,
			'default' => __('What we can offer you', 'cpotheme'),
			'sanitize' => 'esc_html',
			'type' => 'textarea');
		}
		
		if(defined('CPOTHEME_USE_TEAM') && CPOTHEME_USE_TEAM == true){
			$data['home_team'] = array(
			'label' => __('Team Members Description', 'cpotheme'),
			'section' => 'cpotheme_content_home',
			'empty' => true,
			'default' => __('Meet our team', 'cpotheme'),
			'sanitize' => 'esc_html',
			'type' => 'textarea');
		}
		
		if(defined('CPOTHEME_USE_TESTIMONIALS') && CPOTHEME_USE_TESTIMONIALS == true){
			$data['home_testimonials'] = array(
			'label' => __('Testimonials Description', 'cpotheme'),
			'section' => 'cpotheme_content_home',
			'empty' => true,
			'default' => __('What they say about us', 'cpotheme'),
			'sanitize' => 'esc_html',
			'type' => 'textarea');
		}
		
		return apply_filters('cpotheme_customizer_controls', $data);
	}
}