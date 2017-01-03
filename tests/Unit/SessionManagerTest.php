<?php

namespace Faulancer\Test\Unit;

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
     *
     */
    public function setUp()
    {
        $this->sessionManager = SessionManager::instance();
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstance()
    {
        $this->assertSame(SessionManager::instance(), $this->sessionManager);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetSetKey()
    {
        $this->sessionManager->set('testKey', 'testValue');

        $this->assertTrue(is_string($this->sessionManager->get('testKey')));
        $this->assertSame('testValue', $this->sessionManager->get('testKey'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testDeleteKey()
    {
        $this->sessionManager->set('testKey', 'testValue');

        $this->sessionManager->delete('testKey');
        $this->assertEmpty($this->sessionManager->get('testKey'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetSetFlashbag()
    {
        $this->sessionManager->setFlashbag('testKey', 'testValue');

        $value = $this->sessionManager->getFlashbag('testKey');
        $this->assertTrue(is_string($value));
        $this->assertEmpty($this->sessionManager->getFlashbag('testKey'));
        $this->assertNull($this->sessionManager->getFlashbag('testKey'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetSetFlashbagFormData()
    {
        $this->sessionManager->setFlashbagFormData([
            'field1' => 'value1',
            'field2' => 'value2',
            'field3' => 'value3',
            'field4' => 'value4',
            'field5' => 'value5',
            'field6' => 'value6'
        ]);

        $formData = $this->sessionManager->getFlashbag('formData');

        foreach ($formData as $key => $value) {
            $this->assertArrayHasKey($key, $formData);
            $this->assertTrue(is_string($value));
        }

        $this->assertTrue(is_array($formData));
        $this->assertEmpty($this->sessionManager->getFlashbag('formData'));
        $this->assertNull($this->sessionManager->getFlashbag('formData'));
    }

}