PHP Library for Summit Evergreen
=======================

PHP Library for working with **Summit Evergreen** accounts.

Summit Evergreen is an online courseware platform. It provides the tools that make it easy to share your value, by creating beautiful premium membership platforms — making it easy to turn your ideas into valuable online courses.

You can sign up for a Summit Evergreen account at https://summitevergreen.com.

## Requirements

PHP 5.3.3 and later.

## Supported Functionality

The PHP Library allows you to use the Summit Evergreen API to create and refund user purchases in your Summit Evergreen course. 
Easily integrate your own purchase and checkout flow, and create stronger integrations with the system that power your business.

* Register new student accounts
* Add Purchases and Payments
* Refund purchases and cancel accounts
* Track order and purchase Ids locally in your custom systems.


## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Add this to your `composer.json`:

    {
      "require": {
        "summitevergreen/summit-php": "~1.0.0"
      }
    }

Then install via:

    composer.phar install

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading):

    require_once('vendor/autoload.php');

## Getting Started

Sample usage adding a purchase for a customer:

```
use SummitEvergreen\Summit;

// Add your Account ID and Account API Key
$summit = new Summit('A1B2C3D4E5','9ec05a816a63e99e218b88a69614f313c29082be');

$purchaseInfo = [
    'email' => 'luke@example.com',
    'first_name' => 'Luke',
    'last_name' => 'Skywalker',
    'price' => 199.00,
    'sku' => 'COURSE1000'
];

$purchase = $summit->setPurchaseData($purchaseInfo);

// Returns a JSON-ecoding string with the purchase data (see below)
$return = $purchase->addPurchase();
```

We also support Fluent method chaining:

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

