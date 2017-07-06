<?php
/**
 * Class LanguageLink | LanguageLink.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Service\Config;
use Faulancer\View\AbstractViewHelper;

/**
 * Class LanguageLink
 */
class LanguageLink extends AbstractViewHelper
{

    protected $languageTextMapping = [
        'de' => 'Deutsch',
        'en' => 'English',
        'hr' => 'Kroatisch'
    ];

    public function __invoke()
    {
        /** @var Config $config */
        $config       = $this->getServiceLocator()->get(Config::class);
        $translations = $config->get('translation');

        $result  = [];
        $pattern = '<a class="lang %s" href="?lang=%s">%s</a>';

        foreach ($translations as $key => $content) {
            $result[] = sprintf($pattern, strtolower($key), $key, $this->languageTextMapping[$key]);
        }

        return implode('', $result);
    }

}