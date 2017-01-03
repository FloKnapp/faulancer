<?php

namespace tests\unit;

use Faulancer\Controller\Controller;
use Faulancer\Exception\FileNotFoundException;
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
        $this->controller = $this->getMockForAbstractClass(Controller::class);

        if (!defined('VIEWS_ROOT')) {
            define('VIEWS_ROOT', __DIR__ . '/../stubs/views');
        }
    }

    public function testGetView()
    {
        $this->assertInstanceOf(ViewController::class, $this->controller->getView());
    }

    public function testGetOrm()
    {
        $this->assertInstanceOf(ORM::class, $this->controller->getDatabase());
    }

    public function testRender()
    {
        try {
            $this->controller->render();
        } catch (FileNotFoundException $e) {
            $this->assertInstanceOf(FileNotFoundException::class, $e);
            $this->assertSame('Template name missing', $e->getMessage());
        }
        $this->assertStringStartsWith('Test', $this->controller->render('/stubView.phtml'));
    }

}