<?php
/**
 * Class Dispatcher | Dispatcher.php
 * @package Faulancer\AbstractController
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Controller;

use Faulancer\Event\Observer;
use Faulancer\Event\Type\OnDispatch;
use Faulancer\Exception\ClassNotFoundException;
use Faulancer\Exception\DispatchFailureException;
use Faulancer\Exception\IncompatibleResponseException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Http\Http;
use Faulancer\Http\JsonResponse;
use Faulancer\Http\Request;
use Faulancer\Http\Response;
use Faulancer\Exception\MethodNotFoundException;
use Faulancer\Service\AuthenticatorPlugin;
use Faulancer\Service\AuthenticatorService;
use Faulancer\Service\Config;
use Faulancer\Service\SessionManagerService;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class Dispatcher
 */
class Dispatcher
{

    /**
     * The current request object
     *
     * @var Request
     */
    protected $request;

    /**
     * The configuration object
     *
     * @var Config
     */
    protected $config;

    /**
     * user, api
     *
     * @var string
     */
    protected $requestType = 'default';

    /**
     * Dispatcher constructor.
     *
     * @param Request $request
     * @param Config  $config
     */
    public function __construct(Request $request, Config $config)
    {
        $this->request = $request;
        $this->config  = $config;
    }

    /**
     * Bootstrap for every route call
     *
     * @return Response|JsonResponse|mixed
     * @throws MethodNotFoundException
     * @throws IncompatibleResponseException
     * @throws ServiceNotFoundException
     */
    public function dispatch()
    {
        // Check for core assets path
        if ($assets = $this->_resolveAssetsPath()) {
            return $assets;
        }

        Observer::instance()->trigger(new OnDispatch($this));

        $this->_setLanguageFromUri();

        if (strpos($this->request->getPath(), '/api') === 0) {
            $this->requestType = 'api';
        }

        list($class, $action, $permission, $payload) = $this->_getRoute($this->request->getPath());

        /** @var AbstractController $class */
        $class   = new $class($this->request);

        if (!empty($permission)) {

            /** @var AuthenticatorService $authenticator */
            $authenticator = ServiceLocator::instance()->get(AuthenticatorService::class);
            $isPermitted   = $authenticator->isPermitted($permission);

            if ($isPermitted === null) {

                return ServiceLocator::instance()->get(Http::class)->redirect($this->config->get('auth:authUrl'));

            } else if ($isPermitted === false) {

                $errorController = $this->config->get('customErrorController');
                return (new $errorController($this->request))->notPermittedAction();

            }

        }

        if (!method_exists($class, $action)) {

            throw new MethodNotFoundException(
                'Class "' . get_class($class) . '" doesn\'t have the method ' . $action
            );

        }

        $payload = array_map('strip_tags', $payload);
        $payload = array_map('htmlspecialchars', $payload);

        $response = call_user_func_array([$class, $action], $payload);

        if (!$response instanceof Response) {
            throw new IncompatibleResponseException('No valid response returned.');
        }

        return $response;

    }

    /**
     * @return bool
     *
     * @throws ServiceNotFoundException
     */
    private function _setLanguageFromUri()
    {
        if ($this->request->getParam('lang') !== null) {

            $serviceLocator = ServiceLocator::instance();

            /** @var SessionManagerService $sessionManager */
            $sessionManager = $serviceLocator->get(SessionManagerService::class);
            $sessionManager->set('language', $this->request->getParam('lang'));

            return true;

        }

        return false;
    }

    /**
     * @return bool|string
     */
    private function _resolveAssetsPath()
    {
        $matches = [];

        if (preg_match('/(?<style>css)|(?<script>js)/', $this->request->getPath(), $matches)) {

            $file = $this->request->getPath();

            if (strpos($file, 'core') !== false) {

                $path = str_replace('/core', '', $file);

                if ($matches['style'] === 'css') {
                    return $this->sendCssFileHeader($path);
                } else if ($matches['script'] === 'js') {
                    return $this->sendJsFileHeader($path);
                }

            }

        }

        return false;
    }

    /**
     * @param $file
     * @return string
     * @codeCoverageIgnore
     */
    public function sendCssFileHeader($file)
    {
        header('Content-Type: text/css');
        echo file_get_contents(__DIR__ . '/../../public/assets' . $file);
        exit(0);
    }

    /**
     * @param $file
     * @return string
     * @codeCoverageIgnore
     */
    public function sendJsFileHeader($file)
    {
        header('Content-Type: text/javascript');
        echo file_get_contents(__DIR__ . '/../../public/assets' . $file);
        exit(0);
    }

    /**
     * Get data for specific route path
     *
     * @param string $path
     *
     * @return array
     * @throws MethodNotFoundException
     */
    private function _getRoute($path)
    {
        if (strpos($this->request->getPath(), '/api') === 0) {
            $routes = $this->config->get('routes:rest');
        } else {
            $routes = $this->config->get('routes');
        }

        foreach ($routes as $name => $data) {

            if ($target = $this->_getDirectMatch($path, $data)) {
                return $target;
            } else if ($target = $this->_getVariableMatch($path, $data)) {
                return $target;
            }

        }

        throw new MethodNotFoundException('No matching route for path "' . $path . '" found');
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
    private function _getDirectMatch($uri, array $data) :array
    {
        if (!empty($data['path']) && $uri === $data['path']) {

            if ($this->requestType === 'default' && in_array($this->request->getMethod(), $data['method'] ?? ['GET'])) {

                return [
                    $data['controller'],
                    $data['action'] . 'Action',
                    $data['permission'] ?? null,
                    []
                ];

            } else if ($this->requestType === 'api') {

                return [
                    $data['controller'],
                    $this->_getRestfulAction(),
                    $data['permission'] ?? null,
                    []
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
    private function _getVariableMatch($uri, array $data) :array
    {
        if (empty($data['path']) || $data['path'] === '/') {
            return [];
        }

        $var   = [];
        $regex = str_replace(['/', '___'], ['\/', '+'], $data['path']);

        if (preg_match('|^' . $regex . '$|', $uri, $var)) {

            array_splice($var, 0, 1);

            if ($this->requestType === 'default'  && in_array($this->request->getMethod(), $data['method'])) {

                return [
                    $data['controller'],
                    $data['action'] . 'Action',
                    $data['permission'] ?? null,
                    $var
                ];

            } else if ($this->requestType === 'api') {

                return [
                    $data['controller'],
                    $this->_getRestfulAction(),
                    $data['permission'] ?? null,
                    $var
                ];

            }

        }

        return [];
    }
    

    /**
     * @return string
     * @codeCoverageIgnore
     */
    private function _getRestfulAction()
    {
        $method = strtoupper($this->request->getMethod());

        switch ($method) {

            case 'GET':
                return 'get';

            case 'POST':
                return 'create';

            case 'PUT':
                return 'update';

            case 'DELETE':
                return 'delete';

            case 'PATCH':
                return 'update';

            default:
                return 'get';

        }
    }

}