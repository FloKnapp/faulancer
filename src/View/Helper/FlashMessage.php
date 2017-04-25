<?php
/**
 * Class FlashMessage | FlashMessagesage.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Service\SessionManagerService;
use Faulancer\View\AbstractViewHelper;

/**
 * Class FlashMessage
 */
class FlashMessage extends AbstractViewHelper
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
        return $this->getServiceLocator()->get(SessionManagerService::class)->getFlashMessage($key);
    }

}