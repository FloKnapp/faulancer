<?php
/**
 * Class Generate | Generate.php
 * @package ORM\Console
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Console\Handler;

use Faulancer\Console\ArgumentParser;
use Faulancer\Console\ConsoleInterface;
use Faulancer\ServiceLocator\ServiceLocator;
use ORM\DbConfig;
use ORM\EntityManager;
use Faulancer\Service\Config;

/**
 * Class Generate
 */
class Generate implements ConsoleInterface
{

    /**
     * @param ArgumentParser $args
     * @codeCoverageIgnore
     */
    public function schemeAction(ArgumentParser $args)
    {
        $dir = getcwd() . '/src/Entity';
    }

    /**
     * @param ArgumentParser $args
     * @throws \Exception
     * @throws \ORM\Exceptions\NoConnection
     * @codeCoverageIgnore
     */
    public function entitiesAction(ArgumentParser $args)
    {

        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);

        /*
        $dbConf = new DbConfig($conf['type'], $conf['name'], $conf['username'], $conf['password'], $conf['host']);

        $entityManager = new EntityManager([EntityManager::OPT_CONNECTION => $dbConf]);

        $tables = $entityManager->getConnection()->query('SHOW TABLES')->fetch(\PDO::FETCH_ASSOC);

        foreach ($tables as $table) {

            $result[$table] = $entityManager->getConnection()->query('SHOW COLUMNS FROM ' . $table)->fetch(\PDO::FETCH_ASSOC);

        }

        var_dump($tables);

        */

    }

}