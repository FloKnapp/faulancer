<?php
/**
 * Class ArgumentParser | ArgumentParser.php
 * @package ORM\Console
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Console;

use Faulancer\Service\Config;

/**
 * Class ArgumentParser
 */
class ArgumentParser
{

    /** @var array */
    protected $arguments = [];

    /** @var array */
    protected $config;

    /**
     * ArgumentParser constructor.
     * @param array $argv
     * @param Config $config
     * @codeCoverageIgnore
     */
    public function __construct($argv, $config)
    {
        $this->config = $config;
        $this->parseInput($argv);
    }

    /**
     * @param array $argv
     * @return ConsoleInterface
     * @throws \Exception
     * @codeCoverageIgnore
     */
    protected function parseInput(array $argv) :ConsoleInterface
    {
        $args = array_splice($argv, 1, count($argv));

        if (empty($args)) {
            throw new \Exception('Not enough parameters given.');
        }

        for ($i = 0; $i < count($args); $i++) {

            if (strpos($args[$i], '-') === false) {
                continue;
            } else if (empty($args[$i+1])) {
                break;
            }

            $this->set(str_replace('-', '', $args[$i]), $args[$i+1]);

        }

        if (strpos($args[0], ':') !== false) {

            $parts  = explode(':', $args[0]);
            $class  = $parts[0];
            $method = $parts[1] . 'Action';
            $ns     = '\Faulancer\Console\Handler\\' . ucfirst($class);

            $class = new $ns($this->config, $this);

            return call_user_func([$class, $method], $this);

        }

        throw new \Exception('No matching handler found');
    }

    /**
     * @param string $arg
     * @param string $value
     * @codeCoverageIgnore
     */
    public function set($arg, $value)
    {
        $this->arguments[$arg] = $value;
    }

    /**
     * @param string $arg
     * @return string|array
     * @codeCoverageIgnore
     */
    public function get($arg)
    {
        if (empty($this->arguments[$arg])) {
            return '';
        }

        return $this->arguments[$arg];
    }

}