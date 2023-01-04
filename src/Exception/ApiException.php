<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Exception;

use RuntimeException;

/**
 * This is a last resort generic Exception class.
 */
final class ApiException extends RuntimeException implements OVPayApiException
{
}
