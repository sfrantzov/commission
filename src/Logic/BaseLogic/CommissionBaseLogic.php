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
    public function process(Commission $application, User $user, Input $input)
    {
        if ($input->operationType == Input::CASH_IN) {
            $commission = (new CashInLogic($this->config))->process($application, $user, $input);
        } else {
            if ($user->userType == Input::USER_LEGAL) {
                $commission = (new CashOutLegalLogic($this->config))->process($application, $user, $input);
            } else {
                $commission = (new CashOutNaturalLogic($this->config))->process($application, $user, $input);
            }
        }
        return $commission;
    }
}
