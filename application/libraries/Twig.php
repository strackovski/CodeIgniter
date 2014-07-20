<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Library to wrap Twig layout engine. Originally from Bennet Matschullat.
 * Code cleaned up to CodeIgniter standards by Erik Torsner
 *
 * PHP Version 5.3
 *
 * @category Layout
 * @package  Twig
 * @author   Bennet Matschullat <bennet@3mweb.de>
 * @author   Erik Torsner <erik@torgesta.com>
 * @license  Don't be a dick http://www.dbad-license.org/
 * @link     https://github.com/bmatschullat/Twig-Codeigniter
 */

/**
 * Main (and only) class for the Twig wrapper library
 * 
 * @category Layout
 * @package  Twig
 * @author   Bennet Matschullat <hello@bennet-matschullat.com>
 * @author   Erik Torsner <erik@torgesta.com>
 * @license  Don't be a dick http://www.dbad-license.org/
 * @link     https://github.com/bmatschullat/Twig-Codeigniter
 */
class Twig
{
    const 		TWIG_CONFIG_FILE = 'twig';

	/**
	 * Path to templates. Usually application/views.
	 * 
	 * @var string
	 */
	protected $template_dir;

	/**
	 * Path to cache.  Usually applcation/cache.
	 * 
	 * @var string
	 */
	protected $cache_dir;

	/**
	 * Reference to CodeIgniter instance.
	 * 
	 * @var CodeIgniter object
	 */
	private $_ci;

	/**
	 * Twig environment see http://twig.sensiolabs.org/api/v1.8.1/Twig_Environment.html.
	 * 
	 * @var Twig_Envoronment object
	 */
	private $_twig_env;

	/**
	 * constructor of twig ci class
	 */
	public function __construct() 
	{
		$this->_ci = & get_instance();
		$this->_ci->config->load(self::TWIG_CONFIG_FILE);
		Twig_Autoloader::register();
		log_message('debug', 'twig autoloader loaded');
		// init paths
		$this->template_dir = $this->_ci->config->item('template_dir');
        $this->cache_dir = $this->_ci->config->item('cache_dir');
		// load environment
		$loader = new Twig_Loader_Filesystem($this->template_dir, $this->cache_dir);
		$this->_twig_env = new Twig_Environment($loader, array(
			'cache' => $this->cache_dir,
			'auto_reload' => defined('ENVIRONMENT') && ENVIRONMENT == 'development' ? true : false
            )
        );


        // enable all php functions for twig
        foreach(get_defined_functions() as $functions) {
            foreach($functions as $function) {
                $this->_twig_env->addFunction($function, new Twig_Function_Function($function));
            }
        }

		$this->ci_function_init();
	}

	/**
	 * render a twig template file
	 * 
	 * @param string  $template template name
	 * @param array   $data	    contains all varnames
	 * @param boolean $render   render or return raw?
	 *
	 * @return void
	 * 
	 */
	public function render($template, $data = array(), $render = TRUE) 
	{
		$template = $this->_twig_env->loadTemplate($template);
		log_message('debug', 'twig template loaded');
		return ($render) ? $template->render($data) : $template;
	}

	/**
	 * Execute the template and send to CI output
	 * 
	 * @param string $template Name of template
	 * @param array  $data     Parameters for template
	 * 
	 * @return void
	 * 
	 */
	public function display($template, $data = array()) 
	{
		$template = $this->_twig_env->loadTemplate($template);
		$this->_ci->output->set_output($template->render($data));
	}

	/**
	 * Entry point for controllers (and the likes) to register
	 * callback functions to be used from Twig templates
	 * 
	 * @param string                 $name     name of function
	 * @param Twig_FunctionInterface $function Function pointer
	 * 
	 * @return void
	 * 
	 */
	public function register_function($name, Twig_FunctionInterface $function) 
	{
		$this->_twig_env->addFunction($name, $function);
	}

	/**
	 * Initialize standard CI functions
	 * 
	 * @return void
	 */
	public function ci_function_init() 
	{
        $this->_twig_env->addFunction('memory_usage', new Twig_Function_Function('memory_usage'));
        $this->_twig_env->addFunction('elapsed_time', new Twig_Function_Function('elapsed_time'));
  	}
}
