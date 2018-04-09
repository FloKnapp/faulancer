<?php

namespace Faulancer\Test\Unit;

use Faulancer\Http\AbstractHttp;
use Faulancer\Session\SessionManager;
use PHPUnit\Framework\TestCase;

/**
 * File AbstractHttpTest.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class AbstractHttpTest extends TestCase
{

    /** @var AbstractHttp */
    protected $abstractHttp;

    /** @var SessionManager */
    protected $sessionMock;

    public function setUp()
    {
        $this->abstractHttp = $this->getMockForAbstractClass(AbstractHttp::class);
        $this->sessionMock = $this->createMock(SessionManager::class);
    }

    public function testSetGetSession()
    {
        $this->abstractHttp->setSession($this->sessionMock);
        $this->assertSame($this->sessionMock, $this->abstractHttp->getSession());
    }

}