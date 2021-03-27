<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://jmlunalopez.com
 * @since      1.0.0
 *
 * @package    Iswp_Courses_List
 * @subpackage Iswp_Courses_List/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Iswp_Courses_List
 * @subpackage Iswp_Courses_List/public
 * @author     Juan Manuel Luna López <lunalopezjm@gmail.com>
 */
class Iswp_Courses_List_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
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
		 * defined in Iswp_Courses_List_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Iswp_Courses_List_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/iswp-courses-list-public.css', array(), $this->version, 'all' );

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
		 * defined in Iswp_Courses_List_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Iswp_Courses_List_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/iswp-courses-list-public.js', array( 'jquery' ), $this->version, false );

	}

    public function iswpcl_register_shortcodes()
    {
        add_shortcode('iswp_courses_list', [$this, 'iswp_courses_list_render']);
    }

    public function iswp_courses_list_render()
    {
        $this->get_courses();
        ob_start();
        include 'partials/iswp-courses-list-public-display.php';
        return ob_get_clean();
    }

    public function get_courses()
    {
        $options = get_option('iswp_cl__oname__general_settings');
        $course_ids = explode(', ', $options['course_ids']);

        // Get the posts
        $query = new WP_Query([
            //'post_type' => 'sfwd-courses', // Somehow misses one when active
            'post__in'  => $course_ids,
        ]);
        $posts = $query->posts;

        // May need to create extra fields on the "post" to input the data missing here.

        // ----------------------------------------
        // Get the extra data
        // ----------------------------------------

        // Get course description (content? courses are empty atm)
        // Get course (post) thumbnail

        // ----------------------------------------
        // Get the completion ("0 out of N points")
        // ----------------------------------------

        // Filter functions

        // ----------------------------------------
        // Process language out of the title
        // ----------------------------------------

        // ----------------------------------------
        // Get the post category (course type?)
        // ----------------------------------------




        $b = "c";
    }

}
