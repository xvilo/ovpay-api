## OV Pay PHP API Wrapper
[![codecov](https://codecov.io/gh/xvilo/ovpay-api/branch/main/graph/badge.svg?token=TTNSB24MKE)](https://codecov.io/gh/xvilo/ovpay-api)

Unofficial API Wrapper for OVPay. This is a work in progress and based off an undocumented api. **stability is not 
guaranteed**.

For now only the unauthenticated API routes are available, but more will follow. 

### TODO:
- [x] Add models
- [x] Add support for authentication, then:
  - [x] Add support for `Trips`
  - [x] Add support for `PaymentCards`
  - [x] Add support for `Payments`
- [ ] Add working token refreshing logic
- [ ] Add better error response handling
### Example:

Request trips, then request trip details:

```php
<?php
declare(strict_types=1);

use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Xvilo\OVpayApi\Authentication\TokenMethod;
use Xvilo\OVpayApi\Client;
use Xvilo\OVpayApi\Models\Trip;

// Include dependencies
include 'vendor/autoload.php';

// Create JWT parser
$parser = new Parser(new JoseEncoder());

// Create API client and configure authenticated requests
$client = new Client();
$client->Authenticate(new TokenMethod($parser->parse('eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIs...')));

// Get payment cards
$card = $client->tokens()->getPaymentCards()[0];
// Get trips for specified Card xtat UUID 
$trips = $client->trips()->getTrips($card->getExternalTransitAccountToken());
// Get last trip
$items = $trips->getItems();
/** @var Trip $trip */
$trip = end($items)->getTrip();
// Get trip details by ID
var_dump($client->trips()->getTrip($trip->getXbot(), $trip->getId()));
//  class Xvilo\OVpayApi\Models\Receipt\ReceiptTrip#94 (4) {
//    private readonly ?array $correctionOptions =>
//    NULL
//    private readonly Xvilo\OVpayApi\Models\Trip $trip =>
//    class Xvilo\OVpayApi\Models\Trip#131 (13) {
//      private readonly string $xbot =>
//      string(36) "8126d60f-bded-46d3-9a70-30915a61008b"
//      private readonly int $id =>
//      int(12345678)
//      private readonly int $version =>
//      int(1)
//      private readonly string $transport =>
//      string(4) "RAIL"
//      private readonly string $status =>
//      string(8) "COMPLETE"
//      private readonly string $checkInLocation =>
//      string(16) "Utrecht Centraal"
//      private readonly DateTimeImmutable $checkInTimestamp =>
//      class DateTimeImmutable#79 (3) {
//        public $date =>
//        string(26) "2022-12-30 08:55:30.000000"
//        public $timezone_type =>
//        int(1)
//        public $timezone =>
//        string(6) "+01:00"
//      }
//      private readonly ?string $checkOutLocation =>
//      string(5) "Baarn"
//      private readonly ?DateTimeImmutable $checkOutTimestamp =>
//      class DateTimeImmutable#101 (3) {
//        public $date =>
//        string(26) "2022-12-30 09:45:30.000000"
//        public $timezone_type =>
//        int(1)
//        public $timezone =>
//        string(6) "+01:00"
//      }
//      private readonly string $currency =>
//      string(3) "EUR"
//      private readonly int $fare =>
//      int(540)
//      private readonly string $organisationName =>
//      string(2) "NS"
//      private readonly bool $loyaltyOrDiscount =>
//      bool(false)
//    }
//    private readonly mixed $correctedFrom =>
//    NULL
//    private readonly mixed $correctedFromType =>
//    NULL
//  }
```
