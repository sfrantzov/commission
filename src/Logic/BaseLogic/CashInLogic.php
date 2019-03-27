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
class CashInLogic extends AbstractBaseLogic
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

        $cashInCommission = $this->config->getConfig('cashInCommission') / 100;
        $maxCashInCommission = $this->config->getConfig('maxCashInCommission');

        $commission = $this->application->calculator->mul(
            $originalAmount,
            $cashInCommission
        );

        if ($this->application->calculator->isGt(
            $this->application->calculator->mul(
                $this->convertAmount($originalAmount),
                $cashInCommission
            ),
            new Money(
                $maxCashInCommission,
                $this->application->getDefaultCurrency()
            )
        )) {
            $commission = $this->reverseConvertAmount(
                new Money(
                    $maxCashInCommission,
                    $originalAmount->getCurrency()
                )
            );
        }
        return $application->calculator->ceil($commission);
    }
}
