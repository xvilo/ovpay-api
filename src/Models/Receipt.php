<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Models;

use Xvilo\OVpayApi\Models\Receipt\ReceiptTrip;

final class Receipt
{
    /**
     * @param Payment[] $relatedPayments
     * @param ReceiptTrip[] $relatedTrips
     */
    public function __construct(
        private array $relatedPayments,
        private array $relatedTrips,
        private readonly array $relatedBalances,
        private readonly Token $token
    ) {
    }

    /**
     * @return Payment[]
     */
    public function getPayments(): array
    {
        return $this->relatedPayments;
    }

    /**
     * @param Payment[] $payments
     * @return $this
     */
    public function setPayments(array $payments): self
    {
        $this->relatedPayments = $payments;
        return $this;
    }

    /**
     * @return ReceiptTrip[]
     */
    public function getTrips(): array
    {
        return $this->relatedTrips;
    }

    /**
     * @param ReceiptTrip[] $trips
     * @return $this
     */
    public function setTrips(array $trips): self
    {
        $this->relatedTrips = $trips;
        return $this;
    }

    public function getRelatedBalances(): array
    {
        return $this->relatedBalances;
    }

    public function getToken(): Token
    {
        return $this->token;
    }
}
