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

        $defaultCurrency = $this->getConfig('application.defaultCurrency');
        $cashOutCommission = $this->getConfig('baseLogic.legalCashOutCommission') / 100;
        $minCashOutCommission = $this->getConfig('baseLogic.minLegalCashOutCommission');

        $commission = $this->application->calculator->mul(
            $originalAmount,
            $cashOutCommission
        );
        if ($this->application->calculator->isLt(
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
        return $application->calculator->ceil($commission);
    }
}