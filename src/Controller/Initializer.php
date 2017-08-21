<?php
/**
 * Class Initializer | Initializer.php
 *
 * @package Faulancer\Controller
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Controller;

use Faulancer\Exception\MethodNotFoundException;
use Faulancer\Http\Response;
use Faulancer\Service\RequestService;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class Initializer
 */
class Initializer
{

    /** @var string */
    protected $class;

    /** @var string */
    protected $action;

    /** @var array */
    protected $params;

    /**
     * @param string $class
     */
    public function setClass(string $class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return Response
     * @throws MethodNotFoundException
     */
    public function execute()
    {
        $request    = $this->getServiceLocator()->get(RequestService::class);
        $className  = $this->getClass();
        $actionName = $this->getAction();
        $class      = new $className($request);

        if (!method_exists($class, $actionName)) {
            throw new MethodNotFoundException('Class "' . get_class($class) . '" doesn\'t have the method ' . $actionName);
        }

        $payload = array_map('strip_tags', $this->getParams());

        return call_user_func_array([$class, $actionName], $payload);
    }

    /**
     * @return ServiceLocator
     */
    private function getServiceLocator()
    {
        return ServiceLocator::instance();
    }

}