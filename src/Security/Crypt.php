<?php
/**
 * Class Crypt | Crypt.php
 * @package Faulancer\Security
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Security;

/**
 * Class Crypt
 */
class Crypt
{

    /**
     * Generate a hashed password string
     *
     * @param string $password
     * @return bool|string
     * @throws \Exception
     */
    public function hashPassword(string $password) :string
    {
        $hash = password_hash($password, CRYPT_BLOWFISH);

        if ($hash) {
            return $hash;
        }

        throw new \Exception('Hashing failed');
    }

    /**
     * Verify a password
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verifyPassword(string $password, string $hash) :bool
    {
        return password_verify($password, $hash);
    }

}