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

# Parameter
```yaml
parameters:
  google.analytics: "UA-"
  google.adwords: "AW-"
  google.id: ""
  google.type.merchant: "FurnitureStore"
  contact_geo_latitude_longitude: ["", ""]
  contact_geo_opening_hours: [["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"], ["09:00", "19:30"]]

  facebook.pixel: ""
  url.privacy: ""
  website.name: ""
  author: ""
  google.event.purchase: ""
  google.event.search: ""
  google.event.product_show: ""
  google.event.registration: ""
  google.event.checkout.progress: ""
  google.event.select.payment: ""

```

## Add events
```twig
#fmdd.event.marketing.layout_header') }}sylius.shop.layout.head
#fmdd.event.marketing.add_payment_info') }}sylius.shop.checkout.complete.after_steps
#fmdd.event.marketing.box_product') }}sylius.shop.product.index.after_box
#fmdd.event.marketing.checkout_begin') }}sylius.shop.checkout.address.after_steps
#fmdd.event.marketing.checkout_progress') }}sylius.shop.checkout.select_shipping.after_steps
#fmdd.event.marketing.selectPayment') }}sylius.shop.checkout.select_payment.after_steps
#fmdd.event.marketing.purchase') }}sylius.shop.order.thank_you.after_message

#Add event in ShopBundle error404/500 
{{ sonata_block_render_event('fmdd.event.marketing.exception', {"code_error": "500"})) }}
#Add event after <head> in layout
#{{ sonata_block_render_event('fmdd.event.marketing.metadata') }}

Add event in product index (if use elasticSearch, in Shop/Product/Index)
#{{ sonata_block_render_event('fmdd.event.marketing.product_index', {'products': products}) }}
#add event in product/show.html
#{{ sonata_block_render_event('fmdd.event.marketing.product_show', {'product': product}) }}
# use in specific webpage
#{{ sonata_block_render_event('fmdd.event.marketing.promotion', {'variants': variants}) }}
#{{ sonata_block_render_event('fmdd.event.marketing.registration') }}
#{{ sonata_block_render_event('fmdd.event.marketing.search') }}

# TODO
#{{ sonata_block_render_event('fmdd.event.marketing.view_item_list') }}
```

## Add Event json_ld
```twig
{% block header_end #}
{% endblock %}
{{ sonata_block_render_event('fmdd.event.jsonld.website') }} #homepage
{{ sonata_block_render_event('fmdd.event.jsonld.contact.business') }} #contact page
{{ sonata_block_render_event('fmdd.event.jsonld.local.business') }} #contact page
{{ sonata_block_render_event('fmdd.event.jsonld.product', {'product': product}) }} # show product
{{ sonata_block_render_event('fmdd.event.jsonld.breadcrumb.product', {'product': product}) }} # show product
{{ sonata_block_render_event('fmdd.event.jsonld.breadcrumb.search', {'name': name}) }} #Search page
{{ sonata_block_render_event('fmdd.event.jsonld.breadcrumb.taxon') }} #Show list product
```
