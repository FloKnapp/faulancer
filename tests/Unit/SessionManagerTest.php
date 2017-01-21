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

        $data = [
            'flashbagKey1' => 'flashbagValue1',
            'flashbagKey2' => 'flashbagValue2',
            'flashbagKey3' => 'flashbagValue3',
            'flashbagKey4' => 'flashbagValue4',
            'flashbagKey5' => 'flashbagValue5',
        ];

        $this->sessionManager->setFlashbag($data);

        foreach ($data as $key => $value) {

            $this->assertTrue($this->sessionManager->hasFlashbagKey($key));
            $this->assertSame($value, $this->sessionManager->getFlashbag($key));
            $this->assertNull($this->sessionManager->getFlashbag($key));

        }

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

    /**
     * @runInSeparateProcess
     */
    public function testHasSession()
    {
        $this->assertTrue($this->sessionManager->hasSession());
    }

    /**
     * @runInSeparateProcess
     */
    public function testDeleteNonExistentKey()
    {
        $this->assertFalse($this->sessionManager->delete('nonExistent'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testHasFlashbagKey()
    {
        $this->sessionManager->setFlashbag('testKey', 'testValue');
        $this->assertTrue($this->sessionManager->hasFlashbagKey('testKey'));
        $this->assertTrue(is_string($this->sessionManager->getFlashbag('testKey')));
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetGetFlashbagFormData()
    {
        $data = [
            'field1' => 'value1',
            'field2' => 'value2',
            'field3' => 'value3',
            'field4' => 'value4',
            'field5' => 'value5',
        ];

        $this->sessionManager->setFlashbagFormData($data);

        foreach ($data as $key => $value) {

            $val = $this->sessionManager->getFlashbagFormData($key);
            $this->assertNotEmpty($val);
            $this->assertSame($data[$key], $val);

        }

    }

    /**
     * @runInSeparateProcess
     */
    public function testGetNonExistentFormDataKey()
    {
        $result = $this->sessionManager->getFlashbagFormData('nonExistent');

        $this->assertEmpty($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetGetFlashbagErrors()
    {

        $data = [
            'error1' => 'errorValue1',
            'error2' => 'errorValue2',
            'error3' => 'errorValue3',
            'error4' => 'errorValue4',
            'error5' => 'errorValue5',
        ];

        $this->sessionManager->setFlashbag('errors', $data);

        foreach ($data as $key => $value) {

            $this->assertTrue($this->sessionManager->hasFlashbagErrorsKey($key));
            $this->assertSame($value, $this->sessionManager->getFlashbagError($key));
            $this->assertEmpty($this->sessionManager->getFlashbagError($key));

        }

    }

}