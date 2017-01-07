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
     * Enable/disable route cache
     * @var boolean
     */
    protected $routeCacheEnabled;

    /**
     * Kernel constructor.
     *
     * @param Request $request
     * @param array   $config
     * @param boolean $routeCacheEnabled
     */
    public function __construct(Request $request, array $config, $routeCacheEnabled = true)
    {
        $this->request           = $request;
        $this->config            = $config;
        $this->routeCacheEnabled = $routeCacheEnabled;
    }

    /**
     * Initialize the application
     * @return mixed
     * @throws Exception\ConfigInvalidException
     */
    public function run()
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);
        $config->set($this->config);
        $config->set('routeCacheFile', $config->get('projectRoot') . '/cache/routes.json', true);

        $dispatcher = new Dispatcher($this->request, $config, $this->routeCacheEnabled);

        try {
            return $dispatcher->run()->getContent();
        } catch (DispatchFailureException $e) {
            return ErrorController::notFoundAction()->getContent();
        }
    }

}