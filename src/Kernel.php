<?php
/**
 * Class Kernel | File Kernel.php
 *
 * @package Faulancer
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer;

use Faulancer\Controller\Dispatcher;
use Faulancer\Controller\ErrorController;
use Faulancer\Exception\DispatchFailureException;
use Faulancer\Http\Request;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;

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
     * @return mixed
     * @throws Exception\ConfigInvalidException
     * @codeCoverageIgnore
     */
    public function run()
    {
        $dispatcher = new Dispatcher($this->request, $this->config);

        try {
            return $dispatcher->run()->getContent();
        } catch (DispatchFailureException $e) {
            return ErrorController::notFoundAction()->getContent();
        }
    }

}