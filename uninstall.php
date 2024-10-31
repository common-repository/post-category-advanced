<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://all-wp.com
 * @since      1.0.0
 *
 * @package    Post_Category_Advanced
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$pca_delete_on_uninstall = get_option( 'pca_delete_on_uninstall' );

// if option to delete all upon uninstall is true
if($pca_delete_on_uninstall == 1){
	delete_option( 'pca_parent_cat_opt' );
	delete_option( 'pca_all_posts_opt' );
	delete_option( 'pca_cat_tags_opt' );
	delete_option( 'pca_exclude_posts_opt' );
	delete_option( 'pca_opt_n' );
	delete_option( 'pca_delete_on_uninstall' );
}
