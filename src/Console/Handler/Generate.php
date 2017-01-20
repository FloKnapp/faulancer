<?php
/**
 * Class Generate | Generate.php
 * @package ORM\Console
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Console\Handler;

use Faulancer\Console\ArgumentParser;
use Faulancer\Console\ConsoleInterface;
use Faulancer\Console\Helper\DbTables;
use Faulancer\Console\Helper\TableRelationResolver;
use Faulancer\Console\Output;
use Faulancer\ServiceLocator\ServiceLocator;
use ORM\DbConfig;
use ORM\EntityManager;
use Faulancer\Service\Config;
use Symfony\Component\EventDispatcher\Tests\Service;

/**
 * Class Generate
 */
class Generate implements ConsoleInterface
{

    /** @var array */
    private $mysqlTypeMapping = [
        '/@property text/'         => '@property string',
        '/@property int\((\d+)\)/' => '@property integer',
        '/@property datetime/'     => '@property string',
        '/@property timestamp/'    => '@property string',
        '/varchar\((\d+)\)/'       => 'string'
    ];

    /** @var Config */
    protected $config;

    /** @var ArgumentParser */
    protected $args;

    /**
     * Generate constructor.
     * @param Config         $config
     * @param ArgumentParser $args
     * @codeCoverageIgnore
     */
    public function __construct(Config $config, ArgumentParser $args)
    {
        $this->config = $config;
        $this->args   = $args;
    }

    /**
     * @throws \Exception
     * @throws \ORM\Exceptions\NoConnection
     * @codeCoverageIgnore
     */
    public function entitiesAction()
    {
        $creationCount = 0;
        $entitiesRoot  = $this->config->get('projectRoot') . '/src/Entity';

        if (!is_dir($entitiesRoot)) {
            mkdir($entitiesRoot);
        }

        $result = DbTables::getTableData($this->config);

        foreach ($result as $table => $fields) {

            $properties = [];
            $namespace  = $this->config->get('namespacePrefix') . '\\Entity';
            $className  = ucfirst($table) . 'Entity';

            Output::writeLine('Process table "' . $table . '"', 'warning');

            $targetFile = $entitiesRoot . '/' . $className . '.php';

            if (file_exists($targetFile)) {
                Output::writeLine('Entity "' . $className . '" already exists... skipping.', 'error');
                continue;
            }

            $properties[] = '/**';

            foreach ($fields as $field) {

                $properties[] = ' * @property ' . $field['Type'] . ' $' . $field['Field'];
                Output::writeLine('Create property ' . $field['Field']);
                
            }

            $properties[] = ' */';

            $props     = implode(PHP_EOL, $properties);
            $props     = preg_replace(
                array_keys($this->mysqlTypeMapping),
                array_values($this->mysqlTypeMapping),
                $props
            );

            $fixture = str_replace(
                ['{$namespace}', '{$props}', '{$table}', '{$className}'],
                [$namespace, $props, $table, $className],
                file_get_contents(__DIR__ . '/../Fixture/Entity.fixture')
            );


            file_put_contents($entitiesRoot . '/' . $className . '.php', $fixture);

            Output::writeLine('Entity for table "' . $table . '" successfully generated', 'success');

            $creationCount++;

            Output::writeEmptyLine();

        }

        Output::writeLine($creationCount . ' entities generated', 'success');

    }

}