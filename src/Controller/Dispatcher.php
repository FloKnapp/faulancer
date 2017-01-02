<?php

namespace Faulancer\Controller;

use Exception\ClassNotFoundException;
use Faulancer\Http\Request;
use Faulancer\Http\Response;
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
    private static $routeCache = PROJECT_ROOT . '/cache/routes.json';

    /** @var array */
    protected static $classes = [];

    /**
     * Bootstrap for every route call
     *
     * @param Request $request
     * @param boolean $routeCacheEnabled
     *
     * @return Response
     * @throws MethodNotFoundException
     * @throws ClassNotFoundException
     */
    public static function run(Request $request, $routeCacheEnabled = true)
    {
        try {

            $target = self::getRoute($request->getUri(), $routeCacheEnabled);
            $class  = $target['class'];
            $action = $target['action'];

            if (!class_exists($class)) {
                throw new ClassNotFoundException();
            }

            $class = new $class();

            if (!method_exists($class, $action)) {
                throw new MethodNotFoundException();
            }

            if (isset($target['var'])) {
                return call_user_func_array([$class, $action], $target['var']);
            }

            return $class->$action();

        } catch (MethodNotFoundException $e) {

            header('HTTP/2 404 Not found');

        }

        return null;
    }

    /**
     * Get data for specific route path
     *
     * @param string $uri
     * @param boolean $routeCacheEnabled
     *
     * @return array
     * @throws MethodNotFoundException
     */
    private static function getRoute(string $uri = '', $routeCacheEnabled)
    {
        $target = null;

        if ($routeCacheEnabled && $target = self::fromCache($uri)) {
            return $target;
        }

        $routes = self::getRoutes();

        foreach ($routes as $route) {

            foreach ($route as $class => $methods) {

                foreach ($methods as $data) {

                    if ($data === false) {
                        continue;
                    } else if ($target = self::getDirectMatch($uri, $data)) {
                        break;
                    } else if ($target = self::getVariableMatch($uri, $data)) {
                        break;
                    }

                }

            }

        }

        if ($target) {

            if ($routeCacheEnabled) {
                self::saveIntoCache($uri, $target);
            }

            return $target;

        }

        throw new MethodNotFoundException('Could not resolve route ' . $uri);
    }

    /**
     * Determines if we have a direct/static route match
     *
     * @param string $uri  The request uri
     * @param array  $data The result from ClassParser
     *
     * @return array
     * @throws MethodNotFoundException
     */
    private static function getDirectMatch(string $uri, array $data)
    {
        if ($uri === $data['path']) {

            if ($data['method'] === strtolower(Request::getRequestMethod())) {

                return [
                    'class'  => $data['class'],
                    'action' => $data['action'],
                    'name'   => $data['name'],
                    'method' => $data['method']
                ];

            }

            throw new MethodNotFoundException('Non valid request method available.');

        }

        return [];
    }

    /**
     * Determines if we have a variable route match
     *
     * @param string $uri
     * @param array  $data
     *
     * @return array
     * @throws MethodNotFoundException
     */
    private static function getVariableMatch(string $uri, array $data)
    {
        $var = [];

        if ($data['path'] === '/') {
            return [];
        }

        $regex = str_replace(
            ['/', '___'],
            ['\/', '+'],
            $data['path']
        );

        if (preg_match('|^' . $regex . '$|', $uri, $var)) {

            // Abort handling if there are more parts than regex has exposed
            if (count(explode('/', $uri)) > count(explode('/', $var[0]))) {
                return [];
            }

            array_splice($var, 0, 1);

            return [
                'class'  => $data['class'],
                'action' => $data['action'],
                'name'   => $data['name'],
                'var'    => $var
            ];

        }

        return [];
    }

    /**
     * Get all defined routes
     *
     * @return array
     */
    private static function getRoutes()
    {
        $routes  = [];
        $classes = DirectoryIterator::getFiles();

        foreach ($classes as $namespace => $files) {

            foreach ($files as $file) {

                $class    = '\\' . $namespace . '\\' . str_replace('.php', '', $file);
                $parser   = new ClassParser($class);
                $routes[] = $parser->getMethodDoc('Route');

            }

        }

        return $routes;
    }

    /**
     * Retrieve route params from cache
     *
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
     * Save route params into cache
     *
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

    /**
     * Invalidates the whole cache
     *
     * @return boolean
     */
    private static function invalidateCache()
    {
        return unlink(self::$routeCache);
    }

}