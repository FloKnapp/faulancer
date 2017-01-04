<?php

namespace Faulancer\Fixture\View;

use Faulancer\View\AbstractViewHelper;

/**
 * File ViewHelperMock.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class StubViewHelper extends AbstractViewHelper
{

    public function __invoke()
    {
        return $this->renderView('/stubViewHelper.phtml');
    }

}