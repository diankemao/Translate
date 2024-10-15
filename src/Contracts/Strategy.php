<?php

namespace Diankemao\Translate\Contracts;

interface Strategy
{
    /**
     * apply strategy.
     *
     * @author Allen.Liang
     *
     * @return string
     */
    public function apply(array $gateways);
}