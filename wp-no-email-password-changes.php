<?php
/*
 * This plugin removes the ability for a WordPress user to change their email address and password
 *
 * @package No email/password changes
 * @author Jason Linscombe
 * @license GPL-3.0+
 * @link https://github.com/linscombe/wp-no-email-password-changes
 * @copyright 2020 Jason Linscombe All rights reserved.
 *
 *            @wordpress-plugin
 *            Plugin Name: No email/password changes
 *            Plugin URI: https://github.com/linscombe/wp-no-email-password-changes
 *            Description: Removes the ability for a WordPress user to change their email address and password
 *            Version: 1.3
 *            Author: Jason Linscombe
 *            Author URI: https://github.com/linscombe
 *            Text Domain: wp-no-email-password-changes
 *            Contributors: Jason Linscombe
 *            License: GPL-3.0+
 *            License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 */


/*
 *
 * Disabled: Send a confirmation request email when a change of user email address is attempted.
 * https://developer.wordpress.org/reference/functions/send_confirmation_on_profile_email/
 *
 */
remove_action('personal_options_update', 'send_confirmation_on_profile_email');


/*
 *
 * Disabled: Filters whether to send the password change email.
 * https://developer.wordpress.org/reference/hooks/send_password_change_email/
 *
 */
add_filter('send_password_change_email', '__return_false');


/*
 *
 * Disabled: Filters whether to allow a password to be reset.
 * https://developer.wordpress.org/reference/hooks/allow_password_reset/
 *
 */
function disable_password_reset() {
    return false;
}
add_filter ( 'allow_password_reset', 'disable_password_reset' );


/*
 *
 * Remove 'Lost Password' text on the login screen
 *
 */
function remove_lostpassword_text ( $text ) {
    if ($text == 'Lost your password?'){
        $text = '';
    }
    return $text;
}
add_filter( 'gettext', 'remove_lostpassword_text' );


/*
 *
 * Remove generate random password generated on the profile screen
 *
 */
function disable_random_password( $password ) {
    $action = isset( $_GET['action'] ) ? $_GET['action'] : '';
    if ( 'wp-login.php' === $GLOBALS['pagenow'] && ( 'rp' == $action  || 'resetpass' == $action ) ) {
        return '';
    }
    return $password;
}
add_filter( 'random_password', 'disable_random_password', 10, 2 );


/*
 *
 * Disable password fields on the profile screen
 *
 */
function disable_password_fields() {
    $show_password_fields = add_filter( 'show_password_fields', '__return_false' );
}
add_action( 'init', 'disable_password_fields', 10 );


    
/*
 *
 * Disable changing email address
 *
 */
class DisableMailChange
{

    public function __construct()
    {
        //prevent email change
        add_action( 'personal_options_update',  [$this, 'disable_mail_change_BACKEND'], 5  );
        add_action( 'show_user_profile',        [$this, 'disable_mail_change_HTML']  );
    }

    public function disable_mail_change_BACKEND($user_id) {
        if ( !current_user_can( 'manage_options' ) ) {
            $user = get_user_by('id', $user_id ); $_POST['email']=$user->user_email;
        }
    }

    public function disable_mail_change_HTML($user) {
        if ( !current_user_can( 'manage_options' ) ) {
            echo '<script>document.getElementById("email").setAttribute("disabled","disabled");</script>';
        }
    }
}
new DisableMailChange();

?>
