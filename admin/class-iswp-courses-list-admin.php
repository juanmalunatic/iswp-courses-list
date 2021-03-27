<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jmlunalopez.com
 * @since      1.0.0
 *
 * @package    Iswp_Courses_List
 * @subpackage Iswp_Courses_List/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Iswp_Courses_List
 * @subpackage Iswp_Courses_List/admin
 * @author     Juan Manuel Luna López <lunalopezjm@gmail.com>
 */
class Iswp_Courses_List_Admin {

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

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Iswp_Courses_List_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Iswp_Courses_List_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/iswp-courses-list-admin.css', array(), $this->version, 'all' );

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
		 * defined in Iswp_Courses_List_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Iswp_Courses_List_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/iswp-courses-list-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_link_in_appearance_menu()
    {
        add_management_page(
            'ISWP Courses List',
            'ISWP Courses List',
            'manage_options',
            $this->plugin_name,
            array($this, 'display_iswpcourseslist_admin_page')
        );
    }

    public function display_iswpcourseslist_admin_page()
    {
        include_once('partials/iswp-courses-list-admin-display.php');
    }

}
