<?php require_once dirname(dirname(dirname(__FILE__))) . "/vendor/autoload.php";

use Doctrine\ORM\Tools\Setup,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\ClassLoader;

/**
 * Class Doctrine
 * Doctrine bootstrap wrapper
 *
 * @author Vladimir Stračkovski <vlado@nv3.org>
 */
class Doctrine
{
    public $em;

    public function __construct()
    {
        $dev_mode = false;
        require APPPATH . 'config/database.php';
        $connection_options = array(
            'driver'        => 'pdo_mysql',
            'user'          => $db['default']['username'],
            'password'      => $db['default']['password'],
            'host'          => $db['default']['hostname'],
            'dbname'        => $db['default']['database'],
            'charset'       => $db['default']['char_set'],
            'driverOptions' => array(
                'charset' => $db['default']['char_set'],
            ),
        );

        $dir = dirname(dirname(dirname(__FILE__)));
        $proxies_dir      =  FCPATH . 'var/cache/orm/proxy';
        $metadata_paths   = array(
            FCPATH . SRCPATH . 'Model/Entity'
        );

        $config = Setup::createAnnotationMetadataConfiguration($metadata_paths, $dev_mode, $proxies_dir);
        $this->em = EntityManager::create($connection_options, $config);

        $commonLoader = new ClassLoader('nv', $dir);
        $commonLoader->register();
    }
}