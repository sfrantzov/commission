<?php

namespace Commission\Logic\BaseLogic;

use Commission\Commission;
use Commission\Model\Input;
use Commission\Model\User;
use Maba\Component\Monetary\Money;
use Maba\Component\Monetary\MoneyInterface;


/**
 * Abstract base logic for commission
 */
class CashOutLegalLogic extends AbstractBaseLogic
{
    /**
     * execute specific commission logic
     *
     * @param Commission $application
     * @param User $user
     * @param Input $input
     * @return MoneyInterface
     */
    public function process(Commission $application, User $user, Input $input)
    {
        $this->application = $application;
        $originalAmount = $this->getOriginalAmount($input);

        $defaultCurrency = $this->application->getDefaultCurrency();
        $cashOutCommission = $this->config->getConfig('legalCashOutCommission') / 100;
        $minCashOutCommission = $this->config->getConfig('minLegalCashOutCommission');

        $commission = $this->application->getCalculator()->mul(
            $originalAmount,
            $cashOutCommission
        );
        if ($this->application->getCalculator()->isLt(
            $this->convertAmount($commission),
            new Money(
                $minCashOutCommission,
                $defaultCurrency
            )
        )) {
            $commission = $this->reverseConvertAmount(
                new Money(
                    $minCashOutCommission,
                    $originalAmount->getCurrency()
                )
            );
        }
        return $application->getCalculator()->ceil($commission);
    }
}
