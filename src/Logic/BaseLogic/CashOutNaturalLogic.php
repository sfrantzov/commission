<?php

namespace Commission\Logic\BaseLogic;

use Commission\Base\Util;
use Commission\Commission;
use Commission\Model\Input;
use Commission\Model\User;
use Maba\Component\Monetary\Money;
use Maba\Component\Monetary\MoneyInterface;


/**
 * Abstract base logic for commission
 */
class CashOutNaturalLogic extends AbstractBaseLogic
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
        $freeCashOut = $this->getConfig('baseLogic.maxNaturalFreeCashOut');
        $cashOutCommission = $this->getConfig('baseLogic.naturalCashOutCommission') / 100;
        $maxCashOutCommission = $this->getConfig('baseLogic.maxNaturalCashOutCommission');

        $week = Util::getWeek($input->date);
        $oldCashOut = $user->getCashOut($week);

        $convertedAmount = $application->calculator->ceil($this->convertAmount($originalAmount));
        $cashOut = $this->add(
            $convertedAmount,
            $user->getCashOut($week)
        );
        $user->setCashOut($cashOut, $week);

        $commission = $this->application->calculator->mul(
            $originalAmount,
            $cashOutCommission
        );

        if ($user->getCount($week) <= $freeCashOut
            && $this->application->calculator->isGt(
                new Money(
                    $maxCashOutCommission,
                    $defaultCurrency
                ),
                $oldCashOut
            )
        ) {
            if ($this->application->calculator->isLt(
                new Money(
                    $maxCashOutCommission,
                    $defaultCurrency
                ),
                $user->getCashOut($week))
            ) {
                $amount = $this->sub(
                    $user->getCashOut($week),
                    new Money(
                        $maxCashOutCommission,
                        $defaultCurrency
                    )
                );
                $commission = $this->application->calculator->mul(
                    $amount,
                    $cashOutCommission
                );
                $commission = $this->reverseConvertAmount(
                    new Money($commission->getAmount(),
                        $input->currency)
                );
            } else {
                $commission = new Money(0, $input->currency);
            }
        }
        return $application->calculator->ceil($commission);
    }
}
