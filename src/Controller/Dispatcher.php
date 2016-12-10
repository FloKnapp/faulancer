<?php

namespace Faulancer\Controller;

use Faulancer\Http\Request;
use Faulancer\Reflection\ClassParser;
use Faulancer\Helper\DirectoryIterator;
use Faulancer\Exception\MethodNotFoundException;

/**
 * Class Dispatcher
 *
 * @package Controller
 * @author Florian Knapp <office@florianknapp.de>
 */
class Dispatcher
{

    /** @var string */
    private static $routeCache = PROJECT_ROOT . '/cache/routes.php';

    protected static $classes = [];

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws MethodNotFoundException
     */
    public static function run(Request $request)
    {
        try {

            $target = self::getRoute($request->getUri());
            $class  = $target['class'];
            $action = $target['action'];
            return (new $class())->$action();

        } catch (MethodNotFoundException $e) {

            header('HTTP/2.0 404 Not found');
            echo 'Ooops - Site not found';

        }
    }

    /**
     * @param string $uri
     *
     * @return array
     * @throws MethodNotFoundException
     */
    private static function getRoute(string $uri = '')
    {
        if ($target = self::fromCache($uri)) {
            return $target;
        }

        $classes = DirectoryIterator::getFiles();
        $routes  = self::getRoutes($classes);

        foreach ($routes as $route) {

            foreach ($route as $class => $methods) {

                foreach ($methods as $data) {

                    if ($uri === $data['path']) {

                        $target = [
                            'class'  => $class,
                            'action' => $data['action']
                        ];

                        break;

                    }

                }

            }

        }

        if ($target) {

            self::saveIntoCache($uri, $target);
            return $target;

        }

        throw new MethodNotFoundException();
    }


    /**
     * @param array $classes
     *
     * @return array
     */
    private static function getRoutes(array $classes)
    {
        $routes = [];

        foreach ($classes as $namespace => $files) {

            foreach ($files as $file) {

                $curr     = str_replace('.php', '', $file);
                $class    = '\\' . $namespace . '\\' . $curr;
                $parser   = new ClassParser($class);
                $routes[] = $parser->getMethodDoc('Route');

            }

        }

        return $routes;
    }

    /**
     * @param $uri
     *
     * @return array
     */
    private static function fromCache($uri)
    {
        if (file_exists(self::$routeCache)) {

            $target = json_decode(file_get_contents(self::$routeCache), true);

            if (!empty($target[$uri])) {
                return $target[$uri];
            }

        }

        return [];
    }

    /**
     * @param $uri
     * @param $target
     *
     * @return boolean
     */
    private static function saveIntoCache($uri, $target)
    {
        $cache = [];

        if (file_exists(self::$routeCache)) {
            $cache = json_decode(file_get_contents(self::$routeCache), true);
        }

        file_put_contents(self::$routeCache, json_encode($cache + [$uri => $target], JSON_PRETTY_PRINT));

        return true;
    }

}