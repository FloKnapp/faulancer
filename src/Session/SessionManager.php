<?php
/**
 * Class SessionManager | SessionManager.php
 *
 * @package Faulancer\Session;
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Session;

/**
 * Class SessionManager
 */
class SessionManager
{
    /**
     * Start session handler
     * @return void
     * @codeCoverageIgnore Covered by php
     */
    public function startSession()
    {
        if (!$this->hasSession()) {
            session_start();
        }
    }

    /**
     * Destroy session at all
     * @return void
     */
    public function destroySession()
    {
        if ($this->hasSession()) {
            session_destroy();
        }
    }

    /**
     * Check if session exists
     * @return boolean
     */
    public function hasSession()
    {
        if (!empty(session_id())) {
            return true;
        }

        return false;
    }

    /**
     * Set session key with value
     * @param string $key
     * @param string|array $value
     */
    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value by key
     * @param string $key
     * @return null|string|array
     */
    public function get(string $key)
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }

        return $_SESSION[$key];
    }

    /**
     * Delete session value by key
     * @param string $key
     * @return boolean
     */
    public function delete(string $key)
    {
        if (!isset($_SESSION[$key])) {
            return false;
        }

        unset($_SESSION[$key]);

        return true;
    }

    public function has(string $key)
    {
        if (empty($_SESSION[$key])) {
            return false;
        }

        return true;
    }

    /**
     * Check if flash message key exists
     * @param string $key
     * @return boolean
     */
    public function hasFlashMessage(string $key)
    {
        return isset($_SESSION['flashMessage'][$key]) ? true : false;
    }

    /**
     * Set flashbag value by key
     * @param string|array      $key
     * @param null|string|array $value
     * @return boolean
     */
    public function setFlashMessage($key, $value = null)
    {
        if (is_array($key)) {

            foreach ($key AS $k => $val) {
                $_SESSION['flashMessage'][$k] = $val;
            }

            return true;

        }

        $_SESSION['flashMessage'][$key] = $value;

        return true;
    }

    /**
     * Get flash message value by key
     *
     * @param string $key
     * @return null|string|array
     */
    public function getFlashMessage(string $key)
    {
        if (!isset($_SESSION['flashMessage'][$key])) {
            return null;
        }

        $result = $_SESSION['flashMessage'][$key];

        unset($_SESSION['flashMessage'][$key]);

        return $result;
    }

}