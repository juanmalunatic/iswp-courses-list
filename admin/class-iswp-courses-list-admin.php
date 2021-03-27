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

}
