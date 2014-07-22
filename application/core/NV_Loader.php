<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This file is part of nv/CodeIgniter project
 *
 * @copyright 2014 Vladimir Stračkovski <vlado@nv3.org>
 * @license   The MIT License (MIT) <http://choosealicense.com/licenses/mit/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit the link above.
 */

/**
 * Loader Class
 *
 * Loads views and files
 *
 * @category   Loader
 * @package    CodeIgniter
 * @subpackage Core_Extensions
 * @author     Vladimir Stračkovski <vlado@nv3.org>
 * @link       http://codeigniter.com/user_guide/libraries/loader.html
 */
class NV_Loader extends CI_Loader
{
    /**
     * Autoloader
     *
     * The config/autoload.php file contains an array that permits sub-systems,
     * libraries, and helpers to be loaded automatically.
     *
     * @note    Added more directories to search for config [nv]
     * @return	void
     */
    protected function _ci_autoloader()
    {
        if (defined('ENVIRONMENT') AND file_exists(FCPATH.'config/'.ENVIRONMENT.'/autoload.php')) {
            include FCPATH.'config/'.ENVIRONMENT.'/autoload.php';
        } else {
            if (file_exists(FCPATH.'config/autoload.php')) {
                include FCPATH.'config/autoload.php';
            } else {
                include APPPATH.'config/autoload.php';
            }
        }

        if (!isset($autoload)) {
            return false;
        }

        // Autoload packages
        if (isset($autoload['packages'])) {
            foreach ($autoload['packages'] as $package_path) {
                $this->add_package_path($package_path);
            }
        }

        // Load any custom config file
        if (count($autoload['config']) > 0) {
            $CI =& get_instance();
            foreach ($autoload['config'] as $key => $val) {
                $CI->config->load($val);
            }
        }

        // Autoload helpers and languages
        foreach (array('helper', 'language') as $type) {
            if (isset($autoload[$type]) AND count($autoload[$type]) > 0) {
                $this->$type($autoload[$type]);
            }
        }

        // A little tweak to remain backward compatible
        // The $autoload['core'] item was deprecated
        if (!isset($autoload['libraries']) AND isset($autoload['core'])) {
            $autoload['libraries'] = $autoload['core'];
        }

        // Load libraries
        if (isset($autoload['libraries']) AND count($autoload['libraries']) > 0) {
            // Load the database driver.
            if (in_array('database', $autoload['libraries'])) {
                $this->database();
                $autoload['libraries']
                    = array_diff($autoload['libraries'], array('database'));
            }

            // Load all other libraries
            foreach ($autoload['libraries'] as $item) {
                $this->library($item);
            }
        }

        // Autoload models
        if (isset($autoload['model'])) {
            $this->model($autoload['model']);
        }
    }
}
/* End of file Loader.php */
/* Location: ./system/core/Loader.php */