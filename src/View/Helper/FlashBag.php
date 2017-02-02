<?php
/**
 * Class FlashBag | FlashBag.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Session\SessionManager;

/**
 * Class FlashBag
 */
class FlashBag
{

    /**
     * @return self
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param string $key
     * @return array|null|string
     */
    public function get(string $key)
    {
        return SessionManager::instance()->getFlashbag($key);
    }

}