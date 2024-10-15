<?php

declare(strict_types=1);

namespace Diankemao\Translate\Traits;

use ReflectionClass;
use Diankemao\Translate\Tools\Str;

trait Arrayable
{
    /**
     * toArray.
     *
     * @author Allen.Liang
     *
     * @throws \ReflectionException
     */
    public function toArray(): array
    {
        $result = [];

        foreach ((new ReflectionClass($this))->getProperties() as $item) {
            $k = $item->getName();
            $method = 'get'.Str::studly($k);

            $result[Str::snake($k)] = method_exists($this, $method) ? $this->{$method}() : $this->{$k};
        }

        return $result;
    }
}
