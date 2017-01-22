<?php
/**
 * Class FormData | FormData.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Session\SessionManager;
use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class FormData
 */
class FormData extends AbstractViewHelper
{

    /**
     * Get field of submitted form
     *
     * @param ViewController $view
     * @param                $key
     * @return array|null|string
     */
    public function __invoke(ViewController $view, string $key)
    {
        return SessionManager::instance()->getFlashbagFormData($key);
    }

}