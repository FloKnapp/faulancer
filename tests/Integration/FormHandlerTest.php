<?php

namespace Faulancer\Test\Integration;

use Faulancer\Fixture\Form\GenericHandler;
use Faulancer\Http\Request;
use Faulancer\Service\SessionManagerService;
use Faulancer\ServiceLocator\ServiceLocator;
use PHPUnit\Framework\TestCase;

/**
 * Class FormHandlerTest
 * @package Faulancer\Test\Unit
 */
class FormHandlerTest extends TestCase
{

    /** @var SessionManagerService */
    protected $sessionManager;

    public function setUp()
    {
        $this->sessionManager = ServiceLocator::instance()->get(SessionManagerService::class);
    }

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
        $formHandler = new GenericHandler($request, $this->sessionManager);
        $result      = $formHandler->run();

        $this->assertEmpty($this->sessionManager->getFlashbagError('message'));
        $this->assertSame('testSuccess', $result);
    }

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
        $formHandler = new GenericHandler($request, $this->sessionManager);
        $result      = $formHandler->run();

        $this->assertSame('testError', $result);
        $this->assertTrue($this->sessionManager->hasFlashbagErrorsKey('text/message'));

        $errors = $this->sessionManager->getFlashbag('errors');

        $this->assertNotEmpty($errors['text/message']);
        $this->assertArrayHasKey('text/message', $errors);
    }

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
        $formHandler = new GenericHandler($request, $this->sessionManager);
        $result      = $formHandler->run();

        $this->assertEmpty($this->sessionManager->getFlashbagError('text/message'));
        $this->assertSame('testSuccess', $result);
    }

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
        $formHandler = new GenericHandler($request, $this->sessionManager);
        $result      = $formHandler->run();

        $this->assertEmpty($this->sessionManager->getFlashbagError('message'));
        $this->assertSame('testSuccess', $result);
    }
    
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
        $formHandler = new GenericHandler($request, $this->sessionManager);
        $result      = $formHandler->run();

        $this->assertEmpty($this->sessionManager->getFlashbagError('message'));
        $this->assertSame('Florian Knapp', $formHandler->getFormData('rofl/name'));
        $this->assertSame('test@florianknapp.de', $formHandler->getFormData('lol/email'));
        $this->assertSame('testSuccess', $result);
    }

    public function testEmailValidator()
    {

    }

}