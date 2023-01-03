<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Exception;

use RuntimeException;
use Throwable;

final class UnauthorizedException extends RuntimeException implements OVPayApiException
{
    public function __construct(string $message = 'Unauthorized', ?Throwable $previous = null)
    {
        parent::__construct($message, 401, $previous);
    }
}
