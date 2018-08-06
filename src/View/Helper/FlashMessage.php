<?php

namespace Faulancer\View\Helper;

use Faulancer\Exception\ServiceNotFoundException;
use Faulancer\Session\SessionManager;
use Faulancer\Translate\Translator;
use Faulancer\View\AbstractViewHelper;

/**
 * Class FlashMessage
 *
 * @package Faulancer\View\Helper
 * @author  Florian Knapp <office@florianknapp.de>
 */
class FlashMessage extends AbstractViewHelper
{

    /**
     * @param string $key
     * @param string $type
     *
     * @return string
     *
     * @throws ServiceNotFoundException
     */
    public function __invoke(string $key, $type = 'default')
    {
        $result = '';

        /** @var Translator $translator */
        $translator = $this->getServiceLocator()->get(Translator::class);

        /** @var SessionManager $sessionManager */
        $sessionManager = $this->getServiceLocator()->get(SessionManager::class);

        if ($sessionManager->hasFlashMessage($key)) {
            $result = '<span class="flash-message ' . $type . '">' . $translator->translate($sessionManager->getFlashMessage($key)) . '</span>';
        }

        return $result;
    }

}