<p align="center">
    <a href="https://fm2d.com/" target="_blank">
        <img height="50" width="auto" src="https://fm2d.com/fm2d-theme/images/logo.png" alt="FM2D logo" />
        <img height="50" width="auto" src="https://demo.sylius.com/assets/shop/img/logo.png" alt="Sylius logo" />
    </a>
</p>

---
<h1 align="center">FM2D - SyliusMarketingBundle</h1>

---
[![License](http://poser.pugx.org/fmdd/sylius-marketing-plugin/license)](https://packagist.org/packages/fmdd/sylius-marketing-plugin)
[![Latest Stable Version](http://poser.pugx.org/fmdd/sylius-marketing-plugin/v)](https://packagist.org/packages/fmdd/sylius-marketing-plugin)
[![Total Downloads](http://poser.pugx.org/fmdd/sylius-marketing-plugin/downloads)](https://packagist.org/packages/fmdd/sylius-marketing-plugin)
[![PHP Version Require](http://poser.pugx.org/fmdd/sylius-marketing-plugin/require/php)](https://packagist.org/packages/fmdd/sylius-marketing-plugin)
[![Slack](https://img.shields.io/badge/community%20chat-slack-FF1493.svg)](http://sylius-devs.slack.com)
[![Support](https://img.shields.io/badge/support-contact%20author-blue])](https://fm2d.com/contact)

FM2D is a Web Agency publisher of Sylius plugins and open source actor. Since 2016, we strive to produce useful plugins to improve your e-commerce store. FM2D also offers you a first class technical support and customer service.

---

## Summary

---

* [Overview](#overview)
* [Installation](#installation)
* [License](#license)
* [Contact](#contact)

# Overview

---

Marketing bundle is use for :
- Add TrustPilot bundle from Setono\SyliusTrustpilotPlugin
- Email cart abandoned or cart not payed
- Notification Proof of purchase
- Add Instagram

TODO :
- Add multiple email marketing
- Add new Notification Type

# Installation

## Step 1: Download the plugin

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```shell
$ composer require fmdd/sylius-marketing-plugin
```

This command requires you to have Composer installed globally, as explained in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.

## Step 2: Enable the plugin

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

## Step 3: Configure the plugin

```yaml
# config/packages/_sylius.yaml
imports:
    # ...
    - { resource: "@FMDDSyliusMarketingPlugin/Resources/config/config.yml" }
```

## Step 4: Add the plugin routing to your application

```yaml
# config/routes/sylius_admin.yaml
sylius_marketing_plugin:
    resource: "@FMDDSyliusMarketingPlugin/Resources/config/routes.yaml"
```

## Step 5: Extend customer and order entities

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

## Step 6 : Add notification system
```twig
    ...
    <body>
    ...
       {{ sylius_template_event('fmdd.marketing.shop.layout.notification') }}
    </body>
```

Reload assets : 

```
php bin/console assets:install
php bin/console sylius:theme:assets:install
```

You must load orders purchased event notification 
```shell script
$ php bin/console fmdd:notification-order:load
```

## Step 7 : Create notification types
- purchase
- trustpilot
- instagram

## Step 8 : If you want to use Instagram oEmbed
```yaml
parameters:
    fmdd_instagram_client_id: 'your_app_id'
    fmdd_instagram_client_secret: 'your_app_secret'
```


# Additional resources for developers

---
To learn more about our contribution workflow and more, we encourage you to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)

# License

---

This plugin's source code is completely free and released under the terms of the MIT license.

# Contact

---

If you have any questions, feel free to contact us by filling our form on [our website](https://fm2d.com/contact/) or send us an e-mail at [contact@fm2d.com](mailto:contact@fm2d.com) with your question(s). We will anwser you as soon as possible !
