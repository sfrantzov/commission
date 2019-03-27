<?php

namespace Commission\Model;

use Assert\Assert;
use Assert\InvalidArgumentException;
use Commission\Base\Model;

/**
 * Input data mapper
 *
 */
class Input extends Model
{
    /**
     * @var \DateTimeImmutable
     */
    protected $date;

    /**
     * @var int
     */
    protected $userId;

    /**
     * @var string
     */
    protected $userType;

    /**
     * @var string
     */
    protected $operationType;

    /**
     * @var string
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

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
     * @param string
     */
    public function setDate($date)
    {
        Assert::that($date)->notEmpty("Amount must not be empty");
        try {
            $this->date = new \DateTimeImmutable($date);
        } catch (\Exception $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), 'date', $date);
        }
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
        $allowedTypes = [Input::USER_LEGAL, Input::USER_NATURAL];
        Assert::that($userType)->inArray($allowedTypes, 'User type must be from ' . implode(', ', $allowedTypes));
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
        $allowedTypes = [Input::CASH_IN, Input::CASH_OUT];
        Assert::that($operationType)->inArray($allowedTypes, 'Operation type must be from ' . implode(', ', $allowedTypes));
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
     * @param float $amount
     */
    public function setAmount($amount)
    {
        Assert::that($amount)->notEmpty("Amount must not be empty")->greaterThan(0);
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