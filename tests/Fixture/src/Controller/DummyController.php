<?php

namespace Faulancer\Fixture\Controller;

use Faulancer\Controller\Controller;

/**
 * File DummyController.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class DummyController extends Controller
{

    /**
     * @return int
     */
    public function stubHomeAction()
    {
        return 0;
    }

    /**
     * @return int
     */
    public function stubStaticAction()
    {
        return 1;
    }

    /**
     * @return int
     */
    public function stubDynamicAction()
    {
        return 2;
    }

}