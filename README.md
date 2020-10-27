# Sylius Marketing Bundle

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


### Step 4: Extend channel, customer and order entities

```php
<?php
// src/Entity/Channel.php

namespace App\Entity\Channel;

use FMDD\SyliusMarketingPlugin\Entity\ChannelInterface as FMDDChannelInterface;
use FMDD\SyliusMarketingPlugin\Entity\ChannelTrait as FMDDChannelTrait;
use Sylius\Component\Core\Model\Channel as BaseChannel;

class Channel extends BaseChannel implements FMDDChannelInterface
{
    use FMDDChannelTrait;
}
```

```php
<?php
// src/Entity/Customer.php

namespace App\Entity;

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

namespace App\Entity;

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
                model: Tests\FMDD\SyliusMarketingPlugin\Application\Entity\Customer
                # If you already have your own CustomerController - use TrustpilotCustomerTrait instead
                controller: Setono\SyliusTrustpilotPlugin\Controller\CustomerController

sylius_order:
    resources:
        order:
            classes:
                model: Tests\FMDD\SyliusMarketingPlugin\Application\Entity\Order

sylius_channel:
    resources:
        channel:
            classes:
                model: Tests\FMDD\SyliusMarketingPlugin\Application\Entity\Channel
```

### Step 5: Add the blocks event

You must replace the following line : 
```twig
{# @SyliusShopBundle/_layout.html.twig #}
{{ sonata_block_render_event('sylius.shop.layout.head') }}
```
By : 
```twig 
{# @SyliusShopBundle/_layout.html.twig #}
{{ sonata_block_render_event('sylius.shop.layout.head') }}
{% block metatags %}{% endblock %}

#Add event after <head> in layout
#{{ sonata_block_render_event('fmdd.event.marketing.metadata') }}
```

## Add events
```twig
#fmdd.event.marketing.layout_header') }}sylius.shop.layout.head
#fmdd.event.marketing.add_payment_info') }}sylius.shop.checkout.complete.after_steps
#fmdd.event.marketing.box_product') }}sylius.shop.product.index.after_box
#fmdd.event.marketing.checkout_begin') }}sylius.shop.checkout.address.after_steps
#fmdd.event.marketing.checkout_progress') }}sylius.shop.checkout.select_shipping.after_steps

{{ sonata_block_render_event('fmdd.event.marketing.checkout_progress', {'order': order}) }}
#fmdd.event.marketing.selectPayment') }}sylius.shop.checkout.select_payment.after_steps
#fmdd.event.marketing.purchase') }}sylius.shop.order.thank_you.after_message

#Add event in ShopBundle error404/500 
{% block metatags %}
    {{ sonata_block_render_event('fmdd.event.marketing.exception', {"code_error": "500"})) }}
{% endblock %}

Add event in product index (if use elasticSearch, in Shop/Product/Index)
{% block metatags %}
    {{ sonata_block_render_event('fmdd.event.jsonld.breadcrumb.taxon', {'taxon': taxon}) }}
    {{ sonata_block_render_event('fmdd.event.marketing.product_index', {'attr': {'products': products}, 'taxon': taxon}) }}
{% endblock %}

#add event in product/show.html
{% block metatags %}
    {{ sonata_block_render_event('fmdd.event.marketing.product_show', {'product': product}) }}
    {{ sonata_block_render_event('fmdd.event.jsonld.product', {'product': product}) }}
    {{ sonata_block_render_event('fmdd.event.jsonld.breadcrumb.product', {'product': product}) }}
{% endblock %}
# use in specific webpage, import in dialann website bundle
#{{ sonata_block_render_event('fmdd.event.marketing.promotion', {'variants': variants}) }}
#{{ sonata_block_render_event('fmdd.event.marketing.registration') }}
#{{ sonata_block_render_event('fmdd.event.marketing.exception', {"attr": {'code_error': "404"}}) }}

# TODO
#{{ sonata_block_render_event('fmdd.event.marketing.view_item_list') }}
```

## Add Event json_ld
```twig
{% block metatags #}
{% endblock %}
{% block metatags %}
    {{ sonata_block_render_event('fmdd.event.jsonld.website') }}#homepage
{% endblock %}
{{ sonata_block_render_event('fmdd.event.jsonld.contact.business') }} #contact page
{{ sonata_block_render_event('fmdd.event.jsonld.local.business') }} #contact page
{{ sonata_block_render_event('fmdd.event.jsonld.product', {'product': product}) }} # show product -> Fait dans product show
{{ sonata_block_render_event('fmdd.event.jsonld.breadcrumb.product', {'product': product}) }} # show product -> Fait dans product show
{{ sonata_block_render_event('fmdd.event.jsonld.breadcrumb.taxon') }} #Show list product -> Fait dans product index elastic search

Import block with different way, look in dialann-website bundle #Search page
Import block with different way, look in dialann-website bundle #Promotion page
```
