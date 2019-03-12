<?php 

//Top elements
add_action('cpotheme_top', 'cpotheme_top_menu');

//Header elements
add_action('cpotheme_header', 'cpotheme_logo');
add_action('cpotheme_header', 'cpotheme_menu_toggle');
add_action('cpotheme_header', 'cpotheme_menu');

//Before main elements
add_action('cpotheme_before_main', 'cpotheme_home_slider');
add_action('cpotheme_before_main', 'cpotheme_home_tagline');
add_action('cpotheme_before_main', 'cpotheme_home_features');
add_action('cpotheme_before_main', 'cpotheme_home_portfolio');

//Page title elements
add_action('cpotheme_title', 'cpotheme_page_title');
add_action('cpotheme_title', 'cpotheme_breadcrumb');

//After main elements

//Subfooter elements
add_action('cpotheme_subfooter', 'cpotheme_subfooter');

//Footer elements
add_action('cpotheme_footer', 'cpotheme_footer_menu');
add_action('cpotheme_footer', 'cpotheme_footer');


//Add homepage slider
function cpotheme_home_slider(){ 
	if(is_home() || is_front_page()) get_template_part('homepage', 'slider'); 
}

//Add homepage features
function cpotheme_home_features(){ 
	if(is_home() || is_front_page()) get_template_part('homepage', 'features'); 
}

//Add homepage tagline
function cpotheme_home_tagline(){ 
	if(is_home() || is_front_page()) cpotheme_block('home_tagline', 'tagline dark primary-color-bg', 'container'); 
}

//Add homepage portfolio
function cpotheme_home_portfolio(){ 
	if(is_home() || is_front_page()) get_template_part('homepage', 'portfolio'); 
}

add_filter('cpotheme_font_headings', 'cpotheme_theme_fonts');
add_filter('cpotheme_font_menu', 'cpotheme_theme_fonts');
add_filter('cpotheme_font_body', 'cpotheme_theme_fonts');
function cpotheme_theme_fonts($data){ 
	return 'Source+Sans+Pro';
}

//set settings defaults
add_filter('cpotheme_customizer_controls', 'cpotheme_customizer_controls');
function cpotheme_customizer_controls($data){ 
	//Layout
	$data['layout_subfooter_columns']['default'] = 4;
	//Content
	unset($data['home_features']);
	
	return $data;
}

add_action('after_setup_theme', 'cpotheme_theme_images');
if(!function_exists('cpotheme_theme_images')){
	function cpotheme_theme_images(){
		add_image_size('portfolio_large', 800, 400, true);
	}
}

//set settings defaults
add_action('wp_enqueue_scripts', 'cpotheme_theme_scripts');
function cpotheme_theme_scripts($data){ 
	wp_enqueue_script('cpotheme_general', get_template_directory_uri().'/scripts/general.js', array('jquery'), false, true);
}