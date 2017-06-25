<?php
/**
 * Class FlashMessage | FlashMessagesage.php
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\View\Helper;

use Faulancer\Service\SessionManagerService;
use Faulancer\View\AbstractViewHelper;
use Faulancer\View\ViewController;

/**
 * Class FlashMessage
 */
class FlashMessage extends AbstractViewHelper
{

    /**
     * @param ViewController $view
     * @param string         $key
     * @param string         $type
     * @return string
     */
    public function __invoke(ViewController $view, string $key, $type = 'default')
    {
        $result = '';

        /** @var SessionManagerService $sessionManager */
        $sessionManager = $this->getServiceLocator()->get(SessionManagerService::class);

        if ($sessionManager->hasFlashMessage($key)) {
            $result = '<span class="flashMessage ' . $type . '">' . $sessionManager->getFlashMessage($key) . '</span>';
        }

        return $result;
    }

}