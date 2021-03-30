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
class Iswp_Courses_List_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private $post_types_affected = ['sfwd-courses']; //, 'sfwd-lessons', 'sfwd-topic', 'sfwd-quiz'];

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/iswp-courses-list-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/iswp-courses-list-admin.js', array('jquery'), $this->version, false);

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

    public function options_update()
    {
        // Different setting groups
        register_setting(
            "iswp_cl__ogroup__general_settings",
            "iswp_cl__oname__general_settings",
            [$this, 'validate_general_settings']
        );
    }

    public function validate_general_settings($incoming_data): array
    {
        // Defaults
        $default_data = [
            'course_ids' => '18511, 18566, 18748, 18599, 18627, 18551, 18645, 18767, 18702, 18730, 20365',
        ];

        // Manipulate incoming data
        $ids = $incoming_data['course_ids'];
        $ids_nosp = preg_replace("/\s+/", "", $ids); // Remove spaces
        $ids_arr = explode(',', $ids_nosp);                   // Convert into an array
        $numbers = [];
        foreach ($ids_arr as $key => $value) {
            if (is_numeric($value)) {
                $numbers[] = $value;                                  // Only save numbers
            }
        }
        $num_str = implode(', ', $numbers);                   // Re-format string w/ only the valid numbers

        // If at least some of the comma separated values are numbers, this won't be empty
        $is_valid = $num_str !== '';
        if ($is_valid) {
            $validated['course_ids'] = $num_str;
        } else {
            $validated['course_ids'] = $default_data['course_ids'];
        }

        $a = "b";

        // Only fields that appear on default_data will be stored.
        // You can store additional fields using the following (outside of the loop)
        // $this->storeVal('additional-field', $validated, $incoming_data, $default_data);
        // $validated = [];
        // $fields = array_keys($default_data);
        // foreach ($fields as $key => $field) {
        //     $this->storeVal($field, $validated, $incoming_data, $default_data);
        // }


        return $validated;
    }

    public function storeVal($field_name, &$target, $values, $defaults)
    {
        $new_value = $values[$field_name];
        $is_valid = (isset($new_value)) && !empty($new_value);
        if ($is_valid) {
            $target[$field_name] = $new_value;
        } else {
            $target[$field_name] = $defaults[$field_name];
        }
    }

    public function add_metabox ()
    {
        add_meta_box(
            'iswp-courses-list__meta_box',
            'Course List Settings',
            function () {
                // Callback won't work otherwise
                ob_start();
                $this->iswp_courses_list__output_meta_box();
                echo ob_get_clean();
            },
            $this->post_types_affected,
            'normal',
            'high',
            []
        );
    }

    public function iswp_courses_list__output_meta_box()
    {
        $post_id = get_the_ID();

        $short_description = get_post_meta( $post_id, '_learndash_course_short_description', true );
        $short_description = isset($short_description) ? $short_description : "";

        wp_nonce_field('iwspcl_save', 'iswpcl_nonce');

        echo $this->input_structure("Short description","_learndash_course_short_description", $short_description);
    }

    function input_structure ($title, $key, $value) {
        $absolute_url = $this->base_url();
        return <<<EOD
        <div class="sfwd_input " id="sfwd-courses_{$key}" style="">
            <span class="sfwd_option_label" style="text-align:right;vertical-align:top;">
                <a class="sfwd_help_text_link" style="cursor:pointer;" title="Click for Help!" onclick="toggleVisibility('sfwd-courses_tip_$key');">
                    <img src="{$absolute_url}/wp-content/plugins/sfwd-lms/assets/images/question.png">
                    <label class="sfwd_label textinput">{$title}</label>
                </a>
            </span>
            <span class="sfwd_option_input">
                <div class="sfwd_option_div">
                    <textarea
                        name="{$key}"
                          id="{$key}"
                        rows="2" cols="57">{$value}</textarea>
                </div>
                <div class="sfwd_help_text_div" style="display:none" id="sfwd-courses_tip_$key">
                    <label class="sfwd_help_text">
                        Enter a short description for this course. 
                    </label>
                </div>
            </span>
            <p style="clear:left"></p>
        </div>
        EOD;
    }

    function base_url()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            $url = "https://";
        else
            $url = "http://";

        // Append the host(domain name, ip) to the URL.
        $url.= $_SERVER['HTTP_HOST'];

        // Append the requested resource location to the URL
        //$url.= $_SERVER['REQUEST_URI'];

        return $url;
    }


    function save_metabox ( $post_id, $post, $update )
    {
        // Only save for some post types
        if (!in_array( $post->post_type, $this->post_types_affected)) {
            return;
        }

        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        if ( ! isset( $_POST['iswpcl_nonce'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['iswpcl_nonce'], 'iwspcl_save' ) ) {
            wp_die( __( 'Cheatin\' huh?' ) );
        }

        update_post_meta(
            $post_id,
            '_learndash_course_short_description',
            wp_filter_kses( $_POST['_learndash_course_short_description'] )
        );

    }
}