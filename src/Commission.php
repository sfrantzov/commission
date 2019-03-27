<?php

namespace Commission;

use Commission\Base\Config;
use Commission\Base\Model;
use Commission\Base\Util;
use Commission\Collection\ExchangeRateCollection;
use Commission\Collection\UserCollection;
use Commission\Logic\BaseLogic\BaseLogicConfig;
use Commission\Logic\BaseLogic\CommissionBaseLogic;
use Commission\Model\Input;
use Commission\Model\Interfaces\InputStreamInterface;
use Commission\Model\Interfaces\OutputStreamInterface;
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
 * Params:
 *
 * @property MoneyCalculator $calculator
 * @property ExchangeRateCollection $exchangeRates
 */
class Commission extends Model
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
            Util::getOrCreateExchangeRate($this->exchangeRates, $code, $rate);
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
            $user = Util::getOrCreateUser($users, $input->userId);
            $user->userType = $input->userType;

            $baseLogicConfig = new BaseLogicConfig($this->config->getConfig('baseLogic'));
            $logic = new CommissionBaseLogic($baseLogicConfig);

            /* @var MoneyInterface $commission */
            $commission = $logic->process($this, $user, $input);

            $outputStream->stream($commission, $this->formatter);
        }
    }
}
