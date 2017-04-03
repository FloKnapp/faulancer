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

    /**
     * Check if flashbag key exists
     * @param string $key
     * @return boolean
     */
    public function hasFlashbagKey(string $key)
    {
        return isset($_SESSION['flashbag'][$key]) ? true : false;
    }

    /**
     * Check if flashbag error key exists
     * @param string $key
     * @return boolean
     */
    public function hasFlashbagErrorsKey($key)
    {
        return isset($_SESSION['flashbag']['errors'][$key]) ? true : false;
    }

    /**
     * Set flashbag value by key
     * @param string|array      $key
     * @param null|string|array $value
     * @return boolean
     */
    public function setFlashbag($key, $value = null)
    {
        if (is_array($key)) {

            foreach ($key AS $k => $val) {
                $_SESSION['flashbag'][$k] = $val;
            }

            return true;

        }

        $_SESSION['flashbag'][$key] = $value;

        return true;
    }

    /**
     * Get flashbag value by key
     *
     * @param string $key
     * @return null|string|array
     */
    public function getFlashbag(string $key)
    {
        if (!isset($_SESSION['flashbag'][$key])) {
            return null;
        }

        $result = $_SESSION['flashbag'][$key];

        unset($_SESSION['flashbag'][$key]);

        return $result;
    }

    /**
     * Get flashbag error by key
     *
     * @param string $key
     * @return null|string|array
     */
    public function getFlashbagError(string $key)
    {
        if (!isset($_SESSION['flashbag']['errors'][$key])) {
            return null;
        }

        $result = $_SESSION['flashbag']['errors'][$key];

        unset($_SESSION['flashbag']['errors'][$key]);

        return $result;
    }

    /**
     * Set form data in session flashbag
     * @param array $formData
     */
    public function setFlashbagFormData(array $formData)
    {
        if (is_array($formData)) {
            $_SESSION['flashbag']['formData'] = $formData;
        }
    }

    /**
     * Get form data from session flashbag
     * @param string $key
     * @return null|array|string
     */
    public function getFlashbagFormData(string $key)
    {
        if (!isset($_SESSION['flashbag']['formData'][$key])) {
            return null;
        }

        $result = $_SESSION['flashbag']['formData'][$key];

        unset($_SESSION['flashbag']['formData'][$key]);

        return $result;
    }

}