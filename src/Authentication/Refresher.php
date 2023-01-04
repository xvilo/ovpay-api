<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Authentication;

interface Refresher
{
    public function refresh(mixed $token): mixed;
}
