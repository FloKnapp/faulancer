<?php

namespace Faulancer\View\Helper;

use Faulancer\Service\Config;
use Faulancer\Session\SessionManager;
use Faulancer\View\AbstractViewHelper;

/**
 * Class LanguageLink
 *
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class Language extends AbstractViewHelper
{

    /** @var array */
    protected $languageTextMapping = [
        'de' => 'Deutsch',
        'en' => 'English',
        'hr' => 'Hrvatski',
        'fr' => 'France'
    ];

    /**
     * @return Language $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param bool $codeOnly
     * @return string
     */
    public function getCurrent($codeOnly = true)
    {
        /** @var SessionManager $sessionManager */
        $sessionManager = $this->getServiceLocator()->get(SessionManager::class);
        $code           = $sessionManager->get('language') ?? 'de';

        if ($codeOnly) {
            return $code;
        }

        return $this->languageTextMapping[$code];
    }

    /**
     * @return string
     */
    public function getLinks()
    {
        /** @var Config $config */
        $config       = $this->getServiceLocator()->get(Config::class);
        $translations = $config->get('translation');

        $result  = [];
        $pattern = '<a rel="alternate" hreflang="%s" class="lang %s" href="?lang=%s">%s</a>';

        foreach ($translations as $key => $content) {
            $result[] = sprintf($pattern, strtolower($key), strtolower($key), $key, $this->languageTextMapping[$key]);
        }

        return implode('', $result);
    }

}