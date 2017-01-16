<?php
/**
 * Class ORM\Console\Output | Output.php
 *
 * @package ORM\Console\Output
 * @author Florian Knapp <office@florianknapp.de>
 */

namespace Faulancer\Console;

/**
 * Class Output
 */
class Output
{

    /** @var array */
    protected static $foregroundColors = [
        'black'        => "\033[0;30m",
        'dark_gray'    => "\033[1;30m",
        'blue'         => "\033[0;34m",
        'light_blue'   => "\033[1;34m",
        'green'        => "\033[0;32m",
        'light_green'  => "\033[1;32m",
        'cyan'         => "\033[0;36m",
        'light_cyan'   => "\033[1;36m",
        'red'          => "\033[0;31m",
        'light_red'    => "\033[1;31m",
        'purple'       => "\033[0;35m",
        'light_purple' => "\033[1;35m",
        'brown'        => "\033[0;33m",
        'yellow'       => "\033[1;33m",
        'light_gray'   => "\033[0;37m",
        'white'        => "\033[1;37m",
        'default'      => "\033[0m"
    ];

    /** @var array */
    protected static $backgroundColors = [
        'black'      => '40',
        'red'        => '41',
        'green'      => '42',
        'yellow'     => '43',
        'blue'       => '44',
        'magenta'    => '45',
        'cyan'       => '46',
        'light_gray' => '47'
    ];

    /**
     * @param string $message
     * @param string $type
     * @codeCoverageIgnore
     */
    public static function writeLine($message = '', $type = 'notice')
    {
        switch ($type) {

            case 'notice':
                print self::$foregroundColors['light_blue'];
                break;

            case 'warning':
                print self::$foregroundColors['yellow'];
                break;

            case 'error':
                print self::$foregroundColors['light_red'];
                break;

        }

        print $message . self::$foregroundColors['default'] . PHP_EOL;
    }

    /**
     * @return string
     */
    public static function writeEmptyLine()
    {
        print PHP_EOL;
    }

}