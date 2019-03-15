<?php

namespace Commission\Tests;

use Commission\Base\Config;
use Commission\Commission;
use Commission\Model\Input;
use Commission\Model\User;
use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{
    use \Codeception\Specify;

    /**
     * @var string
     */
    protected $logicClass;

    /**
     * @var string
     */
    protected $modelClass;

    public function getApplication()
    {
        return new Commission();
    }

    public function getUser($userId)
    {
        return new User([
            'userId' => $userId
        ]);
    }

    public function getInput($params)
    {
        return new Input($params);
    }

    public function setConfig()
    {
        $configArray = (array) include 'tests.config.php';
        $config = Config::getInstance();
        $config->setConfig($configArray);
    }

    public function shouldBe($specification, \Closure $callable = null, $params = [])
    {
        $this->specify('should be ' . $specification, $callable, $params);
    }

    public function shouldThrowExceptionIf($specification, \Closure $callable = null, $params = [])
    {
        $this->specify('should throw exception if ' . $specification, $callable, $params);
    }
}
