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

use Doctrine\ORM\Tools\Setup,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\ClassLoader;

/**
 * Class Doctrine
 *
 * Doctrine bootstrap
 *
 * @package    CodeIgniter
 * @subpackage Libraries
 */
class Doctrine
{
    /** @var \Doctrine\ORM\EntityManagerInterface Doctrine entity manager */
    public $em;

    /** @var bool Development mode flag */
    public $devMode;

    /**
     * Constructor
     */
    public function __construct()
    {
        if (defined('ENVIRONMENT') and ENVIRONMENT == 'development') {
            $this->devMode = true;
        }
        $this->devMode = false;

        if (file_exists(FCPATH . 'config/database.php')) {
            include FCPATH . 'config/database.php';
        } elseif (file_exists(FCPATH . 'config/database.php')) {
            include APPPATH . 'config/database.php';
        } else {
            throw new Exception('Failed retrieving database configuration.');
        }

        if (!isset($db)) {
            throw new Exception('Failed retrieving database configuration.');
        }
        if (!is_array($db) or !array_key_exists('default', $db)) {
            throw new Exception('Invalid database configuration file.');
        }

        $connection_options = array(
            'driver'        => $db['default']['dbdriver'],
            'user'          => $db['default']['username'],
            'password'      => $db['default']['password'],
            'host'          => $db['default']['hostname'],
            'dbname'        => $db['default']['database'],
            'charset'       => $db['default']['char_set'],
            'driverOptions' => array(
                'charset' => $db['default']['char_set'],
            ),
        );

        $proxies_dir      =  FCPATH . 'var/cache/orm/proxy';
        $metadata_paths   = array(
            SRCPATH . 'Model/Entity'
        );

        $config = Setup::createAnnotationMetadataConfiguration($metadata_paths, $this->devMode, $proxies_dir);
        $this->em = EntityManager::create($connection_options, $config);

        $commonLoader = new ClassLoader('nv\\', SRCPATH);
        $commonLoader->register();
    }
}