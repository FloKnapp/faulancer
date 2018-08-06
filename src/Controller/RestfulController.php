<?php

namespace Faulancer\Controller;

use Faulancer\Http\JsonResponse;

/**
 * Class RestfulAbstractController
 *
 * @category REST
 * @package  Faulancer\AbstractController
 * @author   Florian Knapp <office@florianknapp.de>
 * @license  MIT License
 * @link     Currently no information
 * @codeCoverageIgnore
 */
class RestfulController extends Controller
{

    /**
     * GET
     *
     * @return void
     */
    public function get()
    {
    }

    /**
     * POST
     *
     * @return void
     */
    public function create()
    {
    }

    /**
     * UPDATE
     *
     * @return void
     */
    public function update()
    {
    }

    /**
     * DELETE
     *
     * @return void
     */
    public function delete()
    {
    }

    /**
     * Return success response
     *
     * @param array $data The response data
     *
     * @return JsonResponse
     */
    protected function success($data = [])
    {
        $response = new JsonResponse();

        $response->setContent(
            [
                'success' => true,
                'data'    => $data,
                'errors'  => []
            ]
        );

        $response->setCode(200);

        return $response;
    }

    /**
     * Return erroneous response
     *
     * @param array $errors The response errors
     *
     * @return JsonResponse
     */
    protected function error($errors = [])
    {
        $response = new JsonResponse();

        $response->setContent(
            [
                'success' => false,
                'data'    => [],
                'errors'  => $errors
            ]
        );
        $response->setCode(400);

        return $response;
    }

}