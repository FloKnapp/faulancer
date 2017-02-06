<?php
/**
 * Class FlashBag | FlashBag.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Service\SessionManagerService;
use Faulancer\View\AbstractViewHelper;

/**
 * Class FlashBag
 */
class FlashBag extends AbstractViewHelper
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
        return $this->getServiceLocator()->get(SessionManagerService::class)->getFlashbag($key);
    }

}