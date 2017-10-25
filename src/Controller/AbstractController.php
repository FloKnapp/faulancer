<?php

namespace Faulancer\Controller;

use Faulancer\Exception\PluginException;
use Faulancer\Exception\RouteInvalidException;
use Faulancer\Http\Request;
use Faulancer\Http\Response;
use Faulancer\Plugin\AbstractPlugin;
use Faulancer\Service\AuthenticatorService;
use Faulancer\Service\Config;
use Faulancer\Service\DbService;
use Faulancer\Service\HttpService;
use Faulancer\Service\SessionManagerService;
use Faulancer\ServiceLocator\ServiceInterface;
use Faulancer\View\Helper\Route;
use Faulancer\View\ViewController;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class AbstractController
 *
 * @category Controller
 * @package  Faulancer\AbstractController
 * @author   Florian Knapp <office@florianknapp.de>
 * @license  MIT License
 * @link     none
 */
abstract class AbstractController
{

    /**
     * Holds the views per controller request
     *
     * @var array
     */
    private $_viewArray = [];

    /**
     * Holds the request
     *
     * @var Request
     */
    protected $request;

    /**
     * AbstractController constructor.
     *
     * @param Request $request The request object
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Returns the service locator
     *
     * @return ServiceLocator
     */
    public function getServiceLocator() :ServiceLocator
    {
        return ServiceLocator::instance();
    }

    /**
     * Returns the session manager
     *
     * @return SessionManagerService|ServiceInterface
     */
    public function getSessionManager() :SessionManagerService
    {
        return $this->getServiceLocator()->get(SessionManagerService::class);
    }

    /**
     * Returns the view controller
     *
     * @return ViewController
     */
    public function getView() :ViewController
    {
        $calledClass = get_called_class();

        if (in_array($calledClass, array_keys($this->_viewArray), true)) {
            return $this->_viewArray[$calledClass];
        }

        $viewController = new ViewController();

        $this->_viewArray[$calledClass] = $viewController;

        return $viewController;

    }

    /**
     * Returns the orm/entity manager
     *
     * @return DbService|ServiceInterface
     */
    public function getDb() :DbService
    {
        return $this->getServiceLocator()->get(DbService::class);
    }

    /**
     * Render view with given template
     *
     * @param string $template  The template to be rendered
     * @param array  $variables The variables for the template
     *
     * @return Response
     */
    public function render(string $template = '', $variables = []) :Response
    {
        return new Response(
            $this->getView()
                ->setTemplate($template)
                ->setVariables($variables)
                ->render()
        );
    }

    /**
     * Check if user is permitted based on his role(s)
     *
     * @param array $roles The corresponding user roles
     *
     * @return bool|null
     */
    public function isPermitted($roles = [])
    {
        /** @var AuthenticatorService $authService */
        $authService = $this->getServiceLocator()->get(AuthenticatorService::class);

        return $authService->isPermitted($roles);
    }

    /**
     * Redirect to specific uri
     *
     * @param string $uri The target uri
     *
     * @return bool
     */
    public function redirect(string $uri) :bool
    {
        /** @var HttpService $httpService */
        $httpService = $this->getServiceLocator()->get(HttpService::class);
        return $httpService->redirect($uri);
    }

    /**
     * Set a universal text token which is valid for exactly one request/call
     *
     * @param string $key     Key for the flash message
     * @param string $message Content for the flash message
     *
     * @return void
     */
    public function setFlashMessage(string $key, string $message)
    {
        $sessionManager = $this->getSessionManager();
        $sessionManager->setFlashMessage($key, $message);
    }

    /**
     * Retrieve a flash message
     *
     * @param string $key The flash message key
     *
     * @return string|null
     */
    public function getFlashMessage(string $key)
    {
        $sessionManager = $this->getSessionManager();
        return $sessionManager->getFlashMessage($key);
    }

    /**
     * Get the url for a specific route name
     *
     * @param string $name       Name of the route
     * @param array  $parameters Apply parameters where necessary
     * @param bool   $absolute   Return an absolute url with host as prefix
     *
     * @return string
     * @throws RouteInvalidException
     */
    public function route(string $name, array $parameters = [], $absolute = false)
    {
        return (new Route())($this->getView(), $name, $parameters, $absolute);
    }

    /**
     * Return the current request object
     *
     * @return Request
     */
    public function getRequest() :Request
    {
        return $this->request;
    }

    /**
     * Magic method for providing a view helper
     *
     * @param string $name      The class name
     * @param array  $arguments Arguments if given
     *
     * @return AbstractPlugin
     * @throws PluginException
     *
     * @codeCoverageIgnore Not implemented yet
     */
    public function __call($name, $arguments)
    {
        // Search in core view helpers first
        $corePlugin = 'Faulancer\Plugin\\' . ucfirst($name);

        if (class_exists($corePlugin)) {

            $class = new $corePlugin;
            array_unshift($arguments, $this);

            return call_user_func_array($class, $arguments);

        }

        // No core implementations found; search in custom view helpers

        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);
        $namespace = '\\' . $config->get('namespacePrefix');

        $customPlugin = $namespace . '\Plugin\\' . ucfirst($name);

        if (class_exists($customPlugin)) {

            $class = new $customPlugin;
            array_unshift($arguments, $this);

            return call_user_func_array($class, $arguments);

        }

        throw new PluginException('No plugin for "' . $name . '" found.');
    }

}