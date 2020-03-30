# Marketing Bundle for Sylius

## Install
```shell
$ composer require fmdd/sylius-marketing-plugin
```

## Add in bundles.php
```php
    ...
    FMDD\SyliusMarketingPlugin\FMDDSyliusMarketingPlugin::class => ['all' => true],
```

## in packages/_sylius.yaml
```yaml
imports:
    ...
    - { resource: "@FMDDSyliusMarketingPlugin/Resources/config/config.yml" }
```

## Add events
```twig
#fmdd.event.marketing.layout_header') }}sylius.shop.layout.head
#fmdd.event.marketing.add_payment_info') }}sylius.shop.checkout.complete.after_steps
#fmdd.event.marketing.box_product') }}sylius.shop.product.index.after_box
#fmdd.event.marketing.checkout_begin') }}sylius.shop.checkout.address.after_steps
#fmdd.event.marketing.checkout_progress') }}sylius.shop.checkout.select_shipping.after_steps
#fmdd.event.marketing.selectPayment') }}sylius.shop.checkout.select_payment.after_steps

#Add event in ShopBundle error404/500 
{{ sonata_block_render_event('fmdd.event.marketing.exception', {"code_error": "500"})) }}
#Add event after <head> in layout
#{{ sonata_block_render_event('fmdd.event.marketing.metadata') }}

Add event in product index (if use elasticSearch, in Shop/Product/Index)
#{{ sonata_block_render_event('fmdd.event.marketing.product_index') }}
#add event in product/show.html
#{{ sonata_block_render_event('fmdd.event.marketing.product_show') }}
# use in specific webpage
#{{ sonata_block_render_event('fmdd.event.marketing.promotion') }}
#{{ sonata_block_render_event('fmdd.event.marketing.registration') }}
#{{ sonata_block_render_event('fmdd.event.marketing.search') }}

#{{ sonata_block_render_event('fmdd.event.marketing.purchase') }}
# TODO
#{{ sonata_block_render_event('fmdd.event.marketing.view_item_list') }}
```
