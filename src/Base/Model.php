<?php

namespace Commission\Base;

use Commission\Exception\PropertyException;

/**
 * Base Model for setting params
 */
abstract class Model
{

    /**
     * @param array $params
     * @throws PropertyException
     */
    public function __construct($params = [])
    {
        $this->setParams($params);
    }

    /**
     * @param array $params
     * @throws PropertyException
     */
    public function setParams($params = [])
    {
        $reflection = new \ReflectionClass($this);

        foreach ($params as $name => $value) {
            try {
                // try to set it via setter first
                $this->__set($name, $value);
            } catch (PropertyException $e) {
                // if the property is public set it directly
                if ($reflection->hasProperty($name) && $reflection->getProperty($name)->isPublic()) {
                    $this->$name = $value;
                } else {
                    throw $e;
                }
            }
        }
    }

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
}
