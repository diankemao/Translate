<?php

namespace Diankemao\Translate\Gateways;

use Diankemao\Translate\Tools\Config;
use Diankemao\Translate\Traits\HasHttpRequest;
use Diankemao\Translate\Contracts\Translation;
use Diankemao\Translate\Exceptions\GatewayException;
use Diankemao\Translate\Exceptions\InvalidArgumentException;

class YoudaoGateway implements Translation
{
    use HasHttpRequest;

    /**
     * baidu api.
     *
     * @var string
     */
    protected $gateway = 'https://openapi.youdao.com/api';

    /**
     * baidu api query.
     *
     * @var array
     */
    protected $query;

    /**
     * user_config.
     *
     * @var Config
     */
    protected $config;

    /**
     * construct.
     *
     * @author Allen.Liang
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);

        if (is_null($this->config->get('appid')) || is_null($this->config->get('appsecret'))) {
            throw new InvalidArgumentException("missing config [appid] or [appsecret]", 1);
        }

        $this->query = [
            'q' => '',
            'from' => '',
            'to' => '',
            'appKey' => $this->config->get('appid'),
            'salt' => time(),
            'sign' => '',
        ];
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
     * @return
     */
    public function trans($q, $to = 'en', $from = 'auto')
    {
        $this->query['q'] = $q;
        $this->query['from'] = $from;
        $this->query['to'] = $to;
        $this->query['sign'] = $this->getSign();

        $res = $this->post($this->gateway, $this->query);

        if (!isset($res['errorCode']) || $res['errorCode'] !== '0') {
            throw new GatewayException(
                'get result error, error code' . $res['errorCode'],
                $res['errorCode'],
                $res
            );
        }

        $trans_result = '';
        foreach ($res['translation'] as $v) {
            $trans_result .= $v . "\n";
        }

        return trim($trans_result, "\n");
    }

    /**
     * get sign
     *
     * @author Allen.Liang
     *
     * @return string
     */
    protected function getSign()
    {
        return md5($this->query['appKey'] . $this->query['q'] . $this->query['salt'] . $this->config->get('appsecret'));
    }
}
