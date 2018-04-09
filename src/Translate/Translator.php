<?php

namespace Faulancer\Translate;

use Faulancer\Exception\ConfigInvalidException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Service\Config;
use Faulancer\Service\SessionManagerService;
use Faulancer\ServiceLocator\ServiceLocator;

/**
 * Class Translator | Translator.php
 *
 * @package Faulancer\Translate
 * @author Florian Knapp <office@florianknapp.de>
 */
class Translator
{

    /**
     * Holds the translation data
     * @var mixed
     */
    protected $translation;

    /**
     * Holds the current language key
     * @var string
     */
    protected $language;

    /**
     * Translator constructor.
     *
     * @param string $language
     *
     * @throws ServiceNotFoundException
     * @throws ConfigInvalidException
     */
    public function __construct(string $language = 'de')
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);

        /** @var SessionManagerService $sessionManager */
        $sessionManager = ServiceLocator::instance()->get(SessionManagerService::class);

        $this->language    = $language;
        $this->translation = $config->get('translation');

        if ($sessionManager->get('language')) {
            $this->language = $sessionManager->get('language');
        }
    }

    /**
     * Translate given key
     *
     * @param string $key
     * @param array  $value
     * @return string
     */
    public function translate(string $key, $value = []) :string
    {
        if (empty($this->translation)) {
            return $key;
        }

        if (empty($this->translation[$this->language][$key])) {
            return $key;
        }

        if (!empty($value)) {
            return vsprintf($this->translation[$this->language][$key], $value);
        }

        return $this->translation[$this->language][$key];
    }

}