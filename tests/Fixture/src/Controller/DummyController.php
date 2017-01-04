<?php

namespace Faulancer\Fixture\Controller;

/**
 * File DummyController.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class DummyController
{

    /**
     * @Route(path="/", name="StubStaticHome", method="get")
     *
     * @return int
     */
    public function stubHomeAction()
    {
        return 0;
    }

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