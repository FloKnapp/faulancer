<?php

namespace Faulancer\Service\Factory;

use Faulancer\Service\DbService;
use ORM\DbConfig;
use ORM\EntityManager;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\FactoryInterface;
use Faulancer\ServiceLocator\ServiceLocatorInterface;
use ORM\Exception\InvalidConfiguration;

/**
 * Class DbServiceFactory | DbServiceFactory.php
 *
 * @package Faulancer\Service\Factory
 * @author  Florian Knapp <office@florianknapp.de>
 */
class DbServiceFactory implements FactoryInterface
{

    /**
     * Create an entity manager
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return DbService
     *
     * @throws InvalidConfiguration
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Config $config */
        $config = $serviceLocator->get(Config::class);

        $port = 3306;
        $type = $config->get('db:type') ?? 'config-key-not-found';
        $name = $config->get('db:name') ?? 'config-key-not-found';
        $user = $config->get('db:username') ?? 'config-key-not-found';
        $pass = $config->get('db:password') ?? 'config-key-not-found';
        $host = $config->get('db:host') ?? 'localhost';

        $attributes = [];

        if ($type === 'mysql') {

            $attributes = [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode ='ANSI_QUOTES', NAMES utf8",
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $port = $config->get('db:port');

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