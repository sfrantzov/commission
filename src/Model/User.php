<?php

namespace Commission\Model;

use Assert\Assert;
use Commission\Base\Model;
use Maba\Component\Monetary\Money;

/**
 * User object
 *
 * Params:
 *
 * @property Money $cashIn
 * @property Money $cashOut
 * @property int $userId
 */
class User extends Model
{
    /**
     * @var Money
     */
    private $cashIn;

    /**
     * @var Money
     */
    private $cashOut;

    /**
     * @var int
     */
    private $userId;

    /**
     * Get cache in
     *
     * @return string
     */
    public function getCashIn()
    {
        return $this->cashIn;
    }

    /**
     * set cash is
     *
     * @param Money $cashIn
     */
    public function setCashIn(Money $cashIn)
    {
        $this->cashIn = $cashIn;
    }

    /**
     * get cash out
     *
     * @return Money
     */
    public function getCashOut()
    {
        return $this->cashIn;
    }

    /**
     * Set cash out
     *
     * @param Money $cashOut
     */
    public function setCashOut(Money $cashOut)
    {
        $this->cashOut = $cashOut;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get userId
     *
     * @param int $userId
     */
    public function setUserId($userId)
    {
        Assert::that($userId)->integerish()->greaterThan(0);
        $this->userId = $userId;
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
}