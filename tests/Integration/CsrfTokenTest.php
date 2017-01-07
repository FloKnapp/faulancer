<?php

namespace Faulancer\Test\Integration;

use Faulancer\Security\Csrf;
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
        $this->sessionManager = SessionManager::instance();
    }

    /**
     * @runInSeparateProcess
     */
    public function testTokenGeneration()
    {
        $token = Csrf::getToken();
        $_POST['csrf'] = $token;
        $this->assertTrue(Csrf::isValid());

    }

}