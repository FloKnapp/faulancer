<?php

namespace Faulancer\Test\Unit;

use Faulancer\Exception\ClassNotFoundException;
use Faulancer\Exception\FileNotFoundException;
use Faulancer\Exception\ViewHelperIncompatibleException;
use Faulancer\View\GenericViewHelper;
use Faulancer\View\ViewController;
use PHPUnit\Framework\TestCase;

/**
 * File ViewTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class ViewTest extends TestCase
{

    public function testViewSetTemplate()
    {
        $view = new ViewController();
        $view->setTemplate('/stubView.phtml');
        $this->assertTrue(is_string($view->getTemplate()));
    }

    public function testViewMissingTemplate()
    {
        $this->expectException(FileNotFoundException::class);
        $view = new ViewController();
        $view->setTemplate('NonExistend.phtml');
    }

    public function testViewRender()
    {
        $view = new ViewController();
        $view->setTemplate('/stubView.phtml');
        $this->assertSame('Test', $view->render());
    }

    public function testViewSetVariable()
    {
        $view = new ViewController();
        $view->setVariable('key', 'value');
        $this->assertTrue(is_string($view->getVariable('key')));
        $this->assertSame('value', $view->getVariable('key'));
    }

    public function testViewSetVariables()
    {
        $view = new ViewController();

        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
            'key4' => 'value4',
            'key5' => 'value5'
        ];

        $view->setVariables($data);

        foreach ($view->getVariables() as $key => $value) {

            $this->assertTrue(is_string($value));
            $this->assertSame($value, $view->getVariable($key));

        }

    }

    public function testAddJsAssets()
    {
        $view = new ViewController();
        $inst = $view->addScript('script1.js');
        $this->assertInstanceOf(ViewController::class, $inst);
    }

    public function testAddCssAssets()
    {
        $view = new ViewController();
        $inst = $view->addStylesheet('stylesheet1.css');
        $this->assertInstanceOf(ViewController::class, $inst);
    }

    /**
     * @outputBuffering enabled
     */
    public function testViewExtendedTemplate()
    {
        $view = new ViewController();
        $view->setTemplate('/stubBody.phtml');

        $content = $view->render();

        $this->assertInstanceOf(ViewController::class, $view);
        $this->assertSame('LayoutTestContent', $content);
    }

    /**
     * @outputBuffering enabled
     */
    public function testGenericViewHelperBlock()
    {
        $view       = new ViewController();
        $viewHelper = new GenericViewHelper($view);

        $viewHelper->block('content');
        echo 'Test';
        ob_end_flush();
        $this->expectOutputString('Test');
    }

    public function testCustomViewHelper()
    {
        $view = new ViewController();
        $viewHelper = $view->StubViewHelper();

        $this->assertTrue(is_string($viewHelper));
        $this->assertSame('Test', $viewHelper);
    }

    public function testGetMissingViewHelper()
    {
        $this->expectException(ClassNotFoundException::class);
        $view = new ViewController();
        $view->NonExistingViewHelper();
    }

    public function testViewHelperWithoutConstructor()
    {
        $this->expectException(ViewHelperIncompatibleException::class);
        $view = new ViewController();
        $view->StubViewHelperWithoutConstructor();
    }

    public function testViewHelperWithConstructor()
    {
        $view = new ViewController();
        $viewHelper = $view->StubViewHelperWithConstructor('value');
        $this->assertNotEmpty($viewHelper->getValue());
        $this->assertSame('value', $viewHelper->getValue());
    }

    public function testGetMissingVariable()
    {
        $view = new ViewController();
        $var = $view->getVariable('nonExistend');
        $this->assertFalse($var);
    }

    public function testHasVariable()
    {
        $view = new ViewController();
        $view->setVariable('testKey', 'testValue');
        $this->assertTrue($view->hasVariable('testKey'));
    }

    public function testHasNotVariable()
    {
        $view = new ViewController();
        $this->assertFalse($view->hasVariable('testKey'));
    }

}