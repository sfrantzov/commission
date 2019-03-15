<?php

namespace Commission\Model;

use Assert\Assert;
use Commission\Base\Model;
use Commission\Collection\CashOutCollection;
use Maba\Component\Monetary\Money;
use Maba\Component\Monetary\MoneyInterface;

/**
 * User object
 *
 * Params:
 *
 * @property int $userId
 * @property string $userType
 */
class User extends Model
{
    /**
     * @var CashOutCollection
     */
    protected $cashOut;

    /**
     * @var array
     */
    protected $count = [];

    /**
     * @var int
     */
    protected $userId;

    /**
     * @var string
     */
    protected $userType;

    /**
     * get cash out
     *
     * @param $week
     * @return MoneyInterface
     */
    public function getCashOut($week)
    {
        $this->initCounter($week);
        if (!isset($this->cashOut[$week])) {
            $this->cashOut[$week] = new Money(0, null);
        }
        return $this->cashOut[$week];
    }

    /**
     * Set cash out
     *
     * @param MoneyInterface $cashOut
     * @param int $week
     */
    public function setCashOut(MoneyInterface $cashOut, $week)
    {
        if (empty($this->cashOut)) {
            $this->cashOut = new CashOutCollection();
        }
        $this->initCounter($week);
        $this->count[$week]++;
        $this->cashOut[$week] = $cashOut;
    }

    /**
     * get cash out
     *
     * @param int $week
     * @return array
     */
    public function getCount($week)
    {
        $this->initCounter($week);
        return $this->count[$week];
    }

    /**
     * Get userId
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userId
     *
     * @param int $userId
     */
    public function setUserId($userId)
    {
        Assert::that($userId)->integerish()->greaterThan(0);
        $this->userId = $userId;
    }

    /**
     * Get user type
     *
     * @return string
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Get userType
     *
     * @param string $userType
     */
    public function setUserType($userType)
    {
        Assert::that($userType)->inArray([Input::USER_LEGAL, Input::USER_NATURAL], 'User type must be from ' . implode(', ', [Input::USER_LEGAL, Input::USER_NATURAL]));
        $this->userType = $userType;
    }

    public static function create($user)
    {
        if ($user instanceof static) {
            return $user;
        }

        if ($user instanceof self) {
            $params = [
                'userId' => $user->userId,
            ];
        } elseif (is_array($user)) {
            $params = $user;
        } else {
            $error = 'The user must be an instance of "' . self::class . '", an array of parameters';
            throw new \InvalidArgumentException($error);
        }
        return new static($params);
    }

    protected function initCounter($week)
    {
        if (!isset($this->cashOut[$week])) {
            $this->count[$week] = 0;
        }
    }

}