<?php
/**
 * Genesis Sample.
 *
 * This file adds functions to the Genesis Sample Theme.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 */

! defined('THEME_DIR_PATH') ? define('THEME_DIR_PATH', get_stylesheet_directory()) : '';
! defined('THEME_DIR_URI') ? define('THEME_DIR_URI', get_stylesheet_directory_uri()) : '';

# Start the engine.
include_once(get_template_directory() .'/lib/init.php');

# Setup Theme.
include_once(THEME_DIR_PATH .'/lib/theme-defaults.php');

# Set Localization (do not remove).
add_action('after_setup_theme', 'localizationSetup');
function localizationSetup(){
	load_child_theme_textdomain('theme', THEME_DIR_PATH .'/languages');
}

# Add the helper functions.
include_once(THEME_DIR_PATH .'/lib/helper-functions.php');

# Add Image upload and Color select to WordPress Theme Customizer.
require_once(THEME_DIR_PATH .'/lib/customize.php');

# Include Customizer CSS.
include_once(THEME_DIR_PATH .'/lib/output.php');

# Add WooCommerce support.
include_once(THEME_DIR_PATH .'/lib/woocommerce/woocommerce-setup.php');

# Add the required WooCommerce styles and Customizer CSS.
include_once(THEME_DIR_PATH .'/lib/woocommerce/woocommerce-output.php');

# Add the Genesis Connect WooCommerce notice.
include_once(THEME_DIR_PATH .'/lib/woocommerce/woocommerce-notice.php');

# Child theme (do not remove).
define('CHILD_THEME_NAME', 'Genesis Sample');
define('CHILD_THEME_URL', 'http://www.studiopress.com/');
define('CHILD_THEME_VERSION', '1.0.0');

# Enqueue Scripts and Styles.
add_action('wp_enqueue_scripts', 'enqueueScriptsStyles');
function enqueueScriptsStyles() {

	wp_enqueue_style('theme-fonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700', [], CHILD_THEME_VERSION);
	wp_enqueue_style('dashicons');

	$suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
	wp_enqueue_script('theme-responsive-menu', THEME_DIR_URI .'/js/responsive-menus'. $suffix .'.js', ['jquery'], CHILD_THEME_VERSION, true);
	wp_localize_script(
		'theme-responsive-menu',
		'genesis_responsive_menu',
		responsiveMenuSettings()
	);

}

# Define our responsive menu settings.
function responsiveMenuSettings() {

	$settings = [
		'mainMenu'          => __('Menu', 'theme'),
		'menuIconClass'     => 'dashicons-before dashicons-menu',
		'subMenu'           => __('Submenu', 'theme'),
		'subMenuIconsClass' => 'dashicons-before dashicons-arrow-down-alt2',
		'menuClasses'       => [
			'combine' => [
				'.nav-primary',
				'.nav-header',
			],
			'others'  => [],
		],
	];

	return $settings;

}

# Add HTML5 markup structure.
add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

# Add Accessibility support.
add_theme_support('genesis-accessibility', ['404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links']);

# Add viewport meta tag for mobile browsers.
add_theme_support('genesis-responsive-viewport');

# Add support for custom header.
add_theme_support('custom-header', [
	'width'           => 600,
	'height'          => 160,
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'flex-height'     => true,
]);

# Add support for custom background.
add_theme_support('custom-background');

# Add support for after entry widget.
add_theme_support('genesis-after-entry-widget-area');

# Add support for 3-column footer widgets.
add_theme_support('genesis-footer-widgets', 3);

# Add Image Sizes.
add_image_size('featured-image', 720, 400, TRUE);

# Rename primary and secondary navigation menus.
add_theme_support('genesis-menus', ['primary' => __('After Header Menu', 'theme'), 'secondary' => __('Footer Menu', 'theme')]);

# Reposition the secondary navigation menu.
remove_action('genesis_after_header', 'genesis_do_subnav');
add_action('genesis_footer', 'genesis_do_subnav', 5);

# Reduce the secondary navigation menu to one level depth.
add_filter('wp_nav_menu_args', 'secondaryMenuArgs');
function secondaryMenuArgs($args) {

	if('secondary' != $args['theme_location']) :
		return $args;
	endif;

	$args['depth'] = 1;

	return $args;

}

# Modify size of the Gravatar in the author box.
add_filter('genesis_author_box_gravatar_size', 'athorBoxGravatar');
function athorBoxGravatar($size) {
	return 90;
}

# Modify size of the Gravatar in the entry comments.
add_filter('genesis_comment_list_args', 'commentsGravatar');
function commentsGravatar($args) {

	$args['avatar_size'] = 60;

	return $args;

}

# Modify breadcrumb args
add_filter('genesis_breadcrumb_args', 'breadcrumbArgs');
function breadcrumbArgs($args) {
	$args['home'] = 'Home';
	$args['sep'] = ' / ';
	$args['list_sep'] = ', '; # Genesis 1.5 and later
	$args['prefix'] = '<div class="breadcrumb">';
	$args['suffix'] = '</div>';
	$args['heirarchial_attachments'] = true; # Genesis 1.5 and later
	$args['heirarchial_categories'] = true; # Genesis 1.5 and later
	$args['display'] = true;
	$args['labels']['prefix'] = '';
	$args['labels']['author'] = '';
	$args['labels']['category'] = ''; # Genesis 1.6 and later
	$args['labels']['tag'] = '';
	$args['labels']['date'] = '';
	$args['labels']['search'] = '';
	$args['labels']['tax'] = '';
	$args['labels']['post_type'] = '';
	$args['labels']['404'] = ''; // Genesis 1.5 and later
	
	return $args;
}

# Enqueue secondary scripts
if( !function_exists('enqueueSecondaryScriptsStyles') ) :
	add_action('wp_enqueue_scripts', 'enqueueSecondaryScriptsStyles', 5);
	function enqueueSecondaryScriptsStyles() {

	}
endif;

# Body class
if( !function_exists('bodyClass') ) :
	add_filter('body_class', 'bodyClass');
	function bodyClass($bc) {
        if( is_page_template('page-templates/page_landing.php') ) :
            $classes[] = 'landing-page';
        endif;

		return $bc;
	}
endif;

# Template hooks
if( !function_exists('templateHooks') ) :
	add_action('genesis_before', 'templateHooks');
	function templateHooks() {
		if( is_page() ) :
			remove_action('genesis_entry_header', 'genesis_entry_header_markup_open', 5);
			remove_action('genesis_entry_header', 'genesis_do_post_title');
			remove_action('genesis_entry_header', 'genesis_entry_header_markup_close', 15);
		endif;
	}
endif;