# wp-no-email-password-changes
WordPress plugin - removes ability to change email and passwords

This is particularly useful for WordPress sites that rely on Single sign-on for authentication or when password/email changes shouldn't be made to the WordPress database.

### This plugin disables the following funtions in WordPress
* Disabled: Send a confirmation request email when a change of user email address is attempted.
* Disabled: Filters whether to send the password change email.
* Disabled: Filters whether to allow a password to be reset.
* Remove 'Lost Password' text on the login screen
* Remove generate random password generated on the profile screen
* Disable password fields on the profile screen
* Disable changing email address

### Install

To get started, download the zip file of this repo and upload on the Plugins page of your WordPress site.

https://github.com/linscombe/wp-no-email-password-changes/archive/master.zip

Plugin tested and developed using WordPress version 5.4.2.
