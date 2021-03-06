<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Password Hashing
|--------------------------------------------------------------------------
|
| Set options for the hashing library (phpass by default).
|
*/
$config['phpass_hash_portable'] = FALSE;
$config['phpass_hash_strength'] = 8;

/*
|--------------------------------------------------------------------------
| Registration and activation settings
|--------------------------------------------------------------------------
*/
$config['allow_registration'] = TRUE;
$config['captcha_registration'] = FALSE;
$config['email_activation'] = FALSE;
$config['email_activation_expire'] = 60 * 60 * 24 * 2;
$config['email_account_details'] = FALSE;
$config['use_username'] = TRUE;

/*
|--------------------------------------------------------------------------
| Username/password security
|--------------------------------------------------------------------------
*/
$config['username_min_length'] = 4;
$config['username_max_length'] = 20;
$config['password_min_length'] = 4;
$config['password_max_length'] = 20;

/*
|--------------------------------------------------------------------------
| Login settings
|--------------------------------------------------------------------------
*/
$config['login_by_username'] = TRUE;
$config['login_by_email'] = FALSE;
$config['login_record_ip'] = FALSE;
$config['login_record_time'] = FALSE;
$config['login_count_attempts'] = FALSE;
$config['login_max_attempts'] = 5;
$config['login_attempt_expire'] = 60 * 60 * 24;
$config['autologin_cookie_name'] = 'autologin';
$config['autologin_cookie_life'] = 60 * 60 * 24 * 31 * 2;
$config['forgot_password_expire'] = 60 * 15;
$config['db_table_prefix'] = '';