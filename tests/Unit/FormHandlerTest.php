<?php

namespace Faulancer\Test\Unit;

use Faulancer\Fixture\Form\GenericHandler;
use Faulancer\Form\AbstractFormHandler;
use Faulancer\Http\Request;
use Faulancer\Session\SessionManager;
use PHPUnit\Framework\TestCase;

/**
 * Class FormHandlerTest
 * @package Faulancer\Test\Unit
 */
class FormHandlerTest extends TestCase
{

    /**
     * @runInSeparateProcess
     */
    public function testFormHandler()
    {

        $request = new Request();
        $request->setMethod('POST');

        $data = [
            'text/name'    => 'Florian Knapp',
            'email/email'  => 'test@florianknapp.de',
            'text/message' => 'Test'
        ];

        $_POST = $data;

        $formHandler = new GenericHandler($request, SessionManager::instance());
        
        $result = $formHandler->run();

        $this->assertTrue($result);

    }

    /**
     * @runInSeparateProcess
     */
    public function testInvalidFormData()
    {

        $request = new Request();
        $request->setMethod('POST');

        $data = [
            'text/name'    => 'Florian Knapp',
            'email/email'  => 'test@florianknapp.de',
            'text/message' => ''
        ];

        $_POST = $data;

        $formHandler = new GenericHandler($request, SessionManager::instance());

        $result = $formHandler->run();

        $this->assertFalse($result);
        $this->assertTrue(SessionManager::instance()->hasFlashbagErrorsKey('message'));

        $errors = SessionManager::instance()->getFlashbag('errors');

        $this->assertNotEmpty($errors['message']);
        $this->assertArrayHasKey('message', $errors);

    }

}