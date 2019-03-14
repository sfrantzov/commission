<?php
namespace Commission\Base\Traits;

use Commission\Exception\PropertyException;

trait MagicParamsTrait
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
}