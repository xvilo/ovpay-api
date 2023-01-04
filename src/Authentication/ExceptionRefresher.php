<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Authentication;

use RuntimeException;
use Throwable;

final class ExceptionRefresher implements Refresher
{
    public function __construct(
        private readonly string     $message = "",
        private readonly int        $code = 0,
        private readonly ?Throwable $previous = null
    ) {
    }

    public function refresh(mixed $token): never
    {
        throw new RuntimeException($this->message, $this->code, $this->previous);
    }
}
