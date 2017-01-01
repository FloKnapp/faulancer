<?php

namespace Faulancer\Translate;

use Faulancer\Session\SessionManager;

/**
 * File Translator.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class Translator
{

    protected $config;

    protected $language;

    public function __construct($language = 'ger_DE')
    {

        $this->language = $language;

        $sessionStorage = SessionManager::instance();

        if ($sessionStorage->get('lang')) {
            $this->language = $sessionStorage->get('lang');
        }

    }

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