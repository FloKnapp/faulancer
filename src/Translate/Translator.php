<?php

namespace Faulancer\Translate;

use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Session\SessionManager;

/**
 * File Translator.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 *
 */
class Translator
{

    /** @var array */
    protected $config = [];

    /** @var string */
    protected $language;

    /**
     * Translator constructor.
     * @param string $language
     */
    public function __construct($language = 'ger_DE')
    {
        /** @var Config $config */
        $config    = ServiceLocator::instance()->get(Config::class);
        $transFile = $config->get('translationFile');

        if (file_exists($transFile)) {
            $this->config = require_once $transFile;
        }

        $this->language = $language;
        $sessionManager = SessionManager::instance();

        if ($sessionManager->get('language')) {
            $this->language = $sessionManager->get('language');
        }
    }

    /**
     * @param string $key
     * @param array  $value
     * @return string
     */
    public function translate($key, $value = [])
    {
        if (empty($this->config)) {
            return $key;
        }

        if (isset($this->config[$this->language][$key])) {

            if (!empty($value)) {
                return vsprintf($this->config[$this->language][$key], $value);
            }

            return $this->config[$this->language][$key];

        }

        return $key;
    }

}