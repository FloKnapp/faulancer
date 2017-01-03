<?php

namespace Faulancer\Test\Unit;

use Faulancer\View\AbstractViewHelper;
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

}