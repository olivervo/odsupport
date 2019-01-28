<?php
/*
Plugin Name: Olnhausen Design Support
Description: Support och anpassad kod för din hemsida från Olnhausen Design. Behövs för att hemsidan skall fungera.
Version: 0.5
License: GPL
Author: Olnhausen Design
Author URI: http://www.olnhausendesign.se
*/

// Add Toolbar Menus
function custom_toolbar() {
	global $wp_admin_bar;

	$args = array(
		'title'  => 'Olnhausen Design Support',
		'href'   => 'http://www.olnhausendesign.se/support/',
		'meta'   => array(
			'target'   => '_blank',
		),
	);
	$wp_admin_bar->add_menu( $args );

}

// Hook into the 'wp_before_admin_bar_render' action
add_action( 'wp_before_admin_bar_render', 'custom_toolbar', 999 );

// Disable password changed notifications
if ( !function_exists( 'wp_password_change_notification' ) ) {
    function wp_password_change_notification() {}
}

//Hides the following plugins

add_filter( 'all_plugins', 'hide_plugins');
function hide_plugins($plugins)
{
		if(is_plugin_active('wpremote/plugin.php')) {
				unset( $plugins['wpremote/plugin.php'] );
		}
		if(is_plugin_active('uber-login-logo/uber-login-logo.php')) {
				unset( $plugins['uber-login-logo/uber-login-logo.php'] );
		}
		if(is_plugin_active('wp-master-admin/wp-master-admin.php')) {
				unset( $plugins['wp-master-admin/wp-master-admin.php'] );
		}
		if(is_plugin_active('white-label-cms/wlcms-plugin.php')) {
				unset( $plugins['white-label-cms/wlcms-plugin.php'] );
		}
		if(is_plugin_active('worker/init.php')) {
				unset( $plugins['worker/init.php'] );
		}
		if(is_plugin_active('fusion-core/fusion-core.php')) {
				unset( $plugins['fusion-core/fusion-core.php'] );
		}
		if(is_plugin_active('fusion-builder/fusion-builder.php')) {
				unset( $plugins['fusion-builder/fusion-builder.php'] );
		}
		return $plugins;
}
//Custom login logo
function custom_login_logo() {
	echo '
		<style>
			.login h1 a { background-image: url(https://static.olnhausendesign.se/branding/hotlink-ok/login-wp2x.png) !important; background-size: 295px 62px; width:295px; height:62px; display:block; }
		</style>
	';
}
add_action( 'login_head', 'custom_login_logo' );
//Change login URL
add_filter( 'login_headerurl', 'ec_login_headerurl' );
function ec_login_headerurl( $url ) {
return esc_url( home_url( '/' ) );
}
//Modify Admin Footer Text

function modify_footer() {
	echo 'Created with <span style="color: #cc0000;">&hearts;</span> in Stockholm by <a href="https://www.olnhausendesign.se" target="_blank">Olnhausen Design</a>';
}
add_filter( 'admin_footer_text', 'modify_footer' );

//Create Custom WordPress Dashboard Widget

function dashboard_widget_function() {
	echo '
		<p>Här i adminpanelen kan du göra ändringar på hemsidan. Klicka på "Sidor" här till höger för att se och redigera hemsidans olika sidor.</p>
		<a href="https://www.olnhausendesign.se/support/" class="button button-primary customize load-customize hide-if-no-customize" target="_blank">Jag behöver hjälp</a>
	';
}

function add_dashboard_widgets() {
	wp_add_dashboard_widget( 'custom_dashboard_widget', 'Välkommen till er hemsida från Olnhausen Design', 'dashboard_widget_function' );
}
add_action( 'wp_dashboard_setup', 'add_dashboard_widgets' );

//Hide panel widgets
function remove_dashboard_widgets () {

  remove_meta_box('dashboard_quick_press','dashboard','side'); //Quick Press widget
  remove_meta_box('dashboard_recent_drafts','dashboard','side'); //Recent Drafts
  remove_meta_box('dashboard_primary','dashboard','side'); //WordPress.com Blog
  remove_meta_box('dashboard_secondary','dashboard','side'); //Other WordPress News
  remove_meta_box('dashboard_incoming_links','dashboard','normal'); //Incoming Links
  remove_meta_box('dashboard_plugins','dashboard','normal'); //Plugins
  remove_meta_box('dashboard_right_now','dashboard', 'normal'); //Right Now
  remove_meta_box('rg_forms_dashboard','dashboard','normal'); //Gravity Forms
  remove_meta_box('dashboard_recent_comments','dashboard','normal'); //Recent Comments
  remove_meta_box('icl_dashboard_widget','dashboard','normal'); //Multi Language Plugin
  remove_meta_box('dashboard_activity','dashboard', 'normal'); //Activity
  remove_action('welcome_panel','wp_welcome_panel');
  remove_meta_box( 'themefusion_news', 'dashboard', 'normal' ); // Theme Fusion
}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

// Disable support for comments and trackbacks in post types
function df_disable_comments_post_types_support() {
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('admin_init', 'df_disable_comments_post_types_support');

// Close comments on the front-end
function df_disable_comments_status() {
	return false;
}
add_filter('comments_open', 'df_disable_comments_status', 20, 2);
add_filter('pings_open', 'df_disable_comments_status', 20, 2);

// Hide existing comments
function df_disable_comments_hide_existing_comments($comments) {
	$comments = array();
	return $comments;
}
add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
function df_disable_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
	remove_submenu_page('options-general.php', 'options-discussion.php');
}
add_action('admin_menu', 'df_disable_comments_admin_menu');

// Redirect any user trying to access comments page
function df_disable_comments_admin_menu_redirect() {
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url()); exit;
	}
}
add_action('admin_init', 'df_disable_comments_admin_menu_redirect');

// Remove comments metabox from dashboard
function df_disable_comments_dashboard() {
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'df_disable_comments_dashboard');

// Remove comments links from admin bar
function df_disable_comments_admin_bar() {
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
}
add_action('init', 'df_disable_comments_admin_bar');

// Remove post by email
add_filter( 'enable_post_by_email_configuration', '__return_false' );

// Change wp admin logo
function wpb_custom_logo() {
echo '
<style type="text/css">
#wp-admin-bar-wp-logo {
display: none;
}
</style>
';
}
//hook into the administrative header output
add_action('wp_before_admin_bar_render', 'wpb_custom_logo');

//Plugin updating
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/olivervo/odsupport',
	__FILE__,
	'unique-plugin-or-theme-slug'
);

//Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');
?>
