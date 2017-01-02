<?php

namespace Stubs\Controller;

/**
 * File DummyController.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class DummyController
{

    /**
     * @Route(path="/stub", name="StubStaticRoute", method="get")
     *
     * @return int
     */
    public function stubStaticAction()
    {
        return 1;
    }

    /**
     * @Route(path="/stub/(\w+)", name="StubDynamicRoute", method="get")
     *
     * @return int
     */
    public function stubDynamicAction()
    {
        return 2;
    }

}