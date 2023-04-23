<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Models;

use Xvilo\OVpayApi\Models\Receipt\ReceiptTrip;

final class Receipt
{
    public function __construct(
        /** @var Payment[] */
        private array $relatedPayments,
        /** @var ReceiptTrip[] $relatedTrips */
        private array $relatedTrips,
        private readonly array $relatedBalances,
        private readonly ?Token $token = null,
    ) {
    }

    /**
     * @return Payment[]
     */
    public function getRelatedPayments(): array
    {
        return $this->relatedPayments;
    }

    public function addRelatedPayment(Payment $payment): void
    {
        $this->relatedPayments[] = $payment;
    }

    /**
     * @return ReceiptTrip[]
     */
    public function getRelatedTrips(): array
    {
        return $this->relatedTrips;
    }

    public function addRelatedTrip(ReceiptTrip $trip): void
    {
        $this->relatedTrips[] = $trip;
    }

    public function getRelatedBalances(): array
    {
        return $this->relatedBalances;
    }

    public function getToken(): ?Token
    {
        return $this->token;
    }
}
