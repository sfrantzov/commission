<?php

namespace Commission;

use Commission\Base\Config;
use Commission\Collection\ExchangeRateCollection;
use Commission\Collection\UserCollection;
use Commission\Logic\BaseLogic\BaseLogicConfig;
use Commission\Logic\BaseLogic\CommissionBaseLogic;
use Commission\Model\CashOut;
use Commission\Model\ExchangeRate;
use Commission\Model\Input;
use Commission\Model\Interfaces\InputStreamInterface;
use Commission\Model\Interfaces\OutputStreamInterface;
use Commission\Model\User;
use Maba\Component\Math\BcMath;
use Maba\Component\Math\Math;
use Maba\Component\Math\NumberFormatter;
use Maba\Component\Math\NumberValidator;
use Maba\Component\Monetary\Factory\MoneyFactory;
use Maba\Component\Monetary\Formatting\MoneyFormatter;
use Maba\Component\Monetary\Information\MoneyInformationProvider;
use Maba\Component\Monetary\MoneyCalculator;
use Maba\Component\Monetary\MoneyInterface;
use Maba\Component\Monetary\Validation\MoneyValidator;

/**
 * Commission calculation class
 *
 */
class Commission
{
    /**
     * @var MoneyCalculator
     */
    protected $calculator;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var ExchangeRateCollection
     */
    protected $exchangeRates;

    /**
     * @var MoneyFormatter
     */
    protected $formatter;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->init($config);
    }

    /**
     * get calculator
     *
     * @return MoneyCalculator
     */
    public function getCalculator()
    {
        return $this->calculator;
    }

    /**
     * returns default currency defined in application config
     *
     * @return string
     */
    public function getDefaultCurrency()
    {
        return $this->config->getConfig('application.defaultCurrency');
    }

    /**
     * get exchange rates
     *
     * @return ExchangeRateCollection
     */
    public function getExchangeRates()
    {
        return $this->exchangeRates;
    }

    /**
     * get formatter
     *
     * @return MoneyFormatter
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * init application
     *
     * @param Config $config
     */
    protected function init(Config $config)
    {
        $this->config = $config;

        $math = new Math(new BcMath());
        $informationProvider = new MoneyInformationProvider();
        $factory = new MoneyFactory($math, new MoneyValidator($math, $informationProvider, new NumberValidator()));
        $this->calculator = new MoneyCalculator($math, $factory, $informationProvider);

        $this->formatter = new MoneyFormatter(
            $this->calculator,
            $informationProvider,
            new NumberFormatter($math),
            [],
            '%amount%'
        );
        $this->exchangeRates = new ExchangeRateCollection();
        foreach ($this->config->getConfig('application.currencyExchangeRates') as $code => $rate) {
            $this->getOrCreateExchangeRate($this->exchangeRates, $code, $rate);
        }
    }

    /**
     * @param InputStreamInterface $inputStream
     * @param OutputStreamInterface $outputStream
     */
    public function run(InputStreamInterface $inputStream, OutputStreamInterface $outputStream)
    {
        $users = new UserCollection();

        /* @var Input $input */
        while ($input = $inputStream->getRow()) {
            $user = $this->getOrCreateUser($users, $input->getUserId());
            $user->setUserType($input->getUserType());

            $baseLogicConfig = new BaseLogicConfig($this->config->getConfig('baseLogic'));
            $logic = new CommissionBaseLogic($baseLogicConfig);

            /* @var MoneyInterface $commission */
            $commission = $logic->process($this, $user, $input);

            $outputStream->stream($commission, $this->formatter);
        }
    }

    /**
     * @param ExchangeRateCollection $collection
     * @param string $currency
     * @param float $rate
     * @return ExchangeRate
     */
    protected function getOrCreateExchangeRate(ExchangeRateCollection $collection, $currency, $rate)
    {
        if (isset($collection[$currency])) {
            return $collection[$currency];
        } else {
            $exchangeRate = new ExchangeRate([
                'currency' => $currency,
                'exchangeRate' => $rate
            ]);
            $collection[$currency] = $exchangeRate;
            return $exchangeRate;
        }
    }

    /**
     * @param UserCollection $collection
     * @param int $userId
     * @return User
     */
    public function getOrCreateUser(UserCollection $collection, $userId)
    {
        if (isset($collection[$userId])) {
            return $collection[$userId];
        } else {
             $user = new User([
                'userId' => $userId,
                'cashOut' => new CashOut()
            ]);
            $collection[$userId] = $user;
            return $user;
        }
    }
}
