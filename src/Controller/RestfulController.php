<?php
/**
 * Class RestfulController | RestfulController.php
 * @package Faulancer\Controller
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Controller;

/**
 * Class RestfulController
 */
abstract class RestfulController extends Controller
{

    public function get($id = null) {}

    public function create($data) {}

    public function update($data) {}

    public function delete($id) {}

}