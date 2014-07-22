<?php

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
 * NV Controller
 *
 * Extend this controller to get access to entity manager, language extensions
 * XML content retriever for static pages, and some more common functions.
 *
 * @category   Router
 * @package    CodeIgniter
 * @subpackage Core_Extensions
 * @author     Vladimir Stračkovski <vlado@nv3.org>
 * @link       https://github.com/strackovski/CodeIgniter
 *
 * @property   CI_Loader $load
 * @property   CI_Config $config
 * @property   NV_Lang $lang
 * @property   CI_Session $session
 * @property   Doctrine $doctrine
 * @property   Twig $twig
 */
class NV_Controller extends CI_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        if ($this->config->item('enable_orm')) {
            $this->em = $this->doctrine->em;
        }

        if ($this->config->item('multilingual_ui')) {
            $this->lang->load($this->lang->lang());
        }
    }

    /**
     * Retrieve static content record from XML store
     *
     * @param string $type Type of content record
     * @param string $name Name of content record
     * @param bool|string $format Available formats:
     * SimpleXMLElement, PHP Array (default)
     *
     * @throws Exception
     * @return array|SimpleXMLElement|bool
     */
    protected function getContent($type, $name, $format = false)
    {
        $file = SRCPATH . 'Assets/data/content.xml';
        if (!file_exists($file)) {
            throw new Exception("Contents file not found: {$file}");
        }
        $xml = simplexml_load_file($file);
        if (!$xml instanceof SimpleXMLElement) {
            throw new Exception("Invalid XML document: {$file}");
        }

        $pages = $xml->xpath("//content[@type='{$type}' and @name='{$name}']");
        if (is_array($pages) and $pages[0] instanceof SimpleXMLElement) {
            if (!$format) {
                $data = array();
                return $this->xml2array($pages[0], $data);
            }
            return $pages[0];
        }
        return false;
    }

    /**
     * function xml2array
     *
     * This function is part of the PHP manual.
     *
     * The PHP manual text and comments are covered by the Creative Commons
     * Attribution 3.0 License, copyright (c) the PHP Documentation Group
     *
     * @author  k dot antczak at livedata dot pl
     * @date    2011-04-22 06:08 UTC
     * @link    http://www.php.net/manual/en/ref.simplexml.php#103617
     * @license http://www.php.net/license/index.php#doc-lic
     * @license http://creativecommons.org/licenses/by/3.0/
     * @license CC-BY-3.0 <http://spdx.org/licenses/CC-BY-3.0>
     *
     * return array
     */
    protected function xml2array ( $xmlObject, $out = array () )
    {
        foreach ((array) $xmlObject as $index => $node) {
            $out[$index] = (is_object($node)) ? $this->xml2array($node) : $node;
        }

        return $out;
    }
}
