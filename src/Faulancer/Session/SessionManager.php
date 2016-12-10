<?php

namespace Faulancer\Session;

class SessionManager
{

    /** @var self */
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
     * @return void
     */
    private function startSession()
    {
        session_start();
    }

    /**
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
     * @param string $key
     * @param string|array $value
     */
    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
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
     * @param string $key
     * @return boolean
     */
    public function hasFlashbagKey(string $key)
    {
        return isset($_SESSION['flashbag'][$key]) ? true : false;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function hasFlashbagErrorsKey(string $key)
    {
        return isset($_SESSION['flashbag']['errors'][$key]) ? true : false;
    }

    /**
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
     * @param array $formData
     */
    public function setFlashbagFormData(array $formData)
    {
        if (is_array($formData)) {
            $_SESSION['flashbag']['formData'] = $formData;
        }
    }

    /**
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