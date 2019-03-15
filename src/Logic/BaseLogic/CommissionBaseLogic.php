<?php

namespace Commission\Logic\BaseLogic;

use Commission\Commission;
use Commission\Model\Input;
use Commission\Model\User;
use Maba\Component\Monetary\MoneyInterface;

/**
 * Base logic for commission
 */
class CommissionBaseLogic
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
        if ($input->operationType == Input::CASH_IN) {
            $commission = (new CashInLogic)->process($application, $user, $input);
        } else {
            if ($user->userType == Input::USER_LEGAL) {
                $commission = (new CashOutLegalLogic)->process($application, $user, $input);
            } else {
                $commission = (new CashOutNaturalLogic)->process($application, $user, $input);
            }
        }
        return $commission;
    }
}
