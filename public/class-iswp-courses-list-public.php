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
        // To avoid multiple network calls, this plugin is output as a single chunk of text
        ob_start();

        // First, the HTML + CSS structure is output.
        include 'partials/iswp-courses-list-public-display.php';

        // Then, the JSON payload is output
        $json_data = $this->get_courses_list();
        echo "<script>window.iswpcl_json = {$json_data}</script>";

        // Finally, the JS is output
        echo "<script>";
        include "js/iswp-courses-list-public.js";
        echo "</script>";

        return ob_get_clean();
    }

    public function get_courses_list()
    {

        $plugin_public_dir = plugin_dir_url( __FILE__ );

        // --------------------------------------------------------------------
        // Retrieve the posts using WP Query
        // --------------------------------------------------------------------

        $options = get_option('iswp_cl__oname__general_settings');
        $course_ids = explode(', ', $options['course_ids']);

        $result_array = [];
        foreach ($course_ids as $each_number) {
            $result_array[] = (int) $each_number;
        }

        // Get the posts
        $query = new WP_Query([
            'post_type' => 'sfwd-courses',
            'posts_per_page' => 18,
            'post__in'  => $result_array,
            'orderby' => 'post_name__in', // Keep the given order
            'suppress_filters' => false,
        ]);
        $posts = $query->posts;

        // --------------------------------------------------------------------
        // Retrieve the card data for every course
        // --------------------------------------------------------------------

        // This data is used to construct a JSON array

        $data_all = [];
        foreach ($posts as $post) {

            $data_post['id']     = $post->ID;
            $data_post['title']  = $post->post_title;
            $data_post['link']   = get_permalink($post->ID);

            // Language & Category: Course Tags contain the language; Course Category the category.
            $taxonomy_terms = wp_get_object_terms($post->ID, ['ld_course_tag', 'ld_course_category']);

            $language   = false;
            $categories = [];

            foreach($taxonomy_terms as $taxonomy_term) {

                // Tag parsing
                if ($taxonomy_term->taxonomy == 'ld_course_tag') {
                    $lang_delimiter = 'lang:';
                    $is_lang_tag = strpos($taxonomy_term->name, $lang_delimiter);
                    if ($is_lang_tag !== false) {
                        $tmparr = explode($lang_delimiter, $taxonomy_term->name);
                        $language = ucfirst(end($tmparr));
                    }
                }

                // Category parsing
                if ($taxonomy_term->taxonomy == 'ld_course_category') {
                    $categories[] = $taxonomy_term->name;
                }
            }

            $data_post['categories'] = $categories;

            $data_post['language'] = $language;
            if ($data_post['language'] === false) {
                $data_post['language'] = false;
            }

            // Description: _learndash_course_short_description contains the description
            $short_description = get_post_meta($post->ID, '_learndash_course_short_description', true);
            $data_post['description'] = $short_description;
            if (trim($data_post['description']) === "") {
                $data_post['description'] = false;
            }

            // Thumbnail:
            $data_post['image'] = get_the_post_thumbnail_url($post->ID, 'full');
            if ($data_post['image'] === false) {
                $data_post['image'] = $plugin_public_dir . 'img/no_image.jpg';
            }

            $data_all[] = $data_post;
        }

        return json_encode($data_all);
    }

}
