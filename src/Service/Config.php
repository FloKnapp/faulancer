<?php
/**
 * Config.php
 *
 * @package Faulancer\Service
 * @author Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Service;

use Faulancer\Exception\ConfigInvalidException;

/**
 * Represents a configuration array
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
     * @param mixed $key
     * @param mixed $value
     * @param boolean $force
     * @return boolean
     * @throws ConfigInvalidException
     */
    public function set($key, $value = null, $force = false)
    {
        if (is_array($key) && $value === null) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
            return true;
        }

        if ($force || empty($this->_config[$key])) {
            $this->_config[$key] = $value;
            return true;
        }

        throw new ConfigInvalidException();
    }

    /**
     * Get configuration value by key
     * @param string $key
     * @return mixed
     * @throws ConfigInvalidException
     */
    public function get($key)
    {
        if (strpos($key, ':') !== false) {
            return $this->recursive($key);
        }

        if (empty($this->_config[$key])) {
            throw new ConfigInvalidException('No value for key "' . $key . '" found.');
        }

        return $this->_config[$key];
    }

    /**
     * @param $key
     * @return array|mixed
     * @throws ConfigInvalidException
     */
    private function recursive($key)
    {
        $parts  = explode(':', $key);
        $result = $this->_config;

        foreach ($parts as $part) {

            if (empty($result[$part])) {
                return '';
            }

            $result = $result[$part];
        }

        return $result;
    }

}