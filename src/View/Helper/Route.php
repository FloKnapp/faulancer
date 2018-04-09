<?php

namespace Faulancer\View\Helper;

use Faulancer\Exception\RouteInvalidException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Http\Request;
use Faulancer\Service\Config;
use Faulancer\Service\RequestService;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\View\AbstractViewHelper;

/**
 * Class Route | Route.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class Route extends AbstractViewHelper
{

    /**
     * Get route path by name
     *
     * @param string         $name
     * @param array          $parameters
     * @param bool           $absolute
     *
     * @return string
     *
     * @throws RouteInvalidException
     * @throws ServiceNotFoundException
     */
    public function __invoke(string $name, array $parameters = [], $absolute = false)
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);
        $routes = $config->get('routes');

        $apiRoutes = $config->get('routes:rest') ?? [];

        $routes = array_merge($routes, $apiRoutes);
        $path   = '';

        foreach ($routes as $routeName => $data) {

            if ($routeName === $name) {
                $path = preg_replace('|/\((.*)\)|', '', $data['path']);
                break;
            }

        }

        if (empty($path)) {
            throw new RouteInvalidException('No route for name "' . $name . '" found');
        }

        if (!empty($parameters)) {

            if (in_array('query', array_keys($parameters), true)) {
                $query = $parameters['query'];
                $query = http_build_query($query);
                $path  = $path . '?' . $query;
            } else {
                $path = $path . '/' . implode('/', $parameters);
            }

        }

        $path = str_replace('//' , '/', $path);

        if ($absolute) {

            /** @var Request $request */
            $request = $this->getServiceLocator()->get(RequestService::class);

            $path = $request->getScheme()
                . $request->getHost()
                . $path;

        }

        return $path;
    }

}