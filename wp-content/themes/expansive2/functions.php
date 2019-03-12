<?php if(!isset($content_width)) $content_width = 640;
define('CPOTHEME_ID', 'expansive');
define('CPOTHEME_NAME', 'Expansive');
define('CPOTHEME_VERSION', '1.0.0');
//Other constants
define('CPOTHEME_LOGO_WIDTH', '220');
define('CPOTHEME_THUMBNAIL_WIDTH', '400');
define('CPOTHEME_USE_SLIDES', true);
define('CPOTHEME_USE_FEATURES', true);
define('CPOTHEME_USE_PORTFOLIO', true);

//Load Core; check existing core or load development core
$core_path = get_template_directory().'/core/';
if(defined('CPO_CORE')) $core_path = CPO_CORELITE;
require_once $core_path.'init.php';

$include_path = get_template_directory().'/includes/';

//Main components
require_once($include_path.'setup.php');