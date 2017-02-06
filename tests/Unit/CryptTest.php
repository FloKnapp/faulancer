<?php
/**
 * Class CryptTest | CryptTest.php
 * @package Unit
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Unit;

use Faulancer\Security\Crypt;
use PHPUnit\Framework\TestCase;

/**
 * Class CryptTest
 */
class CryptTest extends TestCase
{

    public function testCrypt()
    {
        $crypt = new Crypt();
        $hash = $crypt->hashPassword('test');

        $this->assertTrue(is_string($hash));
        $this->assertTrue($crypt->verifyPassword('test', $hash));

    }

}