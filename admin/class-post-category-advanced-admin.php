<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://all-wp.com
 * @since      1.0.0
 *
 * @package    Post_Category_Advanced
 * @subpackage Post_Category_Advanced/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Post_Category_Advanced
 * @subpackage Post_Category_Advanced/admin
 * @author     Matteo Montipo <info@all-wp.com>
 */

class Pcadv_Admin {

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
	 * @param      string    $post_category_advanced       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $post_category_advanced, $version ) {

		$this->post_category_advanced = $post_category_advanced;
		$this->version = $version;

		add_action( 'admin_menu', array( $this, 'register_my_custom_menu_page') );
		add_action('save_post', array( $this, 'pca_assign_parent_terms' ), 10, 2);
		add_action('save_post', array( $this, 'pca_assign_tags' ), 10, 2);
		add_action('init', array( $this, 'pca_assign_tags_all_posts' ) );

	}

	// add navigation to plugin in dashboard menu
	public function register_my_custom_menu_page(){
		add_menu_page( 'Post Category Advanced', 'Post Category Advanced', 'manage_options', plugin_dir_path( dirname( __FILE__ ) ) .'admin/partials/post-category-advanced-admin-display.php', '', 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjciIGhlaWdodD0iMTgiIHZpZXdCb3g9IjAgMCAyNyAxOCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTAuOTQ3NTU1IDAuODIyMjIzSDQuNjkxNTZDNS45NTQ5NiAwLjgyMjIyMyA2LjkwMjUyIDEuMTYxMTkgNy41MzQyMiAxLjgzOTExQzguMTY1OTMgMi41MTcwNCA4LjQ4MTc4IDMuNTEwODIgOC40ODE3OCA0LjgyMDQ1VjYuNDE1MTFDOC40ODE3OCA3LjcyNDc0IDguMTY1OTMgOC43MTg1MiA3LjUzNDIyIDkuMzk2NDRDNi45MDI1MiAxMC4wNzQ0IDUuOTU0OTYgMTAuNDEzMyA0LjY5MTU2IDEwLjQxMzNIMy40ODk3OFYxN0gwLjk0NzU1NVYwLjgyMjIyM1pNNC42OTE1NiA4LjEwMjIyQzUuMTA3NTYgOC4xMDIyMiA1LjQxNTcgNy45ODY2NyA1LjYxNiA3Ljc1NTU2QzUuODMxNyA3LjUyNDQ1IDUuOTM5NTUgNy4xMzE1NiA1LjkzOTU1IDYuNTc2ODlWNC42NTg2N0M1LjkzOTU1IDQuMTA0IDUuODMxNyAzLjcxMTExIDUuNjE2IDMuNDhDNS40MTU3IDMuMjQ4ODkgNS4xMDc1NiAzLjEzMzMzIDQuNjkxNTYgMy4xMzMzM0gzLjQ4OTc4VjguMTAyMjJINC42OTE1NlpNMTMuNDQ0NyAxNy4yMzExQzEyLjIyNzUgMTcuMjMxMSAxMS4yOTU0IDE2Ljg4NDQgMTAuNjQ4MyAxNi4xOTExQzEwLjAxNjYgMTUuNDk3OCA5LjcwMDcxIDE0LjUxOTQgOS43MDA3MSAxMy4yNTZWNC41NjYyMkM5LjcwMDcxIDMuMzAyODIgMTAuMDE2NiAyLjMyNDQ1IDEwLjY0ODMgMS42MzExMUMxMS4yOTU0IDAuOTM3Nzc5IDEyLjIyNzUgMC41OTExMTIgMTMuNDQ0NyAwLjU5MTExMkMxNC42NjE5IDAuNTkxMTEyIDE1LjU4NjMgMC45Mzc3NzkgMTYuMjE4IDEuNjMxMTFDMTYuODY1MiAyLjMyNDQ1IDE3LjE4ODcgMy4zMDI4MiAxNy4xODg3IDQuNTY2MjJWNi4yNzY0NUgxNC43ODUyVjQuNDA0NDVDMTQuNzg1MiAzLjQwMjk2IDE0LjM2MTQgMi45MDIyMiAxMy41MTQgMi45MDIyMkMxMi42NjY2IDIuOTAyMjIgMTIuMjQyOSAzLjQwMjk2IDEyLjI0MjkgNC40MDQ0NVYxMy40NDA5QzEyLjI0MjkgMTQuNDI3IDEyLjY2NjYgMTQuOTIgMTMuNTE0IDE0LjkyQzE0LjM2MTQgMTQuOTIgMTQuNzg1MiAxNC40MjcgMTQuNzg1MiAxMy40NDA5VjEwLjk2OEgxNy4xODg3VjEzLjI1NkMxNy4xODg3IDE0LjUxOTQgMTYuODY1MiAxNS40OTc4IDE2LjIxOCAxNi4xOTExQzE1LjU4NjMgMTYuODg0NCAxNC42NjE5IDE3LjIzMTEgMTMuNDQ0NyAxNy4yMzExWk0yMC42NzQxIDAuODIyMjIzSDI0LjExNzdMMjYuNzUyNCAxN0gyNC4yMTAxTDIzLjc0NzkgMTMuNzg3NlYxMy44MzM4SDIwLjg1OUwyMC4zOTY4IDE3SDE4LjAzOTVMMjAuNjc0MSAwLjgyMjIyM1pNMjMuNDQ3NSAxMS42MzgyTDIyLjMxNSAzLjY0MTc4SDIyLjI2ODhMMjEuMTU5NSAxMS42MzgySDIzLjQ0NzVaIiBmaWxsPSJibGFjayIvPgo8L3N2Zz4K', 90 );
	}
	//'dashicons-welcome-widgets-menus'

	// Automatically select parent category if subcategory is selected
	public function pca_assign_parent_terms($post_id, $post){
		$pca_parent_cat_opt = get_option( 'pca_parent_cat_opt' );

		if ( $pca_parent_cat_opt == '1' ){
			// get all existent categories name
			$t_name = 'category';
			$categories = wp_get_post_terms($post_id, $t_name);
			foreach($categories as $cat){
				while($cat->parent != 0 && !has_term( $cat->parent, $t_name, $post )){
					// move upward until we get to 0 level terms
					wp_set_post_terms( $post_id, array($cat->parent), $t_name, true );
					$cat = get_term( $cat->parent, $t_name );
				}
			}
		}
	}

	// Automatically assign tags to the post, if a category is selected for it
	public function pca_assign_tags($post_id, $post){
		$arrayPostTypeAllowed = array('post');
		$pca_exclude_posts_opt = get_option( 'pca_exclude_posts_opt' );
		$to_exclude = explode(",", $pca_exclude_posts_opt);
		//$arrayTermsAllowed = array('category','custom_category');

		if(!in_array($post->post_type, $arrayPostTypeAllowed) || in_array($post_id, $to_exclude)){
			return $post_id;
		}else{
			// for the future in case of custom taxonomies
			$t_name = 'category';
			
			// get all existent rules set by the user
			$pca_cat_tags_opt = get_option( 'pca_cat_tags_opt' );

			if (count($pca_cat_tags_opt) > 0){
				foreach ($pca_cat_tags_opt as $item) {
					$item_cat = json_decode($item)->c;
					$item_tags = json_decode($item)->t;

					// category has tags assigned within PCA
					if (has_term( $item_cat, $t_name, $post)){
						// attach tags to the post
						wp_set_post_terms($post_id, $item_tags, 'post_tag', true);
					}else{
						continue;
					}
				}
			}
		}
	}

	// Automatically assign tags to all existent posts, if the option within PCA is checked
	public function pca_assign_tags_all_posts(){
		// if the PCA option is checked
		if (get_option( 'pca_all_posts_opt' ) == 1){

			// *
			$pca_exclude_posts_opt = get_option( 'pca_exclude_posts_opt' );
			$to_exclude = explode(",", $pca_exclude_posts_opt);

			//TODO: create settings for this, to let the user pick which to include
			$args = array('fields' => 'ids', 'numberposts' => -1, 'post_status' => array(
				'publish',
				'future',
				'draft',
				'pending',
				'private',
				'trash'
			));
			$posts = get_posts($args);

			// for the future in case of custom taxonomies
			$t_name = 'category';

			// go through the posts one by one
			foreach ($posts as $p){

				// exit if post id is among the list to exlude *
				if(in_array($p, $to_exclude)){
					continue;
				}else{
					$cat_names = array();
					// array of this post assigned categories
					$categories = get_the_category( $p );
					// go through the assigned categories
					if ( !empty($categories) ) {
						foreach ($categories as $c){
							// save category name in array
							array_push($cat_names, $c->name);
						}
					}
					// get all tags by category from PCA settings
					$pca_cat_tags_opt = get_option( 'pca_cat_tags_opt' );

					if (count($pca_cat_tags_opt) > 0 && count($cat_names) > 0){
						foreach ($pca_cat_tags_opt as $item) {
							$item_cat = json_decode($item)->c;
							$item_tags = json_decode($item)->t;

							foreach($cat_names as $cn){
								if ($cn === $item_cat){
									// attach tags to the post
									wp_set_post_terms($p, $item_tags, 'post_tag', true);
									break;
								}
							}
						}
					}
				}
				
			}
		}
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->post_category_advanced, plugin_dir_url( __FILE__ ) . 'css/post-category-advanced-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->post_category_advanced, plugin_dir_url( __FILE__ ) . 'js/post-category-advanced-admin.js', array( 'jquery' ), $this->version, false );

	}

}