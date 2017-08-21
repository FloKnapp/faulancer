<?php
/**
 * Class AbstractController
 *
 * @package Faulancer\AbstractController
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Controller;

use Faulancer\Exception\PluginException;
use Faulancer\Exception\RouteInvalidException;
use Faulancer\Fixture\Entity\UserEntity;
use Faulancer\Http\Request;
use Faulancer\Http\Response;
use Faulancer\Plugin\AbstractPlugin;
use Faulancer\Service\AuthenticatorService;
use Faulancer\Service\Config;
use Faulancer\Service\DbService;
use Faulancer\Service\HttpService;
use Faulancer\Service\SessionManagerService;
use Faulancer\ServiceLocator\ServiceInterface;
use Faulancer\View\ViewController;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class AbstractController
 */
abstract class AbstractController
{

    /**
     * Holds the views per controller request
     * @var array
     */
    private $viewArray = [];

    /**
     * @var Request
     */
    protected $request;

    /**
     * AbstractController constructor.
     * @param Request $request
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

        if (in_array($calledClass, array_keys($this->viewArray))) {
            return $this->viewArray[$calledClass];
        }

        $viewController = new ViewController();
        $this->viewArray[$calledClass] = $viewController;

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
     * @param  string $template
     * @param  array $variables
     * @return Response
     */
    public function render(string $template = '', $variables = []) :Response
    {
        return new Response($this->getView()->setTemplate($template)->setVariables($variables)->render());
    }

    /**
     * @param array $roles
     *
     * @return bool
     */
    public function requireAuth($roles = [])
    {
        /** @var AuthenticatorService $authService */
        $authService = $this->getServiceLocator()->get(AuthenticatorService::class);

        if (!$authService->isAuthenticated($roles)) {
            return $authService->redirectToAuthentication();
        }

        return true;
    }

    /**
     * @param string $uri
     * @return bool
     */
    public function redirect(string $uri) :bool
    {
        /** @var HttpService $httpService */
        $httpService = $this->getServiceLocator()->get(HttpService::class);
        return $httpService->redirect($uri);
    }

    /**
     * @param string $key
     * @param string $message
     */
    public function setFlashMessage(string $key, string $message)
    {
        $sessionManager = $this->getSessionManager();
        $sessionManager->setFlashMessage($key, $message);
    }

    /**
     * @param $key
     * @return string|null
     */
    public function getFlashMessage(string $key)
    {
        $sessionManager = $this->getSessionManager();
        return $sessionManager->getFlashMessage($key);
    }

    /**
     * @param string $name
     * @param array  $parameters
     * @param bool   $absolute
     *
     * @return string
     * @throws RouteInvalidException
     */
    public function route(string $name, array $parameters = [], $absolute = false)
    {
        /** @var Config $config */
        $config = $this->getServiceLocator()->get(Config::class);
        $routes = $config->get('routes');

        foreach ($routes as $routeName => $routeConfig) {

            if ($routeName === $name) {
                $path = preg_replace('|/\((.*)\)|', '', $routeConfig['path']);
                break;
            }

        }

        if (empty($path)) {
            throw new RouteInvalidException('No route for name "' . $name . '" found');
        }

        if (!empty($parameters)) {
            $path = $path . '/' . implode('/', $parameters);
        }

        if ($absolute) {
            $path = $this->getRequest()->getScheme() . $this->getRequest()->getHost() . $path;
        }

        return $path;
    }

    /**
     * @return Request
     */
    public function getRequest() :Request
    {
        return $this->request;
    }

    /**
     * Magic method for providing a view helper
     *
     * @param  string $name      The class name
     * @param  array  $arguments Arguments if given
     * @return AbstractPlugin
     * @throws PluginException
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