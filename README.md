## OV Pay PHP API Wrapper
[![codecov](https://codecov.io/gh/xvilo/ovpay-api/branch/main/graph/badge.svg?token=TTNSB24MKE)](https://codecov.io/gh/xvilo/ovpay-api)

Unofficial API Wrapper for OVPay. This is a work in progress and based off an undocumented api. **stability is not 
guaranteed**.

For now only the unauthenticated API routes are available, but more will follow. 

### TODO:
- [ ] Add models
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

// Include dependencies
include 'vendor/autoload.php';

// Create JWT parser
$parser = new Parser(new JoseEncoder());

// Create API client and configure authenticated requests
$client = new Client();
$client->Authenticate(new TokenMethod($parser->parse('eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIs...')));

// Get trips for specified Card xtat UUID 
$trips = $client->trips()->getTrips('12a12a1a-1a12-1234-1a12-1a123ab1ab12');
// Get last trip
$trip = end($trips['items']);
// Get trip details by ID
var_dump($client->trips()->getTrip($trip['trip']['xbot'], $trip['trip']['id']));
//    array(5) {
//        ["token"]=>
//      array(4) {
//            ["mediumType"]=>
//        string(3) "Emv"
//            ["xbot"]=>
//        string(36) "12a12a1a-1a12-1234-1a12-1a123ab1ab12"
//            ["status"]=>
//        string(6) "Active"
//            ["personalization"]=>
//        array(3) {
//                ["name"]=>
//          string(0) ""
//                ["color"]=>
//          string(4) "Pink"
//                ["medium"]=>
//          string(12) "PhysicalCard"
//        }
//      }
//      ["correctionOptions"]=>
//      NULL
//      ["trip"]=>
//      array(13) {
//            ["xbot"]=>
//        string(36) "12a12a1a-1a12-1234-1a12-1a123ab1ab12"
//            ["id"]=>
//        int(12345678)
//        ["version"]=>
//        int(1)
//        ["transport"]=>
//        string(4) "RAIL"
//            ["status"]=>
//        string(8) "COMPLETE"
//            ["checkInLocation"]=>
//        string(16) "Utrecht Centraal"
//            ["checkInTimestamp"]=>
//        string(25) "2022-12-30T01:02:03+01:00"
//            ["checkOutLocation"]=>
//        string(5) "Baarn"
//            ["checkOutTimestamp"]=>
//        string(25) "2022-12-30T02:03:04+01:00"
//            ["currency"]=>
//        string(3) "EUR"
//            ["fare"]=>
//        int(540)
//        ["organisationName"]=>
//        string(2) "NS"
//            ["loyaltyOrDiscount"]=>
//        bool(false)
//      }
//      ["correctedFrom"]=>
//      NULL
//      ["correctedFromType"]=>
//      NULL
//    }
```
