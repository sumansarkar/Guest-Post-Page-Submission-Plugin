<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://sumansarkar.github.io
 * @since      1.0.0
 *
 * @package    Guest_Post
 * @subpackage Guest_Post/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Guest_Post
 * @subpackage Guest_Post/admin
 * @author     Suman Sarkar <suman07.rj@gmail.com>
 */
class Guest_Post_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/guest-post-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/guest-post-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	
	/**
	 * Register new post type.
	 *
	 * @since    1.0.0
	 */
	public function new_cpt_guest_post(){
		
		$cap_type 	= 'post';
		$plural 	= 'Guest Post';
		$single 	= 'guestpost';
		$cpt_name 	= 'guestpost';

		$opts['can_export']								= TRUE;
		$opts['capability_type']						= $cap_type;
		$opts['description']							= '';
		$opts['exclude_from_search']					= FALSE;
		$opts['has_archive']							= FALSE;
		$opts['hierarchical']							= FALSE;
		$opts['map_meta_cap']							= TRUE;
		$opts['menu_icon']								= 'dashicons-businessman';
		$opts['menu_position']							= 25;
		$opts['public']									= TRUE;
		$opts['publicly_querable']						= TRUE;
		$opts['query_var']								= TRUE;
		$opts['register_meta_box_cb']					= '';
		$opts['rewrite']								= FALSE;
		$opts['show_in_admin_bar']						= TRUE;
		$opts['show_in_menu']							= TRUE;
		$opts['show_in_nav_menu']						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['supports']								= array( 'title','excerpt','editor','thumbnail','author' );
		$opts['taxonomies']								= array();

		$opts['capabilities']['delete_others_posts']	= "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']			= "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']			= "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']	= "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts']	= "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']		= "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']				= "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']				= "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']		= "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']	= "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']			= "publish_{$cap_type}s";
		$opts['capabilities']['read_post']				= "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']		= "read_private_{$cap_type}s";

		$opts['labels']['add_new']						= esc_html__( "Add New {$single}", 'guestpost-info' );
		$opts['labels']['add_new_item']					= esc_html__( "Add New {$single}", 'guestpost-info' );
		$opts['labels']['all_items']					= esc_html__( $plural, 'guestpost-info' );
		$opts['labels']['edit_item']					= esc_html__( "Edit {$single}" , 'guestpost-info' );
		$opts['labels']['menu_name']					= esc_html__( $plural, 'guestpost-info' );
		$opts['labels']['name']							= esc_html__( $plural, 'guestpost-info' );
		$opts['labels']['name_admin_bar']				= esc_html__( $single, 'guestpost-info' );
		$opts['labels']['new_item']						= esc_html__( "New {$single}", 'guestpost-info' );
		$opts['labels']['not_found']					= esc_html__( "No {$plural} Found", 'guestpost-info' );
		$opts['labels']['not_found_in_trash']			= esc_html__( "No {$plural} Found in Trash", 'guestpost-info' );
		$opts['labels']['parent_item_colon']			= esc_html__( "Parent {$plural} :", 'guestpost-info' );
		$opts['labels']['search_items']					= esc_html__( "Search {$plural}", 'guestpost-info' );
		$opts['labels']['singular_name']				= esc_html__( $single, 'guestpost-info' );
		$opts['labels']['view_item']					= esc_html__( "View {$single}", 'guestpost-info' );

		$opts['rewrite']['ep_mask']						= EP_PERMALINK;
		$opts['rewrite']['feeds']						= FALSE;
		$opts['rewrite']['pages']						= TRUE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $plural ), 'guestpost-info' );
		$opts['rewrite']['with_front']					= FALSE;

		$opts = apply_filters( 'guestpost-info-cpt-options', $opts );

		register_post_type( strtolower( $cpt_name ), $opts );
		
		
	}

}
