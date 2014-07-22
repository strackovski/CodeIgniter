<?php

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */
define('ENVIRONMENT', 'development');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
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

/*
 *---------------------------------------------------------------
 * RELATIVE PATH TO CODEIGNITER INSTALLATION ROOT
 *---------------------------------------------------------------
 *
 * This variable must contain the relative path (starting from
 * the project root/front controller location) to the root of
 * the CodeIgniter installation. When using Composer as the
 * dependency management tool this should be something like
 * vendor/path/to/codeigniter
 *
 * NO TRAILING SLASH!
 *
 */
$ci_installation_root = 'vendor/nv/codeigniter';

/*
 *---------------------------------------------------------------
 * SYSTEM FOLDER NAME/RELATIVE LOCATION
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" folder.
 * Include the path if the folder is not in the same  directory
 * as this file.
 *
 */
$system_path = $ci_installation_root.'/system';

/*
 *---------------------------------------------------------------
 * APPLICATION FOLDER NAME/RELATIVE LOCATION
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * folder then the default one you can set its name here. The folder
 * can also be renamed or relocated anywhere on your server.  If
 * you do, use a full server path. For more info please see the user guide:
 * http://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 *
 */
$application_folder = $ci_installation_root.'/application';

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */
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

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
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
 * --------------------------------------------------------------------
 * Check/Include autoloader
 * --------------------------------------------------------------------
 */
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

/*
 * --------------------------------------------------------------------
 * Load the bootstrap file
 * --------------------------------------------------------------------
 */
require_once BASEPATH.'core/CodeIgniter.php';
