<?php
/**
 * Class Controller
 *
 * @package Faulancer\Controller
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Controller;

use Faulancer\Http\Request;
use Faulancer\Http\Uri;
use Faulancer\Service\AuthenticatorService;
use Faulancer\Service\DbService;
use Faulancer\Service\HttpService;
use Faulancer\Service\SessionManagerService;
use Faulancer\ServiceLocator\ServiceInterface;
use Faulancer\Session\SessionManager;
use Faulancer\View\ViewController;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class Controller
 */
abstract class Controller
{

    /**
     * Holds the views per controller request
     * @var array
     */
    private $viewArray = [];

    /**
     * Controller constructor.
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
     * @return SessionManager
     */
    public function getSessionManager() :SessionManager
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
     * @return string
     */
    public function render(string $template = '', $variables = []) :string
    {
        return $this->getView()->setTemplate($template)->setVariables($variables)->render();
    }

    /**
     * Set required authentication
     *
     * @param array $role
     * @return bool
     */
    public function requireAuth($role) :bool
    {
        /** @var AuthenticatorService $authenticator */
        $authenticator = $this->getServiceLocator()->get(AuthenticatorService::class);

        if ($authenticator->isAuthenticated($role) === false) {
            return $authenticator->redirectToAuthentication();
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
     * @return Request
     */
    public function getRequest() :Request
    {
        return $this->request;
    }

}