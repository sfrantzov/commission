<?php

namespace Commission\Model;

use Assert\Assert;
use Commission\Base\Model;

/**
 * Input data mapper
 *
 * Params:
 *
 * @property \DatetimeImmutable $date
 * @property int $userId
 * @property string $userType
 * @property string $operationType
 * @property string $amount
 * @property string $currency
 */
class Input extends Model
{
    /**
     * @var string
     */
    private $date;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $userType;

    /**
     * @var string
     */
    private $operationType;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * Supported user types
     */
    const USER_NATURAL = 'natural';
    const USER_LEGAL = 'legal';

    /**
     * Supported cash operations
     */
    const CASH_IN = 'cash_in';
    const CASH_OUT = 'cash_out';

    /**
     * Get date
     *
     * @return \DateTimeImmutable
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get date
     *
     * @param \DateTimeImmutable
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get userId
     *
     * @return int
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
     * Set user type
     *
     * @param string $userType
     */
    public function setUserType($userType)
    {
        Assert::that($userType)->inArray([Input::USER_LEGAL, Input::USER_NATURAL], 'User type must be from ' . implode(', ', [Input::USER_LEGAL, Input::USER_NATURAL]));
        $this->userType = $userType;
    }

    /**
     * Get operation type
     *
     * @return string
     */
    public function getOperationType()
    {
        return $this->operationType;
    }

    /**
     * Set operation type
     *
     * @param string $operationType
     */
    public function setOperationType($operationType)
    {
        Assert::that($operationType)->inArray([Input::CASH_IN, Input::CASH_OUT], 'Operation type must be from ' . implode(', ', [Input::CASH_IN, Input::CASH_OUT]));
        $this->operationType = $operationType;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set amount
     *
     * @param string $amount
     */
    public function setAmount($amount)
    {
        Assert::that($amount)->notEmpty("Amount must not be empty");
        $this->amount = $amount;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set currency
     *
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        Assert::that($currency)->notEmpty("Currency must not be empty");
        $this->currency = $currency;
    }
}