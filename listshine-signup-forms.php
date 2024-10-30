<?php
/*
Plugin Name: Listhine Signup Forms
Plugin URI: https://listshine.com
Description: A responsive and intuitive plugin for using your ListShine signup forms on your wordpress website! Share all of your favorite listshine signup forms on your WordPress and have them totally integrated with your WordPress theme.
Version: 1.0
Author: mporcic
Author URI: https://github.com/MPorcic
License: GPLv2
*/
include "listshine-api-functions.php";

//Exit if accessed directly
if(! defined ("ABSPATH")){
    exit;
}

add_action('admin_menu', 'listshine_signup_forms_menu');

function listshine_signup_forms_menu() {
    add_menu_page('My ListShine SignupForm Plugin', 'Listshine Signup Forms', 'administrator', 'my-listshine-settings', 'listshine_signup_forms_page', plugin_dir_url(__FILE__)."/assets/img/listshine-menu-icon-18x18.png");
}

function listshine_signup_forms_page() {
    require_once dirname(__FILE__) . '/lib/php-listshine-api/ListShine_Api.php';

    ?>
    <br>
    <div style=" border-radius:0;text-align: center; width: 100%; border-bottom: 3px solid black;" class="navbar"><a href="https://listshine.com" style="text-decoration: none; color: black;"><img class="img-rounded" src=<?php echo plugin_dir_url(__FILE__)."/assets/img/listshine-icon.png"; ?>><span style="font-size: 1.5em; position: relative; top: 3px;"> ListShine</span></a></div>

    <div class="wrap">
        <h2>Signup forms</h2>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form method="post" action="options.php">
                        <?php settings_fields( 'listshine-api-group' ); ?>
                        <?php do_settings_sections( 'listshine-api-group' ); ?>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">Api key</th>
                                <td><input class="form-control" type="text" name="api_key" value="<?php echo esc_attr( get_option('api_key') ); ?>" /></td>
                            </tr>
                            
                        </table>

                        <?php submit_button(); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div>
        <?php
        echo get_option('signup_style');
        if(get_option('api_key')) {
            include dirname(__FILE__) . '/includes/signup_template.php';
        } else{
            echo "No api key yet! Please insert the api key we've provided you with to gain access to the signupforms!";
        } ?>
    </div>
    <?php
}
add_action( 'admin_init', 'listshine_signup_forms_settings' );
function listshine_signup_forms_settings() {
    register_setting( 'listshine-api-group', 'api_key' );
    if(get_option('api_key')) {
        wp_register_script( 'populate_listshine_script', plugin_dir_url(__FILE__).'/assets/js/populate_button.js' );
        $forms_array = get_listshine_contactlists_with_forms();
        wp_enqueue_script('shortcode_listshine_script', plugin_dir_url(__FILE__).'/assets/js/shortcode_generator.js',array('jquery'));
        wp_localize_script( 'populate_listshine_script', 'forms', $forms_array);
        wp_enqueue_script( 'populate_listshine_script' );

    }
}

add_action('init', 'listshine_button');
function listshine_button(){
    add_filter( "mce_external_plugins","listshine_add_button");
    add_filter("mce_buttons","listshine_register_button");
}

function listshine_add_button($button_array){
    $button_array['listshine'] = plugin_dir_url(__FILE__).'/assets/js/editor_btn.js';
    return $button_array;
}

function listshine_register_button($buttons){
    array_push( $buttons, 'addlistshineform');
    return $buttons;
}


add_shortcode('listshine_form', 'insert_listshine_signup_forms');
wp_enqueue_style('bootstrap', plugin_dir_url(__FILE__).'/assets/css/bootstrap.min.css');
wp_enqueue_script('bootstrap_js', plugin_dir_url(__FILE__).'/assets/js/bootstrap.min.js', array('jquery'));
?>
