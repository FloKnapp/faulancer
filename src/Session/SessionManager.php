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
     * This instance
     * @var self
     */
    protected static $instance;

    /**
     * SessionStorage constructor.
     */
    private function __construct()
    {
        if (!$this->hasSession()) {
            $this->startSession();
        }
    }

    /**
     * Return self
     * @return self
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Start session handler
     * @return void
     */
    private function startSession()
    {
        session_start();
    }

    /**
     * Check if session exists
     * @return boolean
     */
    public function hasSession()
    {
        if (isset($_SESSION)) {
            return true;
        }

        return false;
    }

    /**
     * Set session key with value
     * @param string $key
     * @param string|array $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value by key
     * @param string $key
     * @return null|string|array
     */
    public function get($key)
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
    public function delete($key)
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
    public function hasFlashbagKey($key)
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
     * @param string $key
     * @return null|string|array
     */
    public function getFlashbag($key)
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
     * @param string $key
     * @return null|string|array
     */
    public function getFlashbagError($key)
    {
        if (!isset($_SESSION['flashbag']['errors'][$key])) {
            return [];
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
    public function getFlashbagFormData($key)
    {
        if (!isset($_SESSION['flashbag']['formData'][$key])) {
            return null;
        }

        $result = $_SESSION['flashbag']['formData'][$key];

        unset($_SESSION['flashbag']['formData'][$key]);

        return $result;
    }

}