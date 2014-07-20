<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * NV Router Class
 *
 * Extends the default CI_Router class, adds support for custom controller directories.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @author		Vladimir Stračkovski <vlado@nv3.org>
 */
class NV_Router extends CI_Router
{
    /**
     * Validates the supplied segments.  Attempts to determine the path to
     * the controller.
     *
     * @access	private
     * @param	array
     * @return	array
     */
    function _validate_request($segments)
    {
        if (count($segments) == 0)
        {
            return $segments;
        }

        // Does the requested controller exist in the src folder?
        if (file_exists($this->config->config['controllers_search_dir'].ucfirst($segments[0]).$this->config->config['controller_suffix'].'.php'))
        {
            return $segments;
        }

        // Does the requested controller exist in the root folder?
        if (file_exists(APPPATH.'controllers/'.ucfirst($segments[0]).$this->config->config['controller_suffix'].'.php'))
        {
            return $segments;
        }

        // Does the controller directory have subdirectories with contoller name?
        if (is_dir(APPPATH.'controllers/'.ucfirst($segments[0]))){
            $subdir = APPPATH.'controllers/';
        }
        elseif (is_dir($this->config->config['controllers_search_dir'].ucfirst($segments[0]))){
            $subdir = $this->config->config['controllers_search_dir'];
        }

        // Is the controller in a sub-folder?
        if(isset($subdir)){
            // Set the directory and remove it from the segment array
            $this->set_directory($segments[0]);
            $segments = array_slice($segments, 1);

            if (count($segments) > 0)
            {
                // Does the requested controller exist in the sub-folder?
                if ( ! file_exists($subdir.$this->fetch_directory().ucfirst($segments[0]).$this->config->config['controller_suffix'].'.php'))
                {
                    if ( ! empty($this->routes['404_override']))
                    {
                        $x = explode('/', $this->routes['404_override']);

                        $this->set_directory('');
                        $this->set_class($x[0]);
                        $this->set_method(isset($x[1]) ? $x[1] : 'index');

                        return $x;
                    }
                    else
                    {
                        show_404($this->fetch_directory().$segments[0]);
                    }
                }
            }
            else
            {
                // Is the method being specified in the route?
                if (strpos($this->default_controller, '/') !== FALSE)
                {
                    $x = explode('/', $this->default_controller);

                    $this->set_class($x[0]);
                    $this->set_method($x[1]);
                }
                else
                {
                    $this->set_class($this->default_controller);
                    $this->set_method('index');
                }

                // Does the default controller exist in the sub-folder?
                if ( ! file_exists($subdir.$this->fetch_directory().ucwords($this->default_controller).$this->config->config['controller_suffix'].'.php'))
                {
                    $this->directory = '';
                    return array();
                }

            }

            return $segments;
        }

        // If we've gotten this far it means that the URI does not correlate to a valid controller class.
        if ( ! empty($this->routes['404_override']))
        {
            $x = explode('/', $this->routes['404_override']);

            $this->set_class($x[0]);
            $this->set_method(isset($x[1]) ? $x[1] : 'index');

            return $x;
        }
        // Nothing else to do at this point but show a 404
        show_404($segments[0]);
    }

}
