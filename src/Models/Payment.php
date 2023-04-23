<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Models;

use DateTimeImmutable;

final class Payment
{
    public function __construct(
        private readonly string $id,
        private readonly string $status,
        private readonly DateTimeImmutable $transactionTimestamp,
        private readonly string $transactionType,
        private readonly int $amount,
        private readonly int $amountDue,
        private readonly string $currency,
        private readonly string $paymentMethod,
        private readonly mixed $rejectionReason,
        private readonly bool $loyaltyOrDiscount,
        private readonly ?string $serviceReferenceId = null,
        private readonly ?string $xbot = null,
    ) {
    }

    public function getServiceReferenceId(): ?string
    {
        return $this->serviceReferenceId;
    }

    public function getXbot(): ?string
    {
        return $this->xbot;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTransactionTimestamp(): DateTimeImmutable
    {
        return $this->transactionTimestamp;
    }

    public function getTransactionType(): string
    {
        return $this->transactionType;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getAmountDue(): int
    {
        return $this->amountDue;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getRejectionReason(): mixed
    {
        return $this->rejectionReason;
    }

    public function isLoyaltyOrDiscount(): bool
    {
        return $this->loyaltyOrDiscount;
    }
}
