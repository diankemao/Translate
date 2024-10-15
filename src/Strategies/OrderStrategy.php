<?php

namespace Diankemao\Translate\Strategies;

use Diankemao\Translate\Contracts\Strategy;

class OrderStrategy implements Strategy
{
    /**
     * apply strategy.
     *
     * @author Allen.Liang
     *
     * @param array $gateways
     *
     * @return array
     */
    public function apply(array $gateways)
    {
        return array_keys($gateways);
    }
}
