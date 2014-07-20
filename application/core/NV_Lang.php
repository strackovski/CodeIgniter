<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* CodeIgniter i18n library
*
* @author Jérôme Jaglale
* @link http://maestric.com/en/doc/php/codeigniter_i18n
* @version 10
* @date May 10, 2012
*/

/**
* Class NV_Lang
* Extends the default CodeIgniter Language class
*
 * @category   Internationalization
 * @package    CodeIgniter
 * @subpackage Libraries
 * @author     Jérôme Jaglale
*/
class NV_Lang extends CI_Lang
{
    // languages
    var $languages = array(
        'en' => 'english',
        'fr' => 'french',
        'sl' => 'slovenian',
        'sr' => 'serbian',
        'it' => 'italian',
        'pl' => 'polish',
        'hr' => 'croatian',
        'de' => 'german'
    );

    // special URIs (not localized)
    var $special = array (
        ""
    );

    // where to redirect if no language in URI
    var $default_uri = '';

    /**
     * Constructor
     *
     * @access	public
     */
    function __construct()
    {
        parent::__construct();
        global $CFG;
        global $URI;
        global $RTR;
        $segment = $URI->segment(1);

        if (isset($this->languages[$segment])) {	// URI with language -> ok{
            $language = $this->languages[$segment];
            $CFG->set_item('language', $language);

        } elseif ($this->is_special($segment)) {
            // special URI -> no redirect{
            return;
        } else {
            // URI without language -> redirect to default_uri
            // set default language
            $CFG->set_item('language', $this->languages[$this->default_lang()]);

            // redirect
            header(
                "Location: ".
                $CFG->site_url($this->localized($this->default_uri)), true, 302
            );
            exit;
        }
    }

    /**
     * Get current language
     * Return 'en' if language in CI config is 'english'
     *
     * @return mixed|null
     */
    function lang()
    {
        global $CFG;
        $language = $CFG->item('language');
        $lang = array_search($language, $this->languages);
        if ($lang) {
            return $lang;
        }

        return null;	// this should not happen
    }

    /**
     * Is special
     *
     * @param string $uri URI
     *
     * @return bool
     */
    function is_special($uri)
    {
        $exploded = explode('/', $uri);
        if (in_array($exploded[0], $this->special)) {
            return true;
        }
        if (isset($this->languages[$uri])) {
            return true;
        }
        return false;
    }

    /**
     * Switch URI
     *
     * @param string $lang Language string
     *
     * @return string
     */
    function switch_uri($lang)
    {
        $CI =& get_instance();

        $uri = $CI->uri->uri_string();
        if ($uri != "") {
            $exploded = explode('/', $uri);
            if ($exploded[0] == $this->lang()) {
                $exploded[0] = $lang;
            }
            $uri = implode('/',$exploded);
        }
        return $uri;
    }

    /**
     * Is there a language segment in this $uri?
     *
     * @param string $uri URI
     *
     * @return bool
     */
    function has_language($uri)
    {
        $first_segment = null;

        $exploded = explode('/', $uri);
        if (isset($exploded[0])) {
            if ($exploded[0] != '') {
                $first_segment = $exploded[0];
            } elseif (isset($exploded[1]) && $exploded[1] != '') {
                $first_segment = $exploded[1];
            }
        }

        if ($first_segment != null) {
            return isset($this->languages[$first_segment]);
        }

        return false;
    }

    /**
     * Default language: first element of $this->languages
     *
     * @return int|string
     */
    function default_lang()
    {
        foreach ($this->languages as $lang => $language) {
            return $lang;
        }
    }

    /**
     * Add language segment to $uri (if appropriate)
     *
     * @param string $uri URI
     *
     * @return string
     */
    function localized($uri)
    {
        if ($this->has_language($uri) || $this->is_special($uri) || preg_match('/(.+)\.[a-zA-Z0-9]{2,4}$/', $uri)) {
            // we don't need a language segment because:
            // - there's already one or
            // - it's a special uri (set in $special) or
            // - that's a link to a file
        } else {
            $uri = $this->lang() . '/' . $uri;
        }
        return $uri;
    }
}
/* End of file */
