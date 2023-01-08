<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Exception;

use RuntimeException;
use Throwable;

final class ApiForbiddenException extends RuntimeException implements OVPayApiException
{
    public function __construct(string $message = 'Forbidden.', ?Throwable $previous = null)
    {
        parent::__construct($message, 403, $previous);
    }
}
