<?php
define('ENVIRONMENT', 'development');

if (defined('ENVIRONMENT')) {
    switch (ENVIRONMENT) {
        case 'development':
            error_reporting(E_ALL);
            break;

        case 'testing':
        case 'production':
            error_reporting(0);
            break;

        default:
            exit('The application environment is not set correctly.');
    }
}

$system_path = 'vendor/nv/codeigniter/system';
$application_folder = 'vendor/nv/codeigniter/application';

// Resolve the system path for increased reliability
if (defined('STDIN')) {
    chdir(dirname(__FILE__));
}

if (realpath($system_path) !== false) {
    $system_path = realpath($system_path).'/';
}
$system_path = rtrim($system_path, '/').'/';

if (!is_dir($system_path)) {
    if(defined('ENVIRONMENT') and ENVIRONMENT == 'development'){
        exit(
            "Your system folder path does not appear to be set correctly.".
                " Please open the following file and correct this: ".
                pathinfo(__FILE__, PATHINFO_BASENAME)
        );
    }
    else{
        exit('Application error.');
    }
}
// Main constants
define('APPNAME', 'PROJECT_NAME');
define('SRCPATH', 'src/');
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('EXT', '.php');
define('BASEPATH', str_replace("\\", "/", $system_path));
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));
define('FCPATH', str_replace(SELF, '', __FILE__));

if (is_dir($application_folder)) {
    define('APPPATH', $application_folder.'/');
    define('LIBPATH', APPPATH . 'libraries/');
} else {
    if (!is_dir(dirname(dirname(BASEPATH)).'/'.$application_folder.'/')) {
        if(defined('ENVIRONMENT') and ENVIRONMENT == 'development'){
            exit(
                "Your application folder path does not appear to be set correctly. ".
                    "Please open the following file and correct this: ".SELF
            );
        }
        else{
            exit('Application error.');
        }
    }
    define('APPPATH', dirname(dirname(BASEPATH)).'/'.$application_folder.'/');
    define('LIBPATH', APPPATH . 'libraries/');
}

define('ROOTPATH', dirname(dirname(BASEPATH)) . '/');
/*
if(!file_exists('vendor/autoload.php')){
    if(defined('ENVIRONMENT') and ENVIRONMENT == 'development'){
        exit(
            'Please run composer install prior to running this application. '.
            'The install command will generate the missing autoloader files.'
        );
    }
    else{
        exit('Application error.');
    }
}
require_once "vendor/autoload.php";
*/
require_once BASEPATH.'core/CodeIgniter.php';
