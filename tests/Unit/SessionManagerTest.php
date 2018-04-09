<?php

namespace Faulancer\Test\Unit;

use Faulancer\Service\SessionManagerService;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Session\SessionManager;
use PHPUnit\Framework\TestCase;

/**
 * File SessionManagerTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class SessionManagerTest extends TestCase
{

    /** @var SessionManager */
    protected $sessionManager;

    /**
     * Define session manager instance
     */
    public function setUp()
    {
        $this->sessionManager = ServiceLocator::instance()->get(SessionManagerService::class);
    }

    public function tearDown()
    {
        $this->sessionManager->destroySession();
    }

    public function testGetSetKey()
    {
        $this->sessionManager->set('testKey', 'testValue');

        $this->assertTrue(is_string($this->sessionManager->get('testKey')));
        $this->assertSame('testValue', $this->sessionManager->get('testKey'));
    }

    public function testDeleteKey()
    {
        $this->sessionManager->set('testKey', 'testValue');

        $this->sessionManager->delete('testKey');
        $this->assertEmpty($this->sessionManager->get('testKey'));
    }

    public function testGetSetFlashbag()
    {
        $this->sessionManager->setFlashMessage('testKey', 'testValue');

        $value = $this->sessionManager->getFlashMessage('testKey');
        $this->assertTrue(is_string($value));
        $this->assertEmpty($this->sessionManager->getFlashMessage('testKey'));
        $this->assertNull($this->sessionManager->getFlashMessage('testKey'));

        $data = [
            'flashbagKey1' => 'flashbagValue1',
            'flashbagKey2' => 'flashbagValue2',
            'flashbagKey3' => 'flashbagValue3',
            'flashbagKey4' => 'flashbagValue4',
            'flashbagKey5' => 'flashbagValue5',
        ];

        $this->sessionManager->setFlashMessage($data);

        foreach ($data as $key => $value) {

            $this->assertTrue($this->sessionManager->hasFlashMessage($key));
            $this->assertSame($value, $this->sessionManager->getFlashMessage($key));
            $this->assertNull($this->sessionManager->getFlashMessage($key));

        }
    }

    public function testHasSession()
    {
        $this->assertFalse($this->sessionManager->hasSession());
    }

    public function testHasKeyValid()
    {
        $this->sessionManager->set('test', 'test2');
        $this->assertTrue($this->sessionManager->has('test'));
    }

    public function testHasKeyInvalid()
    {
        $this->assertFalse($this->sessionManager->has('non_existent'));
    }

    public function testDeleteNonExistentKey()
    {
        $this->assertFalse($this->sessionManager->delete('nonExistent'));
    }

    public function testHasFlashbagKey()
    {
        $this->sessionManager->setFlashMessage('testKey', 'testValue');
        $this->assertTrue($this->sessionManager->hasFlashMessage('testKey'));
        $this->assertTrue(is_string($this->sessionManager->getFlashMessage('testKey')));
    }

    public function testDestroyAndCheckForSession()
    {
        $this->sessionManager->destroySession();
        $this->assertFalse($this->sessionManager->hasSession());
    }

}