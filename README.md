SummitEvergreen PHP SDK
=======================

PHP SDK for working with **Summit Evergreen** accounts.

# Supported Functionality

Our initial release of the API and SDK just supports adding and refunding purchases, but we'll add more features in the future as our customers need.

Installation / Usage
--------------------
Add `"summitevergreen": "~1.0.0"` to your composer.json requirements.

Sample usage adding a purchase for a customer:

```
use SummitEvergreen\Summit;

$summit = new Summit('A1B2C3D4E5','9ec05a816a63e99e218b88a69614f313c29082be');

$purchaseInfo = [
    'email' => 'who@cares.com',
    'first_name' => 'Nunya',
    'last_name' => 'Bidness',
    'price' => 199.00,
    'sku' => 'COURSE1000'
];

$purchase = $summit->setPurchaseData($purchaseInfo);
$return = $purchase->addPurchase();
```
You may chain the addPurchase() method if you prefer:

```
$purchase = $summit->setPurchaseData($purchaseInfo)->addPurchase();
```

To **refund** the purchase, send the same information but with a different final method.

```
$purchase = $summit->setPurchaseData($purchaseInfo)->doRefund();
```

**Note:** `email`, `first_name`, `last_name`, `price`, and `sku` are all required fields for **both** purchases and refunds.

You can include `order_id` if you have generated your own, or the API will generate one for you.

You may also include `payment_id` from your system for tracking purposes.
_If you include the `payment_id`, you **must** include the `order_id` as well._

Returned Data
-------------
The **Summit Evergreen** API and this SDK will return a JSON-encoded array as follows:

```
{
message: "{Operation success message}",
errors: [ ],
order_id: "54f8b72da6c94",
thankyou_url: "{Full URL to Thank You page for customer}"
}
```

