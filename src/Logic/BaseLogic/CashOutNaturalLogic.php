<?php

namespace Commission\Logic\BaseLogic;

use Commission\Base\Date;
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
        $userCashOut = $user->getCashOut();
        $this->application = $application;
        $originalAmount = $this->getOriginalAmount($input);

        $defaultCurrency = $this->application->getDefaultCurrency();
        $freeCashOut = $this->config->getConfig('maxNaturalFreeCashOut');
        $cashOutCommission = $this->config->getConfig('naturalCashOutCommission') / 100;
        $maxCashOutCommission = $this->config->getConfig('maxNaturalCashOutCommission');

        $week = (new Date($input->getDate()))->getWeek();
        $oldCashOut = $userCashOut->getCashOut($week);

        $convertedAmount = $application->getCalculator()->ceil($this->convertAmount($originalAmount));
        $cashOut = $this->add(
            $convertedAmount,
            $userCashOut->getCashOut($week)
        );
        $userCashOut->setCashOut($cashOut, $week);

        $commission = $this->application->getCalculator()->mul(
            $originalAmount,
            $cashOutCommission
        );

        if ($userCashOut->getCount($week) <= $freeCashOut
            && $this->application->getCalculator()->isGt(
                new Money(
                    $maxCashOutCommission,
                    $defaultCurrency
                ),
                $oldCashOut
            )
        ) {
            if ($this->application->getCalculator()->isLt(
                new Money(
                    $maxCashOutCommission,
                    $defaultCurrency
                ),
                $userCashOut->getCashOut($week))
            ) {
                $amount = $this->sub(
                    $userCashOut->getCashOut($week),
                    new Money(
                        $maxCashOutCommission,
                        $defaultCurrency
                    )
                );
                $commission = $this->application->getCalculator()->mul(
                    $amount,
                    $cashOutCommission
                );
                $commission = $this->reverseConvertAmount(
                    new Money($commission->getAmount(),
                        $input->getCurrency())
                );
            } else {
                $commission = new Money(0, $input->getCurrency());
            }
        }
        return $application->getCalculator()->ceil($commission);
    }
}
