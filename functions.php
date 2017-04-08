<?php
/*
Plugin Name: Olnhausen Design Support
Description: Support och anpassad kod för din hemsida från Olnhausen Design. Behövs för att hemsidan skall fungera.
Version: 0.2
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
?>