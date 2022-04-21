<?php
/**
 * Plugin Name:       Registation Form
 * Plugin URI:        https://viserx.com/
 * Description:       Plugin Action any kinds of file with this plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            jahidcse
 * Author URI:        https://profiles.wordpress.org/jahidcse/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://profiles.wordpress.org/jahidcse/#content-plugins
 * Text Domain:       vxregister
 * Domain Path:       /languages
 */


// load textdomain

function vxregister_load_textdomain() {
    load_plugin_textdomain( 'vxregister_monitor', false, dirname( __FILE__ ) . "/languages" );
}

add_action( 'register_form', 'vxregister_registration_form' );

function vxregister_registration_form() {

$firstname = ! empty( $_POST['first_name'] ) ? $_POST['first_name'] : '';
$lastname = ! empty( $_POST['last_name'] ) ? $_POST['last_name'] : '';
$phonenumber = ! empty( $_POST['contact_number'] ) ? $_POST['contact_number'] : '';

?>
<p>
    <label for="first_name"><?php esc_html_e( 'First Name', 'vxregister' ) ?><br/>
        <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($firstname); ?>" />
    </label>
</p>
<p>
    <label for="last_name"><?php esc_html_e( 'Last Name', 'vxregister' ) ?><br/>
        <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($lastname); ?>" />
    </label>
</p>
<p>
    <label for="contact_number"><?php esc_html_e( 'Phone Number', 'vxregister' ) ?><br/>
        <input type="text" id="contact_number" name="contact_number" value="<?php echo esc_attr($phonenumber); ?>" />
    </label>
</p>
<?php

}

add_filter('registration_errors','vxregister_user_registration_error_form',10,3);

function vxregister_user_registration_error_form($errors,$sanitized_user_login,$user_email){
    if (empty($_POST['first_name'])) {
        $errors->add('first_name_error',__('<strong>Error</strong>: Please Enter Your First Name','vxregister'));
    }
    if (empty($_POST['last_name'])) {
        $errors->add('last_name_error',__('<strong>Error</strong>: Please Enter Your Last Name','vxregister'));
    }
    if (empty($_POST['contact_number'])) {
        $errors->add('contact_number_error',__('<strong>Error</strong>: Please Enter Your Phone Number','vxregister'));
    }

    return $errors;
}

add_action('user_register','vxregister_user_register');

function vxregister_user_register($user_id){
    if (!empty($_POST['first_name'])) {
        update_user_meta($user_id,'first_name', sanitize_text_field($_POST['first_name']));
    }
    if (!empty($_POST['last_name'])) {
        update_user_meta($user_id,'last_name', sanitize_text_field($_POST['last_name']));
    }
    if (!empty($_POST['contact_number'])) {
        update_user_meta($user_id,'contact_number', sanitize_text_field($_POST['contact_number']));
    }
}


function vxregister_usermeta_form_field_phone( $user )
{
    ?>
    <table class="form-table">
        <tr>
            <th>
                <label for="contact_number">Phone Number</label>
            </th>
            <td>
                <input type="text" class="regular-text ltr" id="contact_number" name="contact_number" value="<?= esc_attr( get_user_meta( $user->ID, 'contact_number', true ) ) ?>">
            </td>
        </tr>
    </table>
    <?php
}

add_action('show_user_profile','vxregister_usermeta_form_field_phone');
add_action('edit_user_profile','vxregister_usermeta_form_field_phone');


function vxregister_usermeta_form_field_contact( $user_id )
{

    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    return update_user_meta($user_id,'contact_number',$_POST['contact_number']);
}

add_action('personal_options_update','vxregister_usermeta_form_field_contact');
add_action('edit_user_profile_update','vxregister_usermeta_form_field_contact');