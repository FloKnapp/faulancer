<?php

namespace Faulancer\Service;

use Faulancer\Exception\ConfigInvalidException;

/**
 * File Config.php
 *
 * @author Florian Knapp <office@florianknapp.de>
 */
class Config
{

    /** @var array */
    protected $_config = [];

    /**
     * @param string $key
     * @param mixed  $value
     * @param boolean $force
     *
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
     * @param string $key
     *
     * @return mixed
     * @throws ConfigInvalidException
     */
    public function get($key)
    {
        if (!empty($this->_config[$key])) {
            return $this->_config[$key];
        }

        throw new ConfigInvalidException();
    }

}