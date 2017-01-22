<?php
/**
 * Class Route | Route.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Exception\ConfigInvalidException;
use Faulancer\Exception\FactoryMayIncompatibleException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class Route
 */
class Route extends AbstractViewHelper
{

    /**
     * Get route path by name
     *
     * @param ViewController $view
     * @param string         $name
     * @param array          $parameters
     * @return string
     * @throws \Exception
     */
    public function __invoke(ViewController $view, string $name, array $parameters = [])
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);
        $routes = require $config->get('routeFile');
        $path   = '';

        foreach ($routes as $routeName => $data) {

            if ($routeName === $name) {
                $path = preg_replace('|/\((.*)\)|', '', $data['path']);;
                break;
            }

        }

        if (empty($path)) {
            throw new \Exception('No route for name "' . $name . '" found');
        }

        if (!empty($parameters)) {
            $path = $path . '/' . implode('/', $parameters);
        }

        return $path;
    }

}