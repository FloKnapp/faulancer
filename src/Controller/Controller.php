<?php

namespace Faulancer\Controller;

use Faulancer\Exception\FileNotFoundException;
use Faulancer\Exception\InvalidArgumentException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Http\Http;
use Faulancer\Http\Request;
use Faulancer\Http\Response;
use Faulancer\Service\AuthenticatorService;
use Faulancer\Service\DbService;
use Faulancer\Session\SessionManager;
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
 */
class Controller
{

    /**
     * Contains the views per controller request
     *
     * @var array
     */
    private $_viewArray = [];

    /**
     * Contains the current request
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
    public function getServiceLocator(): ServiceLocator
    {
        return ServiceLocator::instance();
    }

    /**
     * Returns the session manager
     *
     * @return SessionManager|ServiceInterface
     */
    public function getSessionManager(): SessionManager
    {
        return $this->getServiceLocator()->get(SessionManager::class);
    }

    /**
     * Returns the view controller
     *
     * @return ViewController
     */
    public function getView(): ViewController
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
    public function getDb(): DbService
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
    public function render(string $template = '', array $variables = []) :Response
    {
        $this->addAssets();

        try {

            /** @var Response $response */
            $response = $this->getServiceLocator()->get(Response::class);

            $viewResult = $this->getView()
                ->setTemplate($template)
                ->setVariables($variables)
                ->render();

        } catch (FileNotFoundException $e) {
            $viewResult = $e->getMessage();
        } catch (ServiceNotFoundException $e) {
            $viewResult = $e->getMessage();
        }

        return $response->setContent($viewResult);
    }

    /**
     * Check if user is permitted based on his role(s)
     *
     * @param array $roles The corresponding user roles
     *
     * @return bool
     */
    public function isPermitted(array $roles = []): bool
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
     *
     * @throws InvalidArgumentException
     */
    public function redirect(string $uri) :bool
    {
        /** @var Http $httpService */
        $httpService = $this->getServiceLocator()->get(Http::class);
        return $httpService->redirect($uri);
    }

    /**
     * Set a generic text token which is valid for exactly one call
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
     */
    public function route(string $name, array $parameters = [], bool $absolute = false)
    {
        return (new Route())($name, $parameters, $absolute);
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
     * Add default assets for every action
     */
    protected function addAssets() {
        // Should be inherited by child classes
    }

}