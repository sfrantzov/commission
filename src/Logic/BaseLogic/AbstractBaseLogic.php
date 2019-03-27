<?php

namespace Commission\Logic\BaseLogic;

use Assert\Assert;
use Assert\InvalidArgumentException;
use Commission\Commission;
use Commission\Model\Input;
use Commission\Model\User;
use Maba\Component\Monetary\Money;
use Maba\Component\Monetary\MoneyInterface;


/**
 * Abstract base logic for commission
 */
abstract class AbstractBaseLogic
{
    /**
     * @var Commission
     */
    protected $application;

    /**
     * @var BaseLogicConfig
     */
    protected $config;

    /**
     * @param BaseLogicConfig $config
     */
    public function __construct(BaseLogicConfig $config)
    {
        $this->config = $config;
    }

    /**
     * execute specific commission logic
     *
     * @param Commission $application
     * @param User $user
     * @param Input $input
     * @return MoneyInterface
     */
    abstract function process(Commission $application, User $user, Input $input);

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     * @return MoneyInterface
     */
    protected function add(MoneyInterface $first, MoneyInterface $second)
    {
        $money = $this->application->getCalculator()->ceil(
            $this->application->getCalculator()->add(
                $first,
                $second
            )
        );
        return $money;
    }

    /**
     * @param MoneyInterface $first
     * @param MoneyInterface $second
     * @return MoneyInterface
     */
    protected function sub(MoneyInterface $first, MoneyInterface $second)
    {
        $money = $this->application->getCalculator()->ceil(
            $this->application->getCalculator()->sub(
                $first,
                $second
            )
        );
        return $money;
    }

    /**
     * @param MoneyInterface $originalAmount
     * @return MoneyInterface
     */
    protected function convertAmount(MoneyInterface $originalAmount)
    {
        $convertedAmount = $this->application->getCalculator()->div(
            new Money($originalAmount->getAmount(), $this->application->getDefaultCurrency()),
            $this->application->getExchangeRates()[$originalAmount->getCurrency()]->getExchangeRate()
        );
        return $convertedAmount;
    }

    /**
     * @param MoneyInterface $originalAmount
     * @return MoneyInterface
     */
    protected function reverseConvertAmount(MoneyInterface $originalAmount)
    {
        $convertedAmount = $this->application->getCalculator()->mul(
            new Money($originalAmount->getAmount(), $originalAmount->getCurrency()),
            $this->application->getExchangeRates()[$originalAmount->getCurrency()]->getExchangeRate()
        );
        return $convertedAmount;
    }

    /**
     * @param Input $input
     * @return MoneyInterface
     */
    protected function getOriginalAmount(Input $input)
    {
        $this->validateCurrency($input->getCurrency());
        return new Money($input->getAmount(), $input->getCurrency());
    }

    /**
     * @param string $currency
     * @throws InvalidArgumentException
     */
    protected function validateCurrency($currency)
    {
        $currencies = array_keys(iterator_to_array($this->application->getExchangeRates()));
        Assert::that($currency)->inArray($currencies, 'Currency must be from ' . implode(', ', $currencies));
    }
}
