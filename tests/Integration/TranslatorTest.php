<?php

namespace Faulancer\Test\Integration;

use Faulancer\Exception\ConfigInvalidException;
use Faulancer\Exception\FileNotFoundException;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;
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

    /**
     * @runInSeparateProcess
     */
    public function testTranslationDontExists()
    {
        $this->expectException(ConfigInvalidException::class);

        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);
        $trans  = $config->get('translation');
        $config->delete('translation');

        $translator = new Translator();
        $this->assertEmpty($config->get('translation'));
        $this->assertSame('test_item_1', $translator->translate('test_item_1'));

        $config->set('translation', $trans, true);
    }

    /**
     * @runInSeparateProcess
     */
    public function testTranslationIsEmpty()
    {
        /** @var Config $config */
        $config     = ServiceLocator::instance()->get(Config::class);
        $trans      = $config->get('translation');
        $emptyTrans = require $config->get('projectRoot') . '/config/translation_empty.conf.php';

        $config->set('translation', $emptyTrans, true);

        $translator = new Translator();
        $this->assertSame('test_item_1', $translator->translate('test_item_1'));

        $config->set('translation', $trans, true);
    }
    
}