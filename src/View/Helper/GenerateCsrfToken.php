<?php
/**
 * Class GenerateCsrfToken | GenerateCsrfToken.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Security\Csrf;
use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class GenerateCsrfToken
 */
class GenerateCsrfToken extends AbstractViewHelper
{

    /**
     * Generate a csrf token
     *
     * @param ViewController $view
     * @return string
     */
    public function __invoke(ViewController $view)
    {
        return Csrf::getToken();
    }

}