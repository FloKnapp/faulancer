<?php

namespace Faulancer\Fixture\Controller;

use Faulancer\Controller\Controller;
use Faulancer\Http\Request;
use Faulancer\Http\Response;

/**
 * Class DummyController
 * @author Florian Knapp <office@florianknapp.de>
 */
class DummyController extends Controller
{

    /** @var Response  */
    private $response;

    /**
     * DummyController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->response = $this->getServiceLocator()->get(Response::class);
    }

    /**
     * @return Response
     */
    public function stubHomeAction()
    {
        return $this->response->setContent(0);
    }

    /**
     * @return Response
     */
    public function stubStaticAction()
    {
        return $this->response->setContent(1);
    }

    /**
     * @return Response
     */
    public function stubDynamicAction()
    {
        return $this->response->setContent(2);
    }

    /**
     * @return integer
     */
    public function stubNoResponseAction()
    {
        return 3;
    }

    public function stubAuthAction()
    {
        return $this->response->setContent('test');
    }

    public function stubAuthAdminAction()
    {
        return $this->response->setContent('admin');
    }

    public function notPermittedAction()
    {
        return false;
    }

}