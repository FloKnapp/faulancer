<?php
/**
 * Class Translator | Translator.php
 *
 * @package Faulancer\Translate
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Translate;

use Faulancer\Exception\FileNotFoundException;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Session\SessionManager;

/**
 * Class Translator
 */
class Translator
{

    /**
     * Holds the translation data
     * @var array
     */
    protected $translation = [];

    /**
     * Holds the current language key
     * @var string
     */
    protected $language;

    /**
     * Translator constructor.
     *
     * @param string $language
     * @param SessionManager $sessionManager
     * @throws FileNotFoundException
     */
    public function __construct(string $language = 'ger_DE', SessionManager $sessionManager = null)
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);

        $this->language    = $language;
        $this->translation = $config->get('translation');
        $sessionManager    = empty($sessionManager) ? SessionManager::instance() : $sessionManager;

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