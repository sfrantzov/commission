<?php

namespace Commission\Base\Traits;

use Commission\Exception\PropertyException;

trait MagicTrait
{
    /**
     * @param $name
     * @param $value
     * @throws PropertyException
     */
    public function __set($name, $value)
    {
        $setter = 'set' . ucfirst($name);

        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } else {
            throw new PropertyException(sprintf('Property "%s" has no setter', $name));
        }
    }

    /**
     * @param $name
     * @return mixed
     * @throws PropertyException
     */
    public function __get($name)
    {
        $getter = 'get' . ucfirst($name);

        if (method_exists(get_class($this), $getter)) {
            return $this->$getter();
        } elseif (method_exists(get_class($this), $name)) {
            return $this->$name(); //isXXX, hasXXX, etc.
        } else {
            throw new PropertyException(sprintf('Property "%s" has no getter', $name));
        }
    }

    /**
     * @param $property
     * @return bool
     */
    public function __isset($property)
    {
        try {
            $value = $this->__get($property);
            return isset($value);
        } catch (PropertyException $e) {
            return false;
        }
    }

    /**
     * @param $property
     */
    public function __unset($property)
    {
        try {
            $this->__set($property, null);
        } catch (PropertyException $e) {
            // do nothing
        }
    }
}
