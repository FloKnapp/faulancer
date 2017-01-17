<?php
/**
 * Class DbTables | DbTables.php
 * @package Faulancer\Console\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Console\Helper;

use Faulancer\Service\Config;
use ORM\DbConfig;
use ORM\EntityManager;

/**
 * Class DbTables
 */
class DbTables
{

    /**
     * @codeCoverageIgnore
     */
    public static function getTableData(Config $config)
    {

        $result = [];

        $dbConf = new DbConfig(
            $config->get('db:type'),
            $config->get('db:name'),
            $config->get('db:username'),
            $config->get('db:password'),
            $config->get('db:host')
        );

        $entityManager = new EntityManager([EntityManager::OPT_CONNECTION => $dbConf]);
        $tables        = $entityManager->getConnection()->query('SHOW TABLES')->fetchAll(\PDO::FETCH_UNIQUE);

        foreach (array_keys($tables) as $table) {

            $columns = $entityManager->getConnection()->query('SHOW COLUMNS FROM ' . $table)->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($columns as $column) {
                $result[$table][] = $column;
            }

        }

        return $result;

    }

}