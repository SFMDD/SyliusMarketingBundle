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

## Add block in layout.html.twig
Replace 
```twig
{{ sonata_block_render_event('sylius.shop.layout.head') }}
```

by 
```twig 
{{ sonata_block_render_event('sylius.shop.layout.head') }}
{% block metatags %}
{% endblock %}
{{ sonata_block_render_event('fmdd.event.marketing.metadata') }}
</head>
```

## Add events JSON-LD and MarketingEvent
```twig
#SyliusShopBundle\Checkout\selectPayment.html.twig
{% block metatags %}
    {{ sonata_block_render_event('fmdd.event.marketing.selectPayment') }}
    {{ sonata_block_render_event('fmdd.event.marketing.checkout_progress', {'order': order}) }}
{% endblock %}

#SyliusShopBundle\Checkout\selectShipping.html.twig
{% block metatags %}
    {{ sonata_block_render_event('fmdd.event.marketing.checkout_progress', {'order': order}) }}
{% endblock %}

#SyliusShopBundle\error404.html.twig and SyliusShopBundle\error500.html.twig error404/500 
{% block metatags %}
    {{ sonata_block_render_event('fmdd.event.marketing.exception', {"code_error": "500"})) }}
{% endblock %}

#SyliusShopBundle\Product\index.html.twig
{% block metatags %}
    {{ sonata_block_render_event('fmdd.event.jsonld.breadcrumb.taxon', {'taxon': taxon}) }}
    {{ sonata_block_render_event('fmdd.event.marketing.product_index', {'attr': {'products': products}, 'taxon': taxon}) }}
{% endblock %}

#SyliusShopBundle\Product\show.html.twig
{% block metatags %}
    {{ sonata_block_render_event('fmdd.event.marketing.product_show', {'product': product}) }}
    {{ sonata_block_render_event('fmdd.event.jsonld.product', {'product': product}) }}
    {{ sonata_block_render_event('fmdd.event.jsonld.breadcrumb.product', {'product': product}) }}
{% endblock %}

#SyliusShopBundle\Homepage\index.html.twig
{% block metatags %}
    {{ sonata_block_render_event('fmdd.event.jsonld.website') }}
{% endblock %}

#SyliusShopBundle\Contact\request.html.twig
{% block metatags %}
    {{ sonata_block_render_event('fmdd.event.jsonld.contact.business') }} #contact page
    {{ sonata_block_render_event('fmdd.event.jsonld.local.business') }}
{% endblock %}

# use in specific webpage
#search page :
{% block metatags %}
    {{ sonata_block_render_event('fmdd.event.marketing.search', {'attr' : {'search_term': search_term, "products": products} } ) }}
    {{ sonata_block_render_event('fmdd.event.jsonld.breadcrumb.search', {'attr' : {'search_term': search_term} } ) }}
{% endblock %}

#{{ sonata_block_render_event('fmdd.event.marketing.promotion', {'variants': variants}) }}
#{{ sonata_block_render_event('fmdd.event.marketing.registration') }}
#{{ sonata_block_render_event('fmdd.event.marketing.exception', {"attr": {'code_error': "404"}}) }}

# TODO
#{{ sonata_block_render_event('fmdd.event.marketing.view_item_list') }}
```
