<?php

namespace Faulancer\Test\Integration;

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

        $_POST       = $data;
        $formHandler = new GenericHandler($request, SessionManager::instance());
        $result      = $formHandler->run();

        $this->assertEmpty(SessionManager::instance()->getFlashbagError('message'));
        $this->assertSame('testSuccess', $result);
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

        $_POST       = $data;
        $formHandler = new GenericHandler($request, SessionManager::instance());
        $result      = $formHandler->run();

        $this->assertSame('testError', $result);
        $this->assertTrue(SessionManager::instance()->hasFlashbagErrorsKey('text/message'));

        $errors = SessionManager::instance()->getFlashbag('errors');

        $this->assertNotEmpty($errors['text/message']);
        $this->assertArrayHasKey('text/message', $errors);
    }

    /**
     * @runInSeparateProcess
     */
    public function testMissingValidators()
    {
        $request = new Request();
        $request->setMethod('POST');

        $data = [
            'text/name' => 'Florian Knapp',
            'email'     => 'test@florianknapp.de',
            'message'   => ''
        ];

        $_POST       = $data;
        $formHandler = new GenericHandler($request, SessionManager::instance());
        $result      = $formHandler->run();

        $this->assertEmpty(SessionManager::instance()->getFlashbagError('text/message'));
        $this->assertSame('testSuccess', $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testMissingValidator()
    {
        $request = new Request();
        $request->setMethod('POST');

        $data = [
            'rofl/name'    => 'Florian Knapp',
            'lol/email'    => 'test@florianknapp.de',
            'kewl/message' => ''
        ];

        $_POST       = $data;
        $formHandler = new GenericHandler($request, SessionManager::instance());
        $result      = $formHandler->run();

        $this->assertEmpty(SessionManager::instance()->getFlashbagError('message'));
        $this->assertSame('testSuccess', $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetFormData()
    {
        $request = new Request();
        $request->setMethod('POST');

        $data = [
            'rofl/name'    => 'Florian Knapp',
            'lol/email'    => 'test@florianknapp.de',
            'kewl/message' => ''
        ];

        $_POST       = $data;
        $formHandler = new GenericHandler($request, SessionManager::instance());
        $result      = $formHandler->run();

        $this->assertEmpty(SessionManager::instance()->getFlashbagError('message'));
        $this->assertSame('Florian Knapp', $formHandler->getFormData('rofl/name'));
        $this->assertSame('test@florianknapp.de', $formHandler->getFormData('lol/email'));
        $this->assertSame('testSuccess', $result);
    }

    public function testEmailValidator()
    {

    }

}