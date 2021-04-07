# Sylius Marketing Bundle

Marketing bundle is use for :
- Add TrustPilot bundle from Setono\SyliusTrustpilotPlugin
- Email cart abandoned or cart not payed
- Notification Proof of purchase

TODO :
- Add multiple email marketing
- Add new Notification Type

## Installation

### Step 1: Download the plugin

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```shell
$ composer require fmdd/sylius-marketing-plugin
```

This command requires you to have Composer installed globally, as explained in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.

### Step 2: Enable the plugin

Then, enable the plugin by adding it to the end of the list of registered plugins/bundles
in `config/bundles.php` file of your project.

```php
<?php
# config/bundles.php
return [
    // ...
    Setono\SyliusTrustpilotPlugin\SetonoSyliusTrustpilotPlugin::class => ['all' => true],
    FMDD\SyliusMarketingPlugin\FMDDSyliusMarketingPlugin::class => ['all' => true],
    // ...
];
```

### Step 3: Configure the plugin

```yaml
# config/packages/_sylius.yaml
imports:
    # ...
    - { resource: "@FMDDSyliusMarketingPlugin/Resources/config/config.yml" }
```

### Step 4: Add the plugin routing to your application

```yaml
# config/routes/sylius_admin.yaml
sylius_marketing_plugin:
    resource: "@FMDDSyliusMarketingPlugin/Resources/config/route.yaml"
```

### Step 5: Extend customer and order entities

```php
<?php
// src/Entity/Customer.php

namespace App\Entity\Customer;

use Doctrine\ORM\Mapping as ORM;
use Setono\SyliusTrustpilotPlugin\Model\CustomerTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Model\CustomerTrait as TrustpilotCustomerTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

/**
 * @ORM\Table(name="sylius_customer")
 * @ORM\Entity()
 */
class Customer extends BaseCustomer implements CustomerTrustpilotAwareInterface
{
    use TrustpilotCustomerTrait;
}
```

```php
<?php
// src/Entity/Order.php

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrait as TrustpilotOrderTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

/**
 * @ORM\Table(name="sylius_order")
 * @ORM\Entity()
 */
class Order extends BaseOrder implements OrderTrustpilotAwareInterface
{
    use TrustpilotOrderTrait;
}
```

Add overrides configuration :

```yaml
# config/packages/_sylius.yml

sylius_customer:
    resources:
        customer:
            classes:
                # If you already have your own CustomerController - use TrustpilotCustomerTrait instead
                controller: Setono\SyliusTrustpilotPlugin\Controller\CustomerController

```

### Step 6 : Add notification system
```twig
    ...
    <body>
    ...
    {% include '@FMDDSyliusMarketingPlugin/Notification/_notification.html.twig' %}
    </body>
```

You must load orders purchased event notification 
```shell script
$ php bin/console fmdd:notification-order:load
```

### Step 7 : Create notification types
- purchase

### Step 8 : If you want to use Instagram oEmbed
```yaml
parameters:
    fmdd_instagram_client_id: 'your_app_id'
    fmdd_instagram_client_secret: 'your_app_secret'
```
