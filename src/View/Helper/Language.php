<?php
/**
 * Class LanguageLink | LanguageLink.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Exception\ConfigInvalidException;
use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Service\Config;
use Faulancer\Service\SessionManagerService;
use Faulancer\Session\SessionManager;
use Faulancer\View\AbstractViewHelper;

/**
 * Class LanguageLink
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
     *
     * @throws ServiceNotFoundException
     */
    public function getCurrent($codeOnly = true)
    {
        /** @var SessionManager $sessionManager */
        $sessionManager = $this->getServiceLocator()->get(SessionManagerService::class);
        $code           = $sessionManager->get('language') ?? 'de';

        if ($codeOnly) {
            return $code;
        }

        return $this->languageTextMapping[$code];
    }

    /**
     * @return string
     *
     * @throws ServiceNotFoundException
     * @throws ConfigInvalidException
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