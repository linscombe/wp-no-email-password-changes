<?php
/*
Plugin Name: No email/password changes
Description: Removes the ability to change email address and password
Version: 1.0
Author: Jason Linscombe
*/


// don't send confirmaion to verify email change
remove_action('personal_options_update', 'send_confirmation_on_profile_email');

// disable email after changing password
add_filter('send_password_change_email', '__return_false');

// disable password reset
function disable_password_reset() { return false; }
add_filter ( 'allow_password_reset', 'disable_password_reset' );


// remove 'Lost Password'
function remove_lostpassword_text ( $text ) {
    if ($text == 'Lost your password?'){$text = '';}
    return $text;
}
add_filter( 'gettext', 'remove_lostpassword_text' );


// disable generate random password
function disable_random_password( $password ) {
    $action = isset( $_GET['action'] ) ? $_GET['action'] : '';
    if ( 'wp-login.php' === $GLOBALS['pagenow'] && ( 'rp' == $action  || 'resetpass' == $action ) ) {
        return '';
    }
    return $password;
}
add_filter( 'random_password', 'disable_random_password', 10, 2 );


// disable password fields
// if ( is_admin() )
  add_action( 'init', 'disable_password_fields', 10 );

function disable_password_fields() {
//   if ( ! current_user_can( 'administrator' ) )
    $show_password_fields = add_filter( 'show_password_fields', '__return_false' );
}


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
