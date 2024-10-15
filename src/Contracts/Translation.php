<?php

namespace Diankemao\Translate\Contracts;

interface Translation
{

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
    public function trans($q, $to = 'en', $from = 'auto');
}