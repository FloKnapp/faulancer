<?php
/**
 * Class ApiDummyController | ApiDummyController.php
 * @package Faulancer\Fixture\Controller
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Fixture\Controller;

use Faulancer\Controller\RestfulController;
use Faulancer\Http\JsonResponse;
use Faulancer\Service\JsonResponseService;
use Faulancer\ServiceLocator\ServiceInterface;

/**
 * Class ApiDummyController
 */
class ApiDummyController extends RestfulController
{

    /** @var ServiceInterface|JsonResponse */
    private $jsonResponse;

    /**
     * ApiDummyController constructor.
     * @param \Faulancer\Http\Request $request
     */
    public function __construct($request)
    {
        parent::__construct($request);
        $this->jsonResponse = $this->getServiceLocator()->get(JsonResponseService::class);
    }

    /**
     * @param bool $param
     * @return JsonResponse
     */
    public function get($param = null)
    {
        if ($param) {
            return $this->jsonResponse->setContent(['param' => $param]);
        }

        return $this->jsonResponse->setContent((['test' => true]));
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function create($data = [])
    {
        return $this->jsonResponse->setContent($data);
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function update($data = [])
    {
        return $this->jsonResponse->setContent($data);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        return $this->jsonResponse->setContent([$id]);
    }

}