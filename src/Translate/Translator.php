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
    protected $config = [];

    /**
     * Holds the current language key
     * @var string
     */
    protected $language;

    /**
     * Translator constructor.
     *
     * @param string $language
     * @throws FileNotFoundException
     */
    public function __construct(string $language = 'ger_DE')
    {
        /** @var Config $config */
        $config    = ServiceLocator::instance()->get(Config::class);
        $transFile = $config->get('translationFile');

        if (!file_exists($transFile)) {
            throw new FileNotFoundException('Translation file couldn\'t be found');
        }

        $this->config   = require $transFile;
        $this->language = $language;
        $sessionManager = SessionManager::instance();

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
        if (empty($this->config)) {
            return $key;
        }

        if (empty($this->config[$this->language][$key])) {
            return $key;
        }

        if (!empty($value)) {
            return vsprintf($this->config[$this->language][$key], $value);
        }

        return $this->config[$this->language][$key];
    }

}