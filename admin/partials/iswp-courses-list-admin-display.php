<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://jmlunalopez.com
 * @since      1.0.0
 *
 * @package    Iswp_Courses_List
 * @subpackage Iswp_Courses_List/admin/partials
 */
?>

<div class="wrap">
    <div class="head-wrap">
        <h1 class="title">
            ISWP Courses List
            <span class="title-count">1.0.0</span>
        </h1>
    </div>
    <div id="form_area">
        <div id="main-form">
            <form id="form-container" method="post" action="options.php">
                <div>
                    <h2>
                        Courses List Options
                    </h2>
                    <hr />
                </div>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th>
                                Course List's shortcode
                                <br />
                                <!--span class="input_info">
                                    Paste this code wherever you want the UX to be displayed.
                                </span-->
                            </th>
                            <td>
                                <code>[iswp_courses_list]</code>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="course_ids">
                                    Course IDs
                                </label>
                            </th>
                            <td>
                                <textarea id="course_ids" rows="5" cols="90" style="width:90%">

                                </textarea>
                                <br >
                                <span class="input_info">
                                    Input the IDs of the courses you want to display in the widget, separated by a comma.
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br >
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary save-settings" value="Save Settings">
                </p>
            </form>
        </div>
    </div>
</div>

<style>
    #form_area {
        background-color: #FFF;
        border-top:    solid 1px #ccc;
        border-bottom: solid 1px #ccc;
        border-left:   solid 1px #ccc;
        border-right:  solid 1px #ccc;
    }
    #main-form {
        margin-left: 22px;
        margin-right: 22px;
    }
    .input_info {
        float: left;
        width: 90%;
        font-size: smaller;
        line-height: 1.9;
    }
</style>