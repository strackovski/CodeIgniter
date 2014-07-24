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
 * NV Router Class
 *
 * Extends the default CI_Router class to add support for custom controller
 * directories._validate_request now includes more directories to look for
 * controllers.
 *
 * @category   Router
 * @package    CodeIgniter
 * @subpackage Core_Extensions
 * @author     Vladimir Stračkovski <vlado@nv3.org>
 * @link       http://codeigniter.com/user_guide/libraries/loader.html
 */
class NV_Router extends CI_Router
{
    /**
     * Validates the supplied segments. Attempts to determine the path to
     * the controller.
     *
     * @param array $segments URI segments
     *
     * @return array
     */
    function _validate_request($segments)
    {
        if (count($segments) == 0) {
            return $segments;
        }

        // Does the requested controller exist in the src folder?
        if (file_exists($this->config->config['controllers_search_dir'].ucfirst($segments[0]).$this->config->config['controller_suffix'].'.php')) {
            return $segments;
        }

        // Does the requested controller exist in the root folder?
        if (file_exists(APPPATH.'controllers/'.ucfirst($segments[0]).$this->config->config['controller_suffix'].'.php')) {
            return $segments;
        }

        // Does the controller directory have subdirectories with contoller name?
        if (is_dir(APPPATH.'controllers/'.ucfirst($segments[0]))) {
            $subdir = APPPATH.'controllers/';
        } elseif (is_dir($this->config->config['controllers_search_dir'].ucfirst($segments[0]))) {
            $subdir = $this->config->config['controllers_search_dir'];
        }

        // Is the controller in a sub-folder?
        if (isset($subdir)) {
            // Set the directory and remove it from the segment array
            $this->set_directory($segments[0]);
            $segments = array_slice($segments, 1);

            if (count($segments) > 0) {
                // Does the requested controller exist in the sub-folder?
                if ( ! file_exists($subdir.$this->fetch_directory().ucfirst($segments[0]).$this->config->config['controller_suffix'].'.php')) {
                    if ( ! empty($this->routes['404_override'])) {
                        $x = explode('/', $this->routes['404_override']);
                        $this->set_directory('');
                        $this->set_class($x[0]);
                        $this->set_method(isset($x[1]) ? $x[1] : 'index');

                        return $x;
                    } else {
                        show_404($this->fetch_directory().$segments[0]);
                    }
                }
            } else {
                // Is the method being specified in the route?
                if (strpos($this->default_controller, '/') !== false) {
                    $x = explode('/', $this->default_controller);
                    $this->set_class($x[0]);
                    $this->set_method($x[1]);
                } else {
                    $this->set_class($this->default_controller);
                    $this->set_method('index');
                }

                // Does the default controller exist in the sub-folder?
                if (!file_exists($subdir.$this->fetch_directory().ucwords($this->default_controller).$this->config->config['controller_suffix'].'.php')) {
                    $this->directory = '';
                    return array();
                }
            }

            return $segments;
        }

        // If we've gotten this far it means that the URI
        // does not correlate to a valid controller class.
        if (!empty($this->routes['404_override'])) {
            $x = explode('/', $this->routes['404_override']);
            $this->set_class($x[0]);
            $this->set_method(isset($x[1]) ? $x[1] : 'index');

            return $x;
        }
        // Nothing else to do at this point but show a 404
        show_404($segments[0]);
        return 0;
    }

    /**
     * Set the route mapping
     *
     * Overridden to provide additional routes configuration path.
     *
     * @access	private
     * @return	void
     */
    function _set_routing()
    {
        $segments = array();
        if ($this->config->item('enable_query_strings') === true AND isset($_GET[$this->config->item('controller_trigger')])) {
            if (isset($_GET[$this->config->item('directory_trigger')])) {
                $this->set_directory(
                    trim(
                        $this->uri->_filter_uri(
                            $_GET[$this->config->item('directory_trigger')]
                        )
                    )
                );
                $segments[] = $this->fetch_directory();
            }

            if (isset($_GET[$this->config->item('controller_trigger')])) {
                $this->set_class(
                    trim(
                        $this->uri->_filter_uri(
                            $_GET[$this->config->item('controller_trigger')]
                        )
                    )
                );
                $segments[] = $this->fetch_class();
            }

            if (isset($_GET[$this->config->item('function_trigger')])) {
                $this->set_method(
                    trim(
                        $this->uri->_filter_uri(
                            $_GET[$this->config->item('function_trigger')]
                        )
                    )
                );
                $segments[] = $this->fetch_method();
            }
        }

        // Load the routes.php file.
        if (defined('ENVIRONMENT') AND file_exists(FCPATH.'config/'.ENVIRONMENT.'/routes.php')) {
            include FCPATH.'config/'.ENVIRONMENT.'/routes.php';
        } elseif (file_exists(FCPATH.'config/routes.php')) {
            include FCPATH.'config/routes.php';
        } elseif (file_exists(APPPATH.'config/routes.php')) {
            include APPPATH.'config/routes.php';
        }

        $this->routes = ( ! isset($route) OR ! is_array($route)) ? array() : $route;
        unset($route);

        // Set the default controller so we can display it in the event
        // the URI doesn't correlated to a valid controller.
        $this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? false : strtolower($this->routes['default_controller']);

        // Were there any query string segments?
        // If so, we'll validate them and bail out since we're done.
        if (count($segments) > 0) {
            return $this->_validate_request($segments);
        }

        // Fetch the complete URI string
        $this->uri->_fetch_uri_string();

        // Is there a URI string? If not, the default
        // controller specified in the "routes" file will be shown.
        if ($this->uri->uri_string == '') {
            return $this->_set_default_controller();
        }

        // Do we need to remove the URL suffix?
        $this->uri->_remove_url_suffix();

        // Compile the segments into an array
        $this->uri->_explode_segments();

        // Parse any custom routing that may exist
        $this->_parse_routes();

        // Re-index the segment array so that it starts with 1 rather than 0
        $this->uri->_reindex_segments();
    }
}
