<?php

namespace Faulancer\Test\Integration;

use Faulancer\Security\Csrf;
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
        $this->sessionManager = ServiceLocator::instance()->get(SessionManager::class);
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

    /**
     *
     */
    public function testTokenIsNotValid()
    {
        $token = Csrf::getToken() . 'test';
        $_POST['csrf'] = $token;
        $this->assertFalse(Csrf::isValid($token));

    }

}