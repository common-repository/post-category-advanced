<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://all-wp.com
 * @since      1.0.0
 *
 * @package    Post_Category_Advanced
 * @subpackage Post_Category_Advanced/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Post_Category_Advanced
 * @subpackage Post_Category_Advanced/public
 * @author     Matteo Montipo <info@all-wp.com>
 */
class Pcadv_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $post_category_advanced    The ID of this plugin.
	 */
	private $post_category_advanced;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $post_category_advanced       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $post_category_advanced, $version ) {

		$this->post_category_advanced = $post_category_advanced;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pcadv_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pcadv_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->post_category_advanced, plugin_dir_url( __FILE__ ) . 'css/post-category-advanced-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pcadv_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pcadv_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->post_category_advanced, plugin_dir_url( __FILE__ ) . 'js/post-category-advanced-public.js', array( 'jquery' ), $this->version, false );

	}

}
