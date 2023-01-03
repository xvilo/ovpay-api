<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Api;

use Xvilo\OVpayApi\Models\Payment;
use Xvilo\OVpayApi\Models\Payments;
use Xvilo\OVpayApi\Models\Receipt;

final class PaymentApi extends AbstractApi
{
    public function getPayments(string $cardXtatUuid): Payments
    {
        /** @var Payments $data */
        $data = $this->client->getSerializer()->deserialize(
            $this->get(sprintf('/api/v1/Payments/%s', $cardXtatUuid), ['offset' => 0]),
            Payments::class,
            'json'
        );

        // TODO: See how we can 'deep serialize' this
        return $data->setItems($this->client->getSerializer()->deserialize(
            json_encode($data->getItems(), JSON_THROW_ON_ERROR),
            'Xvilo\OVpayApi\Models\Payment[]',
            'json'
        ));
    }

    public function getReceipt(string $paymentXbot, string $paymentId): Receipt
    {
        /** @var Receipt $receipt */
        $receipt = $this->client->getSerializer()->deserialize(
            $this->get(sprintf('/api/v1/Payments/receipt/%s/%s', $paymentXbot, $paymentId)),
            Receipt::class,
            'json'
        );

        $receipt->setPayments($this->client->getSerializer()->deserialize(
            json_encode($receipt->getPayments(), JSON_THROW_ON_ERROR),
            Payment::class . '[]',
            'json'
        ));

        $receipt->setTrips($this->client->getSerializer()->deserialize(
            json_encode($receipt->getTrips(), JSON_THROW_ON_ERROR),
            Receipt\ReceiptTrip::class . '[]',
            'json'
        ));

        return $receipt;
    }
}
