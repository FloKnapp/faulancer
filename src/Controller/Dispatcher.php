<?php
/**
 * Class Dispatcher
 *
 * @package Faulancer\Controller
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Controller;

use Faulancer\Exception\ClassNotFoundException;
use Faulancer\Exception\DispatchFailureException;
use Faulancer\Exception\IncompatibleResponse;
use Faulancer\Form\AbstractFormHandler;
use Faulancer\Http\Request;
use Faulancer\Http\Response;
use Faulancer\Exception\MethodNotFoundException;
use Faulancer\Service\Config;

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
     * @return Response|mixed
     * @throws MethodNotFoundException
     * @throws ClassNotFoundException
     * @throws DispatchFailureException
     * @throws IncompatibleResponse
     */
    public function dispatch()
    {
        // Check for form submit
        if ($formRequest = $this->handleFormRequest()) {
            return $formRequest;
        }

        // Check for core assets path
        if ($assets = $this->resolveAssetsPath()) {
            return $assets;
        }

        /** @var Response $response */
        $response = null;

        $target = $this->getRoute($this->request->getUri());
        $class  = $target['class'];
        $action = $target['action'] . 'Action';
        $class  = new $class($this->request);

        if (isset($target['var'])) {
            $response = call_user_func_array([$class, $action], $target['var']);
        } else {
            $response = $class->$action();
        }

        if ($response instanceof Response) {
            return $response->getContent();
        }

        throw new IncompatibleResponse('No valid response');
    }

    /**
     * @return boolean|string
     */
    private function resolveAssetsPath()
    {
        $matches = [];

        if (preg_match('/(?<style>css)|(?<script>js)/', $this->request->getUri(), $matches)) {

            $file = $this->request->getUri();

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
        return file_get_contents(__DIR__ . '/../../public/assets' . $file);
    }

    /**
     * @param $file
     * @return string
     * @codeCoverageIgnore
     */
    public function sendJsFileHeader($file)
    {
        header('Content-Type: text/javascript');
        return file_get_contents(__DIR__ . '/../../public/assets' . $file);
    }

    /**
     * Get data for specific route path
     *
     * @param string $path
     *
     * @return array
     * @throws MethodNotFoundException
     */
    private function getRoute($path)
    {
        $routes = $this->config->get('routes');

        foreach ($routes as $name => $data) {

            if ($target = $this->getDirectMatch($path, $data)) {
                return $target;
            } else if ($target = $this->getVariableMatch($path, $data)) {
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
    private function getDirectMatch($uri, array $data) :array
    {
        if ($uri === $data['path']) {

            if (strcasecmp($data['method'], $this->request->getMethod()) === 0) {

                return [
                    'class'  => $data['controller'],
                    'action' => $data['action']
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
    private function getVariableMatch($uri, array $data) :array
    {
        if ($data['path'] === '/') {
            return [];
        }

        $var   = [];
        $regex = str_replace(['/', '___'], ['\/', '+'], $data['path']);

        if (preg_match('|^' . $regex . '$|', $uri, $var)) {

            array_splice($var, 0, 1);

            return [
                'class'  => $data['controller'],
                'action' => $data['action'],
                'var'    => $var
            ];

        }

        return [];
    }

    /**
     * Detect a form request
     *
     * @return boolean
     */
    private function handleFormRequest()
    {
        if (strpos($this->request->getUri(), '/formrequest/') !== 0 && $this->request->isPost()) {
            return false;
        }

        $handlerName  = ucfirst(str_replace('/formrequest/', '', $this->request->getUri()));
        $handlerClass = '\\' . $this->config->get('namespacePrefix') . '\\Form\\' . $handlerName . 'Handler';

        if (class_exists($handlerClass)) {

            /** @var AbstractFormHandler $handler */
            $handler = new $handlerClass($this->request);
            return $handler->run();

        }

        return false;
    }

}