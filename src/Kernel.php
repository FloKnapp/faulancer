<?php

namespace Faulancer;

use Faulancer\Controller\Dispatcher;
use Faulancer\Controller\ErrorController;
use Faulancer\Event\Observer;
use Faulancer\Event\Type\OnKernelError;
use Faulancer\Event\Type\OnKernelErrorException;
use Faulancer\Event\Type\OnKernelException;
use Faulancer\Event\Type\OnKernelStart;
use Faulancer\Exception\Exception;
use Faulancer\Http\Request;
use Faulancer\Service\Config;

/**
 * Class Kernel
 */
class Kernel
{

    /**
     * The current request object
     * @var Request
     */
    protected $request;

    /**
     * The configuration object
     * @var Config
     */
    protected $config;

    /**
     * Kernel constructor.
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
     * Initialize the application
     *
     * @return mixed
     * @codeCoverageIgnore
     */
    public function run()
    {
        Observer::instance()->trigger(new OnKernelStart($this));

        $dispatcher = new Dispatcher($this->request, $this->config);

        try {

            $this->registerErrorHandler();
            return $dispatcher->dispatch()->getContent();

        } catch (Exception $e) {
            Observer::instance()->trigger(new OnKernelException($this));
            return $this->showErrorPage($this->request, $e);
        } catch (\ErrorException $e) {
            Observer::instance()->trigger(new OnKernelErrorException($this));
            return $this->showErrorPage($this->request, $e);
        } catch (\Error $e) {
            Observer::instance()->trigger(new OnKernelError($this));
            return $this->showErrorPage($this->request, $e);
        }

    }

    /**
     * @param $request
     * @param $e
     * @return Http\Response
     * @codeCoverageIgnore
     */
    private function showErrorPage($request, $e)
    {
        $errorController = new ErrorController($request, $e);
        return $errorController->displayError();
    }

    /**
     * Register error handler
     */
    protected function registerErrorHandler()
    {
        set_error_handler([$this, 'errorHandler'], E_ALL);
    }

    /**
     * Custom error handler
     *
     * @param $errno
     * @param $errmsg
     * @param $errfile
     * @param $errline
     * @throws \ErrorException
     * @codeCoverageIgnore
     */
    public function errorHandler($errno, $errmsg, $errfile, $errline)
    {
        throw new \ErrorException($errmsg, $errno, 1, $errfile, $errline);
    }

}



