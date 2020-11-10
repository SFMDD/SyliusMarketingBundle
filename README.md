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


### Step 4: Extend channel entity

```php
<?php
namespace App\Entity\Channel;

use FMDD\SyliusMarketingPlugin\Entity\ChannelInterface as FMDDChannelInterface;
use FMDD\SyliusMarketingPlugin\Entity\ChannelTrait as FMDDChannelTrait;
use Sylius\Component\Core\Model\Channel as BaseChannel;

class Channel extends BaseChannel implements FMDDChannelInterface
{
    use FMDDChannelTrait;
}
```

### Step 5: Add the blocks event

You must replace the following line : 
```twig
{# @SyliusShopBundle/_layout.html.twig #}
{{ sonata_block_render_event('sylius.shop.layout.head') }}
```
By : 
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
{{ sonata_block_render({ 'type': 'sonata.block.service.template' }, {
        'template': '@FMDDSyliusMarketingPlugin/Marketing/Google/exception.html.twig',
        'attr': {'code_error': '404'}
    }) }}
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
