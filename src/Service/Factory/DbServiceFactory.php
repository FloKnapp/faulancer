<?php
/**
 * Class DbServiceFactory | DbServiceFactory.php
 * @package Faulancer\Service\Factory
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Service\Factory;

use Faulancer\Service\DbService;
use ORM\DbConfig;
use ORM\EntityManager;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\FactoryInterface;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\ServiceLocator\ServiceLocatorInterface;

/**
 * Class DbServiceFactory
 */
class DbServiceFactory implements FactoryInterface
{

    /**
     * Create an entity manager
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return DbService
     * @codeCoverageIgnore
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);

        $type = $config->get('db:type');
        $name = $config->get('db:name');
        $user = $config->get('db:username');
        $pass = $config->get('db:password');
        $host = $config->get('db:host') ?: 'localhost';
        $port = '';

        $attributes = [];

        if ($type === 'mysql') {

            $attributes = [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode ='ANSI_QUOTES', NAMES utf8",
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $port = $config->get('db:port') ?: 3306;

        }

        $dbConf = new DbConfig(
            $type,
            $name,
            $user,
            $pass,
            $host,
            $port,
            $attributes
        );

        $entityManager = new EntityManager([EntityManager::OPT_CONNECTION => $dbConf]);

        return new DbService($entityManager);
    }

}