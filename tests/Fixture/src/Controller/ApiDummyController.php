<?php
/**
 * Class ApiDummyController | ApiDummyController.php
 * @package Faulancer\Fixture\AbstractController
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Fixture\Controller;

use Faulancer\Controller\AbstractRestfulController;
use Faulancer\Exception\InvalidArgumentException;
use Faulancer\Http\JsonResponse;
use Faulancer\Service\JsonResponseService;
use Faulancer\ServiceLocator\ServiceInterface;

/**
 * Class ApiDummyController
 */
class ApiDummyController extends AbstractRestfulController
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
     * @param mixed $id
     * @return JsonResponse
     *
     * @throws InvalidArgumentException
     */
    public function get($id = null)
    {
        if (!empty($id)) {
            return $this->jsonResponse->setContent(['param' => $id]);
        }

        if (!empty($this->request->getParam('test'))) {
            return $this->jsonResponse->setContent(['param' => $this->request->getParam('test')]);
        }

        return $this->jsonResponse->setContent(['param' => false]);
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
     * @param mixed $id
     * @return JsonResponse
     */
    public function delete($id = null)
    {
        return $this->jsonResponse->setContent($id);
    }

}