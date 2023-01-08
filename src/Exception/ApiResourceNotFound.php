<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Exception;

use RuntimeException;
use Throwable;

final class ApiResourceNotFound extends RuntimeException implements OVPayApiException
{
    public function __construct(string $message = 'API Resourcen not found.', ?Throwable $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}
