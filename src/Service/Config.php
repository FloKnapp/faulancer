<?php

namespace Faulancer\Service;

/**
 * Represents a configuration array
 *
 * @package Faulancer\Service
 * @author Florian Knapp <office@florianknapp.de>
 */
class Config
{

    /**
     * Holds the configuration data
     * @var array
     */
    protected $_config = [];

    /**
     * Set configuration value by key
     *
     * @param mixed   $key
     * @param mixed   $value
     * @param boolean $force
     *
     * @return bool
     */
    public function set($key, $value = null, $force = false)
    {
        if (is_array($key) && $value === null) {
            foreach ($key as $k => $v) {
                $this->set($k, $v, $force);
            }
            return true;
        }

        if ($force || empty($this->_config[$key])) {
            $this->_config[$key] = $value;
            return true;
        }

        return false;
    }

    /**
     * Get configuration value by key
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (strpos($key, ':') !== false) {
            return $this->_recursive($key);
        }

        if (!isset($this->_config[$key])) {
            return null;
        }

        return $this->_config[$key];
    }

    /**
     * Delete key from config (just for one request)
     *
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        if (isset($this->_config[$key])) {
            unset($this->_config[$key]);
        }
        return true;
    }

    /**
     * Iterate through configuration till given key is found
     *
     * @param $key
     * @return mixed
     */
    private function _recursive($key)
    {
        $parts  = explode(':', $key);
        $result = $this->_config;

        foreach ($parts as $part) {

            if (!isset($result[$part])) {
                return null;
            }

            $result = $result[$part];

        }

        return $result;
    }

}