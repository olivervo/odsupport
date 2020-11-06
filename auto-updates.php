<?php
//Enable auto updates
add_filter( 'auto_update_core', '__return_true' );
add_filter( 'auto_update_plugin', '__return_true' );

//Disable plugin auto-update email notification
add_filter( 'send_plugins_auto_update_email', '__return_false' ); 
add_filter( 'send_themes_auto_update_email', '__return_false' );
apply_filters( 'send_core_update_notification_email', '__return_true' );
apply_filters( 'auto_core_update_send_email', '__return_false' );