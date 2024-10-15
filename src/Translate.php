<?php

namespace Diankemao\Translate;

use Diankemao\Translate\Tools\Str;
use Diankemao\Translate\Tools\Config;
use Diankemao\Translate\Contracts\Strategy;
use Diankemao\Translate\Contracts\Translation;
use Diankemao\Translate\Strategies\OrderStrategy;
use Diankemao\Translate\Exceptions\Exception;
use Diankemao\Translate\Exceptions\GatewayException;
use Diankemao\Translate\Exceptions\InvalidArgumentException;
use Diankemao\Translate\Exceptions\NoGatewayAvailableException;

class Translate
{
    /**
     * config.
     *
     * @var Config
     */
    protected $config;

    /**
     * strategy.
     *
     * @var array
     */
    protected $strategy;

    /**
     * bootstrap.
     *
     * @author Allen.Liang
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);

        if (count($this->config->get('gateways', [])) == 0) {
            throw new InvalidArgumentException("Missing Gateways", 1);
        }
    }

    /**
     * translate.
     *
     * @author Allen.Liang
     *
     * @param string $q
     * @param string $to
     * @param string $from
     *
     * @return string
     */
    public function trans($q, $to = 'en', $from = 'auto')
    {
        $success = false;

        foreach ($this->strategy() as $value) {
            try {
                $res = $this->driver($value)->trans($q, $to, $from);
                $success = true;

                break;
            } catch (Exception $e) {
                continue;
            }
        }

        if (!$success) {
            throw new NoGatewayAvailableException();
        }

        return $res;
    }

    /**
     * translate to link-like string.
     *
     * @author Allen.Liang
     *
     * @param string $q
     * @param string $separator
     *
     * @return string
     */
    public function link($q, $separator = '-')
    {
        return Str::slug($this->trans($q), $separator);
    }

    /**
     * translation driver.
     *
     * @author Allen.Liang
     *
     * @param string $driver
     *
     * @return Translation
     */
    public function driver($driver)
    {
        if (is_null($this->config->get("gateways.{$driver}"))) {
            throw new InvalidArgumentException("missing driver [$driver] config", 1);
        }

        $gateway = __NAMESPACE__ . '\\Gateways\\' . ucfirst($driver) . 'Gateway';

        return $this->buildDriver($gateway, $driver);
    }

    /**
     * apply strategy.
     *
     * @author Allen.Liang
     *
     * @return array
     */
    protected function strategy()
    {
        $strategy = $this->config->get('strategy', OrderStrategy::class);

        if (!class_exists($strategy)) {
            throw new InvalidArgumentException("Unsupported Strategy [$strategy]", 2);
        }

        $this->strategy = new $strategy();

        if (!($this->strategy instanceof Strategy)) {
            throw new InvalidArgumentException("Strategy should be a instance of StrategyInterface", 3);
        }

        return $this->strategy->apply($this->config->get('gateways'));
    }

    /**
     * build driver.
     *
     * @author Allen.Liang
     *
     * @param string $driver
     *
     * @return Translation
     */
    protected function buildDriver($gateway, $driver)
    {
        return new $gateway($this->config->get("gateways.{$driver}"));
    }
}
