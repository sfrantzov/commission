<?php

namespace Commission\Model;

use Assert\Assert;
use Commission\Base\Model;

/**
 * User object
 *
 */
class User extends Model
{
    /**
     * @var CashOut
     */
    protected $cashOut;

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
     * @return CashOut
     */
    public function getCashOut()
    {
        return $this->cashOut;
    }

    /**
     * Set cash out
     *
     * @param CashOut $cashOut
     */
    public function setCashOut(CashOut $cashOut)
    {
        $this->cashOut = $cashOut;
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
}
