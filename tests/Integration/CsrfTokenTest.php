<?php

namespace Faulancer\Test\Integration;

use Faulancer\Security\Csrf;
use Faulancer\Service\SessionManagerService;
use Faulancer\ServiceLocator\ServiceLocator;
use Faulancer\Session\SessionManager;
use PHPUnit\Framework\TestCase;

/**
 * File CsrfTokenTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class CsrfTokenTest extends TestCase
{

    /** @var SessionManager */
    protected $sessionManager;

    /**
     *
     */
    public function setUp()
    {
        $this->sessionManager = ServiceLocator::instance()->get(SessionManagerService::class);
    }

    /**
     *
     */
    public function testTokenGeneration()
    {
        $token = Csrf::getToken();
        $_POST['csrf'] = $token;
        $this->assertTrue(Csrf::isValid($token));

    }

}