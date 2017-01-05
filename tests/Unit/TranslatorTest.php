<?php

namespace Faulancer\Test\Unit;

use Faulancer\Session\SessionManager;
use Faulancer\Translate\Translator;
use PHPUnit\Framework\TestCase;

/**
 * Class TranslatorTest
 * @package Faulancer\Test\Unit
 */
class TranslatorTest extends TestCase
{

    /**
     * @runInSeparateProcess
     */
    public function testTranslation()
    {
        $translator = new Translator();

        $this->assertSame('Test-Item1', $translator->translate('test_item_1'));
        $this->assertSame('Test-Item2', $translator->translate('test_item_2'));
        $this->assertSame('Test-Item3', $translator->translate('test_item_3'));
        $this->assertSame('Test-Item4', $translator->translate('test_item_4'));
        $this->assertSame('Test-Item5', $translator->translate('test_item_5'));

    }

    /**
     * @runInSeparateProcess
     */
    public function testTranslationOtherLanguage()
    {
        $sessionManager = SessionManager::instance();

        $sessionManager->set('language', 'en_EN');

        $translator = new Translator();

        $this->assertSame('Test-Item1_en', $translator->translate('test_item_1_en'));
        $this->assertSame('Test-Item2_en', $translator->translate('test_item_2_en'));
        $this->assertSame('Test-Item3_en', $translator->translate('test_item_3_en'));
        $this->assertSame('Test-Item4_en', $translator->translate('test_item_4_en'));
        $this->assertSame('Test-Item5_en', $translator->translate('test_item_5_en'));

    }

    /**
     * @runInSeparateProcess
     */
    public function testTranslationVariableContent()
    {
        $translator = new Translator();

        $this->assertSame('Test-Item6', $translator->translate('test_item_6', ['Item6']));
        $this->assertSame('Test-Item7', $translator->translate('test_item_7', ['Item', '7']));
    }

    /**
     * @runInSeparateProcess
     */
    public function testTranslationDontExist()
    {
        $translator = new Translator();

        $this->assertSame('test_item_55', $translator->translate('test_item_55'));
    }
    
}