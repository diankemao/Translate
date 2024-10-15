<?php

namespace Diankemao\Translate\Exceptions;

class NoGatewayAvailableException extends Exception
{
    public function __construct()
    {
        parent::__construct('Translating Has Failed. No Gateway Available.', 0);
    }
}
