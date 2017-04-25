<?php
/**
 * Class RestfulAbstractController | AbstractRestfulController.php
 * @package Faulancer\AbstractController
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Controller;

/**
 * Class RestfulAbstractController
 */
abstract class AbstractRestfulController extends AbstractController
{

    /**
     * GET
     */
    public function get() {}

    /**
     * POST
     */
    public function create() {}

    /**
     * UPDATE
     */
    public function update() {}

    /**
     * DELETE
     */
    public function delete() {}

}