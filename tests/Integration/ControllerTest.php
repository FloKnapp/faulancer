<?php

namespace Faulancer\Test\Integration;

use Faulancer\Controller\Controller;
use Faulancer\Exception\FileNotFoundException;
use Faulancer\ORM\EntityManager;
use Faulancer\ORM\ORM;
use Faulancer\View\ViewController;
use PHPUnit\Framework\TestCase;

/**
 * File ControllerTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class ControllerTest extends TestCase
{

    /** @var Controller */
    protected $controller;

    public function setUp()
    {
        $this->controller = $this->getMockForAbstractClass(Controller::class, [], '', false);
    }

    public function testGetView()
    {
        $this->assertInstanceOf(ViewController::class, $this->controller->getView());
    }

    public function testGetOrm()
    {
        $this->assertInstanceOf(\Faulancer\Service\ORM::class, $this->controller->getDb());
    }

    public function testRender()
    {
        try {
            $this->controller->render();
        } catch (FileNotFoundException $e) {
            $this->assertInstanceOf(FileNotFoundException::class, $e);
        }
        $this->assertStringStartsWith('Test', $this->controller->render('/stubView.phtml'));
    }

}