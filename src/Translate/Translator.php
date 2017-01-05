<?php

namespace Faulancer\Translate;

use Faulancer\Session\SessionManager;

/**
 * File Translator.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 *
 */
class Translator
{

    protected $config;

    protected $language;

    /**
     * Translator constructor.
     * @param string $language
     *
     * @codeCoverageIgnore
     */
    public function __construct($language = 'ger_DE')
    {

        $this->language = $language;

        $sessionStorage = SessionManager::instance();

        if ($sessionStorage->get('lang')) {
            $this->language = $sessionStorage->get('lang');
        }

    }

    /**
     * @param $key
     * @param null $value
     * @return mixed
     *
     * @codeCoverageIgnore
     */
    public function translate($key, $value = null)
    {
        if (isset($this->config[$this->language][$key])) {

            if ($value !== null) {
                return sprintf($this->config[$this->language][$key], $value);
            }

            return $this->config[$this->language][$key];
        }
        return $key;
    }

}